@extends('layouts.kepala')

@section('title', 'Laporan & Statistik')

@section('content')

{{-- 
    FITUR: Pengaturan Styling CSS Kustom untuk Laporan SaaS Profesional
    LOGIKA: Mengatur transisi animasi hover pada kartu statistik (`stat-card`), strip warna aksentuasi garis atas, styling tombol navigasi tab pill, kustomisasi elemen form filter, tata letak tabel fluid anti scroll horizontal, dan desain lembar dokumen resmi (kop surat serta laporan akuntansi).
--}}
<style>
    /* --- STYLE UPGRADE - PREMIUM SAAS REPORTING --- */
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
    
    /* Premium Aksentuasi Top Border Line */
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
    .card-platinum::after { background: #94a3b8; }

    .icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Modern Navigation Pills */
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

    /* Form Filter Controls */
    .form-control, .form-select {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        background-color: var(--bg-card);
        color: var(--text-main);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    /* Anti Scroll Horizontal & Fluid Table Design */
    .table {
        width: 100% !important;
        table-layout: auto;
    }
    .table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: var(--text-muted);
        background-color: var(--hover-bg) !important;
        padding: 12px 14px;
        border-color: var(--border-color) !important;
    }
    .table td {
        padding: 12px 14px;
        border-color: var(--border-color) !important;
        color: var(--text-main);
    }

    /* Lembar Dokumen Laporan (Kop & Kertas Resmi) */
    .report-sheet {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        color: var(--text-main);
    }
    .report-header-title h4, .report-header-title h5, .report-header-title h6 {
        color: var(--text-main) !important;
    }

    /* User Avatar Placeholder */
    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Override fixes bootstrap fade animation hidden elements */
    .tab-content > .tab-pane { display: none; }
    .tab-content > .active { display: block; }
</style>

{{-- 
    FITUR: Header Halaman Laporan & Statistik
    LOGIKA: Menampilkan judul modul pemantauan, kompilasi data otomatis, serta audit analitik eksekutif perpustakaan.
--}}
<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Laporan & Statistik</h3>
        <p class="text-muted mb-0 small">Monitoring, kompilasi data otomatis, dan audit analitik perpustakaan</p>
    </div>
</div>

{{-- 
    FITUR: Grid Kartu Statistik Ringkasan Atas
    LOGIKA: Menampilkan 4 metrik utama secara cepat: Total Buku, Total Anggota, Total Transaksi Sirkulasi, dan Total Buku Dikembalikan.
--}}
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card card-primary shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Buku</small>
                        <h2 class="fw-bold mb-0" style="color: var(--text-main); font-weight: 800;">{{ $totalBooks }}</h2>
                    </div>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card card-success shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Anggota</small>
                        <h2 class="fw-bold text-success mb-0" style="font-weight: 800;">{{ $totalMembers }}</h2>
                    </div>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card card-warning shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Transaksi</small>
                        <h2 class="fw-bold text-warning mb-0" style="font-weight: 800;">{{ $totalBorrowings }}</h2>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card card-info shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Dikembalikan</small>
                        <h2 class="fw-bold text-info mb-0" style="font-weight: 800;">{{ $totalReturned }}</h2>
                    </div>
                    <div class="icon-box bg-info-subtle text-info">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
    FITUR: Navigasi Tab Utama Laporan & Analitik
    LOGIKA: Menyediakan tombol navigasi pill untuk beralih antar modul tampilan: Laporan Periode, Visualisasi Grafik, Katalog Terpopuler, Leaderboard Gamifikasi, dan Analisis Algoritma Apriori.
--}}
<ul class="nav nav-pills mb-4 gap-2" id="reportMainTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#laporan" type="button">
            <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Periode
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#statistik" type="button">
            <i class="bi bi-pie-chart-fill window me-2"></i>Visualisasi Grafik
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#terpopuler" type="button">
            <i class="bi bi-star-fill me-2"></i>Katalog Terpopuler
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#gamifikasi" type="button">
            <i class="bi bi-trophy-fill me-2"></i>Leaderboard
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#apriori" type="button">
            <i class="bi bi-cpu-fill me-2"></i>Analisis Apriori 
        </button>
    </li>
</ul>

