<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perpustakaan</title>
    {{-- 
        FITUR: Pengaturan Styling CSS Universal Dokumen PDF
        LOGIKA: Mengatur konfigurasi font dasar menggunakan DejaVu Sans, ukuran teks mikro (11px), line-height, serta margin untuk kompatibilitas pencetakan dokumen PDF.
    --}}
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 15px;
        }

        /* =====================
           KOP SURAT
        ===================== */
        .kop {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-table {
            width: 100%;
            border: none;
        }
        .kop-table td {
            border: none;
            vertical-align: middle;
        }
        .logo-kiri, .logo-kanan {
            width: 15%;
            text-align: center;
        }
        .logo-kiri img, .logo-kanan img {
            width: 75px;
        }
        .identitas {
            text-align: center;
            width: 70%;
        }
        .identitas h4 { margin: 2px 0; font-size: 13px; font-weight: bold; }
        .identitas h2 { margin: 2px 0; font-size: 18px; font-weight: bold; }
        .identitas h3 { margin: 2px 0; font-size: 14px; font-weight: bold; color: #2563eb; }
        .identitas p { margin-top: 5px; font-size: 10px; color: #555; }

        /* =====================
           JUDUL LAPORAN
        ===================== */
        .title {
            text-align: center;
            margin: 15px 0 25px 0;
        }
        .title h1 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title p {
            margin-top: 5px;
            font-size: 11px;
        }
        .badge-periode {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #bfdbfe;
        }

        /* =====================
           SECTION & TEXT
        ===================== */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .note {
            text-align: justify;
            font-size: 10.5px;
            color: #444;
            margin-bottom: 15px;
        }

        /* =====================
           TABEL
        ===================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            font-size: 10px;
            border: 1px solid #d1d5db;
        }
        table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            font-size: 10px;
            vertical-align: middle;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .fw-bold { font-weight: bold; }
        
        /* Pewarnaan Teks & Background */
        .text-primary { color: #2563eb; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .text-warning { color: #f59e0b; }
        .bg-danger-subtle { background-color: #fee2e2; }
        .bg-warning-subtle { background-color: #fef3c7; }
        
        /* Badges */
        .badge {
            padding: 2px 6px;
            font-size: 9px;
            border-radius: 3px;
            display: inline-block;
            border: 1px solid #ccc;
            background-color: #f9fafb;
            color: #374151;
        }
        .badge-danger { background-color: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .badge-success { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border-color: #fde68a; }
        .badge-primary { background-color: #dbeafe; color: #1e40af; border-color: #bfdbfe; }

        /* =====================
           TANDA TANGAN
        ===================== */
        .footer {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0;
            width: 50%;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

{{-- 
    FITUR: Kop Surat Resmi Instansi
    LOGIKA: Menampilkan struktur tabel identitas lembaga (Pemerintah Kota Padang, Dinas Pendidikan, SMAN 3 Padang) yang diapit oleh logo Kemdikbud dan logo sekolah secara kondisional berdasarkan keberadaan file fisik[cite: 8].
--}}
<!-- KOP SURAT -->
<div class="kop">
    <table class="kop-table">
        <tr>
            <td class="logo-kiri">
                @if(file_exists(public_path('img/logo-tutwuri.png')))
                    <img src="{{ public_path('img/logo-tutwuri.png') }}">
                @endif
            </td>
            <td class="identitas">
                <h4>PEMERINTAH KOTA PADANG</h4>
                <h4>DINAS PENDIDIKAN</h4>
                <h2>SMAN 3 PADANG</h2>
                <h3>PERPUSTAKAAN SEKOLAH</h3>
                <p>Jl. Gajah Mada, Gunung Pangilun, Kota Padang</p>
            </td>
            <td class="logo-kanan">
                @if(file_exists(public_path('img/logo-sman3.png')))
                    <img src="{{ public_path('img/logo-sman3.png') }}">
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- 
    FITUR: Judul Dokumen Laporan & Periode Akuntansi
    LOGIKA: Menyajikan tajuk utama laporan monitoring aktivitas perpustakaan beserta badge periode bulan dan tahun aktif yang diformat secara dinamis[cite: 8].
--}}
<!-- JUDUL LAPORAN -->
<div class="title">
    <h1>LAPORAN MONITORING AKTIVITAS PERPUSTAKAAN</h1>
    <p>Periode Akuntansi: <span class="badge-periode">{{ strtoupper($monthName ?? \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F')) }} {{ $year }}</span></p>
</div>

{{-- 
    FITUR: Logika Ekstraksi Data Buku Hilang & Rusak Otomatis
    LOGIKA: Melakukan iterasi pada koleksi `$borrowings`, memeriksa status hilang dan rusak pada tingkat pivot kolektif, status transaksi, maupun master status eksemplar, serta menyaring data agar unik berdasarkan kode eksemplar.
--}}
<!-- LOGIKA EKSTRAKSI DATA -->
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

{{-- 
    FITUR: Bagian I - Ringkasan Eksekutif
    LOGIKA: Merender paragraf naratif otomatis yang merangkum total peminjaman, jumlah pengembalian, total inventaris buku, dan total anggota aktif pada periode terkait[cite: 8].
--}}
<!-- I. RINGKASAN EKSEKUTIF -->
<div class="section-title">I. Ringkasan Eksekutif</div>
<p class="note">
    Pada periode pertanggungjawaban <b>{{ \Carbon\Carbon::create($year,$month,1)->translatedFormat('F Y') }}</b>, sistem otomasi perpustakaan SISPUS SMAN 3 Padang merekam sebanyak <b class="text-primary">{{ $totalBorrowings }}</b> berkas sirkulasi peminjaman koleksi buku fisik maupun digital. Dari total log sirkulasi tersebut, sebanyak <b class="text-success">{{ $totalReturned }}</b> berkas transaksi dinyatakan telah selesai dikembalikan dengan aman oleh siswa maupun guru. Saat ini, sistem mencakup total inventarisasi koleksi sebanyak <b>{{ $totalBooks }}</b> judul eksemplar terdaftar dengan basis interaksi dari <b>{{ $totalMembers }}</b> civitas akademika berstatus anggota aktif perpustakaan.
</p>

{{-- 
    FITUR: Bagian II - Indikator Kinerja Utama (KPI)
    LOGIKA: Menyajikan tabel indikator utama yang menghitung rasio persentase keterlambatan pengembalian secara otomatis, lengkap dengan lencana kuantitas pelanggaran aset hilang dan rusak.
--}}
<!-- II. INDIKATOR KINERJA UTAMA -->
<div class="section-title">II. Indikator Kinerja Utama</div>
<table>
    <tr>
        <th width="75%">Dimensi Indikator Utama</th>
        <th class="text-center">Kuantitas / Nilai</th>
    </tr>
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
            @php $rasio = $totalBorrowings > 0 ? round(($totalLate / $totalBorrowings) * 100, 2) : 0; @endphp
            <span class="badge {{ $rasio > 20 ? 'badge-danger' : 'badge-success' }} fw-bold">{{ $rasio }} %</span>
        </td>
    </tr>
</table>

{{-- 
    FITUR: Bagian III, IV, dan V - Tata Letak Grid Multi-Kolom PDF
    LOGIKA: Menggunakan tabel tanpa border (`border: none`) untuk menata konten modul Buku Terpopuler, Jenis Peminjaman, dan Segmentasi Alur Sirkulasi secara berdampingan (*side-by-side*)[cite: 8].
--}}
<!-- III & IV & V: TABEL GRID LAYOUT UNTUK PDF -->
<table style="border: none; margin-bottom: 0;">
    <tr>
        <td style="border: none; padding: 0; padding-right: 5px; width: 50%; vertical-align: top;">
            <div class="section-title" style="margin-top: 5px;">III. Buku Terpopuler</div>
            <table>
                <tr>
                    <th class="text-center" width="10%">No</th>
                    <th>Judul Koleksi Buku</th>
                    <th class="text-center" width="25%">Frekuensi</th>
                </tr>
                @forelse($popularBooks as $book)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $book->title }}</td>
                    <td class="text-center"><span class="badge badge-primary">{{ $book->borrowings_count }} Kali</span></td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center">Tidak ada data.</td></tr>
                @endforelse
            </table>
        </td>
        <td style="border: none; padding: 0; padding-left: 5px; width: 50%; vertical-align: top;">
            <div class="section-title" style="margin-top: 5px;">IV. Jenis Peminjaman</div>
            <table>
                <tr>
                    <th>Kategori Jenis</th>
                    <th class="text-center">Kuantitas</th>
                </tr>
                @foreach($popularCategories as $item)
                <tr>
                    <td>{{ ucfirst($item->loan_type) }}</td>
                    <td class="text-center fw-bold">{{ $item->total }}</td>
                </tr>
                @endforeach
            </table>

            <div class="section-title" style="margin-top: 15px;">V. Segmentasi Alur Sirkulasi</div>
            <table>
                <tr>
                    <th>Indikator Alur</th>
                    <th class="text-center">Jumlah</th>
                </tr>
                <tr>
                    <td>Sirkulasi Kolektif</td>
                    <td class="text-center fw-bold text-primary">{{ $borrowings->where('is_collective', true)->count() }}</td>
                </tr>
                <tr>
                    <td>Sirkulasi Individu</td>
                    <td class="text-center fw-bold text-success">{{ $borrowings->where('is_collective', false)->count() }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- 
    FITUR: Bagian VI - Audit Kerugian Aset (Buku Hilang)
    LOGIKA: Menampilkan peringatan kuantitas buku hilang beserta tabel rincian identitas peminjam, judul buku, kode eksemplar, tanggal peminjaman, dan status penggantian aset[cite: 8].
--}}
<!-- VI. AUDIT KERUGIAN ASET (BUKU HILANG) -->
<div class="section-title">VI. Audit Kerugian Aset (Buku Hilang)</div>
@if(count($lostBooksList) > 0)
    <div style="background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 10px; font-size: 10px; margin-bottom: 10px; border-radius: 4px;">
        <b>Peringatan Aset:</b> Terdapat <b>{{ count($lostBooksList) }} eksemplar</b> buku yang dilaporkan hilang pada periode laporan ini.
    </div>
@endif
<table>
    <tr>
        <th width="5%" class="text-center">No</th>
        <th width="20%">Nama Peminjam</th>
        <th width="35%">Judul Koleksi</th>
        <th width="15%" class="text-center">Eksemplar</th>
        <th width="12%" class="text-center">Dipinjam</th>
        <th width="13%" class="text-center">Status</th>
    </tr>
    @forelse($lostBooksList as $lost)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>
            <b>{{ $lost['name'] }}</b><br>
            <i style="font-size: 9px; color:#555;">{{ ucfirst($lost['role']) }}</i>
        </td>
        <td>{{ $lost['title'] }}</td>
        <td class="text-center"><span class="badge badge-danger">{{ $lost['code'] }}</span></td>
        <td class="text-center">{{ \Carbon\Carbon::parse($lost['date'])->format('d/m/y') }}</td>
        <td class="text-center">
            @if(in_array(strtolower($lost['trx_status']), ['dikembalikan', 'selesai']))
                <span class="text-success fw-bold">Diganti</span>
            @else
                <span class="text-danger fw-bold">Hilang</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center">Aman. Tidak ada kerugian buku pada periode ini.</td>
    </tr>
    @endforelse
</table>

{{-- 
    FITUR: Bagian VII - Audit Kerusakan Aset (Buku Rusak)
    LOGIKA: Menampilkan peringatan kuantitas buku rusak beserta tabel rincian identitas peminjam, judul buku, kode eksemplar, tanggal peminjaman, dan status penyelesaian perbaikan aset.
--}}
<!-- VII. AUDIT KERUSAKAN ASET (BUKU RUSAK) -->
<div class="section-title">VII. Audit Kerusakan Aset (Buku Rusak)</div>
@if(count($damagedBooksList) > 0)
    <div style="background-color: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 10px; font-size: 10px; margin-bottom: 10px; border-radius: 4px;">
        <b>Peringatan Aset:</b> Terdapat <b>{{ count($damagedBooksList) }} eksemplar</b> buku yang dilaporkan rusak pada periode laporan ini.
    </div>
@endif
<table>
    <tr>
        <th width="5%" class="text-center">No</th>
        <th width="20%">Nama Peminjam</th>
        <th width="35%">Judul Koleksi</th>
        <th width="15%" class="text-center">Eksemplar</th>
        <th width="12%" class="text-center">Dipinjam</th>
        <th width="13%" class="text-center">Status</th>
    </tr>
    @forelse($damagedBooksList as $damaged)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>
            <b>{{ $damaged['name'] }}</b><br>
            <i style="font-size: 9px; color:#555;">{{ ucfirst($damaged['role']) }}</i>
        </td>
        <td>{{ $damaged['title'] }}</td>
        <td class="text-center"><span class="badge badge-warning">{{ $damaged['code'] }}</span></td>
        <td class="text-center">{{ \Carbon\Carbon::parse($damaged['date'])->format('d/m/y') }}</td>
        <td class="text-center">
            @if(in_array(strtolower($damaged['trx_status']), ['dikembalikan', 'selesai']))
                <span class="text-success fw-bold">Selesai</span>
            @else
                <span class="text-warning fw-bold">Rusak</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center">Aman. Tidak ada kerusakan fisik buku pada periode ini.</td>
    </tr>
    @endforelse
</table>

{{-- 
    FITUR: Bagian VIII - Sampel Riwayat Log Transaksi Mutakhir
    LOGIKA: Menggunakan pemisah halaman (`page-break-before`) untuk mencetak tabel riwayat transaksi lengkap dengan detail buku, kode eksemplar (dengan pewarnaan merah untuk hilang dan oranye untuk rusak), jenis, status, dan evaluasi ketepatan waktu[cite: 8].
--}}
<!-- VIII. SAMPEL RIWAYAT LOG TRANSAKSI -->
<div class="section-title" style="page-break-before: always;">VIII. Sampel Riwayat Log Transaksi Mutakhir</div>
<table>
    <tr>
        <th class="text-center" width="4%">No</th>
        <th width="14%">Nama / Role</th>
        <th width="25%">Katalog Buku</th>
        <th width="12%">Eksemplar</th>
        <th class="text-center" width="9%">Jenis</th>
        <th class="text-center" width="10%">Status</th>
        <th class="text-center" width="12%">Keterangan</th>
        <th width="14%">Waktu (P/K)</th>
    </tr>
    @foreach($borrowings as $item)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>
            <b>{{ $item->user->name ?? '-' }}</b><br>
            <span class="badge {{ ($item->user->role ?? '') == 'guru' ? 'badge-warning' : 'badge-primary' }}">{{ ucfirst($item->user->role ?? '-') }}</span>
        </td>
        <td>
            @foreach($item->details as $detail)
                <div style="margin-bottom:4px;">&#8226; {{ $detail->book->title }} ({{ $detail->qty }}x)</div>
            @endforeach
        </td>
        <td>
            @if($item->is_collective)
                @foreach($item->borrowedExemplars as $borrowed)
                    @php
                        $bStatus = strtolower($borrowed->status ?? '');
                        $mStatus = strtolower(optional($borrowed->exemplar)->status ?? '');
                        $badgeStyle = '';
                        if($bStatus == 'lost' || $mStatus == 'lost' || $mStatus == 'hilang') $badgeStyle = 'badge-danger';
                        elseif($bStatus == 'damaged' || $mStatus == 'damaged' || $mStatus == 'rusak') $badgeStyle = 'badge-warning';
                    @endphp
                    <span class="badge {{ $badgeStyle }}" style="margin-bottom:2px;">{{ $borrowed->exemplar->code }}</span>
                @endforeach
            @else
                @foreach($item->details as $detail)
                    @if($detail->exemplar)
                        @php
                            $mStatus = strtolower($detail->exemplar->status ?? '');
                            $badgeStyle = '';
                            if($mStatus == 'lost' || $mStatus == 'hilang') $badgeStyle = 'badge-danger';
                            elseif($mStatus == 'damaged' || $mStatus == 'rusak') $badgeStyle = 'badge-warning';
                        @endphp
                        <span class="badge {{ $badgeStyle }}" style="margin-bottom:2px;">{{ $detail->exemplar->code }}</span>
                    @endif
                @endforeach
            @endif
        </td>
        <td class="text-center">{{ $item->is_collective ? 'Kolektif' : 'Individu' }}</td>
        <td class="text-center">
            @php
                $sts = strtolower($item->status);
                $badgeCls = '';
                if($sts == 'dikembalikan') $badgeCls = 'badge-success';
                elseif($sts == 'dipinjam') $badgeCls = 'badge-warning';
                elseif($sts == 'menunggu_pengembalian') $badgeCls = 'badge-primary';
                elseif($sts == 'hilang') $badgeCls = 'badge-danger';
                elseif($sts == 'rusak') $badgeCls = 'badge-warning';
            @endphp
            <span class="badge {{ $badgeCls }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
        </td>
        <td class="text-center">
            @php $retDate = \Carbon\Carbon::parse($item->return_date)->startOfDay(); @endphp
            @if($sts == 'dikembalikan')
                @if($item->returned_at && \Carbon\Carbon::parse($item->returned_at)->startOfDay()->gt($retDate))
                    <span class="text-danger fw-bold">Terlambat</span>
                @else
                    <span class="text-success fw-bold">Tepat Waktu</span>
                @endif
            @elseif(in_array($sts, ['dipinjam', 'menunggu_pengembalian']))
                @if(\Carbon\Carbon::now()->startOfDay()->gt($retDate))
                    <span class="text-danger fw-bold">Terlambat</span>
                @else
                    <span class="text-primary fw-bold">Berjalan</span>
                @endif
            @else
                -
            @endif
        </td>
        <td style="font-size: 9px; color: #555;">
            {{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/y') }}<br>
            s/d<br>
            {{ \Carbon\Carbon::parse($item->return_date)->format('d/m/y') }}
        </td>
    </tr>
    @endforeach
</table>

{{-- 
    FITUR: Bagian IX & X - Top 5 Anggota & Catatan Rekomendasi
    LOGIKA: Menampilkan tabel peringkat 5 anggota teraktif dan kotak ringkasan catatan evaluasi operasional (termasuk total buku rusak) dalam layout grid berdampingan[cite: 8].
--}}
<!-- IX & X: TABEL GRID -->
<table style="border: none; margin-bottom: 0;">
    <tr>
        <td style="border: none; padding: 0; padding-right: 5px; width: 50%; vertical-align: top;">
            <div class="section-title" style="margin-top: 5px;">IX. Top 5 Anggota Teraktif</div>
            <table>
                <tr>
                    <th class="text-center" width="10%">Rank</th>
                    <th>Nama Anggota</th>
                    <th>Role</th>
                    <th class="text-center" width="20%">Skor</th>
                </tr>
                @foreach($users->take(5) as $user)
                <tr>
                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td class="text-center fw-bold text-primary">{{ $user->points }} pts</td>
                </tr>
                @endforeach
            </table>
        </td>
        <td style="border: none; padding: 0; padding-left: 5px; width: 50%; vertical-align: top;">
            <div class="section-title" style="margin-top: 5px;">X. Catatan & Rekomendasi</div>
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; font-size: 10px; color: #4b5563;">
                <ul style="margin: 0; padding-left: 15px;">
                    <li style="margin-bottom: 4px;">Total sirkulasi mencakup <b>{{ $totalBorrowings }}</b> entitas berkas.</li>
                    <li style="margin-bottom: 4px;">Rasio kesuksesan pemulihan buku: <b>{{ $totalBorrowings > 0 ? round(($totalReturned / $totalBorrowings) * 100, 2) : 0 }}%</b>.</li>
                    <li style="margin-bottom: 4px;">Pelanggaran keterlambatan terverifikasi sebanyak <b>{{ $totalLate }}</b> kasus aktif.</li>
                    <li style="margin-bottom: 4px;">Kasus kerusakan eksemplar tercatat sebanyak <b>{{ count($damagedBooksList) }}</b> unit.</li>
                    <li>Sirkulasi kolektif tercatat sebanyak <b>{{ $borrowings->where('is_collective', true)->count() }}</b> transaksi.</li>
                </ul>
            </div>
        </td>
    </tr>
</table>

{{-- 
    FITUR: Tanda Tangan Eksekutif Kepala Perpustakaan
    LOGIKA: Menyediakan blok tanda tangan resmi di bagian bawah dokumen dengan tanggal cetak dinamis, jabatan, nama kepala pustakawan, serta nomor induk pegawai (NIP)[cite: 8].
--}}
<!-- TANDA TANGAN -->
<div class="footer">
    <table class="signature-table">
        <tr>
            <td>
                <!-- Jarak kosong untuk sisi kiri -->
            </td>
            <td>
                Padang, {{ now()->translatedFormat('d F Y') }}<br>
                Kepala Urusan Perpustakaan Sekolah
                <br><br><br><br><br>
                <span class="signature-name">{{ $kepalaPustakawan->name ?? 'Drs. Afrizal, M.Pd' }}</span><br>
                NIP. {{ $kepalaPustakawan->nis_nip ?? '_________________' }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>