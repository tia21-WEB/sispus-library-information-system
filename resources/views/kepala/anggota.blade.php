@extends('layouts.kepala')

@section('title', 'Monitoring Anggota')

@section('content')

<style>
    /* --- STYLE UPGRADE - HARMONISASI PREMIUM --- */
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
    
    /* Strip Aksentuasi Warna di Atas Kartu (Presisi Admin) */
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
    }
    .card-primary::after { background: #2563eb; }
    .card-success::after { background: #10b981; }
    .card-warning::after { background: #f59e0b; }
    .card-danger::after { background: #ef4444; }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Modern Navigation Pills Styling */
    .nav-pills .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 18px;
        border-radius: 10px;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    .nav-pills .nav-link:hover {
        color: var(--text-main);
        background-color: var(--hover-bg);
    }
    .nav-pills .nav-link.active {
        color: #ffffff !important;
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
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

    /* User Avatar Placeholder */
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .user-avatar-blocked {
        background: linear-gradient(135deg, #ef4444, #f87171);
    }

    /* Table Customizations */
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
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Monitoring Anggota</h3>
        <p class="text-muted mb-0 small">Manajemen dan pengawasan data seluruh ekosistem civitas perpustakaan</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-primary shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Anggota</p>
                        <h2 class="mb-0" style="font-size: 2.2rem; font-weight: 800; color: var(--text-main);">{{ $totalAnggota }}</h2>
                    </div>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-success shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Siswa</p>
                        <h2 class="text-success mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $totalSiswa }}</h2>
                    </div>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-warning shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Guru</p>
                        <h2 class="text-warning mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $totalGuru }}</h2>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-danger shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Terblokir</p>
                        <h2 class="text-danger mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $blockedUsers->count() }}</h2>
                    </div>
                    <div class="icon-box bg-danger-subtle text-danger">
                        <i class="bi bi-person-lock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 align-items-center">
    <div class="col-12 col-md-6">
        <ul class="nav nav-pills gap-2" id="memberTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-anggota" type="button">
                    <i class="bi bi-person-lines-fill me-2"></i>Anggota Aktif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-blocked" type="button">
                    <i class="bi bi-shield-slash-fill me-2"></i>Anggota Terblokir
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-inactive" type="button">
                    <i class="bi bi-person-x-fill me-2"></i>Anggota Tidak Aktif
                </button>
            </li>
        </ul>
    </div>
    <div class="col-12 col-md-6">
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="searchAnggota" class="form-control border-start-0 ps-0" placeholder="Cari nama, NIS/NIP, atau email anggota secara real-time..." style="border-radius: 0 12px 12px 0;">
        </div>
    </div>
</div>

<div class="tab-content">
    
    <div class="tab-pane fade show active" id="tab-anggota" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                    <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Database Anggota Aktif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Profil / Nama</th>
                                <th>NIS / NIP</th>
                                <th>Alamat Email</th>
                                <th>Kategori Role</th>
                                <th class="pe-4">Nomor HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($anggota as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($item->name, 0, 2)) }}
                                        </div>
                                        <div class="fw-bold" style="color: var(--text-main);">{{ $item->name }}</div>
                                    </div>
                                </td>
                                <td class="fw-medium text-secondary" style="font-size: 0.9rem;">{{ $item->nis_nip }}</td>
                                <td class="text-muted" style="font-size: 0.9rem;">{{ $item->email }}</td>
                                <td>
                                    @if($item->role == 'guru')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-person-workspace me-1"></i>Guru
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-mortarboard-fill me-1"></i>Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-muted small">{{ $item->phone ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-inactive" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0 text-danger">
                    <i class="bi bi-person-x-fill me-2"></i>Daftar Anggota Tidak Aktif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Profil / Nama</th>
                                <th>NIS / NIP</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inactiveUsers as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($user->name,0,2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->nis_nip }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada anggota tidak aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-blocked" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header py-3" style="border-bottom: 1px solid rgba(239, 68, 68, 0.15);">
                <h5 class="fw-bold mb-0 text-danger" style="font-size: 1rem;">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>Daftar Penangguhan Anggota (Terblokir)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Profil / Nama</th>
                                <th>NIS / NIP</th>
                                <th>Alamat Email</th>
                                <th class="pe-4">Kategori Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blockedUsers as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar user-avatar-blocked">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="fw-bold" style="color: var(--text-main);">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="fw-medium text-secondary" style="font-size: 0.9rem;">{{ $user->nis_nip }}</td>
                                <td class="text-muted" style="font-size: 0.9rem;">{{ $user->email }}</td>
                                <td class="pe-4">
                                    @if($user->role == 'guru')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-bold">Guru</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">Siswa</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-shield-check text-success" style="font-size: 3.5rem; opacity: 0.8;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="color: var(--text-main) !important;">Sistem Aman / Bersih</h6>
                                    <p class="small text-muted mb-0">Tidak ada data anggota yang sedang dalam status diblokir.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.getElementById('searchAnggota').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.tab-content table tbody tr');

        rows.forEach(row => {
            // Cek agar tidak memfilter baris status empty state
            if(row.cells.length > 1) {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            }
        });
    });
</script>

@endsection