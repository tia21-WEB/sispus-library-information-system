<?php
// File ini berfungsi menyediakan API untuk proses peminjaman buku pada aplikasi mobile,
// mulai dari pengajuan peminjaman, melihat riwayat peminjaman, pengajuan pengembalian,
// pemindaian QR Code, hingga pelaporan buku hilang.
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Exemplar;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\WebNotification;
use App\Models\BorrowingExemplar;
use App\Services\FirebaseService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
class MobileBorrowingController extends Controller
{
    /**
 * API ini digunakan oleh halaman Peminjaman pada aplikasi mobile
 * untuk mengajukan peminjaman buku oleh siswa maupun guru.
 */
    public function store(Request $request)
{
    // Memvalidasi data pengajuan peminjaman yang dikirim dari aplikasi mobile.
    $request->validate([
    'user_id' => 'required|exists:users,id',

    'book_id' => 'required|array',

    'book_id.*' => 'exists:books,id',

    'loan_type' => 'required|in:harian,mingguan,semester',

    'is_collective' => 'nullable|boolean',

    'class_name' => 'nullable|string',
'quantity' =>
    'nullable|integer|min:1',
]);
    // Mengecek apakah pengguna memiliki laporan buku hilang
// yang menyebabkan akun tidak dapat melakukan peminjaman.
   // KODE BARU (MENGECEK HILANG TOTAL ATAU HILANG SEBAGIAN/EKSEMPLAR)
$lostBook = Borrowing::where('user_id', $request->user_id)
    ->whereHas('borrowedExemplars', function ($q) {
        $q->where('status', 'lost');
    })
    ->exists();

if ($lostBook) {
    return response()->json([
        'success' => false,
        'message' => 'Akun diblokir karena masih memiliki laporan buku hilang'
    ], 422);
}


// Mengecek apakah pengguna memiliki keterlambatan pengembalian buku
// sehingga pengajuan peminjaman baru tidak diperbolehkan.
$lateBorrowing = Borrowing::where(
    'user_id',
    $request->user_id
)
->whereIn('status', [
    'dipinjam',
    'menunggu_pengembalian'
])
->whereDate(
    'return_date',
    '<',
    now()
)
->exists();

if ($lateBorrowing) {

    return response()->json([

        'success' => false,

        'message' =>
        'Akun diblokir karena memiliki keterlambatan pengembalian buku'

    ], 422);

}
// Memastikan setiap buku yang diajukan belum berada
// pada transaksi peminjaman aktif pengguna.
foreach ($request->book_id as $bookId) {

    $alreadyBorrowed = Borrowing::where(
        'user_id',
        $request->user_id
    )

    ->whereIn('status', [

        'menunggu',
        'dipinjam',
        'menunggu_pengembalian'

    ])

    ->whereHas('details', function ($q) use ($bookId) {

        $q->where(
            'book_id',
            $bookId
        );
    })

    ->exists();

    if ($alreadyBorrowed) {

        return response()->json([

            'success' => false,

            'message' =>
                'Masih ada buku yang sama dalam peminjaman aktif'

        ], 422);
    }
}
// Menentukan tanggal peminjaman dan batas pengembalian
// sesuai jenis peminjaman yang dipilih.
    $borrowDate = Carbon::today();

    $loanType = $request->is_collective
        ? 'harian'
        : $request->loan_type;

    if ($request->is_collective) {

        $returnDate = Carbon::today()
            ->addDays(3);

    } else {

        if ($loanType == 'harian') {

            $returnDate = Carbon::today()
                ->addDays(3);

        } elseif ($loanType == 'mingguan') {

            $returnDate = Carbon::today()
                ->addDays(7);

        } else {

            $returnDate = Carbon::today()
                ->addDays(120);
        }
    }
// Menyimpan data transaksi peminjaman baru
// dengan status menunggu persetujuan pustakawan.
    $borrowing = Borrowing::create([

    'user_id' => $request->user_id,

    'book_id' => $request->book_id[0],

    'loan_type' => $request->loan_type,

    'borrow_date' => $borrowDate,

    'return_date' => $returnDate,

    'status' => 'menunggu',

    'is_collective' =>
        $request->is_collective ?? false,

    'class_name' =>
        $request->class_name,
 'quantity' =>
        $request->quantity ?? 1,

]);
// Menyimpan daftar buku yang diajukan
// ke dalam detail transaksi peminjaman.
    foreach ($request->book_id as $bookId) {

        BorrowingDetail::create([
            
            'borrowing_id' => $borrowing->id,

            'book_id' => $bookId,

             'qty' => $request->is_collective
            ? ($request->quantity ?? 1)
            : 1

        ]);
        
    }
    // Mengambil data pengguna untuk keperluan notifikasi.
    $borrowing->load('user');
    $pustakawans = User::where('role', 'pustakawan')->get();
// Mengirim notifikasi ke dashboard pustakawan
// bahwa terdapat pengajuan peminjaman baru dari pengguna.
foreach ($pustakawans as $pustakawan) {

    $pustakawan->notify(
        new WebNotification(
            'Ã°Å¸â€œÅ¡ Pengajuan Peminjaman Baru',
            $borrowing->user->name . ' mengajukan peminjaman buku.',
            '/admin/peminjaman'
        )
    );

}
// Mengirim respon bahwa pengajuan peminjaman berhasil dibuat.
    return response()->json([

        'success' => true,

        'message' => 'Pengajuan berhasil',

        'data' => $borrowing
    ]);
}
/**
 * API ini digunakan oleh menu Peminjaman pada aplikasi mobile
 * untuk menampilkan seluruh data peminjaman milik pengguna,
 * baik yang masih aktif maupun yang telah selesai.
 */
public function myBorrowings($userId)
{
    // Tambahkan 'borrowedExemplars.exemplar.book' di sini agar terbaca di Flutter
    $borrowings = Borrowing::with([
        'details.book',
        'details.exemplar',
        'borrowedExemplars.exemplar.book'
    ])
    ->where('user_id', $userId)
    ->latest()
    ->get();
$borrowings->makeVisible([
    'extension_status',
    'extension_count',
    'extension_note'
]);
    return response()->json([
        'success' => true,
        'data' => $borrowings
    ]);
}
/**
 * API ini digunakan saat pengguna melakukan pemindaian QR Code
 * untuk mengajukan proses pengembalian buku melalui aplikasi mobile.
 */
public function scanReturn(Request $request)
    {
        try {
            $request->validate([
                'borrowing_id' => 'required',
                'qr_code' => 'required'
            ]);

            $borrowing = Borrowing::with('borrowedExemplars')->findOrFail($request->borrowing_id);

            // 🔥 PENCEGAH DOUBLE REQUEST 🔥
            if (in_array($borrowing->status, ['menunggu_pengembalian', 'selesai'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Buku sudah berhasil di-scan dan menunggu verifikasi.',
                    'data' => $borrowing
                ], 200);
            }

            if ($borrowing->qr_code != $request->qr_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR tidak valid'
                ]);
            }

