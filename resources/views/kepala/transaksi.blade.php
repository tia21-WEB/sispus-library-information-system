@extends('layouts.kepala')

@section('title', 'Monitoring Transaksi')

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
    .card-warning::after { background: #f59e0b; }
    .card-success::after { background: #10b981; }
    .card-danger::after { background: #ef4444; }
    .card-info::after { background: #06b6d4; }
    .card-secondary::after { background: #64748b; }

    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Tab Premium Styling */
    .nav-tabs {
        border-bottom: 2px solid var(--border-color);
    }
    .nav-tabs .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 12px 20px;
        position: relative;
        background: transparent;
    }
    .nav-tabs .nav-link:hover {
        color: var(--text-main);
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary-color) !important;
        background: transparent !important;
        border: none;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px; left: 0; width: 100%; height: 2px;
        background: var(--primary-color);
    }

    /* Form Filter Controls */
    .form-control, .form-select {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        background-color: var(--bg-main);
        color: var(--text-main);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    /* Table Adjustments */
    .table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: var(--text-muted);
        background-color: var(--hover-bg) !important;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Monitoring Transaksi</h3>
        <p class="text-muted mb-0 small">Audit dan pantau peredaran sirkulasi buku civitas akademika</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-primary shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Transaksi</small>
                    <div class="icon-box bg-primary-subtle text-primary" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 mt-1" style="color: var(--text-main); font-weight: 800;">{{ $totalTransaksi }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-warning shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Dipinjam</small>
                    <div class="icon-box bg-warning-subtle text-warning" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-warning mb-0 mt-1" style="font-weight: 800;">{{ $totalDipinjam }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-success shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kembali</small>
                    <div class="icon-box bg-success-subtle text-success" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-0 mt-1" style="font-weight: 800;">{{ $totalDikembalikan }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-danger shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Terlambat</small>
                    <div class="icon-box bg-danger-subtle text-danger" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-0 mt-1" style="font-weight: 800;">{{ $totalTerlambat }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-info shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kolektif</small>
                    <div class="icon-box bg-info-subtle text-info" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-info mb-0 mt-1" style="font-weight: 800;">{{ $transaksi->where('is_collective', true)->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card stat-card card-danger shadow-sm p-1">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Buku Hilang</small>
                    <div class="icon-box bg-danger-subtle text-danger" style="transform: scale(0.85); margin-top: -5px;">
                        <i class="bi bi-journal-x"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-0 mt-1" style="font-weight: 800;">{{ $transaksi->where('status', 'hilang')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm p-3 mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-calendar-range me-1"></i> Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-calendar-range me-1"></i> Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-calendar-month me-1"></i> Bulan</label>
                <select name="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for ($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('month') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-calendar-event me-1"></i> Tahun</label>
                <select name="year" class="form-select">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3">
                    <i class="bi bi-filter"></i> Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-light border w-100 py-2 rounded-3 text-muted" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>

        <hr class="my-3 border-secondary opacity-25">

        <div class="row">
            <div class="col-12">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchTransaksi" class="form-control border-start-0 ps-0" placeholder="Ketik kata kunci untuk mencari data sirkulasi pada tabel di bawah..." style="border-radius: 0 10px 10px 0;">
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3 gap-2" id="transactionTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#siswa" type="button">
            <i class="bi bi-person-workspace me-2"></i>Transaksi Siswa
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#guru" type="button">
            <i class="bi bi-person-video3 me-2"></i>Transaksi Guru
        </button>
    </li>
</ul>

<div class="tab-content">
    
    <div class="tab-pane fade show active" id="siswa">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                    <i class="bi bi-table me-2 text-primary"></i>Daftar Sirkulasi Siswa
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableSiswa">
                        <thead>
                            <tr>
                                <th width="60" class="ps-4">No</th>
                                <th>Siswa</th>
                                <th>Buku</th>
                                <th width="200">Eksemplar</th>
                                <th width="130">Pinjam</th>
                                <th width="130">Tempo</th>
                                <th width="150" class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noSiswa = 1; @endphp
                            @foreach($transaksi as $item)
                                @if($item->user && $item->user->role == 'siswa')
                                <tr>
                                    <td class="ps-4 text-muted fw-bold">{{ $noSiswa++ }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--text-main);">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->nis_nip }}</small>
                                    </td>
                                    <td>
                                        @foreach($item->details as $detail)
                                        <div class="border rounded-3 p-2 mb-1" style="background-color: var(--hover-bg); border-color: var(--border-color) !important;">
                                            <div class="fw-semibold small text-truncate" style="max-width: 280px; color: var(--text-main);">{{ $detail->book->title }}</div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Qty: {{ $detail->qty }}</small>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($item->details as $detail)
                                            @if($detail->exemplar)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-2 mb-1">
                                                    <i class="bi bi-qr-code me-1"></i>{{ $detail->exemplar->code }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="small fw-medium text-muted">
                                        {{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}
                                    </td>
                                    <td class="small fw-medium text-muted">
                                        {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}
                                    </td>
                                    <td class="pe-4">
                                        @if($item->status == 'dikembalikan')
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">Dikembalikan</span>
                                        @elseif($item->status == 'dipinjam')
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-bold">Dipinjam</span>
                                        @elseif($item->status == 'hilang')
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">Hilang</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            @if($noSiswa == 1)
                                <tr>
                                    <td colspan="7" class="text-center p-5 text-muted">Tidak ada data transaksi siswa pada periode ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="guru">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                    <i class="bi bi-table me-2 text-success"></i>Daftar Sirkulasi Guru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableGuru">
                        <thead>
                            <tr>
                                <th width="60" class="ps-4">No</th>
                                <th width="220">Guru</th>
                                <th>Buku</th>
                                <th width="220">Eksemplar</th>
                                <th width="120">Jenis</th>
                                <th width="180">Keterangan</th>
                                <th width="130">Pinjam</th>
                                <th width="130">Tempo</th>
                                <th width="150" class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noGuru = 1; @endphp
                            @foreach($transaksi as $item)
                                @if($item->user && $item->user->role == 'guru')
                                <tr>
                                    <td class="ps-4 text-muted fw-bold">{{ $noGuru++ }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--text-main);">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->nis_nip }}</small>
                                    </td>
                                    <td>
                                        @foreach($item->details as $detail)
                                        <div class="border rounded-3 p-2 mb-1" style="background-color: var(--hover-bg); border-color: var(--border-color) !important;">
                                            <div class="fw-semibold small text-truncate" style="max-width: 250px; color: var(--text-main);">{{ $detail->book->title }}</div>
                                            <small class="text-muted" style="font-size: 0.75rem;">Qty: {{ $detail->qty }}</small>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($item->is_collective)
                                            @foreach($item->borrowedExemplars as $borrowed)
                                                <span class="badge {{ $borrowed->status == 'lost' ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-secondary-subtle text-secondary border-secondary-subtle' }} border px-2 py-1 rounded-2 mb-1">
                                                    <i class="bi bi-qr-code me-1"></i>{{ $borrowed->exemplar->code }}
                                                </span>
                                            @endforeach
                                        @else
                                            @foreach($item->details as $detail)
                                                @if($detail->exemplar)
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-2 mb-1">
                                                        <i class="bi bi-qr-code me-1"></i>{{ $detail->exemplar->code }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_collective)
                                            <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">Kolektif</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">Individu</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if($item->is_collective)
                                            <div class="fw-bold text-primary">{{ $item->class_name }}</div>
                                            <small class="text-muted d-block">Total: {{ $item->borrowedExemplars->count() }} Eks</small>
                                            <small class="text-danger">Hilang: {{ $item->borrowedExemplars->where('status','lost')->count() }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small fw-medium text-muted">
                                        {{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}
                                    </td>
                                    <td class="small fw-medium text-muted">
                                        {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}
                                    </td>
                                    <td class="pe-4">
                                        @if($item->status == 'dikembalikan')
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">Dikembalikan</span>
                                        @elseif($item->status == 'dipinjam')
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-bold">Dipinjam</span>
                                        @elseif($item->status == 'hilang')
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">Buku Hilang</span>
                                        @elseif($item->status == 'menunggu')
                                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-bold">Menunggu</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            @if($noGuru == 1)
                                <tr>
                                    <td colspan="9" class="text-center p-5 text-muted">Tidak ada data transaksi guru pada periode ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.getElementById('searchTransaksi').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.tab-content table tbody tr');

        rows.forEach(row => {
            // Abaikan baris "Tidak ada data" jika ada
            if(row.cells.length > 1) {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            }
        });
    });
</script>

@endsection