<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\BookApproval;
use App\Models\Exemplar;
use App\Models\BorrowingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Notifications\WebNotification;
use App\Models\RewardPeriod;
use Carbon\Carbon;
class KepalaPustakawanController extends Controller
{
/**
 * Fungsi ini digunakan untuk menampilkan halaman dashboard utama Kepala Pustakawan,
 * yang menghitung total buku, total anggota (siswa & guru), total transaksi peminjaman,
 * jumlah staf pustakawan, daftar anggota yang diblokir, serta total pengajuan buku pending.
 */
  public function dashboard()
{
    // Menghitung jumlah keseluruhan record buku di database
    $totalBooks = Book::count();

    // Menghitung jumlah user yang memiliki peran 'siswa' atau 'guru'
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();

    // Menghitung jumlah keseluruhan transaksi peminjaman buku
    $totalTransactions = Borrowing::count();

    // Menghitung jumlah staf yang memiliki peran 'pustakawan'
    $totalPustakawan = User::where(
        'role',
        'pustakawan'
    )->count();

  // Mengambil data user yang harus diblokir (terlambat mengembalikan atau ada buku hilang)
  $blockedUsers = User::whereIn(

    'id',

    Borrowing::where(function ($q) {

        $q->where(function ($sub) {

            // Kondisi 1: Peminjaman berstatus dipinjam namun tanggal kembali sudah lewat hari ini
            $sub->where(
                'status',
                'dipinjam'
            )
            ->whereDate(
    'return_date',
    '<',
    today()
);

        })

        // Kondisi 2: Peminjaman memiliki status buku hilang
        ->orWhere(
            'status',
            'hilang'
        );

    })

    ->pluck('user_id')

)->get();

    // TAMBAHKAN INI
    // Menghitung total pengajuan approval buku dari pustakawan yang statusnya masih 'pending'
$pendingApproval = BookApproval::where(
    'status',
    'pending'
)->count();

    // Mengembalikan view 'kepala.dashboard' dengan membawa variabel-variabel statistik
    return view(
        'kepala.dashboard',
        compact(
            'totalBooks',
            'totalMembers',
            'totalTransactions',
            'totalPustakawan',
            'blockedUsers',
            'pendingApproval'
        )
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman daftar seluruh staf pustakawan
 * yang terdaftar di dalam sistem perpustakaan.
 */
    public function pustakawan()
{
    // Mengambil semua data user yang memiliki peran sebagai 'pustakawan'
    $pustakawan = User::where(
        'role',
        'pustakawan'
    )->get();

    // Mengembalikan view 'kepala.pustakawan' dengan membawa data pustakawan
    return view(
        'kepala.pustakawan',
        compact('pustakawan')
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan form halaman tambah pustakawan baru.
 */
public function createPustakawan()
{
    // Mengembalikan view form 'kepala.tambah-pustakawan'
    return view('kepala.tambah-pustakawan');
}

/**
 * Fungsi ini digunakan untuk memproses dan menyimpan data akun pustakawan baru
 * ke dalam database setelah melalui proses validasi input data.
 */
public function storePustakawan(Request $request)
{
    // Melakukan validasi input data form (nama, nis/nip, email unik, password min 6, dll)
    $request->validate([
        'name' => 'required',
        'nis_nip' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'phone' => 'nullable',
        'address' => 'nullable'
    ]);

    // Menyimpan data user baru dengan role 'pustakawan', poin 0, dan badge 'Bronze'
    User::create([
        'name' => $request->name,
        'nis_nip' => $request->nis_nip,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'address' => $request->address,
        'role' => 'pustakawan',
        'points' => 0,
        'badge' => 'Bronze'
    ]);

    // Mengarahkan kembali ke halaman daftar pustakawan dengan pesan flash sukses
    return redirect()
        ->route('kepala.pustakawan')
        ->with('success','Pustakawan berhasil ditambahkan');
}
/**
 * Fungsi ini digunakan untuk menampilkan form edit data pustakawan
 * berdasarkan ID pustakawan tertentu yang dipilih.
 */
public function editPustakawan($id)
{
    // Mencari data user pustakawan berdasarkan ID, atau melempar error 404 jika tidak ditemukan
    $pustakawan = User::findOrFail($id);

    // Mengembalikan view 'kepala.edit-pustakawan' dengan data pustakawan terkait
    return view(
        'kepala.edit-pustakawan',
        compact('pustakawan')
    );
}
/**
 * Fungsi ini digunakan untuk memperbarui informasi data diri pustakawan
 * di dalam database berdasarkan input request yang dikirimkan.
 */
public function updatePustakawan(
    Request $request,
    $id
)
{
    // Mencari data pustakawan berdasarkan ID
    $pustakawan = User::findOrFail($id);

    // Memvalidasi input pembaruan data (nama, email, telepon, dan alamat)
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'nullable',
        'address' => 'nullable'
    ]);

    // Melakukan update data pustakawan ke database
    $pustakawan->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address
    ]);

    // Mengarahkan kembali ke halaman daftar pustakawan dengan pesan sukses pembaruan
    return redirect()
        ->route('kepala.pustakawan')
        ->with(
            'success',
            'Data berhasil diperbarui'
        );
}
/**
 * Fungsi ini digunakan untuk menghapus akun pustakawan dari database
 * berdasarkan ID tertentu.
 */
public function deletePustakawan($id)
{
    // Mencari data pustakawan berdasarkan ID
    $pustakawan = User::findOrFail($id);

    // Menghapus record pustakawan dari database
    $pustakawan->delete();

    // Mengarahkan kembali ke halaman daftar pustakawan dengan pesan sukses penghapusan
    return redirect()
        ->route('kepala.pustakawan')
        ->with(
            'success',
            'Pustakawan berhasil dihapus'
        );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman manajemen buku lengkap khusus Kepala Pustakawan,
 * yang mencakup fitur pencarian, statistik total buku, stok, kategori, stok kosong, eksemplar,
 * jumlah buku hilang, serta 5 buku terpopuler.
 */
public function buku(Request $request)
{
   // Mengambil kata kunci pencarian dari request
$search = $request->search;

// Mengambil data buku beserta relasi kategori dan eksemplar dengan filter pencarian dan paginasi 10 item
$books = Book::with([
    'category',
    'exemplars'
])
->when($search, function ($query) use ($search) {

    $query->where(function ($q) use ($search) {

        $q->where('title', 'like', "%{$search}%")
          ->orWhere('author', 'like', "%{$search}%")
          ->orWhere('publisher', 'like', "%{$search}%")
          ->orWhere('publication_year', 'like', "%{$search}%")
          ->orWhereHas('category', function ($category) use ($search) {

              $category->where('name', 'like', "%{$search}%");

          });

    });

})
->latest()
->paginate(10)
->withQueryString();
    // Menghitung total judul buku
    $totalBooks = Book::count();

    // Menghitung total akumulasi stok seluruh buku
    $totalStock = Book::sum('stock');

    // Menghitung total kategori buku yang ada
    $totalCategories = Category::count();

    // Menghitung total buku yang stoknya bernilai 0 (habis)
    $outOfStock = Book::where(
        'stock',
        0
    )->count();

    // Menghitung total keseluruhan eksemplar buku
    $totalExemplars = Exemplar::count();

    // Menghitung total eksemplar buku yang berstatus 'lost' (hilang)
    $lostBooks = Exemplar::where(
        'status',
        'lost'
    )->count();

    // Mengambil 5 buku terpopuler berdasarkan frekuensi di tabel detail peminjaman
    $popularBooks = BorrowingDetail::join(
        'books',
        'borrowing_details.book_id',
        '=',
        'books.id'
    )
    ->select(
        'books.title',
        DB::raw('COUNT(*) as total')
    )
    ->groupBy(
        'books.id',
        'books.title'
    )
    ->orderByDesc('total')
    ->take(5)
    ->get();

    // Mengembalikan view 'kepala.buku' dengan membawa seluruh data statistik dan hasil pencarian
    return view(
        'kepala.buku',
        compact(
            'books',
            'totalBooks',
            'totalStock',
            'totalCategories',
            'outOfStock',
            'totalExemplars',
            'lostBooks',
            'popularBooks'
        )
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman manajemen anggota perpustakaan bagi Kepala Pustakawan,
 * yang mencakup daftar anggota aktif, anggota non-aktif, statistik total siswa, guru, akun non-aktif,
 * serta daftar user yang sedang diblokir.
 */
public function anggota()
{
  // Mengambil daftar anggota siswa/guru yang berstatus aktif (is_active = 1)
  $anggota = User::whereIn(
    'role',
    ['siswa','guru']
)
->where('is_active',1)
->get();

// Mengambil daftar anggota siswa/guru yang berstatus non-aktif (is_active = 0)
$inactiveUsers = User::whereIn(
    'role',
    ['siswa','guru']
)
->where('is_active',0)
->get();

    // Menghitung total seluruh anggota (siswa dan guru)
    $totalAnggota = User::whereIn(
        'role',
        ['siswa','guru']
    )->count();

    // Menghitung total khusus anggota siswa
    $totalSiswa = User::where(
        'role',
        'siswa'
    )->count();

    // Menghitung total khusus anggota guru
    $totalGuru = User::where(
        'role',
        'guru'
    )->count();
    // Menghitung total anggota yang berstatus non-aktif
    $totalInactive = User::whereIn(
    'role',
    ['siswa','guru']
)
->where('is_active',0)
->count();
// Mengambil daftar user yang diblokir karena terlambat mengembalikan atau buku hilang
$blockedUsers = User::whereIn(

    'id',

    Borrowing::where(function ($q) {

        $q->where(function ($sub) {

            $sub->where(
                'status',
                'dipinjam'
            )
           ->whereDate(
    'return_date',
    '<',
    today()
);

        })

        ->orWhere(
            'status',
            'hilang'
        );

    })

    ->pluck('user_id')

)->get();
    // Mengembalikan view 'kepala.anggota' beserta data anggota dan statistik keanggotaan
    return view(
        'kepala.anggota',
        compact(
            'anggota',
            'totalAnggota',
            'totalSiswa',
            'totalGuru',
            'blockedUsers',
               'totalInactive',
               'inactiveUsers',
        )
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman rekapitulasi data transaksi peminjaman
 * dengan fitur filter berdasarkan rentang tanggal, bulan, atau tahun, serta statistik status transaksi.
 */
public function transaksi(Request $request)
{
    // Mempersiapkan query dasar peminjaman beserta relasi user, details, book, dan exemplar
    $query = Borrowing::with([
        'user',
        'details.book',
        'details.exemplar',
        'borrowedExemplars.exemplar'
    ]);

    // Filter tanggal mulai (start_date) jika diisi
    if ($request->filled('start_date')) {
        $query->whereDate('borrow_date', '>=', $request->start_date);
    }

    // Filter tanggal selesai (end_date) jika diisi
    if ($request->filled('end_date')) {
        $query->whereDate('borrow_date', '<=', $request->end_date);
    }

    // Filter berdasarkan bulan jika diisi
    if ($request->filled('month')) {
        $query->whereMonth('borrow_date', $request->month);
    }

    // Filter berdasarkan tahun jika diisi
    if ($request->filled('year')) {
        $query->whereYear('borrow_date', $request->year);
    }

    // Mengeksekusi query transaksi diurutkan dari yang terbaru
    $transaksi = $query->latest()->get();

    // Statistik mengikuti hasil filter yang diterapkan
    // Menghitung total transaksi hasil filter
    $totalTransaksi = $transaksi->count();

    // Menghitung total transaksi berstatus 'dipinjam'
    $totalDipinjam = $transaksi->where('status', 'dipinjam')->count();

    // Menghitung total transaksi berstatus 'dikembalikan'
    $totalDikembalikan = $transaksi->where('status', 'dikembalikan')->count();

    // Menghitung total transaksi yang terlambat (status dipinjam dan return_date kurang dari waktu sekarang)
    $totalTerlambat = $transaksi
        ->where('status', 'dipinjam')
        ->filter(function ($item) {
            return \Carbon\Carbon::parse($item->return_date)->lt(now());
        })
        ->count();

    // Menghitung total transaksi peminjaman kolektif
    $totalKolektif = $transaksi->where('is_collective', true)->count();

    // Menghitung total transaksi dengan status buku 'hilang'
    $totalHilang = $transaksi->where('status', 'hilang')->count();

    // Mengembalikan view 'kepala.transaksi' dengan data transaksi dan ringkasan statistik terfilter
    return view(
        'kepala.transaksi',
        compact(
            'transaksi',
            'totalTransaksi',
            'totalDipinjam',
            'totalDikembalikan',
            'totalTerlambat',
            'totalKolektif',
            'totalHilang'
        )
    );
}

/**
 * Fungsi ini digunakan untuk menampilkan halaman daftar persetujuan (approval)
 * pengajuan perubahan data buku yang dikirimkan oleh staf pustakawan.
 */
public function approval(Request $request)
{
    // Mengambil seluruh data pengajuan approval buku diurutkan dari yang terbaru
    $approvals = BookApproval::latest()->get();

    // Mengembalikan view 'kepala.approval' dengan membawa data pengajuan
    return view(
        'kepala.approval',
        compact('approvals')
    );
}
/**
 * Fungsi ini digunakan untuk menyetujui pengajuan (approval) buku
 * (mencakup aksi create, update, atau delete buku) serta memperbarui stok dan eksemplar secara otomatis.
 */
public function approveBook($id)
{
    // Mencari data BookApproval berdasarkan ID
    $approval = BookApproval::findOrFail($id);

    // Memastikan status pengajuan masih 'pending', jika tidak kembalikan pesan error
    if ($approval->status != 'pending') {

        return back()->with(
            'error',
            'Approval sudah diproses'
        );
    }

    // Mengambil array data buku dari pengajuan
    $data = $approval->book_data;

    // Jika aksi pengajuan adalah pembuatan buku baru ('create')
    if ($approval->action == 'create') {

        // Membuat record buku baru ke database
        $book = Book::create($data);

        // Melakukan perulangan untuk membuat eksemplar buku sesuai jumlah stok awal
        for ($i = 1; $i <= $book->stock; $i++) {
$prefix = strtoupper(
    substr(
        preg_replace('/[^A-Za-z]/', '', $book->title),
        0,
        3
    )
);

Exemplar::create([

    'book_id' => $book->id,

    'code' => $prefix . '-' .
        str_pad($book->id, 3, '0', STR_PAD_LEFT) .
        '-' .
        str_pad($i, 3, '0', STR_PAD_LEFT),

    'status' => 'available'

]);
        }

    // Jika aksi pengajuan adalah pembaruan data buku ('update')
    } elseif ($approval->action == 'update') {

    // Mencari record buku berdasarkan judul data yang diajukan
    $book = Book::where(
        'title',
        $approval->book_data['title']
    )->first();

    if (!$book) {

        return back()->with(
            'error',
            'Buku tidak ditemukan'
        );
    }

   // Menghitung jumlah stok lama dari eksemplar yang tidak berstatus 'lost'
   $oldStock = Exemplar::where('book_id', $book->id)
    ->where('status', '!=', 'lost')
    ->count();

    // Memperbarui data buku dengan data baru
    $book->update($data);

    // Mendapatkan nilai stok baru dari buku
    $newStock = $book->stock;

    // STOCK BERTAMBAH
    // Kondisi jika stok baru lebih besar daripada stok lama
if ($newStock > $oldStock) {

    $prefix = strtoupper(
        substr(
            preg_replace(
                '/[^A-Za-z]/',
                '',
                $book->title
            ),
            0,
            3
        )
    );

    $lastExemplar = Exemplar::where(
    'book_id',
    $book->id
)
->orderByDesc('id')
->first();

$lastNumber = 0;

if ($lastExemplar) {

    preg_match(
        '/(\d+)$/',
        $lastExemplar->code,
        $matches
    );

    $lastNumber = (int) ($matches[1] ?? 0);
}

$jumlahTambah = $newStock - $oldStock;

for (
    $i = $lastNumber + 1;
    $i <= $lastNumber + $jumlahTambah;
    $i++
){

        Exemplar::create([

    'book_id' => $book->id,

    'code' => $prefix . '-' .
        str_pad(
            $i,
            3,
            '0',
            STR_PAD_LEFT
        ),

    'status' => 'available'

]);

    }

}

    // STOCK BERKURANG
    // Kondisi jika stok baru lebih kecil daripada stok lama
   elseif ($newStock < $oldStock) {

    $selisih = $oldStock - $newStock;

    $availableCount = Exemplar::where('book_id', $book->id)
        ->where('status', 'available')
        ->count();

    if ($availableCount < $selisih) {
        return back()->with(
            'error',
            'Stok tidak bisa dikurangi karena sebagian buku sedang dipinjam atau hilang.'
        );
    }

    $exemplars = Exemplar::where(
        'book_id',
        $book->id
    )
        ->where(
            'status',
            'available'
        )
        ->latest()
        ->take($selisih)
        ->get();

        foreach ($exemplars as $exemplar) {

            $exemplar->delete();

        }

    }

}
// Jika aksi pengajuan adalah penghapusan buku ('delete')
elseif ($approval->action == 'delete') {

    $book = Book::where(
        'title',
        $approval->book_data['title']
    )->first();

    if ($book) {

        // Hapus cover
        if (
            $book->cover &&
            Storage::disk('public')->exists($book->cover)
        ) {

            Storage::disk('public')->delete(
                $book->cover
            );

        }

        // Hapus ebook
        if (
            $book->ebook_file &&
            Storage::disk('public')->exists($book->ebook_file)
        ) {

            Storage::disk('public')->delete(
                $book->ebook_file
            );

        }

        // Hapus exemplar
        Exemplar::where(
            'book_id',
            $book->id
        )->delete();

        // Hapus buku
        $book->delete();

    }

}

// Memperbarui status approval menjadi 'approved', mencatat ID Kepala Pustakawan dan waktu persetujuan
$approval->update([

    'status' => 'approved',

    'approved_by' => Auth::id(),

    'approved_at' => now()

]);
// Memuat relasi pemohon (requester) untuk pengiriman notifikasi
$approval->load('requester');

if ($approval->requester) {

    $approval->requester->notify(

        new WebNotification(

            '✅ Approval Buku Disetujui',

            'Pengajuan buku "' .
            $approval->book_data['title'] .
            '" telah disetujui oleh Kepala Pustakawan.',

            '/admin/buku'

        )

    );

}

    // Mengembalikan halaman sebelumnya dengan pesan sukses approval disetujui
    return back()->with(
        'success',
        'Approval berhasil disetujui'
    );
}
/**
 * Fungsi ini digunakan untuk menolak pengajuan (approval) buku
 * yang diajukan oleh pustakawan sekaligus mengirimkan notifikasi penolakan kepada pemohon.
 */
public function rejectBook(Request $request, $id)
{
    $request->validate([
    'rejection_reason' => 'required|string|max:500',
]);
    // Mencari data approval berdasarkan ID
    $approval = BookApproval::findOrFail($id);

    // Memperbarui status approval menjadi 'rejected', mencatat ID penyetuju dan waktu penolakan
    $approval->update([

    'status' => 'rejected',

    'rejection_reason' => $request->rejection_reason,

    'approved_by' => Auth::id(),

    'approved_at' => now()

]);

// Memuat relasi pemohon (requester)
$approval->load('requester');

if ($approval->requester) {

    // Mengirim notifikasi web penolakan buku kepada pemohon
    $approval->requester->notify(

        new WebNotification(

            '❌ Approval Buku Ditolak',

            'Pengajuan buku "' .
$approval->book_data['title'] .
'" ditolak oleh Kepala Pustakawan.

Alasan: ' .
$request->rejection_reason,

            '/admin/buku'

        )

    );

}
    // Mengembalikan ke halaman sebelumnya dengan pesan sukses penolakan
    return back()->with(
        'success',
        'Approval berhasil ditolak'
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman laporan lengkap perpustakaan khusus Kepala Pustakawan,
 * yang mencakup rekapitulasi statistik peminjaman, buku populer, kategori, analisis asosiasi Apriori,
 * daftar poin user untuk gamifikasi, serta periode reward aktif berdasarkan filter bulan dan tahun.
 */
public function laporan(Request $request)
{
    // Mengambil parameter bulan dan tahun dari request, atau menggunakan bulan/tahun saat ini
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    // Membangun query dasar peminjaman yang difilter berdasarkan bulan dan tahun pembuatan
    $borrowingsQuery = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);

    // Menghitung total keseluruhan buku
    $totalBooks = Book::count();

    // Menghitung total anggota (siswa dan guru)
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();

    // Menghitung total transaksi peminjaman pada periode tersebut
    $totalBorrowings = (clone $borrowingsQuery)
        ->count();

    // Menghitung total peminjaman dengan status 'dikembalikan'
    $totalReturned = (clone $borrowingsQuery)
        ->where('status', 'dikembalikan')
        ->count();

   // Menghitung total peminjaman yang terlambat (baik yang belum dikembalikan maupun yang sudah dikembalikan terlambat)
   $totalLate = (clone $borrowingsQuery)
    ->where(function ($query) {

        // Masih dipinjam dan sudah lewat jatuh tempo
        $query->where(function ($q) {

            $q->where('status', 'dipinjam')
              ->whereDate('return_date', '<', today());

        })

        // Sudah dikembalikan tetapi melewati tenggat waktu
        ->orWhere(function ($q) {

            $q->where('status', 'dikembalikan')
              ->whereColumn('returned_at', '>', 'return_date');

        });

    })
    ->count();

    // Mengambil 10 buku terpopuler berdasarkan frekuensi peminjaman
    $popularBooks = BorrowingDetail::join(
    'books',
    'borrowing_details.book_id',
    '=',
    'books.id'
)
->select(
    'books.title',
    DB::raw('COUNT(*) as borrowings_count')
)
->groupBy(
    'books.id',
    'books.title'
)
->orderByDesc('borrowings_count')
->take(10)
->get();
    // Mengambil kategori peminjaman (loan_type) terpopuler pada periode tersebut
    $popularCategories = Borrowing::selectRaw(
        'loan_type, COUNT(*) as total'
    )
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year)
    ->groupBy('loan_type')
    ->orderByDesc('total')
    ->get();

    // Mengambil daftar transaksi peminjaman terbaru sesuai filter
    $borrowings = $borrowingsQuery
        ->latest()
        ->get();

    // Mengambil data 5 buku teratas untuk chart/grafik laporan
    $chartBooks = BorrowingDetail::join(
    'books',
    'borrowing_details.book_id',
    '=',
    'books.id'
)
->select(
    'books.title',
    DB::raw('COUNT(*) as borrowings_count')
)
->groupBy(
    'books.id',
    'books.title'
)
->orderByDesc('borrowings_count')
->take(5)
->get();
    // Mengambil label judul buku untuk chart
    $bookLabels = $chartBooks
        ->pluck('title');

    // Mengambil angka total peminjaman buku untuk chart
    $bookData = $chartBooks
        ->pluck('borrowings_count');

    // Mengambil label kategori untuk chart
    $categoryLabels = $popularCategories
        ->pluck('loan_type');

    // Mengambil nilai total kategori untuk chart
    $categoryData = $popularCategories
        ->pluck('total');
   
        $returnLabels = [
            'Dipinjam',
            'Dikembalikan',
            'Terlambat'
        ];
        $returnData = [
            $totalBorrowings,
            $totalReturned,
            $totalLate
        ];
    // Mengambil data user Kepala Pustakawan untuk penandatanganan laporan
    $kepalaPustakawan = User::where(
        'role',
        'kepala_pustakawan'
    )->first();

    // Mengambil data user Pustakawan untuk penandatanganan laporan
    $pustakawan = User::where(
        'role',
        'pustakawan'
    )->first();

    // GAMIFIKASI
    // Mengambil daftar user siswa dan guru diurutkan dari poin tertinggi untuk laporan gamifikasi
    $users = User::whereIn(
        'role',
        ['siswa', 'guru']
    )
    ->orderByDesc('points')
    ->get();
// Inisialisasi array untuk menampung hasil perhitungan algoritma Apriori
$aprioriData = [];

// Mengambil seluruh transaksi peminjaman beserta detail bukunya
$transactions = Borrowing::with(
    'details.book'
)->get();

// Melakukan perulangan untuk menghitung kemunculan bersama (asosiasi) pasangan buku
foreach ($transactions as $transaction) {

    $books = $transaction->details
        ->pluck('book.title')
        ->toArray();

    for ($i = 0; $i < count($books); $i++) {

        for ($j = $i + 1; $j < count($books); $j++) {

            $key = $books[$i] . '||' . $books[$j];

            if (!isset($aprioriData[$key])) {

                $aprioriData[$key] = [

                    'buku_utama' => $books[$i],

                    'buku_terkait' => $books[$j],

                    'total' => 0

                ];

            }

            $aprioriData[$key]['total']++;

        }

    }

}

// Mengurutkan hasil data apriori dari frekuensi pasangan terbanyak
$aprioriData = collect($aprioriData)
    ->sortByDesc('total')
    ->values();
 // Mengambil periode reward yang sedang aktif saat ini
 $activePeriod = RewardPeriod::where(
    'is_active',
    true
)->first();
    // Mengembalikan view 'kepala.laporan' dengan membawa seluruh variabel data laporan dan statistik
    return view(
        'kepala.laporan',
        compact(
    'month',
    'year',
    'totalBooks',
    'totalMembers',
    'totalBorrowings',
    'totalReturned',
    'totalLate',

    'returnLabels',
    'returnData',

    'popularBooks',
    'popularCategories',
    'borrowings',
    'bookLabels',
    'bookData',
    'categoryLabels',
    'categoryData',
    'kepalaPustakawan',
    'pustakawan',
    'users',
    'aprioriData',
    'activePeriod',
)
        
    );
}
/**
 * Fungsi ini digunakan untuk mengunduh dokumen laporan bulanan perpustakaan
 * dalam format file PDF siap cetak bagi Kepala Pustakawan.
 */
public function downloadLaporanPdf(Request $request)
{
    // Mengambil parameter bulan dan tahun dari request untuk pencetakan PDF
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    // Menghitung total keseluruhan buku
    $totalBooks = Book::count();

    // Menghitung total anggota siswa dan guru
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();

   // Membangun query peminjaman untuk dokumen laporan PDF
   $borrowingsQuery = Borrowing::with([
    'user',
    'details.book'
])
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);

    // Menghitung total peminjaman untuk data PDF
    $totalBorrowings = (clone $borrowingsQuery)
        ->count();

    // Menghitung total pengembalian untuk data PDF
    $totalReturned = (clone $borrowingsQuery)
        ->where('status', 'dikembalikan')
        ->count();

   // Menghitung total keterlambatan untuk data PDF
   $totalLate = (clone $borrowingsQuery)
    ->where(function ($query) {

        // Masih dipinjam dan sudah lewat jatuh tempo
        $query->where(function ($q) {
            $q->where('status', 'dipinjam')
              ->whereDate('return_date', '<', now());
        })

        // Sudah dikembalikan tetapi terlambat
        ->orWhere(function ($q) {
            $q->where('status', 'dikembalikan')
              ->whereColumn('returned_at', '>', 'return_date');
        });

    })
    ->count();
    $returnLabels = [
    'Dipinjam',
    'Dikembalikan',
    'Terlambat'
];

$returnData = [
    $totalBorrowings,
    $totalReturned,
    $totalLate
];

    // Mengambil 5 buku terpopuler khusus berdasarkan bulan dan tahun laporan
    $popularBooks = Book::withCount([
        'borrowings' => function ($query)
        use ($month, $year) {

            $query->whereMonth(
                'created_at',
                $month
            )
            ->whereYear(
                'created_at',
                $year
            );

        }
    ])
    ->orderByDesc('borrowings_count')
    ->take(5)
    ->get();

    // Mengambil kategori peminjaman terpopuler untuk PDF
    $popularCategories = Borrowing::selectRaw(
        'loan_type, COUNT(*) as total'
    )
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year)
    ->groupBy('loan_type')
    ->orderByDesc('total')
    ->get();

    // Mengambil daftar transaksi peminjaman terbaru
    $borrowings = $borrowingsQuery
        ->latest()
        ->get();

    // Mengambil data user kepala pustakawan
    $kepalaPustakawan = User::where(
        'role',
        'kepala_pustakawan'
    )->first();

    // Mengambil data user pustakawan
    $pustakawan = User::where(
        'role',
        'pustakawan'
    )->first();

    // Mengonversi angka bulan menjadi nama bulan dalam Bahasa Indonesia (misal: 'Juli')
    $monthName = Carbon::create(
        $year,
        (int) $month,
        1
    )->translatedFormat('F');
// Mengambil data user siswa dan guru untuk tabel gamifikasi di PDF
$users = User::whereIn(
    'role',
    ['siswa','guru']
)
->orderByDesc('points')
->get();
    // Memuat view PDF 'kepala.laporan-pdf' dengan membawa seluruh parameter data
    $pdf = Pdf::loadView(
        
        'kepala.laporan-pdf',
        compact(
            'month',
            'year',
            'monthName',
            'totalBooks',
            'totalMembers',
            'totalBorrowings',
            'totalReturned',
            'popularBooks',
            'popularCategories',
            'borrowings',
            'kepalaPustakawan',
            'pustakawan',
            'returnLabels',
            'returnData',
            'totalLate',
            'users'
        )
    );

    // Memicu proses unduhan file PDF laporan dengan penamaan otomatis
    return $pdf->download(
        'Laporan-' .
        $monthName .
        '-' .
        $year .
        '.pdf'
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman profil akun
 * milik Kepala Pustakawan yang sedang aktif login.
 */
public function profile()
{
    // Mengambil data user yang sedang aktif login saat ini
    $user = Auth::user();

    // Mengembalikan view 'kepala.profile' dengan membawa data user
    return view(
        'kepala.profile',
        compact('user')
    );
}
/**
 * Fungsi ini digunakan untuk menghapus seluruh riwayat notifikasi
 * milik pengguna yang sedang aktif/login.
 */
public function clearNotifications()
{
    // Menghapus record notifikasi dari tabel 'notifications' berdasarkan ID user yang sedang login
    DB::table('notifications')
        ->where('notifiable_id', Auth::id())
        ->where('notifiable_type', \App\Models\User::class)
        ->delete();

    // Mengembalikan pengguna ke halaman sebelumnya
    return back();
}
}