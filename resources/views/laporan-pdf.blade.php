<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Perpustakaan</title>
<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
    margin:20px;
    color:#222;
    line-height:1.4;
}

/* HEADER */
.header-table{
    width:100%;
    border:none;
    margin-bottom:5px;
}
.header-table td{
    border:none;
    vertical-align:middle;
}
.logo{
    width:75px;
}
.text-center{
    text-align:center;
}
.line{
    border-top:2px solid #000;
    margin-top:4px;
    margin-bottom:12px;
}

/* SECTION */
.section-title{
    font-size:12px;
    font-weight:bold;
    background:#efefef;
    padding:5px 8px;
    margin-top:16px;
    margin-bottom:8px;
    border-left:4px solid #000;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    margin-top:6px;
}
th,
td{
    border:1px solid #000;
    padding:4px;
    font-size:9px;
    vertical-align:top;
    word-wrap:break-word;
}
th{
    background:#efefef;
    font-weight:bold;
    text-align:center;
}

/* Ringkasan */
.summary-table td:first-child{
    width:60%;
    font-weight:bold;
}

/* Paragraf */
.note{
    text-align:justify;
    font-size:10px;
    line-height:1.5;
}

/* Signature */
.signature{
    margin-top:35px;
    width:100%;
}
.signature td{
    border:none;
    text-align:center;
    vertical-align:top;
}
.small{
    font-size:9px;
}
</style>
</head>

<body>

{{-- FITUR: Tabel Header Kop Surat Laporan Resmi Perpustakaan SMAN 3 Padang[cite: 6] --}}
<table class="header-table">
<tr>
<td width="15%" class="text-center">
@if(file_exists(public_path('img/logo-tutwuri.png'))) 
    <img src="{{ public_path('img/logo-tutwuri.png') }}" class="logo">
@endif
</td>
<td width="70%" class="text-center">
<div style="font-size:14px;font-weight:bold;">PEMERINTAH KOTA PADANG</div>
<div style="font-size:13px;font-weight:bold;">DINAS PENDIDIKAN</div>
<div style="font-size:18px;font-weight:bold;">SMA NEGERI 3 PADANG</div>
<div style="font-size:13px;font-weight:bold;">PERPUSTAKAAN SMA NEGERI 3 PADANG</div>
<div class="small">Jl. Gajah Mada, Gunung Pangilun, Kota Padang</div>
</td>
<td width="15%" class="text-center">
@if(file_exists(public_path('img/logo-sman3.png'))) 
    <img src="{{ public_path('img/logo-sman3.png') }}" class="logo">
@endif
</td>
</tr>
</table>

<div class="line"></div>

{{-- FITUR: Judul Utama Laporan Transaksi Beserta Periode Bulan dan Tanggal Cetak[cite: 6] --}}
<div class="text-center">
<h3>LAPORAN TRANSAKSI PERPUSTAKAAN</h3>
<b>PERIODE {{ strtoupper($monthName) }} {{ $year }}</b>
<br><br>
Tanggal Cetak : {{ date('d-m-Y') }}
</div>

{{-- FITUR: Bagian A - Tabel Rangkuman Data Total Buku, Anggota, Peminjaman, dan Pengembalian[cite: 6] --}}
<div class="section-title">A. Ringkasan Data</div>
<table>
<tr>
<td>Total Buku</td>
<td>{{ $totalBooks }}</td>
</tr>
<tr>
<td>Total Anggota</td>
<td>{{ $totalMembers }}</td>
</tr>
<tr>
<td>Total Peminjaman</td>
<td>{{ $totalBorrowings }}</td>
</tr>
<tr>
<td>Total Pengembalian</td>
<td>{{ $totalReturned }}</td>
</tr>
</table>

