@extends('layouts.admin')

@section('title', 'Laporan & Statistik')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    /* Styling tambahan untuk kesan modern */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important;
    }
    .nav-pills .nav-link {
        border-radius: 50rem;
        padding: 0.5rem 1.5rem;
        color: var(--bs-body-color);
        font-weight: 500;
    }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
    /* Memastikan tabel laporan rapi di dark mode */
    [data-bs-theme="dark"] .report-preview {
        background-color: var(--bs-gray-900);
        color: var(--bs-gray-300);
    }
</style>
@endpush

@section('content')

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Laporan & Statistik</h3>
        <p class="text-secondary mb-0">Ringkasan aktivitas dan data perpustakaan bulan ini.</p>
    </div>

    <div class="d-flex flex-column flex-md-row gap-2">
        <form method="GET" class="d-flex gap-2 align-items-center">
            <select name="month" class="form-select form-select-sm rounded-pill px-3 shadow-sm border-0 bg-body-tertiary">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" class="form-select form-select-sm rounded-pill px-3 shadow-sm border-0 bg-body-tertiary">
                @for($y = now()->year; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-filter"></i> Filter
            </button>
        </form>

        <a href="{{ route('web.laporan.pdf', ['month'=>$month, 'year'=>$year]) }}" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
    </div>
</div>

<ul class="nav nav-pills mb-4 gap-2 bg-body-tertiary p-2 rounded-pill d-inline-flex shadow-sm" id="laporanTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#ringkasan"><i class="bi bi-grid me-1"></i> Ringkasan</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#statistik"><i class="bi bi-bar-chart me-1"></i> Statistik</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#apriori"><i class="bi bi-diagram-3 me-1"></i> Apriori</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#detail"><i class="bi bi-card-text me-1"></i> Detail Laporan</button>
    </li>
</ul>

<div class="tab-content">

    {{-- =========================
         TAB RINGKASAN
    ========================== --}}
    <div class="tab-pane fade show active" id="ringkasan">
        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm bg-body-tertiary rounded-4 h-100 card-hover">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-book fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary mb-1">Total Buku</h6>
                            <h3 class="fw-bold mb-0">{{ $totalBooks }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm bg-body-tertiary rounded-4 h-100 card-hover">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3 text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary mb-1">Total Anggota</h6>
                            <h3 class="fw-bold mb-0">{{ $totalMembers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm bg-body-tertiary rounded-4 h-100 card-hover">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3 text-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-right-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary mb-1">Peminjaman</h6>
                            <h3 class="fw-bold mb-0">{{ $totalBorrowings }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm bg-body-tertiary rounded-4 h-100 card-hover">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-4 me-3 text-info d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-left-circle fs-3"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary mb-1">Pengembalian</h6>
                            <h3 class="fw-bold mb-0">{{ $totalReturned }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
         TAB STATISTIK
    ========================== --}}
    <div class="tab-pane fade" id="statistik">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-body-tertiary rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Buku Terpopuler</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2">
                        <div style="height:300px">
                            <canvas id="bookChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-body-tertiary rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Kategori Peminjaman</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2">
                        <div style="height:300px">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0 bg-body-tertiary rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Distribusi Peminjaman Berdasarkan Peran</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-2">
                        <div style="height:320px">
                            <canvas id="roleChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
         TAB DETAIL LAPORAN
    ========================== --}}
    <div class="tab-pane fade" id="detail">
        <div class="card shadow-sm border-0 rounded-4 report-preview">
            <div class="card-body p-4 p-md-5">
                <!-- KOP SURAT -->
                <div class="row align-items-center mb-4">
                    <div class="col-2 text-center">
                        <img src="{{ asset('img/logo-tutwuri.png') }}" width="70" class="img-fluid object-fit-contain">
                    </div>
                    <div class="col-8 text-center">
                        <h6 class="mb-0 fw-semibold text-secondary">PEMERINTAH KOTA PADANG</h6>
                        <h6 class="mb-0 fw-semibold text-secondary">DINAS PENDIDIKAN</h6>
                        <h4 class="fw-bold mb-0 text-body">SMA NEGERI 3 PADANG</h4>
                        <small class="text-secondary">PERPUSTAKAAN SMA NEGERI 3 PADANG</small>
                    </div>
                    <div class="col-2 text-center">
                        <img src="{{ asset('img/logo-sman3.png') }}" width="70" class="img-fluid object-fit-contain">
                    </div>
                </div>

                <hr class="border-secondary border-2 opacity-50 mb-4">

                <!-- JUDUL LAPORAN -->
                <div class="text-center mb-5">
                    <h5 class="fw-bold text-body">LAPORAN TRANSAKSI PERPUSTAKAAN</h5>
                    <p class="mb-1 text-secondary">Nomor :/ PERPUS-SMAN3 / {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</p>
                    <p class="mb-1 fw-medium text-body">Periode {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</p>
                    <small class="text-secondary">Tanggal Cetak : {{ now()->format('d F Y') }}</small>
                </div>

                <!-- ==========================================
                     EKSTRAKSI DATA BUKU HILANG, RUSAK & TERLAMBAT 
                =========================================== -->
                @php
                    $lostBooksList = [];
                    $damagedBooksList = [];
                    $lateBooksList = [];
                    $now = \Carbon\Carbon::now()->startOfDay();

                    foreach($borrowings as $item) {
                        $trxStatus = strtolower($item->status ?? '');

                        // 1. LOGIKA BUKU HILANG & RUSAK (Kolektif & Individu)
                        if($item->is_collective && $item->borrowedExemplars) {
                            foreach($item->borrowedExemplars as $borrowed) {
                                $pivotStatus = strtolower($borrowed->status ?? '');
                                $masterStatus = isset($borrowed->exemplar) ? strtolower($borrowed->exemplar->status ?? '') : '';
                                
                                $detail = $item->details->where('id', $borrowed->borrowing_detail_id)->first();
                                if(!$detail) $detail = $item->details->first();

                                // Cek Hilang
                                if(in_array($pivotStatus, ['lost', 'hilang']) || in_array($trxStatus, ['lost', 'hilang']) || in_array($masterStatus, ['lost', 'hilang'])) {
                                    if($borrowed->exemplar) {
                                        $lostBooksList[] = [
                                            'name' => $item->user->name ?? 'Unknown',
                                            'role' => $item->user->role ?? '-',
                                            'title' => $detail->book->title ?? 'Judul Tidak Ditemukan',
                                            'code' => $borrowed->exemplar->code ?? '-',
                                            'date' => $item->borrow_date,
                                            'trx_status' => $trxStatus
                                        ];
                                    }
                                }

                                // Cek Rusak
                                if(in_array($pivotStatus, ['damaged', 'rusak']) || in_array($masterStatus, ['damaged', 'rusak'])) {
                                    if($borrowed->exemplar) {
                                        $damagedBooksList[] = [
                                            'name' => $item->user->name ?? 'Unknown',
                                            'role' => $item->user->role ?? '-',
                                            'title' => $detail->book->title ?? 'Judul Tidak Ditemukan',
                                            'code' => $borrowed->exemplar->code ?? '-',
                                            'date' => $item->borrow_date,
                                            'trx_status' => $trxStatus
                                        ];
                                    }
                                }
                            }
                        } else {
                            foreach($item->details as $detail) {
                                $masterStatus = isset($detail->exemplar) ? strtolower($detail->exemplar->status ?? '') : '';
                                
                                // Cek Hilang
                                if(in_array($trxStatus, ['lost', 'hilang']) || in_array($masterStatus, ['lost', 'hilang'])) {
                                    if($detail->exemplar) {
                                        $lostBooksList[] = [
                                            'name' => $item->user->name ?? 'Unknown',
                                            'role' => $item->user->role ?? '-',
                                            'title' => $detail->book->title ?? 'Judul Tidak Ditemukan',
                                            'code' => $detail->exemplar->code ?? '-',
                                            'date' => $item->borrow_date,
                                            'trx_status' => $trxStatus
                                        ];
                                    }
                                }

                                // Cek Rusak
                                if(in_array($masterStatus, ['damaged', 'rusak']) || in_array($trxStatus, ['damaged', 'rusak'])) {
                                    if($detail->exemplar) {
                                        $damagedBooksList[] = [
                                            'name' => $item->user->name ?? 'Unknown',
                                            'role' => $item->user->role ?? '-',
                                            'title' => $detail->book->title ?? 'Judul Tidak Ditemukan',
                                            'code' => $detail->exemplar->code ?? '-',
                                            'date' => $item->borrow_date,
                                            'trx_status' => $trxStatus
                                        ];
                                    }
                                }
                            }
                        }

                        // 2. LOGIKA TERLAMBAT
                        if($item->return_date) {
                            $isLate = false;
                            $lateDays = 0;
                            $returnDate = \Carbon\Carbon::parse($item->return_date)->startOfDay();

                            if($trxStatus == 'dikembalikan') {
                                if($item->returned_at) {
                                    $returnedAt = \Carbon\Carbon::parse($item->returned_at)->startOfDay();
                                    if($returnedAt->gt($returnDate)) {
                                        $isLate = true;
                                        $lateDays = intval($returnDate->diffInDays($returnedAt)); 
                                    }
                                }
                            } elseif(in_array($trxStatus, ['dipinjam', 'menunggu_pengembalian'])) {
                                if($now->gt($returnDate)) {
                                    $isLate = true;
                                    $lateDays = intval($returnDate->diffInDays($now));
                                }
                            }

                            if($isLate && $lateDays > 0) {
                                $bookTitles = [];
                                $qty = 0;
                                foreach($item->details as $detail) {
                                    $bookTitles[] = $detail->book->title ?? 'Unknown';
                                    $qty += $detail->qty ?? 1;
                                }
                                $lateBooksList[] = [
                                    'name' => $item->user->name ?? 'Unknown',
                                    'role' => $item->user->role ?? '-',
                                    'books' => implode(', ', array_unique($bookTitles)),
                                    'qty' => $qty,
                                    'late_days' => $lateDays,
                                    'status' => $item->status
                                ];
                            }
                        }
                    }

                    // Membersihkan duplikasi data
                    $lostBooksList = collect($lostBooksList)->unique('code')->values()->all();
                    $damagedBooksList = collect($damagedBooksList)->unique('code')->values()->all();
                    usort($lateBooksList, function($a, $b) {
                        return $b['late_days'] <=> $a['late_days'];
                    });
                @endphp

                <!-- A. RINGKASAN CARD GRID -->
                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-bar-chart-line-fill me-1"></i> A. Ringkasan Laporan Periode Ini</h6>
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="card bg-body-tertiary border-0 rounded-3 shadow-none h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-info-circle me-1"></i> Sirkulasi Umum</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td class="text-secondary">Total Peminjaman</td><td class="text-end fw-bold">{{ $totalBorrowings }}</td></tr>
                                    <tr><td class="text-secondary">Total Pengembalian</td><td class="text-end fw-bold">{{ $totalReturned }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-body-tertiary border-0 rounded-3 shadow-none h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-stack me-1"></i> Sirkulasi Kolektif</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td class="text-secondary">Transaksi Kolektif</td><td class="text-end fw-bold">{{ $borrowings->where('is_collective', true)->count() }}</td></tr>
                                    <tr><td class="text-secondary">Eksemplar Keluar</td><td class="text-end fw-bold">{{ $borrowings->where('is_collective', true)->sum(fn($i) => optional($i->borrowedExemplars)->count() ?? 0) }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger bg-opacity-10 border-0 rounded-3 shadow-none h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle-fill me-1"></i> Status Perhatian</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-danger">Eksemplar Hilang</td>
                                        <td class="text-end fw-bold text-danger">{{ count($lostBooksList) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-warning">Eksemplar Rusak</td>
                                        <td class="text-end fw-bold text-warning">{{ count($damagedBooksList) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-danger">Transaksi Terlambat</td>
                                        <td class="text-end fw-bold text-danger">{{ count($lateBooksList) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- B. RINCIAN BUKU HILANG -->
                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-journal-x me-1"></i> B. Rincian Eksemplar Buku Hilang</h6>
                <div class="table-responsive rounded-3 border border-secondary-subtle mb-5">
                    <table class="table table-hover align-middle mb-0 table-sm">
                        <thead class="table-danger">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Peminjam</th>
                                <th width="30%">Judul Buku</th>
                                <th width="15%">Kode Eksemplar</th>
                                <th width="15%">Tgl Dipinjam</th>
                                <th width="15%">Penyelesaian</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($lostBooksList as $lost)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-medium d-block">{{ $lost['name'] }}</span>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ ucfirst($lost['role']) }}</span>
                                </td>
                                <td>{{ $lost['title'] }}</td>
                                <td><span class="badge bg-danger text-white border border-danger-subtle">{{ $lost['code'] }}</span></td>
                                <td class="text-secondary">{{ \Carbon\Carbon::parse($lost['date'])->format('d M Y') }}</td>
                                <td>
                                    @if(in_array(strtolower($lost['trx_status']), ['dikembalikan', 'selesai']))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>Selesai / Diganti</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>Belum Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-secondary py-4"><i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>Tidak ada riwayat buku hilang pada data periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- C. RINCIAN BUKU RUSAK -->
                <h6 class="fw-bold mb-3 text-warning">
                    <i class="bi bi-tools me-1"></i>
                    C. Rincian Eksemplar Buku Rusak
                </h6>
                <div class="table-responsive rounded-3 border border-secondary-subtle mb-5">
                    <table class="table table-hover align-middle mb-0 table-sm">
                        <thead class="table-warning">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Peminjam</th>
                                <th width="30%">Judul Buku</th>
                                <th width="15%">Kode Eksemplar</th>
                                <th width="15%">Tgl Dipinjam</th>
                                <th width="15%">Penyelesaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($damagedBooksList as $damaged)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-medium d-block">{{ $damaged['name'] }}</span>
                                    <span class="badge bg-secondary" style="font-size:.7rem">
                                        {{ ucfirst($damaged['role']) }}
                                    </span>
                                </td>
                                <td>{{ $damaged['title'] }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark border border-warning">
                                        {{ $damaged['code'] }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($damaged['date'])->format('d M Y') }}
                                </td>
                                <td>
                                    @if(in_array(strtolower($damaged['trx_status']), ['dikembalikan','selesai']))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">
                                            <i class="bi bi-tools me-1"></i>
                                            Belum Diperbaiki
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                                    Tidak ada riwayat buku rusak pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- D. RINCIAN KETERLAMBATAN PENGEMBALIAN -->
                <h6 class="fw-bold mb-3 text-warning-emphasis"><i class="bi bi-clock-history me-1"></i> D. Rincian Keterlambatan Pengembalian</h6>
                <div class="table-responsive rounded-3 border border-secondary-subtle mb-5">
                    <table class="table table-hover align-middle mb-0 table-sm">
                        <thead class="table-warning">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="25%">Nama Peminjam</th>
                                <th width="35%">Buku yang Dipinjam</th>
                                <th width="15%" class="text-center">Keterlambatan</th>
                                <th width="20%">Status Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($lateBooksList as $late)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-medium d-block">{{ $late['name'] }}</span>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ ucfirst($late['role']) }}</span>
                                </td>
                                <td>
                                    <span class="d-block text-truncate" style="max-width: 250px;" title="{{ $late['books'] }}">{{ $late['books'] }}</span>
                                    <small class="text-secondary">{{ $late['qty'] }} Eksemplar</small>
                                </td>
                                <td class="text-center"><span class="badge bg-danger rounded-pill">{{ $late['late_days'] }} Hari</span></td>
                                <td>
                                    @if($late['status'] == 'dikembalikan')
                                        <span class="text-success small fw-semibold"><i class="bi bi-check-circle me-1"></i>Sudah Kembali</span>
                                    @else
                                        <span class="text-danger small fw-semibold"><i class="bi bi-x-circle me-1"></i>Belum Kembali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-secondary py-4"><i class="bi bi-emoji-smile text-success fs-4 d-block mb-1"></i>Seluruh peminjaman dikembalikan tepat waktu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- E. TRANSAKSI LENGKAP -->
                <h6 class="fw-bold mb-3 mt-4 text-primary"><i class="bi bi-card-checklist me-1"></i> E. Detail Transaksi Lengkap</h6>
                <div class="table-responsive rounded-3 border border-secondary-subtle mb-5">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bg-body-secondary">
                            <tr>
                                <th>No</th><th>Peminjam</th><th>Buku / Eksemplar</th>
                                <th>Jenis</th><th>Status Transaksi</th><th>Keterangan</th><th>Periode</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($borrowings as $item)
                            <tr>
                                <td class="text-body">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="text-body fw-medium d-block">{{ $item->user->name ?? '-' }}</span>
                                    <span class="badge bg-secondary rounded-pill px-2 mt-1" style="font-size:0.7rem">{{ ucfirst($item->user->role ?? '-') }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                        <div class="mb-2">
                                            <span class="text-body fw-semibold d-block" style="font-size: 0.9rem;">{{ $detail->book->title ?? '-' }}</span>
                                            @if($item->is_collective)
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill mb-1">{{ $detail->qty }} Eks</span>
                                                <div class="d-flex flex-wrap gap-1 mt-1">
                                                @foreach(optional($item->borrowedExemplars)->where('borrowing_detail_id', $detail->id) ?? [] as $borrowed)
                                                    <span class="badge {{ strtolower(optional($borrowed->exemplar)->status ?? '') == 'hilang' || $borrowed->status == 'lost' ? 'bg-danger text-white border-danger' : (strtolower(optional($borrowed->exemplar)->status ?? '') == 'rusak' || $borrowed->status == 'damaged' ? 'bg-warning text-dark border-warning' : 'bg-body-secondary text-body') }} border" style="font-size: 0.7rem;">{{ $borrowed->exemplar->code ?? '-' }}</span>
                                                @endforeach
                                                </div>
                                            @else
                                                @if($detail->exemplar)
                                                    <span class="badge {{ strtolower(optional($detail->exemplar)->status ?? '') == 'hilang' ? 'bg-danger text-white border-danger' : (strtolower(optional($detail->exemplar)->status ?? '') == 'rusak' ? 'bg-warning text-dark border-warning' : 'bg-body-secondary text-body') }} border mt-1" style="font-size: 0.7rem;">{{ $detail->exemplar->code }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($item->is_collective)
                                        <span class="badge bg-primary rounded-pill px-3">Kolektif</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3">Individu</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'dikembalikan' => 'success', 'dipinjam' => 'primary',
                                            'menunggu' => 'warning', 'menunggu_pengembalian' => 'info', 'hilang' => 'danger', 'rusak' => 'warning'
                                        ];
                                        $color = $statusColors[strtolower($item->status)] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color == 'warning' ? 'dark' : $color }} border border-{{ $color }}-subtle rounded-pill px-3">
                                        {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $retDate = \Carbon\Carbon::parse($item->return_date)->startOfDay();
                                    @endphp
                                    @if(strtolower($item->status) == 'dikembalikan')
                                        @if($item->returned_at && \Carbon\Carbon::parse($item->returned_at)->startOfDay()->gt($retDate))
                                            <span class="badge bg-danger rounded-pill px-3">Terlambat</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-3">Tepat Waktu</span>
                                        @endif
                                    @elseif(in_array(strtolower($item->status), ['dipinjam', 'menunggu_pengembalian']))
                                        @if(\Carbon\Carbon::now()->startOfDay()->gt($retDate))
                                            <span class="badge bg-danger rounded-pill px-3">Terlambat</span>
                                        @else
                                            <span class="badge bg-primary rounded-pill px-3">Berjalan</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">-</span>
                                    @endif
                                </td>
                                <td class="text-secondary small" style="font-size:0.8rem;">
                                    {{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/Y') }} <br>
                                    <i class="bi bi-arrow-down text-muted"></i> <br>
                                    {{ \Carbon\Carbon::parse($item->return_date)->format('d/m/Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada data transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- TANDA TANGAN -->
                <div class="row mt-5 pt-4 text-body">
                    <div class="col-6 text-center">
                        <p class="mb-5">Mengetahui,<br>Kepala Pustakawan</p>
                        <p class="fw-bold mb-0 text-decoration-underline">{{ $kepalaPustakawan->name ?? '-' }}</p>
                    </div>
                    <div class="col-6 text-center">
                        <p class="mb-5">Padang, {{ now()->format('d F Y') }}<br>Pustakawan</p>
                        <p class="fw-bold mb-0 text-decoration-underline">{{ $pustakawan->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
         TAB APRIORI
    ========================== --}}
    <div class="tab-pane fade" id="apriori">
        <div class="card shadow-sm border-0 rounded-4 bg-body-tertiary">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center gap-2">
                <div class="bg-primary p-2 rounded-3 text-white">
                    <i class="bi bi-cpu"></i>
                </div>
                <h5 class="fw-bold mb-0">Analisis Apriori</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex align-items-start gap-3 rounded-4">
                    <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                    <div>
                        <h6 class="fw-bold text-primary mb-1">Informasi Analisis</h6>
                        <p class="mb-0 text-body-secondary small">Analisis Apriori digunakan untuk menemukan pola buku yang sering dipinjam secara bersamaan oleh anggota perpustakaan, membantu dalam penataan tata letak fisik buku.</p>
                    </div>
                </div>

                <div class="table-responsive rounded-3 border border-secondary-subtle my-4">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bg-body-secondary">
                            <tr><th>No</th><th>Buku Utama</th><th>Buku Terkait</th><th>Frekuensi</th></tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($aprioriData->take(5) as $item)
                            <tr>
                                <td class="text-body">{{ $loop->iteration }}</td>
                                <td class="text-body fw-medium">{{ $item['buku_utama'] }}</td>
                                <td class="text-body">{{ $item['buku_terkait'] }}</td>
                                <td><span class="badge bg-primary rounded-pill px-3">{{ $item['total'] }} kali</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data transaksi yang dapat dianalisis.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($aprioriData->count())
                <div class="row g-4 mt-2">
                    <div class="col-md-7">
                        <div class="p-4 bg-success bg-opacity-10 rounded-4 h-100 border border-success-subtle">
                            <h6 class="fw-bold text-success mb-3"><i class="bi bi-lightbulb-fill me-1"></i> Kesimpulan</h6>
                            @php
                                $topRule = $aprioriData->sortByDesc('total')->first();
                            @endphp

                            @if($topRule)
                            <p class="mb-2">
                                Berdasarkan hasil analisis Apriori, kombinasi buku yang paling sering dipinjam secara bersamaan adalah 
                                <strong>{{ $topRule['buku_utama'] }}</strong> dan <strong>{{ $topRule['buku_terkait'] }}</strong> sebanyak <strong>{{ $topRule['total'] }} transaksi</strong>.
                            </p>
                            <p class="small text-body-secondary mb-0">
                                Kombinasi tersebut menunjukkan adanya keterkaitan kebutuhan informasi pengguna sehingga dapat dijadikan dasar dalam penataan rak dan pengembangan koleksi.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-4 bg-warning bg-opacity-10 rounded-4 h-100 border border-warning-subtle">
                            <h6 class="fw-bold text-warning-emphasis mb-3"><i class="bi bi-bullseye me-1"></i> Rekomendasi Aksi</h6>
                            <p class="text-body-secondary small mb-0">Tempatkan buku-buku yang sering dipinjam bersamaan pada rak yang berdekatan. Pertimbangkan juga penambahan stok eksemplar untuk kombinasi judul tersebut agar mengurangi status *menunggu*.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let bookChart = null;
        let categoryChart = null;
        let roleChart = null;

        function renderCharts() {
            const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            const chartTextColor = isDarkMode ? '#94a3b8' : '#64748b';

            Chart.defaults.color = chartTextColor;
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

            if (bookChart) bookChart.destroy();
            if (categoryChart) categoryChart.destroy();
            if (roleChart) roleChart.destroy();

            const ctxBook = document.getElementById('bookChart');
            if (ctxBook) {
                bookChart = new Chart(ctxBook, {
                    type: 'bar',
                    data: {
                        labels: @json($bookLabels ?? []),
                        datasets: [{
                            label: 'Intensitas Sirkulasi',
                            data: @json($bookData ?? []),
                            backgroundColor: '#2563eb',
                            borderRadius: 6
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
            }

            const ctxCategory = document.getElementById('categoryChart');
            if (ctxCategory) {
                categoryChart = new Chart(ctxCategory, {
                    type: 'pie',
                    data: {
                        labels: @json($categoryLabels ?? []),
                        datasets: [{
                            data: @json($categoryData ?? []),
                            backgroundColor: ['#2563eb', '#f59e0b', '#06b6d4', '#ef4444', '#10b981']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            const ctxRole = document.getElementById('roleChart');
            if (ctxRole) {
                roleChart = new Chart(ctxRole, {
                    type: 'doughnut',
                    data: {
                        labels: ['Siswa/Murid', 'Guru'],
                        datasets: [{
                            data: [
                                {{ $borrowings->filter(fn($b) => in_array(optional($b->user)->role, ['siswa', 'murid', 'Siswa', 'Murid']))->count() }},
                                {{ $borrowings->filter(fn($b) => strtolower(optional($b->user)->role) === 'guru')->count() }}
                            ],
                            backgroundColor: ['#06b6d4', '#f59e0b']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        }

        const statistikTabBtn = document.querySelector('button[data-bs-target="#statistik"]');
        if (statistikTabBtn) {
            statistikTabBtn.addEventListener('shown.bs.tab', function () {
                renderCharts();
            });
        }

        const statistikPane = document.getElementById('statistik');
        if (statistikPane && statistikPane.classList.contains('active')) {
            renderCharts();
        }
    });
</script>
@endsection