            // 1. UBAH STATUS UTAMA SAJA
            $borrowing->update([
                'status' => 'menunggu_pengembalian'
            ]);

            // 2. BAGIAN INI DIHAPUS/DIKOMENTARI AGAR TIDAK BIKIN DATABASE ERROR
            /* 
            foreach ($borrowing->borrowedExemplars as $borrowedExemplar) {
                if ($borrowedExemplar->status != 'lost') {
                    // Baris ini yang ditolak MySQL Anda:
                    // $borrowedExemplar->update(['status' => 'menunggu_pengembalian']);
                }
            }
            */

            // 3. TAMBAHAN BARU: KIRIM NOTIFIKASI WEB SAAT BERHASIL SCAN
            try {
                $borrowing->load('user');
                $pustakawans = User::where('role', 'pustakawan')->get();
                foreach ($pustakawans as $pustakawan) {
                    $pustakawan->notify(
                        new WebNotification(
                            'Pengajuan Pengembalian (QR)',
                            $borrowing->user->name . ' mengajukan pengembalian buku via QR Code.',
                            '/admin/peminjaman'
                        )
                    );
                }
            } catch (\Exception $e) {
                Log::error('Notifikasi gagal: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Menunggu verifikasi pustakawan',
                'data' => $borrowing
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function requestReturn(Request $request)
    {
        try {
            $request->validate([
                'borrowing_id' => 'required|exists:borrowings,id'
            ]);

            $borrowing = Borrowing::with('borrowedExemplars')->findOrFail($request->borrowing_id);

            // 🔥 PENCEGAH DOUBLE REQUEST 🔥
            if (in_array($borrowing->status, ['menunggu_pengembalian', 'selesai'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengembalian sudah berhasil diajukan sebelumnya.',
                    'data' => $borrowing
                ], 200);
            }

            if ($borrowing->status != 'dipinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ]);
            }

            // 1. UBAH STATUS UTAMA
            $borrowing->update([
                'status' => 'menunggu_pengembalian'
            ]);

            // 2. BAGIAN INI DIHAPUS AGAR TIDAK BIKIN DATABASE ERROR
            /*
            foreach ($borrowing->borrowedExemplars as $borrowedExemplar) {
                if ($borrowedExemplar->status != 'lost') {
                    // $borrowedExemplar->update(['status' => 'menunggu_pengembalian']);
                }
            }
            */

            // 3. KIRIM NOTIFIKASI WEB
            try {
                $borrowing->load('user');
                $pustakawans = User::where('role', 'pustakawan')->get();
                foreach ($pustakawans as $pustakawan) {
                    $pustakawan->notify(
                        new WebNotification(
                            'Pengajuan Pengembalian',
                            $borrowing->user->name . ' mengajukan pengembalian buku.',
                            '/admin/peminjaman'
                        )
                    );
                }
            } catch (\Exception $e) {
                Log::error('Notifikasi gagal: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil diajukan',
                'data' => $borrowing
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function requestExtension($id)
{
    // Mengambil data transaksi beserta data pengguna.
    $borrowing = Borrowing::with('user')
    ->where('id', $id)
    ->firstOrFail();

    // Memastikan hanya transaksi yang sedang dipinjam
    // yang dapat mengajukan perpanjangan.
    if ($borrowing->status != 'dipinjam') {

        return response()->json([
            'success' => false,
            'message' => 'Perpanjangan hanya dapat diajukan pada buku yang sedang dipinjam.'
        ], 422);

    }

    // Peminjaman semester tidak dapat diperpanjang.
    if ($borrowing->loan_type == 'semester') {

        return response()->json([
            'success' => false,
            'message' => 'Peminjaman semester tidak dapat diperpanjang.'
        ], 422);

    }

    // Maksimal satu kali perpanjangan.
    if ($borrowing->extension_count >= 1) {

        return response()->json([
            'success' => false,
            'message' => 'Perpanjangan hanya dapat dilakukan satu kali.'
        ], 422);

    }

    // Tidak boleh mengajukan lagi jika masih menunggu persetujuan.
    if ($borrowing->extension_status == 'pending') {

        return response()->json([
            'success' => false,
            'message' => 'Pengajuan perpanjangan sedang diproses.'
        ], 422);

    }

    // Tidak boleh mengajukan jika sudah melewati jatuh tempo.
    if (Carbon::today()->gt(Carbon::parse($borrowing->return_date))) {

        return response()->json([
            'success' => false,
            'message' => 'Perpanjangan tidak dapat dilakukan karena buku telah melewati batas pengembalian.'
        ], 422);

    }

    // Mengubah status pengajuan perpanjangan.
    $borrowing->update([

        'extension_status' => 'pending',
        'extension_note' => null

    ]);

    // Mengirim notifikasi ke dashboard pustakawan.
    $pustakawans = User::where(
        'role',
        'pustakawan'
    )->get();

    foreach ($pustakawans as $pustakawan) {

        $pustakawan->notify(

            new WebNotification(

                '📅 Pengajuan Perpanjangan',

                $borrowing->user->name .
                ' mengajukan perpanjangan peminjaman.',

                '/admin/peminjaman'

            )

        );

    }

    return response()->json([

        'success' => true,

        'message' =>
            'Pengajuan perpanjangan berhasil dikirim.'

    ]);
}
/**
 * API ini digunakan oleh menu Peminjaman pada aplikasi mobile
 * untuk melaporkan buku yang hilang kepada pustakawan.
 */
public function reportLostBook(Request $request)
{
    // Validasi menerima borrowing_id dan spesifik item yang hilang (borrowed_exemplar_id / exemplar_id)
    $request->validate([
    'borrowing_id' => 'required|exists:borrowings,id',

    'borrowed_exemplar_ids' => 'required|array|min:1',

    'borrowed_exemplar_ids.*' => 'exists:borrowing_exemplars,id',
]);

    $borrowing = Borrowing::with(['user', 'borrowedExemplars.exemplar.book', 'details.exemplar'])->findOrFail($request->borrowing_id);

    if ($borrowing->status != 'dipinjam') {
        return response()->json([
            'success' => false,
            'message' => 'Buku tidak dapat dilaporkan hilang'
        ], 422);
    }

    foreach ($request->borrowed_exemplar_ids as $borrowedExemplarId) {

    $borrowedExemplar = BorrowingExemplar::where(
        'id',
        $borrowedExemplarId
    )
    ->where(
        'borrowing_id',
        $borrowing->id
    )
    ->firstOrFail();

    $borrowedExemplar->update([
        'status' => 'lost'
    ]);

    if ($borrowedExemplar->exemplar) {

        $borrowedExemplar->exemplar->update([
            'status' => 'lost'
        ]);

    }

}
if (!empty($borrowing->user->fcm_token)) {

    FirebaseService::send(
        $borrowing->user->fcm_token,
        '[BLOKIR] Akun Diblokir Sementara',
        'Laporan buku hilang telah diterima. Akun Anda diblokir sementara dan akan aktif kembali setelah proses kehilangan buku diselesaikan oleh pustakawan.'
    );

}
    // Kirim notifikasi ke pustakawan
    $borrowing->load('user');
    $pustakawans = User::where('role', 'pustakawan')->get();
    foreach ($pustakawans as $pustakawan) {
        $pustakawan->notify(
            new WebNotification(
                '[LAPORAN] Buku Hilang',
                $borrowing->user->name . ' melaporkan buku hilang.',
                '/admin/peminjaman'
            )
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Laporan buku hilang berhasil dikirim'
    ]);
}
public function clearanceStatus($userId)
{
    $borrowed = Borrowing::where('user_id', $userId)
        ->where('status', 'dipinjam')
        ->count();

    $pendingReturn = Borrowing::where('user_id', $userId)
        ->where('status', 'menunggu_pengembalian')
        ->count();

    $lostBook = Borrowing::where('user_id', $userId)
        ->whereHas('borrowedExemplars', function ($q) {
            $q->where('status', 'lost');
        })
        ->exists();

    $eligible = ($borrowed == 0 && $pendingReturn == 0 && !$lostBook);

    return response()->json([
        'success' => true,
        'is_eligible' => $eligible, // Disesuaikan menjadi 'is_eligible'
        'requirements' => [
            'no_active_borrowings' => $borrowed == 0, // Disesuaikan dengan Flutter
            'no_pending_returns' => $pendingReturn == 0, // Disesuaikan dengan Flutter
            'no_lost_books' => !$lostBook, // Disesuaikan dengan Flutter
        ],
        'message' => $eligible
            ? 'Anda memenuhi syarat Bebas Pustaka.'
            : 'Anda masih memiliki tanggungan perpustakaan.'
    ]);
}
public function downloadClearance($userId)
{
    try {
        $user = User::findOrFail($userId);

        $borrowedCount = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'menunggu', 'menunggu_pengembalian'])
            ->count();

        $hasLostTrans = Borrowing::where('user_id', $userId)
            ->where('status', 'hilang')
            ->exists();

        $hasLostExemplar = BorrowingExemplar::whereHas('borrowing', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('status', 'lost')
            ->exists();

        $lostBook = $hasLostTrans || $hasLostExemplar;

        if ($borrowedCount > 0 || $lostBook) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mencetak surat. Anda belum memenuhi syarat bebas pustaka.'
            ], 422);
        }

        $kepalaPustakawan = User::where('role', 'kepala_pustaka')->first();
        $pustakawan = User::where('role', 'pustakawan')->first();

        $pdf = Pdf::loadView('bebas-pustaka', compact('user', 'kepalaPustakawan', 'pustakawan'));

        // Gunakan stream() agar aman dibuka di browser HP tanpa ERR_CONNECTION_RESET
        return $pdf->stream('Surat_Bebas_Pustaka_' . $user->name . '.pdf');
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mencetak PDF: ' . $e->getMessage()
        ], 500);
    }
}

}