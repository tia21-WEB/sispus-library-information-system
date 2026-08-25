@extends('layouts.kepala')

@section('title', 'Monitoring Data Buku')

@section('content')

{{-- 
    FITUR: Pengaturan Styling CSS Kustom untuk Tampilan Katalog SaaS & Dashboard Monitoring
    LOGIKA: Mengatur efek animasi hover naik pada kartu statistik (`stat-card`), strip warna aksentuasi di bagian atas kartu, styling komponen widget, kontrol form pencarian, progress bar lengkung, serta penataan visual sampul buku.
--}}
<style>
    /* --- STYLE UPGRADE - PREMIUM SAAS CATALOG COMPONENT --- */
    .stat-card {
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    /* Strip Aksentuasi Atas Kartu Metrik */
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
    }
    .card-primary::after { background: #2563eb; }
    .card-success::after { background: #10b981; }
    .card-info::after { background: #06b6d4; }
    .card-danger::after { background: #ef4444; }
    .card-warning::after { background: #f59e0b; }
    .card-dark-danger::after { background: #991b1b; }

    /* List Widget Components */
    .widget-list-item {
        padding: 10px 12px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.88rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .widget-list-item:last-child {
        border-bottom: none;
    }

    /* Form Search Controls */
    .form-control {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 0.9rem;
    }
    .form-control:focus {
        background-color: var(--bg-card);
        color: var(--text-main);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    /* Fluid Table Styling */
    .table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: var(--text-muted);
        background-color: var(--hover-bg) !important;
        padding: 14px 16px;
    }
    .table td {
        padding: 14px 16px;
        color: var(--text-main);
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }
    
    /* Sleek Rounded Progress Bar */
    .progress {
        background-color: var(--hover-bg);
        border-radius: 50px;
        overflow: hidden;
    }
    .progress-bar {
        border-radius: 50px;
    }

    /* Book Placeholder Cover */
    .book-cover-placeholder {
        width: 48px;
        height: 64px;
        border-radius: 6px;
        background-color: var(--hover-bg);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: var(--shadow-sm);
    }
    .book-cover-real {
        width: 48px;
        height: 64px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: var(--shadow-sm);
    }
</style>

{{-- 
    FITUR: Header Halaman Monitoring Data Buku
    LOGIKA: Menampilkan judul utama modul beserta teks deskriptif yang merangkum fungsi audit kuantitas, status ketersediaan stok, dan manajemen pelacakan katalog buku.
--}}
<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Monitoring Data Buku</h3>
        <p class="text-muted mb-0 small">Audit kuantitas, status ketersediaan stok, dan manajemen melacak katalog buku perpustakaan</p>
    </div>
</div>

{{-- 
    FITUR: Grid Kartu Statistik Metrik Utama Buku
    LOGIKA: Menyediakan 6 kartu informasi untuk menampilkan ringkasan metrik sistem secara real-time: Total Judul, Total Stok, Total Eksemplar, Buku Hilang, Kategori, dan Stok Habis.
--}}
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-primary shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Judul</small>
                <h3 class="fw-bold text-primary mb-0" style="font-size: 1.75rem; font-weight: 800;">{{ $totalBooks }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-success shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Stok</small>
                <h3 class="fw-bold text-success mb-0" style="font-size: 1.75rem; font-weight: 800;">{{ $totalStock }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-info shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Eksemplar</small>
                <h3 class="fw-bold text-info mb-0" style="font-size: 1.75rem; font-weight: 800;">{{ $totalExemplars }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-danger shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Buku Hilang</small>
                <h3 class="fw-bold text-danger mb-0" style="font-size: 1.75rem; font-weight: 800;">{{ $lostBooks }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-warning shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Kategori</small>
                <h3 class="fw-bold text-warning mb-0" style="font-size: 1.75rem; font-weight: 800;">{{ $totalCategories }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-2">
        <div class="card stat-card card-dark-danger shadow-sm h-100">
            <div class="card-body p-3">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Stok Habis</small>
                <h3 class="fw-bold text-danger mb-0" style="font-size: 1.75rem; font-weight: 800; color: #b91c1c !important;">{{ $outOfStock }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- 
    FITUR: Widget Analitik Buku Terpopuler & Peringatan Stok Menipis
    LOGIKA: 
    1. Menggunakan koleksi `$popularBooks` untuk menampilkan daftar buku dengan frekuensi peminjaman tertinggi.
    2. Menyaring buku dengan stok kritis (`stock <= 5` dan `stock > 0`) menggunakan query inline untuk memperingatkan pengelola perpustakaan.
--}}
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header py-3">
                <h6 class="fw-bold mb-0" style="color: var(--text-main);">
                    <i class="bi bi-fire text-primary me-2"></i>Koleksi Buku Terpopuler
                </h6>
            </div>
            <div class="card-body p-2">
                @forelse($popularBooks as $book)
                    <div class="widget-list-item">
                        <span class="text-truncate pe-2" style="max-width: 80%;">
                            <strong class="text-muted me-1">{{ $loop->iteration }}.</strong> {{ $book->title }}
                        </span>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 rounded-3">
                            {{ $book->total }}x Dipinjam
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">Belum ada rekaman aktivitas peminjaman</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header py-3">
                <h6 class="fw-bold mb-0" style="color: var(--text-main);">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Peringatan Stok Menipis (≤ 5 Eks)
                </h6>
            </div>
            <div class="card-body p-2">
                @forelse($books->where('stock','<=',5)->where('stock','>',0)->take(5) as $book)
                    <div class="widget-list-item">
                        <span class="text-truncate pe-2" style="max-width: 85%;"><i class="bi bi-bookmark-x text-muted me-2"></i>{{ $book->title }}</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold px-2.5 py-1">
                            Sisa {{ $book->stock }}
                        </span>
                    </div>
                @empty
                    <div class="text-center text-success small py-4">
                        <i class="bi bi-shield-check me-1"></i> Aman, seluruh stok buku terpenuhi di atas limit batas minim.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- 
    FITUR: Formulir Pencarian Katalog Buku (Search Bar)
    LOGIKA: Menggunakan method GET untuk mengirim parameter request `search` agar pengguna dapat menyaring data berdasarkan judul, penulis, ISBN, atau kategori secara spesifik.
--}}
<div class="row mb-4">
    <div class="col-12">
        <form method="GET">
            <div class="input-group shadow-sm" style="border-radius: 12px;">
                <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">
                    <i class="bi bi-search"></i>
                </span>
                <input
                    type="text"
                    name="search"
                    class="form-control border-start-0 ps-0"
                    value="{{ request('search') }}"
                    placeholder="Cari berdasarkan judul, penulis, ISBN atau kategori" style="border-radius: 0;">
                <button class="btn btn-primary px-4" style="border-radius: 0 12px 12px 0;">
                    Cari
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 
    FITUR: Tabel Database Koleksi Buku Terdaftar
    LOGIKA: Melakukan iterasi `@forelse` pada koleksi `$books` untuk merender informasi lengkap buku: nomor urut progresif, visual sampul (gambar asli atau placeholder), metadata (ISBN, penerbit), penulis, kategori, tahun, status e-book, jumlah eksemplar, serta volume stok dengan progress bar adaptif.
--}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header py-3">
        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
            <i class="bi bi-collection-fill me-2 text-primary"></i>Database Koleksi Buku Terdaftar
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="bookTable">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" width="60">No</th>
                        <th width="80" class="text-center">Visual</th>
                        <th>Informasi & Metadata Buku</th>
                        <th width="160">Penulis</th>
                        <th width="130" class="text-center">Kategori</th>
                        <th width="80" class="text-center">Tahun</th>
                        <th width="120" class="text-center">E-Book</th>
                        <th width="100" class="text-center">Eksemplar</th>
                        <th class="pe-4" width="160">Volume Ketersediaan Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="ps-4 text-center text-muted fw-bold">{{ $books->firstItem() + $loop->index }}</td>
                        
                        <td class="text-center">
                            @if(isset($book->cover) && $book->cover)
                                <img src="{{ asset('storage/'.$book->cover) }}" class="book-cover-real" alt="Cover {{ $book->title }}">
                            @else
                                <div class="book-cover-placeholder mx-auto">📖</div>
                            @endif
                        </td>

                        <td>
                            <div class="fw-bold mb-1" style="color: var(--text-main); line-height: 1.35;">
                                {{ $book->title }}
                            </div>
                            <div class="d-flex flex-column gap-0.5 mb-1">
                                @if(isset($book->isbn))
                                    <span class="text-muted font-monospace" style="font-size: 0.75rem;">ISBN: {{ $book->isbn }}</span>
                                @endif
                                <small class="text-muted" style="font-size: 0.78rem;">{{ $book->publisher }}</small>
                            </div>

                            {{-- FITUR: Badge Status & Tombol Lihat E-Book di Bawah Metadata --}}
                            @if($book->ebook_file)
                                <div class="mt-1 d-flex flex-wrap gap-2 align-items-center">
                                    <span class="badge bg-success">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                        Ebook Tersedia
                                    </span>
                                    <a href="{{ asset('storage/'.$book->ebook_file) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-fill me-1"></i>Lihat Ebook
                                    </a>
                                </div>
                            @else
                                <div class="mt-1">
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-file-earmark-x me-1"></i>
                                        Tidak Ada Ebook
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td class="fw-medium text-secondary" style="font-size: 0.9rem;">{{ $book->author }}</td>

                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-3 fw-bold small">
                                {{ $book->category->name ?? '-' }}
                            </span>
                        </td>

                        <td class="text-center text-muted font-monospace small">{{ $book->publication_year }}</td>

                        {{-- FITUR: Kolom Khusus E-Book Status Singkat --}}
                        <td class="text-center">
                            @if($book->ebook_file)
                                <span class="badge bg-success">Ada</span>
                            @else
                                <span class="badge bg-secondary">Tidak Ada</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 rounded-3 fw-bold">
                                {{ $book->exemplars->count() }} Eks
                            </span>
                        </td>

                        <td class="pe-4">
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="fw-bold" style="font-size: 0.85rem;">{{ $book->stock }} Buku</span>
                                <span class="fw-bold" style="font-size: 0.75rem;">
                                    @if($book->stock == 0)
                                        <span class="text-danger">Habis</span>
                                    @elseif($book->stock <= 5)
                                        <span class="text-warning">Menipis</span>
                                    @else
                                        <span class="text-success">Aman</span>
                                    @endif
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar @if($book->stock == 0) bg-danger @elseif($book->stock <= 5) bg-warning @else bg-success @endif" 
                                     role="progressbar" 
                                     style="width: {{ min(($book->stock / 20) * 100, 100) }}%;" 
                                     aria-valuenow="{{ $book->stock }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bi bi-book-half text-muted" style="font-size: 3.5rem; opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main) !important;">Katalog Masih Kosong</h6>
                            <p class="small text-muted mb-0">Belum ada rekam data buku yang terdaftar di database sistem sirkulasi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- 
            FITUR: Navigasi Paginasi Bootstrap
            LOGIKA: Merender tautan paginasi halaman menggunakan pustaka paginasi Bootstrap 5 (`$books->links('pagination::bootstrap-5')`) untuk menangani volume data katalog yang besar.
        --}}
        <div class="card-footer bg-white py-3">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection