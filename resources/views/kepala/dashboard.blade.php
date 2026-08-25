@extends('layouts.kepala')

@section('title', 'Dashboard Kepala Pustakawan')

@section('content')

<style>
    /* Desain Upgrade Untuk Kartu Stats */
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
    
    /* Sentuhan Gradasi Tipis di Atas Kartu */
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
    }
    .card-primary::after { background: #2563eb; }
    .card-success::after { background: #10b981; }
    .card-warning::after { background: #f59e0b; }
    .card-info::after { background: #06b6d4; }
    .card-danger::after { background: #ef4444; }
    .card-secondary::after { background: #64748b; }

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
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Dashboard Kepala Perpustakaan</h3>
        <p class="text-muted mb-0 small">Monitoring aktivitas perpustakaan dan kinerja petugas</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-primary shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Buku</p>
                        <h2 class="mb-0" style="font-size: 2.3rem; font-weight: 800; color: var(--text-main);">{{ $totalBooks }}</h2>
                        <span class="text-muted small">Koleksi perpustakaan</span>
                    </div>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-success shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Anggota</p>
                        <h2 class="text-success mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $totalMembers }}</h2>
                        <span class="text-muted small">Guru dan siswa</span>
                    </div>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-warning shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Transaksi</p>
                        <h2 class="text-warning mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $totalTransactions }}</h2>
                        <span class="text-muted small">Seluruh transaksi</span>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-info shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Pustakawan</p>
                        <h2 class="text-info mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $totalPustakawan }}</h2>
                        <span class="text-muted small">Petugas aktif</span>
                    </div>
                    <div class="icon-box bg-info-subtle text-info">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-danger shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Anggota Terblokir</p>
                        <h2 class="text-danger mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $blockedUsers->count() }}</h2>
                        <span class="text-muted small">Melewati jatuh tempo</span>
                    </div>
                    <div class="icon-box bg-danger-subtle text-danger">
                        <i class="bi bi-person-lock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card card-secondary shadow-sm p-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Approval Menunggu</p>
                        <h2 class="text-secondary mb-0" style="font-size: 2.3rem; font-weight: 800;">{{ $pendingApproval }}</h2>
                        <span class="text-muted small">Menunggu persetujuan</span>
                    </div>
                    <div class="icon-box bg-secondary-subtle text-secondary">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm p-3">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1" style="color: var(--text-main);">Monitoring Sistem</h5>
                    <p class="text-muted small mb-0">Navigasi kontrol dan pemantauan cepat data entitas</p>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <a href="{{ route('kepala.buku') }}" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Data Buku</h6>
                                <p class="text-muted small mb-0">Lihat katalog fisik & e-book</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6">
                        <a href="{{ route('kepala.anggota') }}" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Data Anggota</h6>
                                <p class="text-muted small mb-0">Pantau guru & siswa aktif</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6">
                        <a href="{{ route('kepala.transaksi') }}" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-arrow-left-right fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Transaksi</h6>
                                <p class="text-muted small mb-0">Audit riwayat peredaran sirkulasi</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6">
                        <a href="{{ route('kepala.pustakawan') }}" class="text-decoration-none">
                            <div class="quick-action-card rounded-4 p-4 text-center h-100">
                                <div class="bg-info-subtle text-info rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-person-badge fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Kelola Pustakawan</h6>
                                <p class="text-muted small mb-0">Manajemen staf administrasi</p>
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
                    <h5 class="fw-bold mb-3" style="color: var(--text-main);">Ringkasan Perpustakaan</h5>
                    
                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Total Buku</span>
                        <span class="fw-bold">{{ $totalBooks }} Eksemplar</span>
                    </div>

                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Total Anggota</span>
                        <span class="fw-bold">{{ $totalMembers }} Orang</span>
                    </div>

                    <div class="info-list-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Total Pustakawan</span>
                        <span class="fw-bold">{{ $totalPustakawan }} Petugas</span>
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