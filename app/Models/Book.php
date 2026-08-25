<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Model Book merepresentasikan tabel 'books' di database untuk mengelola data buku perpustakaan
class Book extends Model
{
    use HasFactory; // Menggunakan trait HasFactory untuk mendukung pembuatan factory data pengujian/seeder

    // Properti fillable mendefinisikan kolom-kolom tabel yang diizinkan untuk diisi secara mass-assignment
    protected $fillable = [

        'category_id',      // ID kategori buku (foreign key yang menghubungkan ke tabel categories)
        'title',            // Judul buku
        'author',           // Penulis atau pengarang buku
        'publisher',        // Penerbit buku
        'publication_year', // Tahun terbit buku
        'description',      // Deskripsi atau sinopsis lengkap tentang buku
        'cover',            // Nama file gambar sampul (cover) buku
        'stock' ,          // Jumlah total stok fisik buku
'book_type',
'ebook_file',
    ];

/**
 * Fungsi relasi Eloquent (One-to-Many) ke model Borrowing.
 * Menunjukkan bahwa satu judul buku dapat memiliki banyak catatan transaksi peminjaman utama.
 */
    public function borrowings()
{
    return $this->hasMany(Borrowing::class);
}

/**
 * Fungsi relasi Eloquent (Belongs-To) ke model Category.
 * Menunjukkan bahwa satu buku terikat atau dimiliki oleh satu kategori tertentu.
 */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

/**
 * Fungsi relasi Eloquent (One-to-Many) ke model Exemplar.
 * Menunjukkan bahwa satu judul buku memiliki banyak eksemplar fisik satuan buku di perpustakaan.
 */
    public function exemplars()
    {
        return $this->hasMany(Exemplar::class);
    }

/**
 * Fungsi relasi Eloquent (One-to-Many) ke model BorrowingDetail.
 * Menunjukkan bahwa satu buku memiliki banyak rincian item detail transaksi peminjaman.
 */
    public function borrowingDetails()
{
    return $this->hasMany(
        BorrowingDetail::class
    );
}
}