{{-- FITUR: Bagian B - Tabel Statistik Daftar Buku Terpopuler Berdasarkan Frekuensi Pinjam[cite: 6] --}}
<div class="section-title">B. Buku Terpopuler</div>
<table>
<thead>
<tr>
<th>No</th>
<th>Judul Buku</th>
<th>Total Peminjaman</th>
</tr>
</thead>
<tbody>
@foreach($popularBooks as $book)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $book->title }}</td>
<td>{{ $book->borrowings_count }}</td>
</tr>
@endforeach
</tbody>
</table>

{{-- FITUR: Bagian C - Tabel Kategori Jenis Peminjaman Terfavorit[cite: 6] --}}
<div class="section-title">C. Kategori Peminjaman Terfavorit</div>
<table>
<thead>
<tr>
<th>No</th>
<th>Kategori</th>
<th>Total</th>
</tr>
</thead>
<tbody>
@foreach($popularCategories as $category)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ ucfirst($category->loan_type) }}</td>
<td>{{ $category->total }}</td>
</tr>
@endforeach
</tbody>
</table>

{{-- FITUR: Bagian D - Logika PHP & Statistik Ringkasan Peminjaman Kolektif (Transaksi & Eksemplar)[cite: 6] --}}
<div class="section-title">D. Statistik Peminjaman Kolektif</div>
@php
$totalTransaksiKolektif = 0;
$totalExemplarKolektif = 0;
foreach($borrowings as $b){
    if($b->is_collective){
        $totalTransaksiKolektif++;
        $totalExemplarKolektif += $b->borrowedExemplars->count();
    }
}
@endphp
<table>
<tr>
<td width="70%">Total Transaksi Kolektif</td>
<td>{{ $totalTransaksiKolektif }}</td>
</tr>
<tr>
<td>Total Exemplar Kolektif</td>
<td>{{ $totalExemplarKolektif }}</td>
</tr>
</table>

{{-- FITUR: Bagian E - Logika PHP & Tabel Statistik Eksemplar Buku Hilang[cite: 6] --}}
<div class="section-title">E. Statistik Buku Hilang</div>
@php
$totalHilang = 0;
foreach($borrowings as $b){
    $totalHilang += $b->borrowedExemplars->where('status','lost')->count();
}
@endphp
<table>
<tr>
<td width="70%">Total Exemplar Hilang</td>
<td>{{ $totalHilang }}</td>
</tr>
</table>

<table>
<thead>
<tr>
<th width="10%">No</th>
<th>Kode Exemplar Hilang</th>
</tr>
</thead>
<tbody>
@php $noHilang = 1; @endphp
@foreach($borrowings as $item)
    @foreach($item->borrowedExemplars->where('status','lost') as $lost)
        <tr>
            <td>{{ $noHilang++ }}</td>
            <td>{{ $lost->exemplar->code }}</td>
        </tr>
    @endforeach
@endforeach
@if($noHilang == 1)
<tr>
<td colspan="2" align="center">Tidak ada buku hilang</td>
</tr>
@endif
</tbody>
</table>

{{-- FITUR: Bagian F - Logika PHP & Tabel Statistik Eksemplar Buku Rusak (Baru) --}}
<div class="section-title">F. Statistik Buku Rusak</div>
@php
$totalRusak = 0;
foreach($borrowings as $b){
    $totalRusak += $b->borrowedExemplars->where('status','damaged')->count();
}
@endphp
<table>
<tr>
<td width="70%">Total Exemplar Rusak</td>
<td>{{ $totalRusak }}</td>
</tr>
</table>

<table>
<thead>
<tr>
<th width="10%">No</th>
<th>Kode Exemplar Rusak</th>
</tr>
</thead>
<tbody>
@php $noRusak = 1; @endphp
@foreach($borrowings as $item)
    @foreach($item->borrowedExemplars->where('status','damaged') as $damaged)
        <tr>
            <td>{{ $noRusak++ }}</td>
            <td>{{ $damaged->exemplar->code }}</td>
        </tr>
    @endforeach
@endforeach
@if($noRusak == 1)
<tr>
<td colspan="2" align="center">Tidak ada buku rusak</td>
</tr>
@endif
</tbody>
</table>

