@extends('layouts.admin')

@section('title', 'Detail Anggota')

@section('content')

<style>
    /* --- STYLE DETAIL PREMIUM --- */
    .detail-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }
    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #212529;
    }
    .custom-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .table-custom th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        border-bottom: 2px solid #f8f9fa;
        padding: 16px;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 16px;
        color: #495057;
        border-bottom: 1px solid #f8f9fa;
    }
    .table-custom tbody tr:hover {
        background-color: #fcfcfc;
    }
</style>

<div class="container-fluid p-0 mb-5">

    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-auto">
            <a href="/admin/anggota" class="btn btn-light bg-white border shadow-sm rounded-3 px-3 py-2 fw-medium d-inline-flex align-items-center transition-all">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
        <div class="col-md">
            <h3 class="fw-bold mb-0" style="letter-spacing: -0.8px;">Informasi Detail Anggota</h3>
        </div>
    </div>

    @php
        $blocked = $user->borrowings()
            ->where('status', 'dipinjam')
            ->whereDate('return_date', '<', now()->subDay())
            ->exists();

        $lostBook = $user->borrowings()
            ->where('status', 'hilang')
            ->exists();

        $lateBook = $user->borrowings()
            ->whereIn('status', ['dipinjam', 'menunggu_pengembalian'])
            ->whereDate('return_date', '<', today())
            ->exists();
    @endphp

    @if($lostBook)
        <div class="alert alert-danger d-flex align-items-center rounded-4 shadow-sm border-0 mb-4 p-4">
            <i class="bi bi-exclamation-triangle-fill fs-1 me-4 opacity-75"></i>
            <div>
                <h5 class="fw-bold mb-1 text-danger">STATUS: DIBLOKIR</h5>
                <p class="mb-0">Anggota ini memiliki laporan buku hilang. Silakan menghubungi pustakawan untuk proses penggantian buku atau ganti rugi.</p>
            </div>
        </div>
    @elseif($lateBook)
        <div class="alert alert-warning d-flex align-items-center rounded-4 shadow-sm border-0 mb-4 p-4" style="background-color: #fff3cd;">
            <i class="bi bi-clock-history fs-1 me-4 text-warning opacity-75"></i>
            <div>
                <h5 class="fw-bold mb-1 text-warning-emphasis">STATUS: DIBLOKIR SEMENTARA</h5>
                <p class="mb-0 text-warning-emphasis">Anggota terlambat mengembalikan buku lebih dari 24 jam.</p>
            </div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center rounded-4 shadow-sm border-0 mb-4 p-4">
            <i class="bi bi-check-circle-fill fs-1 me-4 opacity-75"></i>
            <div>
                <h5 class="fw-bold mb-1 text-success">STATUS: AKTIF</h5>
                <p class="mb-0">Akun anggota dalam keadaan baik dan tidak memiliki pelanggaran peminjaman aktif.</p>
            </div>
        </div>
    @endif


<!-- STATUS AKUN -->
<div class="custom-card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <h5 class="fw-bold mb-1">
                <i class="bi bi-person-check-fill text-primary me-2"></i>
                Status Akun
            </h5>

            @if($user->is_active)

                <span class="badge bg-success rounded-pill px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Aktif
                </span>

                <p class="text-muted mt-2 mb-0">
                    Pengguna dapat login dan menggunakan aplikasi perpustakaan.
                </p>

            @else

                <span class="badge bg-secondary rounded-pill px-3 py-2">
                    <i class="bi bi-slash-circle-fill me-1"></i>
                    Nonaktif
                </span>

                <p class="text-muted mt-2 mb-0">
                    Pengguna tidak dapat login ke aplikasi sampai akun diaktifkan kembali.
                </p>

            @endif

        </div>

        <form action="{{ route('web.anggota.toggle', $user->id) }}"
              method="POST">

            @csrf

            @if($user->is_active)

                <button
                    class="btn btn-outline-danger rounded-pill px-4">

                    <i class="bi bi-person-x-fill me-2"></i>

                    Nonaktifkan Akun

                </button>

            @else

                <button
                    class="btn btn-outline-success rounded-pill px-4">

                    <i class="bi bi-person-check-fill me-2"></i>

                    Aktifkan Akun

                </button>

            @endif

        </form>

    </div>

</div>

<div class="row g-4"></div>
    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <div class="custom-card p-4 mb-4">
                <div class="text-center border-bottom pb-4 mb-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 90px; height: 90px; font-size: 36px; font-weight: bold;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-person-badge me-1"></i> {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="px-2">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Ringkasan Profil</h6>
                    
                    <div class="mb-3">
                        <span class="detail-label">NIS / NIP</span>
                        <div class="detail-value">{{ $user->nis_nip ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="detail-label">Email</span>
                        <div class="detail-value text-break">{{ $user->email }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="detail-label">Nomor HP</span>
                        <div class="detail-value">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="detail-label">Alamat Domisili</span>
                        <div class="detail-value">{{ $user->address ?? '-' }}</div>
                    </div>
                    
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <span class="detail-label text-center mb-1">Total Poin</span>
                                <div class="detail-value text-success fs-4">{{ $user->points ?? '0' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <span class="detail-label text-center mb-1">Badge</span>
                                <div class="detail-value text-warning fs-5 mt-1">
                                    <i class="bi bi-award-fill me-1"></i>{{ $user->badge ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="custom-card h-100 overflow-hidden">
                <div class="card-header bg-white border-bottom py-4 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                        <i class="bi bi-journal-text text-primary me-2 fs-4"></i> Riwayat Peminjaman
                    </h5>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $user->borrowings->count() }} Transaksi</span>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Buku</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->borrowings as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->book->title ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2 py-1">
                                                {{ ucfirst($item->loan_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted small">
                                                <i class="bi bi-calendar2-event me-1"></i> {{ $item->borrow_date }}
                                            </div>
                                        </td>
                                        <td>
                                            @if(strtolower($item->status) == 'dipinjam')
                                                <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bi bi-book-half me-1"></i> Dipinjam</span>
                                            @elseif(strtolower($item->status) == 'menunggu_pengembalian')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                            @elseif(strtolower($item->status) == 'hilang')
                                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Hilang</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> {{ ucfirst($item->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <i class="bi bi-inboxes text-secondary opacity-50 mb-3" style="font-size: 3rem;"></i>
                                                <h6 class="fw-semibold">Belum Ada Riwayat Peminjaman</h6>
                                                <p class="small mb-0">Anggota ini belum pernah meminjam buku apapun.</p>
                                            </div>
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

</div>

@endsection