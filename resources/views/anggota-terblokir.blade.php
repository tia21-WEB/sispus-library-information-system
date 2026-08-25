{{-- Menggunakan layout admin sebagai template dasar --}}
@extends('layouts.admin')

{{-- Menentukan judul halaman untuk tab browser --}}
@section('title','Anggota Terblokir')

{{-- Memulai bagian konten utama yang akan dirender ke dalam layout --}}
@section('content')

<!-- Wrapper utama berupa card dengan bayangan (shadow) dan sudut membulat -->
<div class="card shadow-sm border-0 rounded-3 overflow-hidden">
    
    <!-- Bagian header card yang berisi ikon dan judul tabel -->
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
        <div class="bg-danger-subtle text-danger rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 42px; height: 42px;">
            <i class="bi bi-person-x-fill fs-5"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold text-dark">Daftar Anggota Terblokir</h5>
            <small class="text-muted">Manajemen anggota yang melanggar aturan atau menghilangkan buku</small>
        </div>
    </div>

    <!-- Bagian isi/body dari card -->
    <div class="card-body p-0">
        <!-- Container tabel agar responsif (bisa digeser mendatar) pada layar kecil -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <!-- Bagian judul baris/kolom tabel (Header Tabel) -->
                <thead class="table-light text-secondary text-uppercase" style="font-size: 0.8rem;">
                    <tr>
                        <th class="py-3 ps-4" width="5%">No</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Buku</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Jatuh Tempo</th>
                        <th class="py-3">Keterangan</th>
                        <th class="py-3 pe-4 text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <!-- Bagian isi data tabel -->
                <tbody class="border-top-0">
                    {{-- Melakukan perulangan untuk menampilkan daftar pengguna yang terblokir --}}
                    @forelse($blockedUsers as $item)
                    <tr>
                        <!-- Menampilkan Nomor Urut (Auto Increment dari loop) -->
                        <td class="ps-4 text-muted fw-medium py-3">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Menampilkan Nama Pengguna -->
                        <td class="py-3">
                            <span class="fw-semibold text-dark">{{ $item->user->name }}</span>
                        </td>

                        <!-- Menampilkan Peran/Role Pengguna (membuat huruf awalnya kapital) -->
                        <td class="py-3">
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">
                                {{ ucfirst($item->user->role) }}
                            </span>
                        </td>

                        <!-- Menampilkan Detail Buku -->
                     <td class="py-3 text-muted" style="font-size: 0.9rem;">
    @php
        // Ambil khusus buku yang statusnya benar-benar hilang (lost) pada transaksi ini
        $problemExemplars = $item->borrowedExemplars
    ->whereIn('status', ['lost', 'damaged']);
    @endphp

    @if($problemExemplars->count() > 0)
        {{-- Jika ada buku yang hilang, tampilkan HANYA buku yang hilang tersebut --}}
        @foreach($problemExemplars as $problemItem)
            <div class="d-flex align-items-start mb-1">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1 text-danger" style="font-size: 0.8rem;"></i>
                <div>
                    <span class="fw-bold text-danger">{{ $problemItem->exemplar->book->title ?? 'Buku' }}</span>
                    <br>
                    <small class="text-muted" style="font-size: 0.75rem;">Kode: {{ $problemItem->exemplar->code ?? '-' }}</small>
                </div>
            </div>
        @endforeach
    @else
        {{-- Jika murni keterlambatan (tidak ada buku hilang), tampilkan semua buku dalam transaksi --}}
        @foreach($item->details as $detail)
            <div class="d-flex align-items-start mb-1">
                <i class="bi bi-journal-text me-2 mt-1 text-primary" style="font-size: 0.8rem;"></i>
                <span class="fw-medium text-dark">{{ $detail->book->title }}</span>
            </div>
        @endforeach
    @endif
</td>

                        <!-- Menampilkan Badge Status Transaksi -->
                        <td class="py-3">
    {{-- Kondisi untuk menampilkan badge Buku Hilang jika ada status hilang/lost --}}
   @if($item->borrowedExemplars->where('status','lost')->count() > 0)

<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 me-1">
    <i class="bi bi-exclamation-triangle-fill me-1"></i>
    Buku Hilang
</span>

@endif


@if($item->borrowedExemplars->where('status','damaged')->count() > 0)

<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
    <i class="bi bi-tools me-1"></i>
    Buku Rusak
</span>

@endif


@if(
$item->borrowedExemplars->whereIn('status',['lost','damaged'])->count() == 0
)

<span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3 py-2">
    <i class="bi bi-clock-fill text-warning me-1"></i>
    Terlambat
</span>

@endif
</td>
                        
                        <!-- Menampilkan Tanggal Jatuh Tempo Pengembalian -->
                        <td class="py-3 text-secondary font-monospace" style="font-size: 0.9rem;">
                            {{ $item->return_date }}
                        </td>

                        <!-- Menampilkan Keterangan Durasi Keterlambatan atau Status Tunggu -->
                      <td class="py-3">
    {{-- Jika buku hilang, muncul pesan menunggu verifikasi dari admin/pustakawan --}}
    @if(
$item->borrowedExemplars->whereIn('status',['lost','damaged'])->count() > 0
)
        <span class="text-secondary small fw-medium">
            <i class="bi bi-hourglass-split me-1"></i> Menunggu verifikasi pustakawan
        </span>
    @else
        {{-- Menghitung kalkulasi jarak hari antara tanggal jatuh tempo dengan hari ini menggunakan Carbon --}}
        <span class="text-danger fw-bold">
            {{ \Carbon\Carbon::parse($item->return_date)->startOfDay()->diffInDays(now()->startOfDay()) }} Hari Terlambat
        </span>
    @endif
</td>
<!-- Menampilkan Tombol Aksi Penyelesaian -->
<td class="py-3 pe-4 text-center">
    {{-- Aksi 'Selesaikan' hanya muncul untuk kasus buku yang dinyatakan hilang --}}
   @if($item->borrowedExemplars->where('status','lost')->count() > 0)
   <form action="{{ route('web.buku.hilang.selesai', $item->id) }}"
      method="POST">

    @csrf
    @method('PUT')
            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center">
                <i class="bi bi-check-circle me-1"></i> Selesaikan
            </button>
        </form>
    @elseif($item->borrowedExemplars->where('status','damaged')->count() > 0)

<form action="{{ route('web.buku.rusak.selesai', $item->id) }}" method="POST">
    @csrf
    @method('PUT')

    <button type="submit"
        class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm d-inline-flex align-items-center">
        <i class="bi bi-tools me-1"></i>
        Selesaikan
    </button>

</form>
    @else
        {{-- Tampilan kosong (tanda strip) jika hanya terlambat dan tidak butuh diselesaikan dari halaman ini --}}
        <span class="text-muted opacity-50">-</span>
    @endif
</td>
                    </tr>

                    {{-- Blok ini akan ditampilkan jika variabel $blockedUsers kosong (tidak ada data) --}}
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-shield-check text-success opacity-50" style="font-size: 3.5rem;"></i>
                                <h6 class="mt-3 fw-bold text-dark">Status Bersih!</h6>
                                <p class="text-muted mb-0 small">Tidak ada anggota yang terblokir saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection