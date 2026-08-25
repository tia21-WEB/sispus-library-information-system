<?php
// File ini berfungsi mengelola data buku dan kategori,
// di mobile
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Ambil Semua Data Buku (Untuk Halaman Katalog Buku di Flutter)
     */
   /**
     * Ambil Semua Data Buku (Untuk Halaman Katalog Buku di Flutter)
     */
    public function index()
    {
        // Mengambil semua buku beserta kategori, 
        // sekaligus menghitung berapa eksemplar yang statusnya benar-benar 'available' (tersedia di rak)
        $books = Book::with('category')
            ->withCount([
                'exemplars as available_stock' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->get();

        // Kita map datanya agar aplikasi Flutter langsung membaca 'available_stock' sebagai nilai stok
        $formattedBooks = $books->map(function ($book) {
            return [
                'id' => $book->id,
                'category_id' => $book->category_id,
                'category' => $book->category,
                'title' => $book->title,
                'author' => $book->author,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'description' => $book->description,
                'cover' => $book->cover,
                'ebook_file' => $book->ebook_file
    ? asset('storage/' . $book->ebook_file)
    : null,
                // INI KUNCINYA: Kolom 'stock' yang dikirim ke Flutter sekarang 
                // adalah jumlah eksemplar murni yang ada di rak (available)!
                'stock' => $book->available_stock, 
                
                // Kalau sewaktu-waktu aplikasi Flutter butuh info total keseluruhan aset:
                'total_asset' => $book->stock, 
                
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar buku berhasil diambil',
            'data' => $formattedBooks
        ], 200);
    }

    /**
     * Ambil Detail Buku Berdasarkan ID beserta info Eksemplar fisiknya
     */
    public function show($id)
    {
        $book = Book::with(['category', 'exemplars'])->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail buku ditemukan',
            'data' => [

    'id' => $book->id,

    'category_id' => $book->category_id,

    'category' => $book->category,

    'title' => $book->title,

    'author' => $book->author,

    'publisher' => $book->publisher,

    'publication_year' => $book->publication_year,

    'description' => $book->description,

    'cover' => $book->cover,

    'ebook_file' => $book->ebook_file
        ? asset('storage/' . $book->ebook_file)
        : null,

    'stock' => $book->stock,

    'exemplars' => $book->exemplars,

    'created_at' => $book->created_at,

    'updated_at' => $book->updated_at,

]

        ], 200);
    }

    /**
     * Ambil Semua Kategori (Untuk filter menu di Flutter)
     */
    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil',
            'data' => $categories
        ], 200);
    }
    /**
 * Fungsi untuk menambahkan data buku baru.
 */
    public function store(Request $request)
{
    // Memvalidasi data buku yang akan disimpan.
    $request->validate([
        'category_id'      => 'required|exists:categories,id',
        'title'            => 'required',
        'author'           => 'required',
        'publisher'        => 'required',
        'publication_year' => 'required|numeric',
        'stock'            => 'required|numeric',
    ]);
// Menyimpan data buku baru ke database.
    $book = Book::create([
        'category_id'      => $request->category_id,
        'title'            => $request->title,
        'author'           => $request->author,
        'publisher'        => $request->publisher,
        'publication_year' => $request->publication_year,
        'description'      => $request->description,
        'stock'            => $request->stock,
    ]);
// Mengembalikan respon bahwa buku berhasil ditambahkan.
    return response()->json([
        'success' => true,
        'message' => 'Buku berhasil ditambahkan',
        'data' => $book
    ], 201);
}
}