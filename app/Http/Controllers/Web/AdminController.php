<?php
// File ini berfungsi mengelola seluruh fitur pada Dashboard Pustakawan,
// seperti autentikasi, sirkulasi buku, data master, anggota,
// gamifikasi, laporan, serta notifikasi pada website SISPUS.
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\BookApproval;
use App\Models\BorrowingDetail;
use Illuminate\Support\Facades\Storage;
use App\Models\Borrowing;
use App\Models\Exemplar;
use App\Models\BorrowingExemplar;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Exports\UserTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use App\Models\RewardPeriod;
use App\Models\RewardHistory;
use App\Services\FirebaseService;
use App\Notifications\WebNotification;
class AdminController extends Controller
{
   /**
 * Fungsi ini digunakan untuk menampilkan halaman login
 * bagi pustakawan dan kepala pustakawan pada website SISPUS.
 */
    public function showLogin()
{
    $captcha = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5));

    session(['captcha' => $captcha]);

    return view('login', compact('captcha'));
}

/**
 * Fungsi ini digunakan untuk memproses login pengguna website
 * serta mengarahkan pengguna ke dashboard sesuai hak aksesnya.
 */
   public function prosesLogin(Request $request)
{
    // Memvalidasi data login yang dimasukkan pengguna.
    $credentials = $request->validate([
        'nis_nip' => 'required',
        'password' => 'required',
        'captcha' => 'required'
    ]);

    // Memastikan kode keamanan yang dimasukkan benar.
    if (strtoupper($request->captcha) != session('captcha')) {

        // Membuat captcha baru jika salah
        $captcha = strtoupper(substr(
            str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
            0,
            5
        ));

        session(['captcha' => $captcha]);

        return back()
            ->withErrors([
                'msg' => 'Kode keamanan salah.'
            ])
            ->withInput();
    }

    // Hapus captcha agar tidak ikut dicek oleh Auth::attempt()
    unset($credentials['captcha']);

    // Memastikan NIS/NIP dan password yang dimasukkan benar.
    if (!Auth::attempt($credentials)) {

        // Membuat captcha baru jika login gagal
        $captcha = strtoupper(substr(
            str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
            0,
            5
        ));

        session(['captcha' => $captcha]);

        return back()
            ->withErrors([
                'msg' => 'NIS/NIP atau password salah.'
            ])
            ->withInput();
    }

    // Hapus captcha dari session setelah login berhasil
    session()->forget('captcha');

    // Membuat sesi login baru untuk meningkatkan keamanan.
    $request->session()->regenerate();

    if (Auth::user()->role == 'kepala_pustakawan') {
        return redirect()->route('kepala.dashboard');
    }

    // Mengarahkan Pustakawan ke halaman dashboard.
    if (Auth::user()->role == 'pustakawan') {
        return redirect()->route('web.dashboard');
    }

    // Menolak login apabila role tidak memiliki hak akses website.
    Auth::logout();

    return back()->withErrors([
        'msg' => 'Akses ditolak.'
    ]);
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman profil
 * pengguna yang sedang login pada website SISPUS.
 */
public function profile()
{
    // Mengambil data pengguna yang sedang login.
    $user = Auth::user();
// Mengirim data profil ke halaman website.
    return view(
        'profile',
        compact('user')
    );
}
/**
 * Fungsi ini digunakan untuk memperbarui informasi profil
 * pustakawan atau kepala pustakawan pada website SISPUS.
 */
public function updateProfile(Request $request)
{
    /** @var \App\Models\User $user */
    $user = User::findOrFail(Auth::id());
// Memvalidasi data profil yang akan diperbarui.
    $request->validate([

        'name' => 'required',

        'email' => 'required|email',

        'phone' => 'nullable',

        'address' => 'nullable'

    ]);
    // Menyimpan perubahan data profil ke database.
    $user->update([

        'name' => $request->name,

        'email' => $request->email,

        'phone' => $request->phone,

        'address' => $request->address

    ]);
// Menampilkan pesan bahwa profil berhasil diperbarui.
    return back()
        ->with(
            'success',
            'Profil berhasil diperbarui'
        );
}
/**
 * Fungsi ini digunakan untuk mengubah password akun
 * pustakawan atau kepala pustakawan pada website SISPUS.
 */
public function updatePassword(Request $request)
{
    // Memvalidasi data perubahan password.
    $request->validate([

        'old_password' => 'required',

        'new_password' => 'required|min:6|confirmed'

    ]);

    /** @var \App\Models\User $user */
    // Mengambil data pengguna yang sedang login.
    $user = User::findOrFail(Auth::id());
// Memastikan password lama yang dimasukkan sudah benar.
    if (
        !Hash::check(
            $request->old_password,
            $user->password
        )
    ) {

        return back()
            ->with(
                'error',
                'Password lama salah'
            );
    }
// Menyimpan password baru yang telah dienkripsi.
    $user->update([

        'password' =>
        Hash::make(
            $request->new_password
        )

    ]);
// Menampilkan pesan bahwa password berhasil diubah.
    return back()
        ->with(
            'success',
            'Password berhasil diubah'
        );
}
/**
 * Fungsi ini digunakan untuk mengakhiri sesi login
 * dan mengembalikan pengguna ke halaman login website.
 */
    public function logout(Request $request) {
        // Menghapus sesi login pengguna.
        Auth::logout();
        // Menghapus seluruh data sesi yang masih aktif.
        $request->session()->invalidate();
        // Membuat token sesi baru untuk mencegah penyalahgunaan sesi.
        $request->session()->regenerateToken();
        // Mengarahkan pengguna kembali ke halaman login.
        return redirect()->route('web.login');
    }
/**
 * Fungsi ini digunakan untuk menampilkan halaman Dashboard Pustakawan
 * yang berisi ringkasan statistik kondisi perpustakaan.
 */
   public function dashboard()
{
    // Menghitung jumlah seluruh koleksi buku yang tersedia.
    $totalBooks = Book::count();
// Menghitung jumlah buku yang sedang dipinjam.
    $totalBorrowed = Borrowing::where(
        'status',
        'dipinjam'
    )->count();
// Menghitung jumlah anggota yang sedang diblokir
// karena terlambat mengembalikan buku atau memiliki laporan buku hilang.
   $blockedUsers = Borrowing::where(function ($q) {

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

   ->orWhereHas('borrowedExemplars', function ($sub) {

    $sub->whereIn('status', ['lost', 'damaged']);

});

})
->distinct('user_id')
->count('user_id');
// Menghitung jumlah seluruh anggota perpustakaan
// yang terdiri dari siswa dan guru.
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();
// Menghitung jumlah kategori buku yang tersedia.
    $totalCategories = Category::count();
// Mengambil buku yang paling sering dipinjam
// untuk ditampilkan pada Dashboard.
    $popularBook = Book::withCount(
        'borrowings'
    )
    ->orderByDesc(
        'borrowings_count'
    )
    ->first();
// Mengirim seluruh data statistik ke halaman Dashboard website.
    return view(
        'dashboard',
        compact(
            'totalBooks',
            'totalBorrowed',
            'blockedUsers',
            'totalMembers',
            'totalCategories',
            'popularBook'
        )
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan daftar anggota
 * yang sedang diblokir karena keterlambatan pengembalian
 * atau laporan buku hilang.
 */
public function anggotaTerblokir()
{
    $blockedUsers = Borrowing::with([
        'user',
        'details.book',
        'borrowedExemplars.exemplar.book'
    ])

    ->where(function ($q) {

        // Anggota terlambat
        $q->where(function ($sub) {

            $sub->where('status', 'dipinjam')
                ->whereDate('return_date', '<', today());

        })

        // Anggota memiliki buku hilang
        ->orWhereHas('borrowedExemplars', function ($sub) {

            $sub->whereIn('status', ['lost', 'damaged']);

        });

    })

    ->latest()
    ->get();

    return view(
        'anggota-terblokir',
        compact('blockedUsers')
    );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk menyelesaikan
 * kasus buku hilang setelah proses penggantian buku atau ganti rugi selesai.
 */
public function selesaikanBukuHilang($id)
{
    try {
        $borrowing = Borrowing::with(['user', 'borrowedExemplars.exemplar.book', 'details.book'])->findOrFail($id);

        // Ambil HANYA eksemplar yang statusnya benar-benar 'lost' pada peminjaman ini
        $lostBorrowingExemplars = $borrowing->borrowedExemplars()->where('status', 'lost')->get();

        if ($lostBorrowingExemplars->count() > 0) {
            // Proses HANYA untuk eksemplar yang hilang
            foreach ($lostBorrowingExemplars as $borrowedExemplar) {
                $book = $borrowedExemplar->exemplar->book ?? null;
                
                if ($book) {
                    // 1. Tambah stok total HANYA untuk buku yang hilang itu saja
                    $book->increment('stock', 1);

                    // 2. Deteksi pola nama eksemplar
                    $lastExemplar = Exemplar::where('book_id', $book->id)
                        ->where('code', 'NOT LIKE', 'EX-%')
                        ->latest('id')
                        ->first();

                    if ($lastExemplar && str_contains($lastExemplar->code, '-')) {
                        $parts = explode('-', $lastExemplar->code);
                        $prefix = $parts[0];
                        $lastNumber = intval(end($parts));
                        $nextNumber = $lastNumber + 1;
                        $uniqueCode = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                    } else {
                        $totalExemplar = Exemplar::where('book_id', $book->id)->count() + 1;
                        $uniqueCode = 'UTB-' . str_pad($totalExemplar, 3, '0', STR_PAD_LEFT);
                    }

                    // 3. Buat eksemplar fisik baru pengganti
                    Exemplar::create([
                        'book_id' => $book->id,
                        'code' => $uniqueCode,
                        'qr_code' => $uniqueCode,
                        'status' => 'available' // Yang available HANYA eksemplar baru ini
                    ]);
                }

                // 4. Ubah status relasi eksemplar lama ini menjadi 'returned', tapi fisik lamanya TETAP 'lost'
                $borrowedExemplar->update([
                    'status' => 'returned'
                ]);
            }
        } else {
            // Fallback jika tidak ada data di borrowedExemplars
            $detail = $borrowing->details->first();
            if ($detail && $detail->book) {
                $detail->book->increment('stock', 1);

                $lastExemplar = Exemplar::where('book_id', $detail->book_id)
                    ->where('code', 'NOT LIKE', 'EX-%')
                    ->latest('id')
                    ->first();

                if ($lastExemplar && str_contains($lastExemplar->code, '-')) {
                    $parts = explode('-', $lastExemplar->code);
                    $prefix = $parts[0];
                    $lastNumber = intval(end($parts));
                    $uniqueCode = $prefix . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $totalExemplar = Exemplar::where('book_id', $detail->book_id)->count() + 1;
                    $uniqueCode = 'UTB-' . str_pad($totalExemplar, 3, '0', STR_PAD_LEFT);
                }

                Exemplar::create([
                    'book_id' => $detail->book_id,
                    'code' => $uniqueCode,
                    'qr_code' => $uniqueCode,
                    'status' => 'available'
                ]);
            }
        }

        // ==========================================
        // PERBAIKAN LOGIKA STATUS TRANSAKSI UTAMA
        // ==========================================
        $remainingLost = $borrowing->borrowedExemplars()->where('status', 'lost')->count();
        // Cek apakah masih ada sisa buku yang masih aktif dipinjam oleh siswa
        $hasActiveBooks = $borrowing->borrowedExemplars()->whereIn('status', ['borrowed'])->exists();
if ($remainingLost === 0) {

    $borrowing->update([
        'status' => 'dikembalikan'
    ]);

}

        // Kirim notifikasi ke mobile
        if (!empty($borrowing->user->fcm_token)) {
            FirebaseService::send(
                $borrowing->user->fcm_token,
                '📕 Kasus Buku Hilang Selesai',
                'Kasus buku hilang Anda telah diselesaikan.'
            );
        }

        return back()->with(
            'success',
            'Kasus buku hilang berhasil diselesaikan & eksemplar pengganti ditambahkan!'
        );

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
    }
}

public function selesaikanBukuRusak($id)
{
    try {
        $borrowing = Borrowing::with(['user', 'borrowedExemplars.exemplar.book', 'details.book'])->findOrFail($id);

        // Ambil HANYA eksemplar yang statusnya benar-benar 'damaged' pada peminjaman ini
        $lostBorrowingExemplars = $borrowing->borrowedExemplars()->where('status', 'damaged')->get();

        if ($lostBorrowingExemplars->count() > 0) {
            // Proses HANYA untuk eksemplar yang rusak
            foreach ($lostBorrowingExemplars as $borrowedExemplar) {
                $book = $borrowedExemplar->exemplar->book ?? null;
                
                if ($book) {
                    // 1. Tambah stok total HANYA untuk buku yang rusak itu saja
                    $book->increment('stock', 1);

                    // 2. Deteksi pola nama eksemplar
                    $lastExemplar = Exemplar::where('book_id', $book->id)
                        ->where('code', 'NOT LIKE', 'EX-%')
                        ->latest('id')
                        ->first();

                    if ($lastExemplar && str_contains($lastExemplar->code, '-')) {
                        $parts = explode('-', $lastExemplar->code);
                        $prefix = $parts[0];
                        $lastNumber = intval(end($parts));
                        $nextNumber = $lastNumber + 1;
                        $uniqueCode = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                    } else {
                        $totalExemplar = Exemplar::where('book_id', $book->id)->count() + 1;
                        $uniqueCode = 'UTB-' . str_pad($totalExemplar, 3, '0', STR_PAD_LEFT);
                    }

                    // 3. Buat eksemplar fisik baru pengganti
                    Exemplar::create([
                        'book_id' => $book->id,
                        'code' => $uniqueCode,
                        'qr_code' => $uniqueCode,
                        'status' => 'available' // Yang available HANYA eksemplar baru ini
                    ]);
                }

                // 4. Ubah status relasi eksemplar lama ini menjadi 'returned', tapi fisik lamanya TETAP 'lost'
                $borrowedExemplar->update([
                    'status' => 'returned'
                ]);
            }
        } else {
            // Fallback jika tidak ada data di borrowedExemplars
            $detail = $borrowing->details->first();
            if ($detail && $detail->book) {
                $detail->book->increment('stock', 1);

                $lastExemplar = Exemplar::where('book_id', $detail->book_id)
                    ->where('code', 'NOT LIKE', 'EX-%')
                    ->latest('id')
                    ->first();

                if ($lastExemplar && str_contains($lastExemplar->code, '-')) {
                    $parts = explode('-', $lastExemplar->code);
                    $prefix = $parts[0];
                    $lastNumber = intval(end($parts));
                    $uniqueCode = $prefix . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $totalExemplar = Exemplar::where('book_id', $detail->book_id)->count() + 1;
                    $uniqueCode = 'UTB-' . str_pad($totalExemplar, 3, '0', STR_PAD_LEFT);
                }

                Exemplar::create([
                    'book_id' => $detail->book_id,
                    'code' => $uniqueCode,
                    'qr_code' => $uniqueCode,
                    'status' => 'available'
                ]);
            }
        }

        // ==========================================
        // PERBAIKAN LOGIKA STATUS TRANSAKSI UTAMA
        // ==========================================
        $remainingLost = $borrowing->borrowedExemplars()->where('status', 'damaged')->count();
        // Cek apakah masih ada sisa buku yang masih aktif dipinjam oleh siswa
        $hasActiveBooks = $borrowing->borrowedExemplars()->whereIn('status', ['borrowed'])->exists();
if ($remainingLost === 0) {

    $borrowing->update([
        'status' => 'dikembalikan'
    ]);

}

        // Kirim notifikasi ke mobile
        if (!empty($borrowing->user->fcm_token)) {
            FirebaseService::send(
                $borrowing->user->fcm_token,
                '📕 Kasus Buku Rusak Selesai',
                'Kasus buku rusak Anda telah diselesaikan.'
            );
        }

        return back()->with(
            'success',
            'Kasus buku rusak berhasil diselesaikan!'
        );

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
    }
}
/**
 * Fungsi ini digunakan untuk menandai eksemplar tertentu
 * sebagai hilang pada sistem perpustakaan.
 */
public function lostExemplar($id)
{
    // Mengambil data eksemplar yang dipinjam.
    $borrowed =
        BorrowingExemplar::findOrFail($id);
// Mengubah status riwayat peminjaman eksemplar menjadi hilang.
    $borrowed->update([
        'status' => 'lost'
    ]);
// Mengubah status eksemplar menjadi hilang
// sehingga tidak dapat digunakan lagi.
    $borrowed->exemplar->update([
        'status' => 'lost'
    ]);

// Menampilkan pesan bahwa status eksemplar berhasil diperbarui.
    return back()->with(
        'success',
        'Exemplar berhasil ditandai hilang'
    );
}
public function damagedExemplar($id)
{
    // Mengambil data eksemplar yang dipinjam.
    $borrowed =
        BorrowingExemplar::findOrFail($id);
// Mengubah status riwayat peminjaman eksemplar menjadi hilang.
    $borrowed->update([
        'status' => 'damaged'
    ]);
// Mengubah status eksemplar menjadi hilang
// sehingga tidak dapat digunakan lagi.
    $borrowed->exemplar->update([
        'status' => 'damaged'
    ]);

// Menampilkan pesan bahwa status eksemplar berhasil diperbarui.
    return back()->with(
        'success',
        'Exemplar berhasil ditandai Rusak'
    );
}
    // --- SIRKULASI ---
    /**
 * Fungsi ini digunakan untuk menampilkan halaman Peminjaman pada website,
 * yang berisi daftar pengajuan peminjaman, riwayat transaksi,
 * serta data siswa dan guru.
 */
   public function peminjaman(Request $request)
{
    // Mengambil kata kunci pencarian
// untuk memfilter data peminjaman.
     $search = $request->search;
    // SISWA PENDING
    // Mengambil seluruh pengajuan peminjaman siswa
// yang masih menunggu persetujuan pustakawan.
    $siswaPending = Borrowing::with([
       
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
    
    ->whereHas('user', function ($q) {
        $q->where('role', 'siswa');
    })
    ->where('status', 'menunggu')
    ->latest()
    ->get();
// Mengambil seluruh pengajuan perpanjangan siswa.
$siswaExtension = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
->whereHas('user', function ($q) {
    $q->where('role', 'siswa');
})
->where('extension_status', 'pending')
->latest()
->get();
    // Mengambil riwayat peminjaman siswa
// beserta fitur pencarian dan pagination.
    $siswaRiwayat = Borrowing::with([
      
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
    
    ->whereHas('user', function ($q) {
        $q->where('role', 'siswa');
    })
    ->whereIn('status', [
    'dipinjam',
    'menunggu_pengembalian',
    'dikembalikan',
    'ditolak'
])

->when($search, function ($query) use ($search) {

    $query->where(function ($q) use ($search) {

        $q->whereHas('user', function ($u) use ($search) {

            $u->where('name', 'like', "%{$search}%")
              ->orWhere('nis_nip', 'like', "%{$search}%");

        })

        ->orWhereHas('details.book', function ($b) use ($search) {

            $b->where('title', 'like', "%{$search}%");

        });

    });

})

->latest()
->paginate(10, ['*'], 'siswa')
->withQueryString();

    // Mengambil seluruh pengajuan peminjaman guru
// yang masih menunggu persetujuan pustakawan.
    $guruPending = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])

    ->whereHas('user', function ($q) {
        $q->where('role', 'guru');
    })
    ->where('status', 'menunggu')
    ->latest()
    ->get();
// Mengambil seluruh pengajuan perpanjangan guru.
$guruExtension = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
->whereHas('user', function ($q) {
    $q->where('role', 'guru');
})
->where('extension_status', 'pending')
->latest()
->get();
    // Mengambil riwayat peminjaman guru
// beserta fitur pencarian dan pagination.
    $guruRiwayat = Borrowing::with([
      
    'user',
    'details.book',
    'details.exemplar',
    'borrowedExemplars.exemplar'
])
    
    ->whereHas('user', function ($q) {
        $q->where('role', 'guru');
    })
    
->whereIn('status', [
    'dipinjam',
    'menunggu_pengembalian',
    'dikembalikan',
    'ditolak'
])
->when($search, function ($query) use ($search) {

    $query->where(function ($q) use ($search) {

        $q->whereHas('user', function ($u) use ($search) {

            $u->where('name', 'like', "%{$search}%")
              ->orWhere('nis_nip', 'like', "%{$search}%");

        })

        ->orWhereHas('details.book', function ($b) use ($search) {

            $b->where('title', 'like', "%{$search}%");

        });

    });

})

->latest()
->paginate(10, ['*'], 'guru')
->withQueryString();
// Mengambil data seluruh anggota
// untuk kebutuhan form peminjaman manual.
   $users = User::whereIn('role', ['siswa', 'guru'])->get();
// Mengambil daftar buku
// yang dapat dipilih saat membuat transaksi peminjaman.
    $books = Book::all();
// Mengirim seluruh data peminjaman ke halaman website.
    return view(
        'peminjaman',
        compact(
    'siswaPending',
    'siswaExtension',
    'siswaRiwayat',

    'guruPending',
    'guruExtension',
    'guruRiwayat',

    'users',
    'books'
)
    );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk membuat
 * transaksi peminjaman baru melalui website SISPUS.
 */
public function storePeminjaman(Request $request)
{
    // Memvalidasi data transaksi peminjaman
// yang diinput oleh pustakawan.
    $request->validate([

    'user_id' => 'required',

    'book_id' => 'required|array',

    'book_id.*' => 'exists:books,id',

    'loan_type' => 'required',
'is_collective' => 'nullable|boolean',

'class_name' => 'nullable|string',

'quantity' => 'nullable|integer|min:1',
]);
// Memastikan anggota tidak sedang diblokir
// karena keterlambatan atau laporan buku hilang.
    $lateBorrowing = Borrowing::where(
    'user_id',
    $request->user_id
)
->where(function ($q) {

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

    ->orWhereHas('borrowedExemplars', function ($sub) {

    $sub->whereIn('status', ['lost', 'damaged']);

});

})
->exists();

if ($lateBorrowing) {

    return redirect()->back()
    ->with(
        'error',
        'Pengguna sedang diblokir karena memiliki keterlambatan atau laporan buku hilang.'
    );
}
// Menentukan tanggal peminjaman
// dan batas pengembalian buku.
    $borrowDate = $request->borrow_date
    ? Carbon::parse($request->borrow_date)
    : Carbon::today();

if ($request->filled('return_date')) {

    $returnDate = Carbon::parse($request->return_date);

} else {

    if ($request->loan_type == 'harian') {

        $returnDate = $borrowDate->copy()->addDays(3);

    } elseif ($request->loan_type == 'mingguan') {

        $returnDate = $borrowDate->copy()->addDays(7);

    } else {

        $returnDate = $borrowDate->copy()->addDays(120);

    }

}
// Menyimpan transaksi peminjaman
// dengan status menunggu persetujuan.
$borrowing = Borrowing::create([

    'user_id' => $request->user_id,

    // sementara pakai buku pertama
    'book_id' => $request->book_id[0],

    'loan_type' => $request->loan_type,

    'borrow_date' => $borrowDate,

    'return_date' => $returnDate,

    'status' => 'menunggu'

]);
// Menyimpan daftar buku
// ke dalam detail transaksi peminjaman.
foreach ($request->book_id as $bookId) {

    BorrowingDetail::create([

        'borrowing_id' => $borrowing->id,

        'book_id' => $bookId,

        'qty' => $request->is_collective
            ? $request->quantity
            : 1

    ]);

}
// Menampilkan pesan bahwa pengajuan peminjaman berhasil dibuat.
    return redirect()->back()
        ->with('success', 'Pengajuan peminjaman berhasil dibuat');
}
    // --- DATA MASTER BUKU ---
    /**
 * Fungsi ini digunakan untuk menampilkan halaman Data Master Buku
 * pada website SISPUS, lengkap dengan daftar buku, kategori,
 * serta riwayat pengajuan perubahan data buku.
 */
    public function buku(Request $request)
{
    // Mengambil daftar buku beserta kategori dan eksemplarnya
// untuk ditampilkan pada halaman Data Master Buku.
   $books = Book::with([
        'category',
        'exemplars'
    ])
// Menerapkan fitur pencarian berdasarkan judul,
// penulis, atau penerbit buku.
    ->when($request->search, function ($query) use ($request) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('publisher', 'like', "%{$search}%");

        });

    })
// Menampilkan data buku dari yang terbaru ditambahkan.
    ->latest()
// Membatasi jumlah data yang ditampilkan
// agar halaman lebih ringan dan mudah digunakan.
    ->paginate(10)

    ->withQueryString();
    // Mengambil seluruh kategori buku
// untuk kebutuhan filter maupun form tambah/edit buku.
    $categories = Category::all();
// Mengambil riwayat pengajuan penambahan, perubahan,
// atau penghapusan buku yang dibuat oleh pustakawan.
   // Mengambil riwayat pengajuan buku dengan fitur
// pencarian, filter status, dan filter aksi.
$approvals = BookApproval::where(
    'requested_by',
    Auth::id()
)

->when($request->approval_search, function ($query) use ($request) {

    $query->where('book_data->title', 'like', '%' . $request->approval_search . '%');

})

->when($request->approval_status, function ($query) use ($request) {

    $query->where('status', $request->approval_status);

})

->when($request->approval_action, function ($query) use ($request) {

    $query->where('action', $request->approval_action);

})

->latest()

->get();
// Mengirim seluruh data buku, kategori,
// dan riwayat pengajuan ke halaman website.
    return view(
        'buku',
        compact(
            'books',
            'categories',
            'approvals'
        )
    );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk menyetujui
 * pengajuan peminjaman yang berasal dari aplikasi mobile
 * maupun website SISPUS.
 */
public function approvePeminjaman($id)
{
    // Mengambil data transaksi yang akan disetujui.
    $borrowing = Borrowing::with(
        'details.book'
    )->findOrFail($id);

    // Memastikan stok buku dan jumlah eksemplar
// masih mencukupi sebelum peminjaman disetujui.
    foreach ($borrowing->details as $detail) {

        if ($detail->book->stock < $detail->qty) {

            return redirect()->back()
                ->with(
                    'error',
                    'Stok buku ' .
                    $detail->book->title .
                    ' tidak mencukupi'
                );

        }

        // cek exemplar tersedia
        $availableExemplar = Exemplar::where(
            'book_id',
            $detail->book_id
        )
        ->where(
            'status',
            'available'
        )
        ->count();

        if ($availableExemplar < $detail->qty) {

            return redirect()->back()
                ->with(
                    'error',
                    'Exemplar buku ' .
                    $detail->book->title .
                    ' tidak mencukupi'
                );

        }
    }

    // Mengubah status transaksi menjadi dipinjam.
    $borrowing->update([

        'status' => 'dipinjam'

    ]);

    // kurangi stok & assign exemplar
    foreach ($borrowing->details as $detail) {

      $detail->book->decrement(
    'stock',
    $detail->qty
);

        // ambil exemplar sesuai qty
        $exemplars = Exemplar::where(
            'book_id',
            $detail->book_id
        )
        ->where(
            'status',
            'available'
        )
        ->limit($detail->qty)
        ->get();
// Menghubungkan setiap eksemplar dengan transaksi peminjaman
// kemudian mengubah statusnya menjadi sedang dipinjam.
        foreach ($exemplars as $index => $exemplar) {

    if (!$borrowing->is_collective && $index == 0) {

        $detail->update([
            'exemplar_id' => $exemplar->id
        ]);
    }

    BorrowingExemplar::create([

        'borrowing_id' => $borrowing->id,

        'borrowing_detail_id' => $detail->id,

        'exemplar_id' => $exemplar->id,

        'status' => 'borrowed'

    ]);

    $exemplar->update([

        'status' => 'borrowed'

    ]);
}
    }
    // Mengirim notifikasi ke aplikasi mobile
// bahwa pengajuan peminjaman telah disetujui.
if (!empty($borrowing->user->fcm_token)) {

    if ($borrowing->is_collective) {

        FirebaseService::send(
            $borrowing->user->fcm_token,
            '📚 Peminjaman Kolektif Disetujui',
            'Peminjaman kolektif Anda telah disetujui.'
        );

    } else {

        FirebaseService::send(
            $borrowing->user->fcm_token,
            '📖 Peminjaman Disetujui',
            'Pengajuan peminjaman buku Anda telah disetujui.'
        );

    }

}
// Menampilkan pesan bahwa peminjaman berhasil disetujui.
    return redirect()->back()
        ->with(
            'success',
            'Peminjaman disetujui'
        );
}

public function approveExtension($id)
{
    // Mengambil data transaksi beserta pengguna.
    $borrowing = Borrowing::with('user')->findOrFail($id);

    // Memastikan memang ada pengajuan perpanjangan.
    if ($borrowing->extension_status != 'pending') {

        return back()->with(
            'error',
            'Tidak ada pengajuan perpanjangan.'
        );

    }

    // Menambahkan masa pinjam selama 3 hari.
    $borrowing->update([

        'return_date' => Carbon::parse(
            $borrowing->return_date
        )->addDays(3),

        'extension_status' => 'none',

        'extension_count' =>
            $borrowing->extension_count + 1,

        'extension_note' => null

    ]);

    // Mengirim FCM ke pengguna.
    if (!empty($borrowing->user->fcm_token)) {

        FirebaseService::send(

            $borrowing->user->fcm_token,

            '📅 Perpanjangan Disetujui',

            'Perpanjangan peminjaman disetujui. '
            .'Batas pengembalian baru: '
            .Carbon::parse($borrowing->return_date)
            ->translatedFormat('d F Y')

        );

    }

    return back()->with(

        'success',

        'Perpanjangan berhasil disetujui.'

    );
}
public function rejectExtension(
    Request $request,
    $id
)
{
    // Memastikan alasan wajib diisi.
    $request->validate([

        'extension_note' =>
            'required|string|max:500'

    ]);

    // Mengambil transaksi.
    $borrowing = Borrowing::with('user')
        ->findOrFail($id);

    // Memastikan masih pending.
    if ($borrowing->extension_status != 'pending') {

        return back()->with(

            'error',

            'Pengajuan tidak ditemukan.'

        );

    }

    // Menolak pengajuan.
    $borrowing->update([

        'extension_status' => 'none',

        'extension_note' =>
            $request->extension_note

    ]);

    // Kirim FCM.
    if (!empty($borrowing->user->fcm_token)) {

        FirebaseService::send(

            $borrowing->user->fcm_token,

            '❌ Perpanjangan Ditolak',

            'Pengajuan perpanjangan ditolak. '
            .'Alasan: '
            .$request->extension_note

        );

    }

    return back()->with(

        'success',

        'Pengajuan berhasil ditolak.'

    );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk menolak
 * pengajuan peminjaman buku.
 */
public function rejectPeminjaman(Request $request, $id)
{
    // Memvalidasi agar alasan penolakan wajib diisi.
    $request->validate([
        'rejection_note' => 'required|string|max:500'
    ]);

    // Mengambil data transaksi yang akan ditolak.
    $borrowing = Borrowing::with('user')->findOrFail($id);

    // Mengubah status transaksi menjadi ditolak
    // sekaligus menyimpan alasan penolakan.
    $borrowing->update([
        'status' => 'ditolak',
        'rejection_note' => $request->rejection_note
    ]);

    // Mengirim notifikasi ke aplikasi mobile
    // bahwa pengajuan peminjaman ditolak.
    if (!empty($borrowing->user->fcm_token)) {

        FirebaseService::send(
            $borrowing->user->fcm_token,
            '❌ Peminjaman Ditolak',
            'Pengajuan peminjaman Anda ditolak. Alasan: ' . $request->rejection_note
        );

    }

    // Menampilkan pesan bahwa pengajuan berhasil ditolak.
    return redirect()->back()->with(
        'success',
        'Pengajuan peminjaman berhasil ditolak.'
    );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk mengajukan
 * permintaan penghapusan buku kepada Kepala Pustakawan.
 */
public function destroyBuku($id)
{
    // Mengambil data buku yang akan diajukan untuk dihapus.
    $book = Book::findOrFail($id);
// Menyimpan permintaan penghapusan buku
// ke tabel persetujuan agar diverifikasi Kepala Pustakawan.
    $approval = BookApproval::create([
        'requested_by' => Auth::id(),

        'action' => 'delete',

        'book_id' => $book->id,

        'book_data' => [


    'id' => $book->id,
            'title' => $book->title,

            'author' => $book->author,

            'publisher' => $book->publisher,

            'stock' => $book->stock

        ]

    ]);
$approval = BookApproval::latest()->first();
// Mengambil seluruh akun Kepala Pustakawan
// yang akan menerima notifikasi persetujuan.
$kepalas = User::where(
    'role',
    'kepala_pustakawan'
)->get();
// Mengirim notifikasi ke dashboard Kepala Pustakawan
// mengenai pengajuan penghapusan buku.
foreach ($kepalas as $kepala) {

    $kepala->notify(

        new WebNotification(

            '🗑️ Pengajuan Hapus Buku',

            Auth::user()->name .
            ' mengajukan penghapusan buku "' .
            $approval->book_data['title'] .
            '".',

            '/kepala/approval'

        )

    );

}
// Menampilkan pesan bahwa pengajuan penghapusan berhasil dikirim.
    return redirect()->back()
        ->with(
            'success',
            'Permintaan hapus buku berhasil diajukan ke Kepala Perpustakaan'
        );
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk mengajukan
 * penambahan data buku baru melalui website SISPUS.
 */
    public function storeBuku(Request $request)
{
    // Memvalidasi data buku yang akan diajukan.
    $request->validate([

        'category_id'      => 'required',
        'title'            => 'required',
        'author'           => 'required',
        'publisher'        => 'required',
        'publication_year' => 'required|numeric',
        'stock'            => 'required|numeric',

        'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'ebook_file' => 'nullable|mimes:pdf|max:20480'
    ]);

    $coverPath = null;
    // Mengunggah gambar sampul buku
// ke penyimpanan server.
if ($request->hasFile('cover')) {

    $file = $request->file('cover');

    $coverPath = $file->store(
        'covers',
        'public'
    );

    $source = storage_path(
        'app/public/' . $coverPath
    );

    $destination =
        '/home/sisd2648/public_html/storage/' .
        $coverPath;

    if (!file_exists(dirname($destination))) {

        mkdir(
            dirname($destination),
            0755,
            true
        );
    }

    copy($source, $destination);
}
$ebookPath = null;

if ($request->hasFile('ebook_file')) {

    $ebookFile = $request->file('ebook_file');

    $ebookPath = $ebookFile->store(
        'ebooks',
        'public'
    );
    $source = storage_path(
    'app/public/' . $ebookPath
);

$destination =
    '/home/sisd2648/public_html/storage/' .
    $ebookPath;

if (!file_exists(dirname($destination))) {

    mkdir(
        dirname($destination),
        0755,
        true
    );
}

copy($source, $destination);

}
// Menyimpan pengajuan penambahan buku
// agar menunggu persetujuan Kepala Pustakawan.
   $approval = BookApproval::create([
    

    'requested_by' => Auth::id(),

    'action' => 'create',

    'book_data' => [

        'category_id' => $request->category_id,
        'title' => $request->title,
        'author' => $request->author,
        'publisher' => $request->publisher,
        'publication_year' => $request->publication_year,
        'description' => $request->description,
        'stock' => $request->stock,
        'cover' => $coverPath,
        'ebook_file' => $ebookPath

    ]

]);
$approval = BookApproval::latest()->first();
// Mengambil daftar Kepala Pustakawan
// yang akan menerima notifikasi.
$kepalas = User::where(
    'role',
    'kepala_pustakawan'
)->get();
// Mengirim notifikasi pengajuan buku baru
// ke dashboard Kepala Pustakawan.
foreach ($kepalas as $kepala) {

    $kepala->notify(

        new WebNotification(

            '📚 Pengajuan Buku Baru',

            Auth::user()->name .
            ' mengajukan penambahan buku "' .
            $approval->book_data['title'] .
            '".',

            '/kepala/approval'

        )

    );

}
// Menampilkan pesan bahwa pengajuan buku berhasil dibuat.
return redirect()->back()
    ->with(
        'success',
        'Pengajuan buku berhasil dikirim ke Kepala Perpustakaan'
    );}
/**
 * Fungsi ini digunakan untuk menambahkan kategori buku
 * pada Data Master Buku.
 */
    
public function storeKategori(Request $request)
{
    // Memastikan nama kategori belum pernah digunakan.
    $request->validate([
        'name' => 'required|unique:categories,name'
    ]);
// Menyimpan kategori buku baru ke database.
    Category::create([
        'name' => $request->name
    ]);
// Menampilkan pesan bahwa kategori berhasil ditambahkan.
    return redirect()->back()
        ->with('success', 'Kategori berhasil ditambahkan');
}
/**
 * Fungsi ini digunakan untuk menghapus kategori buku
 * dari Data Master Buku.
 */
public function destroyKategori($id)
{
    // Mengambil data kategori yang akan dihapus.
    $category = Category::findOrFail($id);
// Menghapus kategori buku dari database.
    $category->delete();
// Menampilkan pesan bahwa kategori berhasil dihapus.
    return redirect()->back()
        ->with('success', 'Kategori berhasil dihapus');
}
//Pustakawan → membuat pengajuan → Kepala Pustakawan menyetujui → baru database berubah.
/**
 * Fungsi ini digunakan oleh pustakawan untuk mengajukan
 * perubahan data buku kepada Kepala Pustakawan.
 */
public function updateBuku(Request $request, $id)
{
    // Mengambil data buku yang akan diperbarui.
    $book = Book::findOrFail($id);
// Memvalidasi data perubahan buku.
    $request->validate([

        'category_id'      => 'required',
        'title'            => 'required',
        'author'           => 'required',
        'publisher'        => 'required',
        'publication_year' => 'required|numeric',
        'stock'            => 'required|numeric',

        'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'ebook_file' => 'nullable|mimes:pdf|max:20480',
    ]);
$coverPath = $book->cover;
$ebookPath = $book->ebook_file;

// Mengunggah sampul baru apabila pengguna
// mengganti gambar buku.
if ($request->hasFile('cover')) {

    if (
        $book->cover &&
        Storage::disk('public')->exists($book->cover)
    ) {

        Storage::disk('public')->delete(
            $book->cover
        );
    }

    $file = $request->file('cover');

    $coverPath = $file->store(
        'covers',
        'public'
    );

    $source = storage_path(
        'app/public/' . $coverPath
    );

    $destination =
        '/home/sisd2648/public_html/storage/' .
        $coverPath;

    if (!file_exists(dirname($destination))) {

        mkdir(
            dirname($destination),
            0755,
            true
        );
    }

    copy($source, $destination);
}

// Mengunggah ebook baru apabila pengguna
// mengganti file PDF.
if ($request->hasFile('ebook_file')) {

    if (
        $book->ebook_file &&
        Storage::disk('public')->exists($book->ebook_file)
    ) {

        Storage::disk('public')->delete(
            $book->ebook_file
        );

    }

    $ebookPath = $request
        ->file('ebook_file')
        ->store(
            'ebooks',
            'public'
        );

    $source = storage_path(
        'app/public/' . $ebookPath
    );

    $destination =
        '/home/sisd2648/public_html/storage/' .
        $ebookPath;

    if (!file_exists(dirname($destination))) {

        mkdir(
            dirname($destination),
            0755,
            true
        );
    }

    copy($source, $destination);
}

// Menyimpan pengajuan perubahan buku
// untuk diverifikasi Kepala Pustakawan.
   $approval = BookApproval::create([

    'requested_by' => Auth::id(),

    'action' => 'update',

    'book_id' => $book->id,

    'book_data' => [
         'id' => $book->id,
        'category_id'      => $request->category_id,
        'title'            => $request->title,
        'author'           => $request->author,
        'publisher'        => $request->publisher,
        'publication_year' => $request->publication_year,
        'description'      => $request->description,
        'stock'            => $request->stock,
        'cover'            => $coverPath,
        'ebook_file'       => $ebookPath

    ]

]);
$approval = BookApproval::latest()->first();
// Mengambil seluruh akun Kepala Pustakawan
// yang akan menerima notifikasi.
$kepalas = User::where(
    'role',
    'kepala_pustakawan'
)->get();
// Mengirim notifikasi pengajuan perubahan buku
// ke dashboard Kepala Pustakawan.
foreach ($kepalas as $kepala) {

    $kepala->notify(

        new WebNotification(

            '✏️ Pengajuan Perubahan Buku',

            Auth::user()->name .
            ' mengajukan perubahan buku "' .
            $approval->book_data['title'] .
            '".',

            '/kepala/approval'

        )

    );

}
// Menampilkan pesan bahwa pengajuan perubahan berhasil dikirim.
return redirect()->back()
    ->with(
        'success',
        'Perubahan buku berhasil diajukan ke Kepala Perpustakaan'
    );
}
/**
 * Fungsi ini digunakan untuk menampilkan halaman Pengembalian
 * pada website SISPUS yang berisi daftar pengajuan pengembalian
 * dari siswa dan guru.
 */
public function pengembalian()
{
    // 1. Ambil pengembalian siswa tanpa filter 'lost'
    $siswaPengembalian = Borrowing::with(['user', 'details.book', 'details.exemplar', 'borrowedExemplars'])
        ->whereHas('user', function ($q) {
            $q->where('role', 'siswa');
        })
        ->where('status', 'menunggu_pengembalian')
        ->latest()
        ->get();

    // 2. Ambil pengembalian guru tanpa filter 'lost'
    $guruPengembalian = Borrowing::with(['user', 'details.book', 'details.exemplar', 'borrowedExemplars'])
        ->whereHas('user', function ($q) {
            $q->where('role', 'guru');
        })
        ->where('status', 'menunggu_pengembalian')
        ->latest()
        ->get();

    return view('pengembalian', compact('siswaPengembalian', 'guruPengembalian'));
}
/**
 * Fungsi ini digunakan oleh pustakawan untuk memverifikasi
 * pengembalian buku sehingga transaksi dinyatakan selesai
 * dan stok buku kembali tersedia.
 */
public function approvePengembalian($id)
{
    $borrowing = Borrowing::with(['user', 'details.book', 'details.exemplar'])->findOrFail($id);

    // CEK LOGIKA: Apakah transaksi ini mengandung buku yang dilaporkan hilang?
    $hasProblemBook = BorrowingExemplar::where('borrowing_id', $borrowing->id)
    ->whereIn('status', ['lost', 'damaged'])
    ->exists();

if ($hasProblemBook) {

    // Tetap berada di proses pengembalian
    $borrowing->update([
        'status' => 'menunggu_pengembalian',
        'reminder_sent' => false,
        'block_notification_sent' => false,
    ]);

} else {

    // Semua buku sudah selesai
    $borrowing->update([
        'status' => 'dikembalikan',
        'returned_at' => now(),
        'reminder_sent' => false,
        'block_notification_sent' => false,
    ]);

}
    $totalBooksReturned = 0;

    foreach ($borrowing->details as $detail) {
        // ==========================
        // KOLEKTIF
        // ==========================
        if ($borrowing->is_collective) {
            // Status 'borrowed' otomatis mengabaikan yang statusnya 'lost'
            $borrowedExemplars = BorrowingExemplar::where('borrowing_id', $borrowing->id)
                ->where('borrowing_detail_id', $detail->id)
                ->where('status', 'borrowed')
                ->get();

            $countReturned = $borrowedExemplars->count();
            if ($countReturned > 0) {
                $detail->book->increment('stock', $countReturned);
                $totalBooksReturned += $countReturned;

                foreach ($borrowedExemplars as $borrowed) {
                    $borrowed->update(['status' => 'returned']);
                    $borrowed->exemplar->update(['status' => 'available']);
                }
            }
        }
        // ==========================
        // INDIVIDU
        // ==========================
        else {
            // PASTIKAN HANYA BUKU YANG TIDAK HILANG YANG DIKEMBALIKAN STOKNYA
            if ($detail->exemplar && $detail->exemplar->status != 'lost' && $detail->exemplar->status != 'damaged') {
                $detail->book->increment('stock', $detail->qty);
                $detail->exemplar->update(['status' => 'available']);
                
                BorrowingExemplar::where('borrowing_id', $borrowing->id)
                    ->where('exemplar_id', $detail->exemplar_id)
                    ->update(['status' => 'returned']);

                $totalBooksReturned += $detail->qty;
            }
        }
    }

    $user = $borrowing->user;
    
    // Hitung poin gamifikasi HANYA berdasarkan jumlah buku yang benar-benar dikembalikan
    if ($totalBooksReturned > 0) {
        if (now()->toDateString() > $borrowing->return_date) {
            $user->points -= ($totalBooksReturned * 2);
        } else {
            $user->points += ($totalBooksReturned * 3);
        }

        if ($user->points < 0) { $user->points = 0; }
        if ($user->points > 200) { $user->badge = 'Platinum'; } 
        elseif ($user->points > 100) { $user->badge = 'Gold'; } 
        elseif ($user->points > 50) { $user->badge = 'Silver'; } 
        else { $user->badge = 'Bronze'; }
        
        $user->save();
    }

    // Notifikasi
    if (!empty($user->fcm_token)) {
        if ($borrowing->is_collective) {
            FirebaseService::send($user->fcm_token, '📚 Pengembalian Kolektif Berhasil', 'Pengembalian kolektif telah diverifikasi oleh pustakawan.');
        } else {
            FirebaseService::send($user->fcm_token, '✅ Pengembalian Berhasil', 'Pengembalian buku Anda telah diverifikasi.');
        }
    }

    // Berikan pesan peringatan yang sesuai kepada Pustakawan
    $message = $hasProblemBook
    ? 'Sebagian buku berhasil diverifikasi. Masih terdapat buku hilang atau rusak sehingga transaksi tetap berada pada proses pengembalian sampai pustakawan menyelesaikannya.'
    : 'Pengembalian berhasil diverifikasi seluruhnya.';
    return back()->with('success', $message);
}

/**
 * Fungsi ini digunakan untuk menandai beberapa eksemplar
 * sebagai hilang pada peminjaman kolektif.
 */
public function bulkLostExemplar(
    Request $request,
    $borrowingId
)
{
    // Mengambil daftar eksemplar
// yang dipilih sebagai hilang.
    $ids =
        $request->borrowed_exemplar_ids ?? [];
// Memproses setiap eksemplar
// yang dilaporkan hilang.
    foreach ($ids as $id) {

        $borrowed =
            BorrowingExemplar::find($id);

        if (!$borrowed) {
            continue;
        }
// Mengubah status riwayat peminjaman
// menjadi hilang.
        $borrowed->update([

            'status' => 'lost'

        ]);
// Mengubah status eksemplar
// menjadi hilang permanen.
        $borrowed->exemplar->update([

            'status' => 'lost'

        ]);

    }
// Menampilkan pesan bahwa status eksemplar berhasil diperbarui.
    return back()->with(
        'success',
        'Exemplar berhasil ditandai hilang'
    );
}

public function bulkDamagedExemplar(
    Request $request,
    $borrowingId
)
{
    // Mengambil daftar eksemplar
// yang dipilih sebagai hilang.
    $ids =
        $request->borrowed_exemplar_ids ?? [];
// Memproses setiap eksemplar
// yang dilaporkan hilang.
    foreach ($ids as $id) {

        $borrowed =
            BorrowingExemplar::find($id);

        if (!$borrowed) {
            continue;
        }
// Mengubah status riwayat peminjaman
// menjadi hilang.
        $borrowed->update([

            'status' => 'damaged'

        ]);
// Mengubah status eksemplar
// menjadi hilang permanen.
        $borrowed->exemplar->update([

            'status' => 'damaged'

        ]);

    }
// Menampilkan pesan bahwa status eksemplar berhasil diperbarui.
    return back()->with(
        'success',
        'Exemplar berhasil ditandai Rusak'
    );
}
public function bulkLostIndividu(
    Request $request,
    $borrowingId
)
{
    // Mengambil daftar detail
    // yang dipilih sebagai hilang.
    $ids = $request->detail_ids ?? [];

    foreach ($ids as $id) {

        $detail = BorrowingDetail::find($id);

        if (!$detail || !$detail->exemplar) {
            continue;
        }

        // Mengubah status riwayat peminjaman
        // menjadi hilang.
        BorrowingExemplar::where('borrowing_id', $borrowingId)
            ->where('exemplar_id', $detail->exemplar_id)
            ->update([
                'status' => 'lost'
            ]);

        // Mengubah status eksemplar
        // menjadi hilang permanen.
        $detail->exemplar->update([
            'status' => 'lost'
        ]);
    }

    // Menampilkan pesan bahwa status buku berhasil diperbarui.
    return back()->with(
        'success',
        'Buku berhasil ditandai hilang'
    );
}

public function bulkDamagedIndividu(
    Request $request,
    $borrowingId
)
{
    // Mengambil daftar detail
    // yang dipilih sebagai hilang.
    $ids = $request->detail_ids ?? [];

    foreach ($ids as $id) {

        $detail = BorrowingDetail::find($id);

        if (!$detail || !$detail->exemplar) {
            continue;
        }

        // Mengubah status riwayat peminjaman
        // menjadi hilang.
        BorrowingExemplar::where('borrowing_id', $borrowingId)
            ->where('exemplar_id', $detail->exemplar_id)
            ->update([
                'status' => 'damaged'
            ]);

        // Mengubah status eksemplar
        // menjadi hilang permanen.
        $detail->exemplar->update([
            'status' => 'damaged'
        ]);
    }

    // Menampilkan pesan bahwa status buku berhasil diperbarui.
    return back()->with(
        'success',
        'Buku berhasil ditandai Rusak'
    );
}
  /**
 * Fungsi ini digunakan untuk menampilkan daftar anggota perpustakaan
 * (siswa dan guru) lengkap dengan fitur pencarian dan paginasi.
 */
public function anggota(Request $request)
{
    // Mengambil data user yang memiliki role 'siswa' atau 'guru' sekaligus menghitung jumlah peminjamannya
    $users = User::withCount('borrowings')
        ->whereIn('role', ['siswa', 'guru'])

        // Mengecek apakah ada parameter pencarian (search) yang dikirimkan dari request
        ->when($request->search, function ($query) use ($request) {

            $search = $request->search;

            // Melakukan pencarian berdasarkan kolom name, email, atau nis_nip yang cocok dengan keyword
            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%");

            });

        })

        // Mengurutkan dari data terbaru, membaginya 10 data per halaman, dan mempertahankan query string URL
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Mengembalikan tampilan view 'anggota' dengan membawa data $users
    return view('anggota', compact('users'));
}

/**
 * Fungsi ini digunakan untuk menampilkan halaman detail informasi profil
 * serta riwayat peminjaman buku dari seorang anggota tertentu berdasarkan ID.
 */
public function detailAnggota($id)
{
    // Mengambil data user berdasarkan ID beserta relasi peminjaman dan bukunya, atau lempar error 404 jika tidak ada
    $user = User::with([
        'borrowings.book'
    ])->findOrFail($id);

    // Mengembalikan tampilan view 'detail-anggota' dengan membawa data $user
    return view(
        'detail-anggota',
        compact('user')
    );
}

/**
 * Fungsi ini digunakan untuk mengunduh file template Excel
 * yang nantinya digunakan oleh admin untuk mengimpor data anggota secara massal.
 */
public function downloadTemplate()
{
    // Mengunduh file template Excel yang digunakan untuk import data anggota
    return Excel::download(
        new UserTemplateExport,
        'Template_Data_Anggota.xlsx'
    );
}

/**
 * Fungsi ini digunakan untuk memproses dan mengimpor data anggota baru
 * ke dalam database berdasarkan file Excel yang diunggah oleh admin.
 */
public function importExcel(Request $request)
{
    // Memvalidasi bahwa file wajib diunggah dan harus berformat xlsx atau xls
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    // Memproses import data anggota ke database menggunakan class UserImport
    Excel::import(
        new UserImport,
        $request->file('file')
    );

    // Mengembalikan pengguna ke halaman sebelumnya dengan pesan sukses
    return back()->with(
        'success',
        'Data anggota berhasil diimpor.'
    );
}

/**
 * Fungsi ini digunakan untuk mengubah status keaktifan akun anggota
 * (dari aktif menjadi non-aktif atau sebaliknya) berdasarkan ID user.
 */
public function toggleStatus($id)
{
    // Mencari data user berdasarkan ID, atau berikan error 404 jika tidak ditemukan
    $user = User::findOrFail($id);

    // Membalik nilai status keaktifan user (is_active menjadi kebalikannya)
    $user->update([
        'is_active' => !$user->is_active
    ]);

    // Mengembalikan ke halaman sebelumnya dengan pesan sukses pembaruan status
    return back()->with(
        'success',
        'Status akun berhasil diperbarui.'
    );
}

/**
 * Fungsi ini digunakan untuk menampilkan halaman leaderboard atau peringkat
 * gamifikasi siswa dan guru, statistik poin, badge, serta riwayat reward per periode.
 */
//leaderboard siswa & guru, riwayat siswa & guru, total poin, jumlah badge gold & platinum
public function gamifikasi(Request $request)
{
    // Mengambil periode reward yang sedang aktif saat ini
    $activePeriod = RewardPeriod::where('is_active', true)->first();

    // Mengambil seluruh daftar periode reward dari yang terbaru
    $periods = RewardPeriod::latest()->get();

    // Menentukan periode yang dipilih berdasarkan request, atau default ke ID periode aktif
    $selectedPeriod = $request->period ?? optional($activePeriod)->id;

    // Mengambil leaderboard siswa aktif diurutkan berdasarkan poin terbanyak (paginasi 10 item)
    $siswaUsers = User::where('role', 'siswa')
    ->where('is_active', 1)
    ->orderByDesc('points')
    ->paginate(10, ['*'], 'leaderboard_siswa');

    // Mengambil leaderboard guru aktif diurutkan berdasarkan poin terbanyak (paginasi 10 item)
    $guruUsers = User::where('role', 'guru')
    ->where('is_active', 1)
    ->orderByDesc('points')
    ->paginate(10, ['*'], 'leaderboard_guru');

    // Menghitung total akumulasi poin dari seluruh user yang aktif
    $totalPoints = User::where('is_active', 1)->sum('points');

    // Menghitung jumlah user aktif yang memiliki badge 'Gold'
    $goldUsers = User::where('is_active', 1)
        ->where('badge', 'Gold')
        ->count();

    // Menghitung jumlah user aktif yang memiliki badge 'Platinum'
    $platinumUsers = User::where('is_active', 1)
        ->where('badge', 'Platinum')
        ->count();

    // Mengambil riwayat reward siswa berdasarkan periode yang dipilih, diurutkan berdasarkan peringkat
    $historySiswa = RewardHistory::with(['user','period'])
        ->where('reward_period_id', $selectedPeriod)
        ->whereHas('user', function ($q) {
            $q->where('role', 'siswa');
        })
        ->orderBy('rank')
        ->paginate(10, ['*'], 'history_siswa');

    // Mengambil riwayat reward guru berdasarkan periode yang dipilih, diurutkan berdasarkan peringkat
    $historyGuru = RewardHistory::with(['user','period'])
        ->where('reward_period_id', $selectedPeriod)
        ->whereHas('user', function ($q) {
            $q->where('role', 'guru');
        })
        ->orderBy('rank')
      ->paginate(10, ['*'], 'history_guru');

    // Mengembalikan tampilan view 'gamifikasi' dengan membawa seluruh variabel data gamifikasi
    return view(
        'gamifikasi',
        compact(
            'activePeriod',
            'periods',
            'selectedPeriod',
            'siswaUsers',
            'guruUsers',
            'historySiswa',
            'historyGuru',
            'totalPoints',
            'goldUsers',
            'platinumUsers'
        )
    );
}

/**
 * Fungsi ini digunakan untuk menutup periode reward yang sedang aktif,
 * menyimpan data peringkat akhir ke riwayat, dan mereset poin user ke nol.
 */
public function closeRewardPeriod()
{
    // Memulai transaksi database (DB Transaction) untuk menjaga konsistensi data
    DB::beginTransaction();

    try {

        // Mengambil periode reward yang sedang berstatus aktif
        $period = RewardPeriod::where('is_active', true)->first();

        // Mengecek apakah masih ada peminjaman buku dengan status belum selesai
        $activeBorrowing = Borrowing::whereIn('status', [
            'dipinjam',
            'menunggu_pengembalian'
        ])->exists();

        // Jika masih ada peminjaman aktif, batalkan penutupan dan kembalikan pesan error
        if ($activeBorrowing) {
            return back()->with(
                'error',
                'Masih ada peminjaman yang belum selesai. Selesaikan seluruh pengembalian sebelum menutup periode.'
            );
        }

        // Jika tidak ditemukan periode aktif, kembalikan pesan error
        if (!$period) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN LEADERBOARD SISWA
        |--------------------------------------------------------------------------
        */

        // Mengambil data siswa aktif diurutkan berdasarkan poin tertinggi
        $siswaUsers = User::where('role', 'siswa')
            ->where('is_active', 1)
            ->orderByDesc('points')
            ->get();

        // Perulangan untuk menyimpan setiap peringkat siswa ke tabel RewardHistory
        foreach ($siswaUsers as $index => $user) {

            RewardHistory::create([

                'reward_period_id' => $period->id,

                'user_id' => $user->id,

                'rank' => $index + 1,

                'points' => $user->points,

                'badge' => $user->badge,

        

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN LEADERBOARD GURU
        |--------------------------------------------------------------------------
        */

        // Mengambil data guru aktif diurutkan berdasarkan poin tertinggi
        $guruUsers = User::where('role', 'guru')
            ->where('is_active', 1)
            ->orderByDesc('points')
            ->get();

        // Perulangan untuk menyimpan setiap peringkat guru ke tabel RewardHistory
        foreach ($guruUsers as $index => $user) {

            RewardHistory::create([

                'reward_period_id' => $period->id,

                'user_id' => $user->id,

                'rank' => $index + 1,

                'points' => $user->points,

                'badge' => $user->badge,

                
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESET POINT
        |--------------------------------------------------------------------------
        */

        // Mereset poin semua siswa dan guru menjadi 0 serta mengembalikan badge menjadi 'Bronze'
        $count = User::whereIn('role', ['siswa','guru'])
            ->update([
                'points' => 0,
                'badge' => 'Bronze',
            ]);


        /*
        |--------------------------------------------------------------------------
        | TUTUP PERIODE
        |--------------------------------------------------------------------------
        */

        // Mengubah status keaktifan periode reward menjadi false (ditutup)
        $period->update([
            'is_active' => false
        ]);

        // Menyimpan semua perubahan ke database secara permanen
        DB::commit();

        return back()->with(
            'success',
            'Periode berhasil ditutup.'
        );

    } catch (\Exception $e) {

        // Membatalkan seluruh perubahan database jika terjadi error di tengah proses
        DB::rollBack();

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}  

/**
 * Fungsi ini digunakan untuk membuat dan menyimpan data periode reward baru
 * serta otomatis menonaktifkan periode reward yang sebelumnya berjalan.
 */
public function storeRewardPeriod(Request $request)
{
    // Memvalidasi data input form untuk pembuatan periode reward baru
    $request->validate([

        'semester'=>'required',

        'academic_year'=>'required',

        'start_date'=>'required|date',

        'end_date'=>'required|date',

    ]);

    // Mengubah seluruh status periode reward lain menjadi non-aktif (is_active = false)
    RewardPeriod::query()->update([
        'is_active'=>false
    ]);

    // Membuat dan menyimpan data periode reward baru dengan status aktif (is_active = true)
    RewardPeriod::create([

        'name'=>$request->semester.' '.$request->academic_year,

        'semester'=>$request->semester,

        'academic_year'=>$request->academic_year,

        'start_date'=>$request->start_date,

        'end_date'=>$request->end_date,

        'is_active'=>true

    ]);

    // Mengembalikan pengguna ke halaman sebelumnya dengan pesan sukses
    return back()->with(
        'success',
        'Periode berhasil ditambahkan.'
    );
}

/**
 * Fungsi ini digunakan untuk menampilkan halaman antarmuka analisis
 * asosiasi data peminjaman buku menggunakan algoritma Apriori.
 */
public function apriori() 
{ 
    // Mengembalikan view halaman apriori
    return view('apriori'); 
}

//laporan
/**
 * Fungsi ini digunakan untuk menghasilkan dan menampilkan halaman laporan lengkap
 * perpustakaan (statistik peminjaman, buku populer, kategori, dan hasil analisis apriori)
 * berdasarkan filter bulan dan tahun.
 */
public function laporan(Request $request)
{
    // Mengambil parameter bulan dan tahun dari request, atau default ke bulan dan tahun saat ini
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    // Membangun query dasar untuk peminjaman yang difilter berdasarkan bulan dan tahun
    $borrowingsQuery = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar'
])
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);

    // Menghitung total keseluruhan buku yang ada
    $totalBooks = Book::count();

    // Menghitung total anggota yang memiliki role siswa atau guru
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();

    // Menghitung total transaksi peminjaman pada periode tersebut menggunakan clone query
    $totalBorrowings = (clone $borrowingsQuery)->count();

    // Menghitung total peminjaman dengan status 'dikembalikan' pada periode tersebut
    $totalReturned = (clone $borrowingsQuery)
        ->where('status', 'dikembalikan')
        ->count();

    // Mengambil 5 buku terpopuler berdasarkan detail peminjaman
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
->take(5)
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

    // Mengambil daftar seluruh peminjaman terbaru sesuai filter
    $borrowings = $borrowingsQuery
        ->latest()
        ->get();

// Mengambil data 5 buku untuk kebutuhan chart/grafik
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

// Mengambil angka jumlah peminjaman buku untuk chart
$bookData = $chartBooks
    ->pluck('borrowings_count');

// Mengambil label kategori tipe pinjaman untuk chart
$categoryLabels = $popularCategories
    ->pluck('loan_type');

// Mengambil user dengan role kepala_pustakawan untuk tanda tangan laporan
$kepalaPustakawan = User::where(
    'role',
    'kepala_pustakawan'
)->first();

// Mengambil user dengan role pustakawan untuk tanda tangan laporan
$pustakawan = User::where(
    'role',
    'pustakawan'
)->first();

// Mengambil data jumlah total per kategori untuk chart
$categoryData = $popularCategories
    ->pluck('total');

// Inisialisasi array untuk proses perhitungan aturan asosiasi algoritma Apriori
$aprioriData = [];

// Mengambil seluruh transaksi peminjaman beserta detail bukunya
$transactions = Borrowing::with(
    'details.book'
)->get();

// Melakukan perulangan untuk mencari kombinasi buku yang sering dipinjam bersamaan
foreach ($transactions as $transaction) {

    $books = $transaction->details
        ->pluck('book.title')
        ->toArray();

    for ($i = 0; $i < count($books); $i++) {

        for ($j = $i + 1; $j < count($books); $j++) {

            $bookA = $books[$i];
            $bookB = $books[$j];

            $key = $bookA . '||' . $bookB;

            if (!isset($aprioriData[$key])) {

                $aprioriData[$key] = [
                    'buku_utama' => $bookA,
                    'buku_terkait' => $bookB,
                    'total' => 0
                ];

            }

            $aprioriData[$key]['total']++;

        }

    }

}

// Menghitung total peminjaman kolektif
$totalCollective = Borrowing::where(
    'is_collective',
    true
)->count();

// Menghitung total eksemplar buku kolektif
$totalCollectiveExemplars =
BorrowingExemplar::count();

// Menghitung total eksemplar buku yang berstatus hilang (lost)
$totalLostExemplars =
BorrowingExemplar::where(
    'status',
    'lost'
)->count();

// Mengambil daftar eksemplar buku yang berstatus hilang beserta relasinya
$lostExemplars =
BorrowingExemplar::with(
    'exemplar'
)
->where(
    'status',
    'lost'
)
->get();

// Menghitung total peminjaman kolektif
$collectiveBorrowings =
Borrowing::where(
    'is_collective',
    true
)
->count();

// Mengurutkan hasil data apriori dari total kemunculan terbanyak
$aprioriData = collect($aprioriData)
    ->sortByDesc('total')
    ->values();

    // Menghitung total peminjaman khusus untuk role siswa
    $totalBorrowingSiswa = Borrowing::whereHas(
    'user',
    fn($q) => $q->where('role', 'siswa')
)->count();

// Menghitung total peminjaman khusus untuk role guru
$totalBorrowingGuru = Borrowing::whereHas(
    'user',
    fn($q) => $q->where('role', 'guru')
)->count();

// Menghitung total peminjaman kolektif
$totalBorrowingKolektif = Borrowing::where(
    'is_collective',
    true
)->count();

    // Menyiapkan koleksi label role untuk chart
    $roleLabels = collect([
    'Siswa',
    'Guru',
    'Kolektif'
]);

// Menyiapkan koleksi data angka jumlah peminjaman per role untuk chart
$roleData = collect([
    $totalBorrowingSiswa,
    $totalBorrowingGuru,
    $totalBorrowingKolektif
]);

   // Mengembalikan view 'laporan' dengan membawa seluruh variabel data laporan yang dibutuhkan
   return view(
    'laporan',
    compact(
        'month',
        'year',
        'totalBooks',
        'totalMembers',
        'totalBorrowings',
        'totalReturned',
        'popularBooks',
        'popularCategories',
        'borrowings',
        'bookLabels',
        'bookData',
        'categoryLabels',
        'categoryData',
        'kepalaPustakawan',
        'pustakawan',
        'aprioriData',
        'totalBorrowingSiswa',
'totalBorrowingGuru',
'totalBorrowingKolektif',
'roleLabels',
'roleData',
    )
);

}

/**
 * Fungsi ini digunakan untuk mengunduh laporan bulanan perpustakaan
 * dalam bentuk dokumen file PDF siap cetak.
 */
public function downloadLaporanPdf(Request $request)
{
    // Mengambil parameter bulan dan tahun dari request untuk cetak PDF
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    // Menghitung total keseluruhan buku
    $totalBooks = Book::count();

    // Menghitung total anggota siswa dan guru
    $totalMembers = User::whereIn(
        'role',
        ['siswa', 'guru']
    )->count();

// Menyiapkan query peminjaman berdasarkan bulan dan tahun untuk laporan PDF
$borrowingsQuery = Borrowing::with([
    'user',
    'details.book',
    'details.exemplar'
])
    ->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);

    // Menghitung total peminjaman pada periode tersebut
    $totalBorrowings =
        (clone $borrowingsQuery)->count();

    // Menghitung total peminjaman yang berstatus 'dikembalikan'
    $totalReturned =
        (clone $borrowingsQuery)
        ->where(
            'status',
            'dikembalikan'
        )
        ->count();

// Menghitung total peminjaman yang mengalami keterlambatan pengembalian
$totalLate = (clone $borrowingsQuery)
    ->where(function ($query) {

        $query->where(function ($q) {

            $q->where('status', 'dipinjam')
              ->whereDate('return_date', '<', today());

        })

        ->orWhere(function ($q) {

            $q->where('status', 'dikembalikan')
              ->whereColumn('returned_at', '>', 'return_date');

        });

    })
    ->count();

   // Mengambil 5 buku terpopuler untuk laporan PDF
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
->take(5)
->get();

// Mengambil kategori peminjaman terpopuler untuk laporan PDF
$popularCategories = Borrowing::selectRaw(
    'loan_type, COUNT(*) as total'
)
->whereMonth('created_at', $month)
->whereYear('created_at', $year)
->groupBy('loan_type')
->orderByDesc('total')
->get();

    // Mengambil daftar seluruh peminjaman terbaru
    $borrowings =
        $borrowingsQuery
        ->latest()
        ->get();

    // Mengambil data user kepala pustakawan
    $kepalaPustakawan =
        User::where(
            'role',
            'kepala_pustakawan'
        )->first();

    // Mengambil data user pustakawan
    $pustakawan =
        User::where(
            'role',
            'pustakawan'
        )->first();

        // Mengonversi angka bulan menjadi nama bulan berformat teks bahasa Indonesia
        $monthName = Carbon::create(
    $year,
    (int)$month,
    1
)->translatedFormat('F');

// Menghitung total peminjaman siswa untuk laporan PDF
$totalBorrowingSiswa = Borrowing::whereHas(
    'user',
    fn($q) => $q->where('role', 'siswa')
)->count();

// Menghitung total peminjaman guru untuk laporan PDF
$totalBorrowingGuru = Borrowing::whereHas(
    'user',
    fn($q) => $q->where('role', 'guru')
)->count();

// Menghitung total peminjaman kolektif
$totalCollective = Borrowing::where(
    'is_collective',
    true
)->count();

// Menghitung total eksemplar kolektif
$totalCollectiveExemplars =
BorrowingExemplar::count();

// Menghitung total eksemplar buku yang hilang (lost)
$totalLostExemplars =
BorrowingExemplar::where(
    'status',
    'lost'
)->count();

// Mengambil daftar eksemplar buku yang hilang
$lostExemplars =
BorrowingExemplar::with(
    'exemplar'
)
->where(
    'status',
    'lost'
)
->get();

// Menghitung total peminjaman kolektif
$collectiveBorrowings =
Borrowing::where(
    'is_collective',
    true
)
->count();

// Menghitung total peminjaman kolektif untuk PDF
$totalBorrowingKolektif = Borrowing::where(
    'is_collective',
    true
)->count();

   // Memuat tampilan view PDF dengan template 'laporan-pdf' dan data yang diperlukan
   $pdf = Pdf::loadView(
    'laporan-pdf',
    
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
        'totalBorrowingSiswa',
'totalBorrowingGuru',
'totalBorrowingKolektif',
    )
);

// Memicu proses unduhan file laporan PDF dengan penamaan otomatis
return $pdf->download(
    'Laporan-'.$monthName.'-'.$year.'.pdf'
);
}

/**
 * Fungsi ini digunakan untuk menghapus seluruh riwayat notifikasi
 * milik pengguna yang sedang aktif/login.
 */
public function clearNotifications()
{
    // Menghapus data notifikasi dari tabel notifications berdasarkan user ID yang sedang aktif login
    DB::table('notifications')
        ->where('notifiable_id', Auth::id())
        ->where('notifiable_type', \App\Models\User::class)
        ->delete();

    // Mengembalikan pengguna ke halaman sebelumnya
    return back();
}
}