{{-- FITUR: Bagian G - Tabel Detail Transaksi Peminjaman Lengkap (Individu, Kolektif, Status, Keterlambatan, dan Periode)[cite: 6] --}}
<div class="section-title">G. Detail Transaksi Lengkap</div>
<table>
<thead>
<tr>
<th width="3%">No</th>
<th width="10%">Nama</th>
<th width="6%">Role</th>
<th width="18%">Buku</th>
<th width="8%">Jenis</th>
<th width="12%">Keterangan</th>
<th width="9%">Status</th>
<th width="10%">Ket</th>
<th width="14%">Periode</th>
</tr>
</thead>
<tbody>
@forelse($borrowings as $item)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $item->user->name }}</td>
<td>{{ ucfirst($item->user->role) }}</td>
<td>
@foreach($item->details as $detail)
<b>{{ $detail->book->title }}</b>
<br>
@if($item->is_collective)
{{ $detail->qty }} Eksemplar
<br>
@foreach($item->borrowedExemplars->where('borrowing_detail_id',$detail->id) as $borrowed)
    @if($borrowed->status == 'lost')
        <span style="color:red">{{ $borrowed->exemplar->code }}</span>
    @elseif($borrowed->status == 'damaged')
        <span style="color:orange; font-weight:bold;">{{ $borrowed->exemplar->code }}</span>
    @else
        {{ $borrowed->exemplar->code }}
    @endif
    &nbsp;
@endforeach
@else
@if($detail->exemplar)
    @if($detail->exemplar->status == 'lost')
        <span style="color:red">{{ $detail->exemplar->code }}</span>
    @elseif($detail->exemplar->status == 'damaged')
        <span style="color:orange; font-weight:bold;">{{ $detail->exemplar->code }}</span>
    @else
        {{ $detail->exemplar->code }}
    @endif
@endif
@endif
<br><br>
@endforeach
</td>

<td>
@if($item->is_collective)
Kolektif
@else
Individu
@endif
</td>

<td>
@if($item->is_collective)
Kelas : {{ $item->class_name }}
<br>
Total Exemplar : {{ $item->borrowedExemplars->count() }}
<br>
Hilang : {{ $item->borrowedExemplars->where('status','lost')->count() }}
<br>
Rusak : {{ $item->borrowedExemplars->where('status','damaged')->count() }}
@else
-
@endif
</td>

<td>{{ ucfirst($item->status) }}</td>

<td>
@if($item->status == 'dikembalikan')
    @if($item->returned_at && \Carbon\Carbon::parse($item->returned_at)->gt(\Carbon\Carbon::parse($item->return_date)))
        <span style="color:red;font-weight:bold;">Terlambat</span>
    @else
        <span style="color:green;font-weight:bold;">Tepat Waktu</span>
    @endif
@elseif($item->status == 'dipinjam')
    @if(\Carbon\Carbon::parse($item->return_date)->isPast())
        <span style="color:red;font-weight:bold;">Terlambat</span>
    @else
        Sedang Dipinjam
    @endif
@else
    -
@endif
</td>

<td>
{{ \Carbon\Carbon::parse($item->borrow_date)->format('d-m-Y') }}
<br>s/d<br>
{{ \Carbon\Carbon::parse($item->return_date)->format('d-m-Y') }}
</td>
</tr>
@empty
<tr>
<td colspan="9" align="center">Tidak ada data transaksi</td>
</tr>
@endforelse
</tbody>
</table>

{{-- FITUR: Tabel Blok Tanda Tangan Pengesahan (Kepala Pustakawan & Pustakawan)[cite: 6] --}}
<table class="signature">
<tr>
<td>
Mengetahui,
<br><br>Kepala Pustakawan<br><br>
<b>{{ $kepalaPustakawan->name ?? '-' }}</b>
<br>NIP : __________
</td>
<td>
Pustakawan
<br><br><br><br>
<b>{{ $pustakawan->name ?? '........................' }}</b>
</td>
</tr>
</table>

</body>
</html>