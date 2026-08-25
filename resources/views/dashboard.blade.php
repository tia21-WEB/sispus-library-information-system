@extends('layouts.admin')

@section('title', 'Dashboard Pustakawan')

@section('content')

<style>
    /* Desain Upgrade Untuk Kartu Stats */
    .stat-card {
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    /* Sentuhan Gradasi Tipis di Atas Kartu */
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
    }
    .card-primary::after { background: #2563eb; }
    .card-warning::after { background: #f59e0b; }
    .card-danger::after { background: #ef4444; }

    .icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    /* Grid Menu Cepat Modern */
    .quick-action-card {
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        transition: all 0.25s ease;
    }
    .quick-action-card:hover {
        border-color: var(--primary-color) !important;
        background: var(--hover-bg);
        transform: translateY(-3px);
    }
    .quick-action-card h6 { color: var(--text-main); }

    /* Indikator List */
    .info-list-item {
        padding: 16px 0;
        border-bottom: 1px dashed var(--border-color);
        color: var(--text-main);
    }
    .info-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Dashboard Perpustakaan</h3>
        <p class="text-muted mb-0 small">Selamat datang kembali, kelola aktivitas perpustakaan hari ini</p>
    </div>
    <div class="col-md-auto">
        <div class="d-flex gap-2 flex-wrap">
            <a href="/admin/peminjaman" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-bookmark-check"></i> Verifikasi Peminjaman
            </a>
            <a href="/admin/pengembalian" class="btn btn-light border d-inline-flex align-items-center gap-2 bg-body shadow-sm">
                <i class="bi bi-check2-circle text-success"></i> Verifikasi Pengembalian
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card card-primary shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Koleksi</p>
                        <h2 class="mb-0" style="font-size: 2.3rem; font-weight: 800; color: var(--text-main);">{{ $totalBooks }}</h2>
                        <span class="text-muted small">Buku terdaftar aktif</span>
                    </div>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-journal-richtext"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card stat-card card-warning shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Sedang Dipinjam</p>
                        <h2 class="text-warning mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $totalBorrowed }}</h2>
                        <span class="text-muted small">Sirkulasi buku diluar</span>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <a href="{{ route('web.anggota.terblokir') }}" class="text-decoration-none">
            <div class="card stat-card card-danger shadow-sm p-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Anggota Terblokir</p>
                            <h2 class="text-danger mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $blockedUsers }}</h2>
                            <span class="text-muted small">Melewati jatuh tempo</span>
                        </div>
                        <div class="icon-box bg-danger-subtle text-danger">
                            <i class="bi bi-person-lock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm p-3">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1" style="color: var(--text-main);">Aktivitas Cepat</h5>
                    <p class="text-muted small mb-0">Akses fitur utama manajemen dengan satu klik</p>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-4">
                        <a href="/admin/buku" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Data Buku</h6>
                                <p class="text-muted small mb-0">Kelola katalog fisik & e-book</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-4">
                        <a href="/admin/peminjaman" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-bookmark-check fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Peminjaman</h6>
                                <p class="text-muted small mb-0">Verifikasi sirkulasi masuk</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-4">
                        <a href="/admin/laporan" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-bar-chart fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Laporan</h6>
                                <p class="text-muted small mb-0">Algoritma & Ekspor Data</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm p-3">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3" style="color: var(--text-main);">Informasi Sistem</h5>
                    
                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Buku Populer</span>
                        <span class="fw-bold text-truncate ms-2 text-end" style="max-width: 160px;" title="{{ $popularBook?->title ?? '-' }}">
                            {{ $popularBook?->title ?? '-' }}
                        </span>
                    </div>

                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Total Anggota</span>
                        <span class="fw-bold">{{ $totalMembers }} Orang</span>
                    </div>

                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Kategori Buku</span>
                        <span class="fw-bold">{{ $totalCategories }} Rak</span>
                    </div>
                </div>

                <div class="pt-3 d-flex justify-content-between align-items-center border-top mt-4">
                    <span class="text-muted small fw-medium">Status Operasional</span>
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Sistem Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection