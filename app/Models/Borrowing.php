<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Model Borrowing merepresentasikan tabel 'borrowings' di database untuk mengelola data transaksi utama peminjaman buku
class Borrowing extends Model
{
    use HasFactory; // Menggunakan trait HasFactory untuk mendukung pembuatan factory data pengujian/seeder

    // Properti fillable mendefinisikan kolom-kolom tabel yang diizinkan untuk diisi secara mass-assignment
   protected $fillable = [

    'user_id',
    'book_id',
    'borrow_date',
    'return_date',
    'status',
    'rejection_note', // <-- TAMBAHKAN INI
    'qr_code',
    'loan_type',
    'is_collective',
    'class_name',
    'quantity',
    'reminder_sent',
    'block_notification_sent',
    'extension_status',
'extension_count',
'extension_note',

];
    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

/**
 * Fungsi relasi Eloquent (Belongs-To) ke model User.
 * Menunjukkan bahwa satu data transaksi peminjaman dimiliki oleh satu user (peminjam) tertentu.
 */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

/**
 * Fungsi relasi Eloquent (Belongs-To) ke model Book.
 * Menunjukkan bahwa satu data transaksi peminjaman terikat ke satu judul buku utama tertentu.
 */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

/**
 * Fungsi relasi Eloquent (One-to-Many) ke model BorrowingDetail.
 * Menunjukkan bahwa satu transaksi peminjaman utama dapat memiliki banyak detail rincian buku.
 */
    public function details()
{
    return $this->hasMany(
        BorrowingDetail::class
    );
}

/**
 * Fungsi relasi Eloquent (One-to-Many) ke model BorrowingExemplar.
 * Menunjukkan bahwa satu transaksi peminjaman mencakup banyak relasi data eksemplar fisik buku.
 */
public function borrowedExemplars()
{
    return $this->hasMany(
        BorrowingExemplar::class
    );
}
}