@extends('layouts.admin')

@section('title', 'Leaderboard Literasi')

@section('content')

<style>
    /* --- SYSTEM-WIDE & CARD PREMIUM PREMIUM --- */
    :root {
        --glass-border: rgba(0, 0, 0, 0.06);
        --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 1px 3px 0 rgba(0, 0, 0, 0.03);
        --hover-shadow: 0 20px 40px -10px rgba(13, 110, 253, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.02);
    }

    .custom-card {
        border: 1px solid var(--glass-border);
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
    }

    /* --- STATISTIC CARDS MODERNIZATION --- */
    .stat-card {
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--hover-shadow);
    }
    .stat-icon {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 5.5rem;
        opacity: 0.06;
        transform: rotate(-10deg);
        transition: all 0.3s ease;
    }
    .stat-card:hover .stat-icon {
        transform: rotate(-20deg) scale(1.1);
        opacity: 0.1;
    }
    .icon-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
    
    /* --- NAV TABS PILLS GLOW EFFECT --- */
    .nav-pills-custom {
        background-color: #f1f3f5;
        padding: 6px;
        border-radius: 50px;
        display: inline-flex;
    }
    .nav-pills-custom .nav-link {
        border-radius: 50px;
        padding: 10px 28px;
        font-weight: 600;
        color: #495057;
        background: transparent;
        border: none;
        transition: all 0.25s ease;
    }
    .nav-pills-custom .nav-link.active {
        background-color: #ffffff;
        color: #0d6efd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    /* --- PREMIUM GAMIFICATION BADGES --- */
    .badge-premium {
        padding: 8px 16px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.75rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .badge-bronze { background: linear-gradient(135deg, #f5a663, #b87333); color: #ffffff; }
    .badge-silver { background: linear-gradient(135deg, #e9ecef, #adb5bd); color: #495057; }
    .badge-gold { background: linear-gradient(135deg, #ffe066, #fcc419); color: #7c5e00; }
    .badge-platinum { background: linear-gradient(135deg, #495057, #212529); color: #ffffff; }
    
    /* --- TABLE CRAFTSMANSHIP --- */
    .table-custom th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #868e96;
        font-weight: 700;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 16px 20px;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 18px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }
    .table-custom tbody tr {
        transition: background-color 0.2s ease;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.015);
    }

    /* Top 3 Highlight Vibe */
    .row-rank-1 { background-color: rgba(252, 196, 25, 0.03); }
    .row-rank-2 { background-color: rgba(173, 181, 189, 0.03); }
    .row-rank-3 { background-color: rgba(184, 115, 51, 0.02); }

    .rank-medal-wrapper {
        font-size: 1.6rem;
        display: inline-block;
        animation: float 3s ease-in-out infinite;
    }
    .rank-number-badge {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f3f5;
        color: #495057;
        font-weight: 700;
        border-radius: 50px;
        font-size: 0.85rem;
    }
    
    /* --- LARAVEL PAGINATION CLEANUP --- */
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        border: 1px solid var(--glass-border);
        color: #495057;
    }
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }

    /* Rule Card Minimalist Vibe */
    .rule-box {
        border: 1px dashed var(--glass-border);
        border-radius: 14px;
        background: #ffffff;
        transition: transform 0.2s ease;
    }
    .rule-box:hover {
        transform: scale(1.02);
    }
</style>

<div class="container-fluid px-4 py-3 mb-5">

    <!-- Header Section -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="bi bi-trophy-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Leaderboard Literasi</h3>
                    <p class="text-muted mb-0 small">
                        Pantau performa, akumulasi poin, tingkatan badge, dan riwayat distribusi reward per periode semester.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Action Card -->
    <div class="card custom-card rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <form method="GET" action="{{ route('web.gamifikasi') }}" id="periodForm">
                        <label class="form-label fw-bold text-secondary small mb-2 text-uppercase" style="letter-spacing: 0.5px;">
                            <i class="bi bi-calendar-event me-1 text-primary"></i> Pilih Periode Analisis
                        </label>
                        <div class="input-group">
                            <select name="period" class="form-select border-end-0 rounded-start-3" onchange="document.getElementById('periodForm').submit()">
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ $selectedPeriod == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-text bg-white border-start-0 text-muted rounded-end-3">
                                <i class="bi bi-chevron-down small"></i>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="col-md-7 text-md-end d-flex flex-wrap justify-content-md-end align-items-center gap-2 mt-3 mt-md-0">
                    @if($activePeriod)
                        <span class="badge bg-success bg-opacity-10 text-success fs-7 fw-semibold px-3 py-2.5 rounded-3 border border-success border-opacity-20 me-auto ms-md-2 mb-2 mb-sm-0">
                            <span class="spinner-grow spinner-grow-sm me-1" role="status" style="width: 8px; height: 8px;"></span>
                            Periode Aktif: {{ $activePeriod->name }}
                        </span>
                    @endif
                    <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">
                        <form action="{{ route('leaderboard.closePeriod') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light text-danger border border-danger border-opacity-20 fw-semibold px-3 py-2 rounded-3" onclick="return confirm('Yakin ingin menutup periode ini? Seluruh poin akan direset.')">
                                <i class="bi bi-lock-fill me-1"></i> Tutup Periode
                            </button>
                        </form>
                        <button class="btn btn-primary fw-semibold px-3 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPeriode">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Periode
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Poin -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card custom-card stat-card h-100 p-4">
                <i class="bi bi-lightning-charge-fill stat-icon text-primary"></i>
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-star-fill fs-5"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-0 text-uppercase small" style="letter-spacing: 0.5px;">Poin </h6>
                </div>
                <h2 class="fw-extrabold mb-1 text-dark display-6 font-monospace">{{ number_format($totalPoints) }}</h2>
                <span class="text-muted small">Total akumulasi skor</span>
            </div>
        </div>

        <!-- Pengguna Gold -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card custom-card stat-card h-100 p-4" style="border-top: 4px solid #fcc419 !important;">
                <i class="bi bi-award-fill stat-icon text-warning"></i>
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning-emphasis me-3">
                        <i class="bi bi-trophy-fill fs-5"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-0 text-uppercase small" style="letter-spacing: 0.5px;">Gold</h6>
                </div>
                <h2 class="fw-extrabold mb-1 text-dark display-6 font-monospace">{{ $goldUsers }}</h2>
                <span class="text-muted small">Anggota tier emas</span>
            </div>
        </div>

        <!-- Pengguna Platinum -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card custom-card stat-card h-100 p-4" style="border-top: 4px solid #212529 !important;">
                <i class="bi bi-shield-fill-check stat-icon text-dark"></i>
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper bg-dark bg-opacity-10 text-dark me-3">
                        <i class="bi bi-gem fs-5"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-0 text-uppercase small" style="letter-spacing: 0.5px;"> Platinum</h6>
                </div>
                <h2 class="fw-extrabold mb-1 text-dark display-6 font-monospace">{{ $platinumUsers }}</h2>
                <span class="text-muted small">Anggota tier platinum</span>
            </div>
        </div>

        <!-- Total Partisipan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card custom-card stat-card h-100 p-4">
                <i class="bi bi-people-fill stat-icon text-info"></i>
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper bg-info bg-opacity-10 text-info-emphasis me-3">
                        <i class="bi bi-person-lines-fill fs-5"></i>
                    </div>
                    <h6 class="text-muted fw-bold mb-0 text-uppercase small" style="letter-spacing: 0.5px;">Total Partisipan</h6>
                </div>
                <h2 class="fw-extrabold mb-1 text-dark display-6 font-monospace">{{ $siswaUsers->count() + $guruUsers->count() }}</h2>
                <span class="text-muted small">Gabungan Siswa & Guru</span>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation Component -->
    <div class="d-flex justify-content-center mb-4">
        <ul class="nav nav-pills nav-pills-custom" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active d-flex align-items-center gap-2" data-bs-toggle="pill" data-bs-target="#siswa" type="button" role="tab">
                    <i class="bi bi-mortarboard-fill"></i> Papan Siswa
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center gap-2" data-bs-toggle="pill" data-bs-target="#guru" type="button" role="tab">
                    <i class="bi bi-person-workspace"></i> Papan Guru
                </button>
            </li>
        </ul>
    </div>

    <!-- Tabs Content Blueprint -->
    <div class="tab-content">
        
        <!-- Tab Siswa -->
        <div class="tab-pane fade show active" id="siswa" role="tabpanel">
            <div class="card custom-card rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3.5 px-4 d-flex align-items-center justify-content-between border-bottom border-light">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart-line-fill text-primary"></i>Leaderboard Siswa
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 12%;">Rank</th>
                                    <th style="width: 43%;">Profil Anggota</th>
                                    <th style="width: 20%;">Koleksi Poin</th>
                                    <th style="width: 25%;">Badge Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaUsers as $index => $user)
                                    @php $rank = $siswaUsers->firstItem() + $index; @endphp
                                    <tr class="row-rank-{{ $rank <= 3 ? $rank : 'default' }}">
                                        <td class="text-center">
                                            @if($rank == 1)
                                                <span class="rank-medal-wrapper" title="Juara 1">🥇</span>
                                            @elseif($rank == 2)
                                                <span class="rank-medal-wrapper" title="Juara 2">🥈</span>
                                            @elseif($rank == 3)
                                                <span class="rank-medal-wrapper" title="Juara 3">🥉</span>
                                            @else
                                                <div class="rank-number-badge">{{ $rank }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark mb-0.5" style="font-size:1rem;">{{ $user->name }}</span>
                                                <small class="text-muted font-monospace" style="font-size: 0.8rem;">
                                                    <i class="bi bi-card-text me-1 text-secondary"></i>NIS: {{ $user->nis_nip }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-baseline gap-1">
                                                <span class="fw-extrabold fs-4 text-primary font-monospace">{{ number_format($user->points) }}</span>
                                                <span class="text-secondary small fw-medium">Pts</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->badge == 'Bronze')
                                                <span class="badge badge-premium badge-bronze rounded-pill shadow-sm"><i class="bi bi-shield-shaded me-1"></i> Bronze</span>
                                            @elseif($user->badge == 'Silver')
                                                <span class="badge badge-premium badge-silver rounded-pill shadow-sm"><i class="bi bi-shield-fill me-1"></i> Silver</span>
                                            @elseif($user->badge == 'Gold')
                                                <span class="badge badge-premium badge-gold rounded-pill shadow-sm"><i class="bi bi-trophy-fill me-1"></i> Gold</span>
                                            @else
                                                <span class="badge badge-premium badge-platinum rounded-pill shadow-sm"><i class="bi bi-gem me-1"></i> Platinum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Menampilkan data {{ $siswaUsers->firstItem() ?? 0 }}-{{ $siswaUsers->lastItem() ?? 0 }} dari {{ $siswaUsers->total() }}</span>
                    <div>{{ $siswaUsers->withQueryString()->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>

        <!-- Tab Guru -->
        <div class="tab-pane fade" id="guru" role="tabpanel">
            <div class="card custom-card rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3.5 px-4 border-bottom border-light">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart-line-fill text-primary"></i> Leaderboard Guru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 12%;">Rank</th>
                                    <th style="width: 43%;">Profil Anggota</th>
                                    <th style="width: 20%;">Koleksi Poin</th>
                                    <th style="width: 25%;">Badge Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guruUsers as $index => $user)
                                    @php $rank = $guruUsers->firstItem() + $index; @endphp
                                    <tr class="row-rank-{{ $rank <= 3 ? $rank : 'default' }}">
                                        <td class="text-center">
                                            @if($rank == 1)
                                                <span class="rank-medal-wrapper" title="Juara 1">🥇</span>
                                            @elseif($rank == 2)
                                                <span class="rank-medal-wrapper" title="Juara 2">🥈</span>
                                            @elseif($rank == 3)
                                                <span class="rank-medal-wrapper" title="Juara 3">🥉</span>
                                            @else
                                                <div class="rank-number-badge">{{ $rank }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark mb-0.5" style="font-size:1rem;">{{ $user->name }}</span>
                                                <small class="text-muted font-monospace" style="font-size: 0.8rem;">
                                                    <i class="bi bi-card-text me-1 text-secondary"></i>NIP: {{ $user->nis_nip }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-baseline gap-1">
                                                <span class="fw-extrabold fs-4 text-primary font-monospace">{{ number_format($user->points) }}</span>
                                                <span class="text-secondary small fw-medium">Pts</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->badge == 'Bronze')
                                                <span class="badge badge-premium badge-bronze rounded-pill shadow-sm"><i class="bi bi-shield-shaded me-1"></i> Bronze</span>
                                            @elseif($user->badge == 'Silver')
                                                <span class="badge badge-premium badge-silver rounded-pill shadow-sm"><i class="bi bi-shield-fill me-1"></i> Silver</span>
                                            @elseif($user->badge == 'Gold')
                                                <span class="badge badge-premium badge-gold rounded-pill shadow-sm"><i class="bi bi-trophy-fill me-1"></i> Gold</span>
                                            @else
                                                <span class="badge badge-premium badge-platinum rounded-pill shadow-sm"><i class="bi bi-gem me-1"></i> Platinum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Menampilkan data {{ $guruUsers->firstItem() ?? 0 }}-{{ $guruUsers->lastItem() ?? 0 }} dari {{ $guruUsers->total() }}</span>
                    <div>{{ $guruUsers->withQueryString()->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Riwayat Section Grid Layout -->
    <div class="row g-4 mt-4">
        <!-- History Siswa -->
        <div class="col-12 col-xl-6">
            <div class="card custom-card rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3.5 px-4 border-bottom border-light">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-secondary"></i> Riwayat Leaderboard Semester - Siswa
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-secondary fw-semibold">Periode</th>
                                    <th class="text-secondary fw-semibold">Nama </th>
                                    <th class="text-secondary fw-semibold">Rank</th>
                                    <th class="text-secondary fw-semibold">Poin</th>
                                    <th class="pe-4 text-secondary fw-semibold">Tier Badges</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historySiswa as $history)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $history->period->name }}</td>
                                    <td><span class="d-inline-block text-truncate" style="max-width: 140px;">{{ $history->user->name }}</span></td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">#{{ $history->rank }}</span></td>
                                    <td class="font-monospace fw-bold text-primary">{{ number_format($history->points) }}</td>
                                    <td class="pe-4"><span class="small fw-semibold text-uppercase text-muted border-start border-3 ps-2" style="border-color:#dee2e6!important;">{{ $history->badge }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-folder-x fs-3 d-block mb-2 opacity-50"></i>Belum terdata arsip historis.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2.5 border-top border-light d-flex justify-content-end">
                    {{ $historySiswa->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- History Guru -->
        <div class="col-12 col-xl-6">
            <div class="card custom-card rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3.5 px-4 border-bottom border-light">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-secondary"></i> Riwayat Leaderboard Semester - Guru
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-secondary fw-semibold">Periode</th>
                                    <th class="text-secondary fw-semibold">Nama</th>
                                    <th class="text-secondary fw-semibold">Rank</th>
                                    <th class="text-secondary fw-semibold">Poin</th>
                                    <th class="pe-4 text-secondary fw-semibold">Tier Badges</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historyGuru as $history)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $history->period->name }}</td>
                                    <td><span class="d-inline-block text-truncate" style="max-width: 140px;">{{ $history->user->name }}</span></td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">#{{ $history->rank }}</span></td>
                                    <td class="font-monospace fw-bold text-primary">{{ number_format($history->points) }}</td>
                                    <td class="pe-4"><span class="small fw-semibold text-uppercase text-muted border-start border-3 ps-2" style="border-color:#dee2e6!important;">{{ $history->badge }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-folder-x fs-3 d-block mb-2 opacity-50"></i>Belum terdata arsip historis.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2.5 border-top border-light d-flex justify-content-end">
                    {{ $historyGuru->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Aturan Penilaian Modern Dashboard Rules -->
    <div class="card custom-card rounded-4 mt-5">
        <div class="card-header bg-white border-bottom border-light py-3.5 px-4">
            <h6 class="mb-0 fw-bold text-dark text-uppercase" style="letter-spacing: 0.5px;">
                <i class="bi bi-shield-fill-exclamation text-primary me-2"></i> Konfigurasi Kriteria Tingkatan & Aturan Lencana
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="p-3 rule-box">
                        <span class="badge badge-premium badge-bronze rounded-pill px-3 py-2 mb-3 w-100">Bronze Badge</span>
                        <h5 class="fw-extrabold mb-0 text-dark font-monospace">0 - 50 <span class="fs-7 fw-normal text-muted">Pts</span></h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rule-box">
                        <span class="badge badge-premium badge-silver rounded-pill px-3 py-2 mb-3 w-100">Silver Badge</span>
                        <h5 class="fw-extrabold mb-0 text-dark font-monospace">51 - 100 <span class="fs-7 fw-normal text-muted">Pts</span></h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rule-box" style="border: 1px solid rgba(252, 196, 25, 0.4);">
                        <span class="badge badge-premium badge-gold rounded-pill px-3 py-2 mb-3 w-100">Gold Trophy</span>
                        <h5 class="fw-extrabold mb-0 text-dark font-monospace">101 - 200 <span class="fs-7 fw-normal text-muted">Pts</span></h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rule-box" style="border: 1px solid rgba(33, 37, 41, 0.4);">
                        <span class="badge badge-premium badge-platinum rounded-pill px-3 py-2 mb-3 w-100">Platinum Crown</span>
                        <h5 class="fw-extrabold mb-0 text-dark font-monospace">> 200 <span class="fs-7 fw-normal text-muted">Pts</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Periode dengan Desain Clean Premium -->
<div class="modal fade" id="modalPeriode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('leaderboard.storePeriod') }}" method="POST" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom border-light px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-plus text-primary"></i> Tambah Periode Reward Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Jenis Semester</label>
                        <select name="semester" class="form-select rounded-3">
                            <option value="ganjil">Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Tahun Ajaran</label>
                        <input type="text" name="academic_year" class="form-control rounded-3" placeholder="Contoh: 2025/2026" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary small">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary small">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control rounded-3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light px-4 py-3">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold text-muted" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-semibold px-4 shadow-sm">Simpan Periode</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection