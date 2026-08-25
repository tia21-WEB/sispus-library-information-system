<?php

namespace App\Http\Controllers\Api;
// File ini berfungsi menyediakan data Dashboard pada aplikasi mobile,

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
/**
 * API ini digunakan oleh halaman Dashboard pada aplikasi mobile
 * untuk menampilkan seluruh informasi utama pengguna setelah login.
 */     
public function index($userId)
    {
        // Mengambil data pengguna yang sedang login.
        $user = User::findOrFail($userId);
        
        $activeBorrowings = Borrowing::with([
            'details.book',
            'details.exemplar',
            'borrowedExemplars.exemplar'
        ])
        ->where('user_id', $userId)
        ->where('status', 'dipinjam')
        ->orderBy('return_date')
        ->get();

// Mengambil daftar buku terpopuler berdasarkan jumlah peminjaman
        $popularBooks = Book::select(
        'books.*',
        DB::raw('COUNT(borrowing_details.book_id) as total_borrowed')
    )
    ->join(
        'borrowing_details',
        'books.id',
        '=',
        'borrowing_details.book_id'
    )
    ->groupBy(
    'books.id',
    'books.category_id',
    'books.title',
    'books.author',
    'books.publisher',
    'books.publication_year',
    'books.description',
    'books.cover',
    'books.book_type',
    'books.ebook_file',
    'books.stock',
    'books.created_at',
    'books.updated_at'
)
    ->havingRaw('COUNT(borrowing_details.book_id) > 0')
    ->orderByDesc('total_borrowed')
    ->take(10)
    ->get();

// Menyiapkan data notifikasi yang akan ditampilkan kepada pengguna.

$notification = null;

// [PERBAIKAN]: Ambil SEMUA borrowing yang memiliki eksemplar hilang/lost (baik individu maupun kolektif)
$lostBorrowings = Borrowing::with([
    'borrowedExemplars.exemplar',
    'details.book'
])
->where('user_id', $userId)
->whereHas('borrowedExemplars', function ($q) {
    $q->whereIn('status', ['lost', 'hilang']);
})
->get();

$lostBook = $lostBorrowings->isNotEmpty();

// Mengecek apakah pengguna memiliki peminjaman yang terlambat.
$lateBook = Borrowing::where('user_id', $userId)
->where('status', 'dipinjam')
->whereDate('return_date', '<', now())
->exists();

// Mengecek apakah terdapat pengajuan peminjaman
$waitingBorrow = Borrowing::where('user_id', $userId)
->where('status', 'menunggu')
->exists();

// Mengecek apakah pengembalian buku sedang menunggu verifikasi
$waitingReturn = Borrowing::where('user_id', $userId)
->where('status', 'menunggu_pengembalian')
->exists();

// Mengambil data peminjaman terdekat
$reminderBorrow = Borrowing::with('details.book')
    ->where('user_id', $userId)
    ->where('status', 'dipinjam')
    ->whereDate('return_date', '>=', now())
    ->orderBy('return_date')
    ->first();

$daysReminder = null;
$totalReminderBooks = $reminderBorrow ? $reminderBorrow->details->count() : 0;
$reminderText = '';

if ($reminderBorrow) {
    if ($totalReminderBooks == 1) {
        $reminderText = 'Buku "' . ($reminderBorrow->details->first()->book?->title ?? '-') . '"';
    } else {
        $reminderText = $totalReminderBooks . ' buku';
    }
}

if ($reminderBorrow) {
    $daysReminder = Carbon::today()->diffInDays(Carbon::parse($reminderBorrow->return_date), false);
}

// Menampilkan notifikasi prioritas
if ($lostBook) {
    // Hitung total keseluruhan buku hilang dari semua transaksi (individu + kolektif)
    $totalLostCount = $lostBorrowings->sum(function ($borrowing) {
        return $borrowing->borrowedExemplars->whereIn('status', ['lost', 'hilang'])->count();
    });

    if ($totalLostCount == 1) {
        // Ambil judul buku yang hilang pertama kali untuk disebutkan namanya
        $firstLostExemplar = $lostBorrowings->first()->borrowedExemplars->whereIn('status', ['lost', 'hilang'])->first();
        $bookDetail = $lostBorrowings->first()->details->where('exemplar_id', $firstLostExemplar?->exemplar_id)->first();
        $bookName = $bookDetail?->book?->title ?? 'Buku';
        
        $message = 'Buku "' . $bookName . '" dilaporkan hilang. Silakan menghubungi pustakawan untuk proses penggantian buku.';
    } else {
        $message = $totalLostCount . ' buku dilaporkan hilang (termasuk dari peminjaman kolektif). Silakan menghubungi pustakawan untuk proses penggantian.';
    }

    $notification = [
        'type' => 'hilang',
        'title' => 'Peringatan: Buku Hilang!',
        'message' => $message,
        'blocked' => true
    ];
} elseif ($lateBook) {
    $notification = [
        'type' => 'terlambat',
        'title' => 'Peminjaman Terlambat',
        'message' => 'Anda memiliki buku yang melewati batas pengembalian.',
        'blocked' => true
    ];
} elseif ($daysReminder !== null && $daysReminder == 0) {
    $notification = [
        'type' => 'due_today',
        'title' => 'Jatuh Tempo Hari Ini',
        'message' => $reminderText . ' harus dikembalikan hari ini.',
        'blocked' => false
    ];
} elseif ($daysReminder !== null && $daysReminder == 1) {
    $notification = [
        'type' => 'reminder_h1',
        'title' => 'Besok Jatuh Tempo',
        'message' => $reminderText . ' harus dikembalikan besok.',
        'blocked' => false
    ];
} elseif ($daysReminder !== null && $daysReminder >= 2 && $daysReminder <= 3) {
    $notification = [
        'type' => 'reminder_h3',
        'title' => 'Pengingat Pengembalian',
        'message' => $reminderText . ' harus dikembalikan dalam ' . $daysReminder . ' hari lagi.',
        'blocked' => false
    ];
} elseif ($waitingReturn) {
    $notification = [
        'type' => 'pengembalian',
        'title' => 'Pengembalian Diproses',
        'message' => 'Pengembalian buku sedang diverifikasi pustakawan.',
        'blocked' => false
    ];
} elseif ($waitingBorrow) {
    $notification = [
        'type' => 'menunggu',
        'title' => 'Pengajuan Peminjaman',
        'message' => 'Pengajuan peminjaman sedang menunggu persetujuan pustakawan.',
        'blocked' => false
    ];
}

// Mengirim seluruh data Dashboard ke aplikasi mobile.
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nis_nip' => $user->nis_nip,
                'role' => $user->role,
                'points' => $user->points ?? 0,
                'badge' => $user->badge ?? 'Bronze',
                'notification' => $notification,
            ],
            'stats' => [
                'borrowed' => Borrowing::where('user_id', $userId)->where('status', 'dipinjam')->count(),
                'history' => Borrowing::where('user_id', $userId)->where('status', 'dikembalikan')->count(),
                'pending' => Borrowing::where('user_id', $userId)->where('status', 'menunggu')->count(),
                'remaining_days' => $activeBorrowings->count() > 0
                    ? max(0, Carbon::today()->diffInDays(Carbon::parse($activeBorrowings->first()->return_date), false))
                    : 0,
            ],
            'active_books' => $activeBorrowings->flatMap(function ($borrowing) {
                $daysLeft = max(0, Carbon::today()->diffInDays(Carbon::parse($borrowing->return_date), false));

                // =========================
                // PEMINJAMAN KOLEKTIF
                // =========================
                if ($borrowing->is_collective) {
                    return [[
                        'id' => $borrowing->id,
                        'title' => 'Peminjaman Kolektif',
                        'author' => $borrowing->class_name,
                        'class_name' => $borrowing->class_name,
                        'cover' => null,
                        'return_date' => $borrowing->return_date,
                        'days_left' => $daysLeft,
                        'status' => $borrowing->status,
                        'is_collective' => true,
                        // [PERBAIKAN 2]: Hitung jumlah fisik buku (3 eksemplar) bukan jumlah tipe bukunya
                        'total_books' => $borrowing->borrowedExemplars->count(),
                        'details' => $borrowing->details->map(function ($d) {
                            return [
                                'book' => $d->book,
                                'code' => $d->exemplar?->code ?? '-',
                                'status' => $d->status
                            ];
                        })
                    ]];
                }

                // =========================
                // PEMINJAMAN BIASA
                // =========================
                return $borrowing->details
                ->filter(function ($detail) use ($borrowing) {
                    if (!$detail->exemplar) {
                        return true;
                    }
                    // Filter agar buku yang hilang tidak masuk ke daftar "Peminjaman Aktif"
                    return !$borrowing->borrowedExemplars
                        ->whereIn('status', ['lost', 'hilang']) // Pastikan filter menggunakan array
                        ->contains('exemplar_id', $detail->exemplar_id);
                })
                ->map(function ($detail) use ($borrowing, $daysLeft) {
                    return [
                        'id' => $detail->id,
                        'title' => $detail->book?->title,
                        'author' => $detail->book?->author,
                        'cover' => $detail->book?->cover,
                        'code' => $detail->exemplar?->code ?? '-',
                        'return_date' => $borrowing->return_date,
                        'days_left' => $daysLeft,
                        'status' => $borrowing->status,
                        'is_collective' => false,
                    ];
                })->values(); // Reset indeks array JSON
            }),
            'popular_books' => $popularBooks->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'cover' => $book->cover,
                    'stock' => $book->stock,
                    'total_borrowed' => $book->total_borrowed
                ];
            }),
        ]);
    }
}