<div class="tab-content">

  {{-- ================================= --}}
  {{-- TAB 1: LAPORAN PERIODE (DOKUMEN RESMI) --}}
  {{-- ================================= --}}
  <div class="tab-pane fade show active" id="laporan" role="tabpanel">
        {{-- 
            FITUR: Formulir Filter Bulan dan Tahun Laporan
            LOGIKA: Mengirimkan parameter `month` dan `year` melalui method GET ke rute `kepala.laporan` untuk memfilter data laporan akuntansi secara dinamis.
        --}}
        <div class="card border-0 shadow-sm p-3 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('kepala.laporan') }}" class="row g-3 align-items-center">
                    <div class="col-12 col-sm-4 col-md-3">
                        <select name="month" class="form-select">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3">
                        <select name="year" class="form-select">
                            @for($i=date('Y');$i>=2024;$i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 col-md-2">
                        <button type="submit" class="btn btn-primary w-100 d-inline-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-funnel-fill"></i> Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 
            FITUR: Lembar Dokumen Laporan Resmi Sekolah (Kop Surat & Konten Audit)
            LOGIKA: Merender lembar dokumen selayaknya dokumen resmi instansi, lengkap dengan kop surat logo Kemdikbud dan SMAN 3 Padang, ringkasan eksekutif, tabel indikator utama, buku populer, jenis peminjaman, alur sirkulasi, ekstraksi buku hilang & rusak, tabel riwayat transaksi, kesimpulan analitis, dan tanda tangan kepala pustakawan.
        --}}
        <div class="card report-sheet rounded-4 p-4 p-md-5">
            <div class="card-body p-0">
                
                {{-- KOP SURAT RESMI --}}
                <div class="border-bottom border-3 border-dark pb-3 mb-4">
                    <div class="row align-items-center">
                        <div class="col-2 text-center d-none d-sm-block">
                            @if(file_exists(public_path('img/logo-tutwuri.png')))
                                <img src="{{ asset('img/logo-tutwuri.png') }}" style="width:75px" alt="Logo Kemdikbud">
                            @endif
                        </div>
                        <div class="col-12 col-sm-8 text-center report-header-title">
                            <h6 class="mb-0 fw-bold small" style="letter-spacing: 0.5px;">PEMERINTAH KOTA PADANG</h6>
                            <h6 class="mb-0 fw-bold small" style="letter-spacing: 0.5px;">DINAS PENDIDIKAN</h6>
                            <h4 class="fw-bold mb-0 my-1" style="letter-spacing: -0.5px;">SMAN 3 PADANG</h4>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem; color: var(--primary-color) !important;">PERPUSTAKAAN SEKOLAH</h5>
                            <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-geo-alt-fill me-1"></i>Jl. Gajah Mada, Gunung Pangilun, Padang</small>
                        </div>
                        <div class="col-2 text-center d-none d-sm-block">
                            @if(file_exists(public_path('img/logo-sman3.png')))
                                <img src="{{ asset('img/logo-sman3.png') }}" style="width:75px" alt="Logo SMAN 3 Padang">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center mb-5">
                    <h5 class="fw-bold mb-1" style="letter-spacing: -0.3px;">LAPORAN MONITORING AKTIVITAS PERPUSTAKAAN</h5>
                    <p class="text-muted small mb-0">Periode Akuntansi: <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded">{{ \Carbon\Carbon::create($year,$month,1)->translatedFormat('F Y') }}</span></p>
                </div>

                {{-- I. RINGKASAN EKSEKUTIF --}}
                <h6 class="fw-bold border-bottom pb-2 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>I. Ringkasan Eksekutif</h6>
                <p class="text-justify lh-base text-muted small mb-4">
                    Pada periode pertanggungjawaban <span class="fw-semibold text-dark">{{ \Carbon\Carbon::create($year,$month,1)->translatedFormat('F Y') }}</span>, sistem otomasi perpustakaan SISPUS SMAN 3 Padang merekam sebanyak <span class="fw-bold text-dark">{{ $totalBorrowings }}</span> berkas sirkulasi peminjaman koleksi buku fisik maupun digital. Dari total log sirkulasi tersebut, sebanyak <span class="fw-bold text-success">{{ $totalReturned }}</span> berkas transaksi dinyatakan telah selesai dikembalikan dengan aman oleh siswa maupun guru. Saat ini, sistem mencakup total inventarisasi koleksi sebanyak <span class="fw-bold text-dark">{{ $totalBooks }}</span> judul eksemplar buku terdaftar dengan basis interaksi dari <span class="fw-bold text-dark">{{ $totalMembers }}</span> civitas akademika berstatus anggota aktif perpustakaan.
                </p>

                {{-- EKSTRAKSI DATA BUKU HILANG & RUSAK (KEPALA PERPUS) --}}
                @php
                    $lostBooksList = [];
                    $damagedBooksList = [];
                    foreach($borrowings as $item) {
                        $trxStatus = strtolower($item->status ?? '');
                        
                        if($item->is_collective && $item->borrowedExemplars) {
                            foreach($item->borrowedExemplars as $borrowed) {
                                $pivotStatus = strtolower($borrowed->status ?? '');
                                $masterStatus = isset($borrowed->exemplar) ? strtolower($borrowed->exemplar->status ?? '') : '';
                                
                                $detail = $item->details->where('id', $borrowed->borrowing_detail_id)->first();
                                if(!$detail) $detail = $item->details->first();

                                // Ekstrak Hilang
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

                                // Ekstrak Rusak
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
                                
                                // Ekstrak Hilang
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

                                // Ekstrak Rusak
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
                    }
                    $lostBooksList = collect($lostBooksList)->unique('code')->values()->all();
                    $damagedBooksList = collect($damagedBooksList)->unique('code')->values()->all();
                @endphp

                {{-- II. INDIKATOR KINERJA UTAMA --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>II. Indikator Kinerja Utama</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr class="table-light">
                                <th width="75%">Dimensi Indikator Utama</th>
                                <th class="text-center">Kuantitas / Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Total Koleksi Inventaris Buku Terdaftar</td><td class="text-center fw-bold">{{ $totalBooks }}</td></tr>
                            <tr><td>Total Anggota Ekosistem Aktif</td><td class="text-center fw-bold">{{ $totalMembers }}</td></tr>
                            <tr><td>Log Aktivitas Peminjaman Buku Masuk</td><td class="text-center fw-bold text-primary">{{ $totalBorrowings }}</td></tr>
                            <tr><td>Log Penyelesaian Pengembalian Buku</td><td class="text-center fw-bold text-success">{{ $totalReturned }}</td></tr>
                            <tr><td>Total Pelanggaran Keterlambatan Sirkulasi</td><td class="text-center fw-bold text-danger">{{ $totalLate }}</td></tr>
                            <tr><td>Total Pelanggaran Kasus Eksemplar Hilang</td><td class="text-center fw-bold text-danger">{{ count($lostBooksList) }}</td></tr>
                            <tr><td>Total Pelanggaran Kasus Eksemplar Rusak</td><td class="text-center fw-bold text-warning">{{ count($damagedBooksList) }}</td></tr>
                            <tr>
                                <td>Rasio Deviasi / Persentase Keterlambatan Pengembalian</td>
                                <td class="text-center">
                                    <span class="badge {{ $totalBorrowings > 0 && ($totalLate / $totalBorrowings) * 100 > 20 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} fw-bold">
                                        {{ $totalBorrowings > 0 ? round(($totalLate / $totalBorrowings) * 100, 2) : 0 }} %
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- III. BUKU TERPOPULER --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>III. Buku Terpopuler (Intensitas Tinggi)</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr class="table-light">
                                <th width="10%" class="text-center">No</th>
                                <th>Judul Koleksi Buku</th>
                                <th width="25%" class="text-center">Frekuensi Pinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularBooks as $book)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $book->title }}</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded">{{ $book->borrowings_count }} Kali Peminjaman</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted small py-3">Tidak ada peredaran buku pada bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- IV & V. STATISTIK JENIS PEMINJAMAN & SEGMENTASI SIRKULASI --}}
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold border-bottom pb-2" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>IV. Statistik Jenis Peminjaman</h6>
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr class="table-light"><th>Kategori Jenis</th><th class="text-center">Kuantitas</th></tr>
                            </thead>
                            <tbody>
                                @foreach($popularCategories as $item)
                                <tr><td>{{ ucfirst($item->loan_type) }}</td><td class="text-center fw-bold text-secondary">{{ $item->total }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold border-bottom pb-2" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>V. Segmentasi Alur Sirkulasi</h6>
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr class="table-light"><th>Indikator Alur</th><th class="text-center">Jumlah</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Total Transaksi Sirkulasi Kolektif</td><td class="text-center fw-bold text-primary">{{ $borrowings->where('is_collective', true)->count() }}</td></tr>
                                <tr><td>Total Transaksi Sirkulasi Individu</td><td class="text-center fw-bold text-success">{{ $borrowings->where('is_collective', false)->count() }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- VI. BUKU HILANG --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>VI. Buku Hilang</h6>
                
                @if(count($lostBooksList) > 0)
                <div class="alert alert-danger bg-danger-subtle border-0 text-danger p-3 mb-3 small rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Peringatan Aset:</strong> Terdapat <strong>{{ count($lostBooksList) }} eksemplar</strong> buku yang dilaporkan hilang pada periode laporan ini.
                </div>
                @endif

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 0.85rem;">
                        <thead>
                            <tr class="table-light">
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Peminjam</th>
                                <th width="35%">Judul Koleksi</th>
                                <th width="15%" class="text-center">Eksemplar</th>
                                <th width="12%" class="text-center">Dipinjam</th>
                                <th width="13%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lostBooksList as $lost)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="d-block fw-semibold">{{ $lost['name'] }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">{{ ucfirst($lost['role']) }}</span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $lost['title'] }}">
                                        {{ $lost['title'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $lost['code'] }}</span>
                                </td>
                                <td class="text-center text-muted">{{ \Carbon\Carbon::parse($lost['date'])->format('d/m/y') }}</td>
                                <td class="text-center">
                                    @if(in_array(strtolower($lost['trx_status']), ['dikembalikan', 'selesai']))
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Diganti</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i>Hilang</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>Aman. Tidak ada kerugian buku pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- VII. BUKU RUSAK (BARU) --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>VII. Buku Rusak</h6>
                
                @if(count($damagedBooksList) > 0)
                <div class="alert alert-warning bg-warning-subtle border-0 text-warning-emphasis p-3 mb-3 small rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Peringatan Aset:</strong> Terdapat <strong>{{ count($damagedBooksList) }} eksemplar</strong> buku yang dilaporkan rusak pada periode laporan ini.
                </div>
                @endif

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 0.85rem;">
                        <thead>
                            <tr class="table-light">
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Peminjam</th>
                                <th width="35%">Judul Koleksi</th>
                                <th width="15%" class="text-center">Eksemplar</th>
                                <th width="12%" class="text-center">Dipinjam</th>
                                <th width="13%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($damagedBooksList as $damaged)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="d-block fw-semibold">{{ $damaged['name'] }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">{{ ucfirst($damaged['role']) }}</span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $damaged['title'] }}">
                                        {{ $damaged['title'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ $damaged['code'] }}</span>
                                </td>
                                <td class="text-center text-muted">{{ \Carbon\Carbon::parse($damaged['date'])->format('d/m/y') }}</td>
                                <td class="text-center">
                                    @if(in_array(strtolower($damaged['trx_status']), ['dikembalikan', 'selesai']))
                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-tools me-1"></i>Rusak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>Aman. Tidak ada kerusakan fisik buku pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- VIII. RIWAYAT TRANSAKSI --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>VIII. Riwayat Transaksi </h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead>
                            <tr class="table-light">
                                <th class="text-center" width="5%">No</th>
                                <th width="10%">Nama</th>
                                <th width="8%">Role</th>
                                <th width="20%">Katalog Buku</th>
                                <th width="13%">Eksemplar</th>
                                <th class="text-center" width="10%">Jenis</th>
                                <th class="text-center" width="12%">Status</th>
                                <th class="text-center" width="11%">Keterangan</th>
                                <th width="14%">Rentang Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $item)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ ($item->user->role ?? '') == 'guru' ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary' }} small">
                                        {{ ucfirst($item->user->role ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                        <div class="mb-1 text-truncate" style="max-width: 220px;" title="{{ $detail->book->title }}">
                                            <i class="bi bi-bookmark-fill small text-muted me-1"></i>{{ $detail->book->title }} ({{ $detail->qty }}x)
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($item->is_collective)
                                        @foreach($item->borrowedExemplars as $borrowed)
                                            <span class="badge {{ strtolower(optional($borrowed->exemplar)->status ?? '') == 'hilang' || $borrowed->status == 'lost' ? 'bg-danger' : (strtolower(optional($borrowed->exemplar)->status ?? '') == 'rusak' || $borrowed->status == 'damaged' ? 'bg-warning text-dark' : 'bg-secondary') }} mb-1" style="font-size: 0.7rem;">{{ $borrowed->exemplar->code }}</span>
                                        @endforeach
                                    @else
                                        @foreach($item->details as $detail)
                                            @if($detail->exemplar)
                                                <span class="badge {{ strtolower(optional($detail->exemplar)->status ?? '') == 'hilang' ? 'bg-danger' : (strtolower(optional($detail->exemplar)->status ?? '') == 'rusak' ? 'bg-warning text-dark' : 'bg-secondary') }} mb-1" style="font-size: 0.7rem;">{{ $detail->exemplar->code }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="small fw-semibold">{{ $item->is_collective ? 'Kolektif' : 'Individu' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'dikembalikan')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Kembali</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1">Dipinjam</span>
                                    @elseif($item->status == 'menunggu_pengembalian')
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1">Menunggu</span>
                                    @elseif($item->status == 'hilang')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1">Hilang</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $retDate = \Carbon\Carbon::parse($item->return_date)->startOfDay();
                                    @endphp
                                    @if(strtolower($item->status) == 'dikembalikan')
                                        @if($item->returned_at && \Carbon\Carbon::parse($item->returned_at)->startOfDay()->gt($retDate))
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Terlambat</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Tepat Waktu</span>
                                        @endif
                                    @elseif(in_array(strtolower($item->status), ['dipinjam', 'menunggu_pengembalian']))
                                        @if(\Carbon\Carbon::now()->startOfDay()->gt($retDate))
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Terlambat</span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">Berjalan</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">-</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/y') }} s/d {{ \Carbon\Carbon::parse($item->return_date)->format('d/m/y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- IX & X. KESIMPULAN MONITORING ANALITIS & TOP 5 ANGGOTA --}}
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold border-bottom pb-2" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>IX. Kesimpulan Monitoring Analitis</h6>
                        <div class="p-3 border rounded-3 bg-light-subtle small text-muted">
                            <p class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Koleksi buku dengan grafik peminjaman tertinggi: <strong class="text-dark">"{{ $popularBooks->first()->title ?? '-' }}"</strong>.</p>
                            <p class="mb-0"><i class="bi bi-shield-check text-success me-2"></i>Metode sirkulasi operasional primadona: <strong class="text-dark">{{ ucfirst($popularCategories->first()->loan_type ?? '-') }}</strong>.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold border-bottom pb-2" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>X. Top 5 Anggota Teraktif </h6>
                        <table class="table table-bordered table-sm align-middle mb-0 small">
                            <thead>
                                <tr class="table-light">
                                    <th class="text-center">Rank</th><th>Nama Anggota</th><th>Role</th><th class="text-center">Skor Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users->take(5) as $user)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold text-dark">{{ $user->name }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $user->points }} pts</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- XI. KLAUSUL CATATAN EVALUASI & REKOMENDASI --}}
                <h6 class="fw-bold border-bottom pb-2 mt-4 mb-2" style="color: var(--primary-color);"><i class="bi bi-caret-right-fill me-1"></i>XI. Klausul Catatan Evaluasi & Rekomendasi</h6>
                <div class="alert alert-light border shadow-inner p-3 small text-muted mb-5">
                    <div class="mb-1"><i class="bi bi-dot me-1"></i> Total sirkulasi transaksi peminjaman buku terkelola pada periode operasional ini mencakup <strong>{{ $totalBorrowings }}</strong> entitas berkas sirkulasi.</div>
                    <div class="mb-1"><i class="bi bi-dot me-1"></i> Rasio kesuksesan pemulihan/pengembalian buku tepat waktu berada pada angka kuantitatif: <strong>{{ $totalBorrowings > 0 ? round(($totalReturned / $totalBorrowings) * 100, 2) : 0 }}%</strong>.</div>
                    <div class="mb-1"><i class="bi bi-dot me-1"></i> Pelanggaran keterlambatan pengembalian buku terverifikasi sebanyak <strong>{{ $totalLate }}</strong> kasus sirkulasi aktif.</div>
                    <div class="mb-1"><i class="bi bi-dot me-1"></i> Pelanggaran kerusakan eksemplar buku terverifikasi sebanyak <strong>{{ count($damagedBooksList) }}</strong> kasus.</div>
                    <div class="mb-0"><i class="bi bi-dot me-1"></i> Interaksi jalur sirkulasi peminjaman kolektif tercatat sebanyak <strong>{{ $borrowings->where('is_collective', true)->count() }}</strong> berkas transaksi.</div>
                </div>

                {{-- TANDA TANGAN KEPALA PERPUSTAKAAN --}}
                <div class="row mt-5 pt-4">
                    <div class="col-6"></div>
                    <div class="col-6 text-center small text-muted">
                        Padang, {{ now()->translatedFormat('d F Y') }}
                        <br class="mb-1">Kepala Urusan Perpustakaan Sekolah
                        <br><br><br><br><br>
                        <strong class="text-dark border-bottom pb-1 px-3 d-inline-block">{{ $kepalaPustakawan->name ?? 'Drs. Afrizal, M.Pd' }}</strong>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('kepala.laporan.pdf',['month'=>$month,'year'=>$year]) }}" class="btn btn-danger d-inline-flex align-items-center gap-2 px-4 shadow-sm py-2 rounded-3">
                <i class="bi bi-file-earmark-pdf-fill fs-5"></i> Unduh PDF 
            </a>
        </div>
    </div>

  {{-- ================================= --}}
  {{-- TAB 2: VISUALISASI GRAFIK CHART.JS --}}
  {{-- ================================= --}}
  <div class="tab-pane fade" id="statistik" role="tabpanel">
        <!-- Baris Pertama: Grafik Buku & Pengembalian -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100 p-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: var(--text-main);"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Grafik Distribusi Buku Terpopuler</h6>
                        <div style="position: relative; width:100%; height:320px;">
                            <canvas id="bookChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100 p-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: var(--text-main);"><i class="bi bi-graph-up-arrow text-success me-2"></i>Monitoring Pengembalian Buku</h6>
                        <div style="position: relative; width:100%; height:320px;">
                            <canvas id="returnChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Baris Kedua: Grafik Proporsi Konten & Demografi Pengguna -->
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100 p-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: var(--text-main);"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Proporsi Jenis Peminjaman Konten</h6>
                        <div style="position: relative; width:100%; height:300px; display: flex; justify-content: center;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- GRAFIK BARU: SISWA VS GURU -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm h-100 p-2">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: var(--text-main);"><i class="bi bi-people-fill text-info me-2"></i>Demografi Peminjam (Siswa vs Guru)</h6>
                        <div style="position: relative; width:100%; height:300px; display: flex; justify-content: center;">
                            <canvas id="roleChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================= --}}
    {{-- TAB 3: KATALOG TERPOPULER --}}
    {{-- ================================= --}}
    <div class="tab-pane fade" id="terpopuler" role="tabpanel">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                    <i class="bi bi-award-fill text-primary me-2"></i>Buku Berpiringan Sirkulasi Tertinggi (Populer)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="80" class="ps-4 text-center">No</th>
                                <th>Judul Koleksi Katalog Buku</th>
                                <th width="220" class="pe-4 text-center">Intensitas Total Pinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularBooks as $book)
                            <tr>
                                <td class="ps-4 text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td class="fw-bold" style="color: var(--text-main);">{{ $book->title }}</td>
                                <td class="pe-4 text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-3 fw-bold" style="font-size: 0.8rem;">
                                        <i class="bi bi-arrow-repeat me-1"></i>{{ $book->borrowings_count }} Kali Terpinjam
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================= --}}
    {{-- TAB 4: LEADERBOARD & GAMIFIKASI --}}
    {{-- ================================= --}}
    <div class="tab-pane fade" id="gamifikasi" role="tabpanel">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Periode Leaderboard Aktif</small>
                    <h5 class="fw-bold mb-0">{{ optional($activePeriod)->name ?? 'Belum Ada Periode Aktif' }}</h5>
                </div>
                <span class="badge bg-success px-3 py-2">Monitoring</span>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card card-primary shadow-sm p-1">
                    <div class="card-body p-3">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Total Poin Siswa</small>
                        <h4 class="fw-bold mb-0" style="color: var(--text-main);">{{ $users->where('role','siswa')->sum('points') }} <span class="small text-muted fs-6">pts</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card card-success shadow-sm p-1">
                    <div class="card-body p-3">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Total Poin Guru</small>
                        <h4 class="fw-bold mb-0 text-success">{{ $users->where('role','guru')->sum('points') }} <span class="small text-muted fs-6">pts</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card card-info shadow-sm p-1">
                    <div class="card-body p-3">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Total Siswa Terdaftar</small>
                        <h4 class="fw-bold mb-0 text-info">{{ $users->where('role','siswa')->count() }} <span class="small text-muted fs-6">Orang</span></h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card card-warning shadow-sm p-1">
                    <div class="card-body p-3">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Total Guru Terdaftar</small>
                        <h4 class="fw-bold mb-0 text-warning">{{ $users->where('role','guru')->count() }} <span class="small text-muted fs-6">Orang</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-2">
                    <div class="card-body">
                        <div class="fs-2 mb-1">🥉</div>
                        <div class="text-muted small fw-bold text-uppercase">Bronze Badge</div>
                        <h4 class="fw-bold mb-0 mt-1" style="color: var(--text-main);">{{ $users->where('badge','Bronze')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-2">
                    <div class="card-body">
                        <div class="fs-2 mb-1">🥈</div>
                        <div class="text-muted small fw-bold text-uppercase">Silver Badge</div>
                        <h4 class="fw-bold mb-0 mt-1" style="color: var(--text-main);">{{ $users->where('badge','Silver')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-2">
                    <div class="card-body">
                        <div class="fs-2 mb-1">🏆</div>
                        <div class="text-muted small fw-bold text-uppercase">Gold Badge</div>
                        <h4 class="fw-bold mb-0 mt-1" style="color: var(--text-main);">{{ $users->where('badge','Gold')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-2">
                    <div class="card-body">
                        <div class="fs-2 mb-1">💎</div>
                        <div class="text-muted small fw-bold text-uppercase">Platinum Badge</div>
                        <h4 class="fw-bold mb-0 mt-1" style="color: var(--text-main);">{{ $users->where('badge','Platinum')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert bg-primary-subtle text-primary border-0 shadow-sm p-3 mb-4 rounded-3 d-flex flex-column gap-1 small">
            <div><i class="bi bi-info-circle-fill me-2"></i><strong>Kalkulasi Panel Gamifikasi</strong></div>
            <div class="mt-2">Total Akumulasi Poin Ekosistem Sistem: <span class="fw-bold">{{ $users->sum('points') }} pts</span></div>
            <div>Top Reader Kategori Siswa: <span class="fw-bold text-decoration-underline">{{ optional($users->where('role','siswa')->sortByDesc('points')->first())->name ?? 'Belum ada data' }}</span></div>
            <div>Top Reader Kategori Guru: <span class="fw-bold text-decoration-underline">{{ optional($users->where('role','guru')->sortByDesc('points')->first())->name ?? 'Belum ada data' }}</span></div>
        </div>

        <ul class="nav nav-tabs mb-3 gap-2" id="leaderboardSubTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active py-2 small" data-bs-toggle="tab" data-bs-target="#rankingSiswa" type="button">Ranking Siswa</button>
            </li>
            <li class="nav-item">
                <button class="nav-link py-2 small" data-bs-toggle="tab" data-bs-target="#rankingGuru" type="button">Ranking Guru</button>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="rankingSiswa" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="100" class="ps-4 text-center">Rank</th>
                                        <th>Nama Siswa</th>
                                        <th width="180" class="text-center">Skor Poin</th>
                                        <th width="180" class="pe-4 text-center">Badge Tier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users->where('role','siswa')->sortByDesc('points') as $user)
                                    <tr>
                                        <td class="ps-4 text-center fw-bold text-muted">
                                            @if($loop->iteration == 1) 🥇 @elseif($loop->iteration == 2) 🥈 @elseif($loop->iteration == 3) 🥉 @else {{ $loop->iteration }} @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                                <div class="fw-bold" style="color: var(--text-main);">{{ $user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-primary">{{ $user->points }} pts</td>
                                        <td class="pe-4 text-center">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">{{ $user->badge }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="rankingGuru" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="100" class="ps-4 text-center">Rank</th>
                                        <th>Nama Lengkap Guru</th>
                                        <th width="180" class="text-center">Skor Poin</th>
                                        <th width="180" class="pe-4 text-center">Badge Tier</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users->where('role','guru')->sortByDesc('points') as $user)
                                    <tr>
                                        <td class="ps-4 text-center fw-bold text-muted">
                                            @if($loop->iteration == 1) 🥇 @elseif($loop->iteration == 2) 🥈 @elseif($loop->iteration == 3) 🥉 @else {{ $loop->iteration }} @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="background: linear-gradient(135deg, #10b981, #34d399);">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                                <div class="fw-bold" style="color: var(--text-main);">{{ $user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-success">{{ $user->points }} pts</td>
                                        <td class="pe-4 text-center">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fw-bold">{{ $user->badge }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================= --}}
    {{-- TAB 5: ANALISIS APRIORI --}}
    {{-- ================================= --}}
    <div class="tab-pane fade" id="apriori" role="tabpanel">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                    <i class="bi bi-cpu-fill text-primary me-2"></i>Algoritma Apriori
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="alert bg-primary-subtle text-primary border-0 shadow-sm rounded-3 small mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>Analisis keterkaitan data menggunakan kerangka kerja Apriori membantu mendeteksi pola kombinasi buku yang memiliki kecenderungan paling tinggi untuk dipinjam secara bersamaan (*bundling sirkulasi) oleh anggota.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="70" class="ps-4 text-center">No</th>
                                <th>Katalog Utama (Antecedent)</th>
                                <th>Katalog Buku Terkait (Consequent)</th>
                                <th width="180" class="pe-4 text-center">Frekuensi Bersama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aprioriData as $item)
                            <tr>
                                <td class="ps-4 text-center text-muted fw-bold">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">{{ $item['buku_utama'] }}</td>
                                <td class="fw-semibold text-secondary">{{ $item['buku_terkait'] }}</td>
                                <td class="pe-4 text-center">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-3 fw-bold">
                                        <i class="bi bi-layer-forward me-1"></i>{{ $item['total'] }} Kali Sinergi
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">Belum ada kompilasi pola asosiasi Apriori pada database saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($aprioriData->count())
                <div class="mt-4 pt-3 border-top">
                    <div class="alert bg-success-subtle text-success border-0 rounded-3 p-3 small mb-3">
                        <i class="bi bi-lightbulb-fill me-2"></i><strong>Hasil Ekstraksi Aturan Asosiasi:</strong> Pengguna yang melakukan transaksi peminjaman buku <strong class="text-dark">"{{ $aprioriData->first()['buku_utama'] }}"</strong> memiliki kecenderungan kuat untuk meminjam koleksi <strong class="text-dark">"{{ $aprioriData->first()['buku_terkait'] }}"</strong> secara beriringan dengan intensitas kecocokan sebanyak <strong>{{ $aprioriData->first()['total'] }} kali</strong> transaksi.
                    </div>
                    <p class="text-muted small mb-0"><i class="bi bi-arrow-right-circle-fill me-2 text-primary"></i><strong>Rekomendasi Kebijakan:</strong> Urusan sarana perpustakaan direkomendasikan untuk mendekatkan posisi rak penempatan tata letak kedua buku tersebut, atau menambah kuantitas eksemplar keduanya guna menunjang tingginya pemintaan paralel dari anggota.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

{{-- 
    FITUR: Skrip JavaScript Render Grafik Chart.js & Sinkronisasi Tema
    LOGIKA: 
    1. Mendefinisikan fungsi `renderLibraryCharts()` untuk menginisialisasi 4 grafik Chart.js (Buku Terpopuler, Pengembalian, Jenis Peminjaman, dan Demografi Siswa vs Guru).
    2. Mendengarkan event tab agar grafik dirender saat tab visualisasi aktif.
    3. Menyinkronkan ulang render chart ketika tombol ubah tema dark/light mode diklik.
--}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Deklarasikan variabel chart secara global
    let bookChart = null;
    let returnChart = null;
    let categoryChart = null;
    let roleChart = null;

    // Fungsi untuk membuat/merender ulang grafik
    function renderLibraryCharts() {
        const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const chartTextColor = isDarkMode ? '#94a3b8' : '#64748b';
        const chartGridColor = isDarkMode ? '#222c45' : '#f1f5f9';

        // Atur default styling Chart.js
        Chart.defaults.color = chartTextColor;
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        // Hancurkan instance chart lama jika ada (mencegah duplikasi render bug)
        if (bookChart) bookChart.destroy();
        if (returnChart) returnChart.destroy();
        if (categoryChart) categoryChart.destroy();
        if (roleChart) roleChart.destroy();

        // 1. Chart Buku Terpopuler
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
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: chartGridColor } }
                    }
                }
            });
        }

        // 2. Chart Monitoring Pengembalian
        const ctxReturn = document.getElementById('returnChart');
        if (ctxReturn) {
            returnChart = new Chart(ctxReturn, {
                type: 'bar',
                data: {
                    labels: @json($returnLabels ?? []),
                    datasets: [{
                        label: 'Kuantitas Berkas',
                        data: @json($returnData ?? []),
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: chartGridColor } }
                    }
                }
            });
        }

        // 3. Chart Proporsi Jenis Peminjaman
        const ctxCategory = document.getElementById('categoryChart');
        if (ctxCategory) {
            categoryChart = new Chart(ctxCategory, {
                type: 'pie',
                data: {
                    labels: @json($categoryLabels ?? []),
                    datasets: [{
                        data: @json($categoryData ?? []),
                        backgroundColor: ['#2563eb', '#f59e0b', '#06b6d4', '#ef4444', '#10b981'],
                        borderWidth: isDarkMode ? 3 : 1,
                        borderColor: isDarkMode ? '#151b2c' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
                    }
                }
            });
        }

        // 4. GRAFIK BARU: Chart Demografi Peminjam (Siswa vs Guru)
        const ctxRole = document.getElementById('roleChart');
        if (ctxRole) {
            roleChart = new Chart(ctxRole, {
                type: 'doughnut',
                data: {
                    labels: ['Siswa/Murid', 'Guru'],
                    datasets: [{
                        data: [
                            {{ $borrowings->filter(fn($b) => in_array(optional($b->user)->role, ['siswa', 'murid']))->count() }},
                            {{ $borrowings->filter(fn($b) => optional($b->user)->role === 'guru')->count() }}
                        ],
                        backgroundColor: ['#06b6d4', '#f59e0b'], // Cyan untuk siswa, Amber untuk guru
                        borderWidth: isDarkMode ? 3 : 1,
                        borderColor: isDarkMode ? '#151b2c' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
                    }
                }
            });
        }
    }

    // Jalankan Chart saat Tab Statistik Aktif Sempurna
    document.addEventListener('DOMContentLoaded', function () {
        const statistikTabBtn = document.querySelector('button[data-bs-target="#statistik"]');
        if (statistikTabBtn) {
            statistikTabBtn.addEventListener('shown.bs.tab', function () {
                renderLibraryCharts();
            });
        }
    });

    // Sinkronisasi ulang warna teks grafik saat tombol ubah tema layout utama di-klik
    document.getElementById('darkModeToggle')?.addEventListener('click', () => {
        setTimeout(() => {
            window.location.reload();
        }, 350);
    });
</script>
@endsection