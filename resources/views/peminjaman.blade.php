@extends('layouts.admin')

@section('title', 'Verifikasi Peminjaman')

@section('content')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .nav-pills .nav-link {
        color: var(--bs-secondary-color);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .nav-pills .nav-link.active {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
    .book-detail-box {
        transition: transform 0.2s;
    }
    .book-detail-box:hover {
        transform: translateY(-2px);
    }
</style>

<div class="row align-items-center g-3 mb-4">
    <div class="col-12 col-md-6">
        <h3 class="fw-bold text-body mb-1">Verifikasi Peminjaman</h3>
        <p class="text-body-secondary mb-0">Kelola dan tinjau pengajuan peminjaman buku civitas sekolah</p>
    </div>
    <div class="col-12 col-md-6 d-flex justify-content-md-end gap-2">
        <div class="position-relative" style="width: 280px;">
            <input type="text" id="searchPeminjaman" class="form-control bg-body border-secondary-subtle" placeholder="Cari data peminjaman...">
        </div>
        <button class="btn btn-primary px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Data</span>
        </button>
    </div>
</div>

<ul class="nav nav-pills gap-2 mb-4 p-1 bg-body-tertiary rounded-3 d-inline-flex" role="tablist">
    <li class="nav-item">
        <button class="nav-link {{ request('tab', 'siswa') == 'siswa' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#siswa">
            <i class="bi bi-people-fill"></i> Siswa
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('tab') == 'guru' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#guru">
            <i class="bi bi-person-badge-fill"></i> Guru
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ================================= --}}
    {{-- TAB SISWA --}}
    {{-- ================================= --}}
    <div class="tab-pane fade {{ request('tab', 'siswa') == 'siswa' ? 'show active' : '' }}" id="siswa">

        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-warning-subtle text-warning rounded-2"><i class="bi bi-hourglass-split"></i></span>
                Pengajuan Peminjaman Siswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tableRiwayatSiswa" class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Siswa</th>
                                <th>Buku / Eksemplar</th>
                                <th width="160">Periode</th>
                                <th width="120">Status</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaPending as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-3 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                        <div class="text-body-secondary small mb-2">Jumlah: <span class="fw-semibold text-body">{{ $detail->qty }}</span></div>
                                        @if($detail->exemplar)
                                        <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-2">
                                            {{ $detail->exemplar->code }}
                                        </span>
                                        @endif
                                    </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 small text-body fw-medium">
                                        <span class="text-success"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</span>
                                        <span class="text-danger"><i class="bi bi-calendar-x me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2">
                                        Menunggu
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('web.peminjaman.approve',$item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-success btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-lg"></i> Setuju
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $item->id }}">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-inbox text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan aktif dari siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PENGAJUAN PERPANJANGAN SISWA --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-info-subtle text-info rounded-2">
                    <i class="bi bi-arrow-repeat"></i>
                </span>
                Pengajuan Perpanjangan Siswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Siswa</th>
                                <th>Buku</th>
                                <th>Batas Lama</th>
                                <th>Batas Baru</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaExtension as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="text-danger fw-medium"><i class="bi bi-calendar-x me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        <i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->addDays(3)->format('d M Y') }}
                                    </span>
                                    <br>
                                    <small class="badge bg-primary-subtle text-primary mt-1">+3 Hari</small>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('web.extension.approve',$item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-success btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-lg"></i> Setuju
                                            </button>
                                        </form>
                                        <button
                                            class="btn btn-outline-danger btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectExtension{{ $item->id }}">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-arrow-repeat text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan perpanjangan siswa.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-primary-subtle text-primary rounded-2"><i class="bi bi-clock-history"></i></span>
                Riwayat Peminjaman Siswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Siswa</th>
                                <th>Buku / Eksemplar</th>
                                <th width="160">Periode</th>
                                <th width="150">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaRiwayat as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $siswaRiwayat->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-3 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                        @if($detail->exemplar)
                                        <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-2">
                                            {{ $detail->exemplar->code }}
                                        </span>
                                        @endif
                                    </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 small text-body fw-medium">
                                        <span><i class="bi bi-calendar-plus text-body-secondary me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</span>
                                        <span><i class="bi bi-calendar-minus text-body-secondary me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($item->status == 'dipinjam')
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-3 py-2">Dipinjam</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-pill px-3 py-2">Dikembalikan</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3 py-2">Ditolak</span>
                                    @elseif($item->status == 'menunggu_pengembalian')
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-2">Menunggu Kembali</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill px-3 py-2">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-folder-x text-body-secondary fs-2 mb-2 d-block"></i>
                                    Belum ada data riwayat peminjaman siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $siswaRiwayat->appends(['tab' => 'siswa'])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

    {{-- ================================= --}}
    {{-- TAB GURU --}}
    {{-- ================================= --}}
    <div class="tab-pane fade {{ request('tab') == 'guru' ? 'show active' : '' }}" id="guru">

        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-warning-subtle text-warning rounded-2"><i class="bi bi-hourglass-split"></i></span>
                Pengajuan Peminjaman Guru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Guru</th>
                                <th>Buku</th>
                                <th width="320">Eksemplar</th>
                                <th width="120">Jenis</th>
                                <th width="160">Periode</th>
                                <th width="120">Status</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guruPending as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-2 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                        <div class="small text-body-secondary">Jumlah: {{ $detail->qty }}</div>
                                    </div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($item->is_collective)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->borrowedExemplars as $borrowed)
                                        <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-2">
                                            {{ $borrowed->exemplar->code }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->details as $detail)
                                            @if($detail->exemplar)
                                            <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-2">
                                                {{ $detail->exemplar->code }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_collective)
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-pill px-3 py-1.5">Kolektif</span>
                                        <div class="small text-body-secondary fw-semibold mt-1 text-center">{{ $item->class_name }}</div>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-3 py-1.5">Individu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 small text-body fw-medium">
                                        <span class="text-success"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</span>
                                        <span class="text-danger"><i class="bi bi-calendar-x me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2">
                                        Menunggu
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('web.peminjaman.approve',$item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-success btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-lg"></i> Setuju
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModalGuru{{ $item->id }}">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-inbox text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan aktif dari guru
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PENGAJUAN PERPANJANGAN GURU --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-info-subtle text-info rounded-2">
                    <i class="bi bi-arrow-repeat"></i>
                </span>
                Pengajuan Perpanjangan Guru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Guru</th>
                                <th>Buku</th>
                                <th>Batas Lama</th>
                                <th>Batas Baru</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guruExtension as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="text-danger fw-medium"><i class="bi bi-calendar-x me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        <i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->addDays(3)->format('d M Y') }}
                                    </span>
                                    <br>
                                    <small class="badge bg-primary-subtle text-primary mt-1">+3 Hari</small>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('web.extension.approve',$item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-success btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-lg"></i> Setuju
                                            </button>
                                        </form>
                                        <button
                                            class="btn btn-outline-danger btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectExtensionGuru{{ $item->id }}">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-arrow-repeat text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan perpanjangan guru.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-primary-subtle text-primary rounded-2"><i class="bi bi-clock-history"></i></span>
                Riwayat Peminjaman Guru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Guru</th>
                                <th>Buku</th>
                                <th width="320">Eksemplar</th>
                                <th width="120">Jenis</th>
                                <th width="160">Periode</th>
                                <th width="140">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guruRiwayat as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $guruRiwayat->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-2 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📖 {{ $detail->book->title }}</div>
                                    </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($item->is_collective)
                                            @foreach($item->borrowedExemplars as $borrowed)
                                            <span class="badge {{ $borrowed->status == 'lost' ? 'bg-danger-subtle text-danger-emphasis border border-danger-subtle' : 'bg-dark-subtle text-dark-emphasis border border-dark-subtle' }} rounded-2">
                                                {{ $borrowed->exemplar->code }}
                                            </span>
                                            @endforeach
                                        @else
                                            @foreach($item->details as $detail)
                                                @if($detail->exemplar)
                                                <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-2">
                                                    {{ $detail->exemplar->code }}
                                                </span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item->is_collective)
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-pill px-3 py-1.5">Kolektif</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-3 py-1.5">Individu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 small text-body fw-medium">
                                        <span><i class="bi bi-calendar-plus text-body-secondary me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</span>
                                        <span><i class="bi bi-calendar-minus text-body-secondary me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill px-3 py-2">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-folder-x text-body-secondary fs-2 mb-2 d-block"></i>
                                    Belum ada data riwayat peminjaman guru
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $guruRiwayat->appends(['tab' => 'guru'])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- PISAHKAN SEMUA MODAL PENOLAKAN KELUAR DARI TABEL KE SINI --}}
{{-- ========================================================= --}}

{{-- Modal Penolakan Peminjaman Siswa --}}
@foreach($siswaPending as $item)
<div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('web.peminjaman.reject', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <label class="form-label fw-semibold">Alasan Penolakan</label>
                <textarea name="rejection_note" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Penolakan Peminjaman Guru --}}
@foreach($guruPending as $item)
<div class="modal fade" id="rejectModalGuru{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('web.peminjaman.reject', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Pengajuan Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <label class="form-label fw-semibold">Alasan Penolakan</label>
                <textarea name="rejection_note" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Penolakan Perpanjangan Siswa --}}
@foreach($siswaExtension as $item)
<div class="modal fade" id="rejectExtension{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('web.extension.reject', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Perpanjangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <label class="form-label fw-semibold">Alasan Penolakan</label>
                <textarea name="extension_note" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Penolakan Perpanjangan Guru --}}
@foreach($guruExtension as $item)
<div class="modal fade" id="rejectExtensionGuru{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('web.extension.reject', $item->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Perpanjangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <label class="form-label fw-semibold">Alasan Penolakan</label>
                <textarea name="extension_note" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Tambah Data --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('web.peminjaman.store') }}" method="POST" class="modal-content border-0 rounded-4 bg-body text-body shadow-lg">
            @csrf
            <div class="modal-header border-bottom border-secondary-subtle py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-body mb-1">Tambah Pengajuan</h5>
                    <p class="text-body-secondary small mb-0">Simulasi pengajuan peminjaman sirkulasi perpustakaan</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-body">Pilih Pengguna</label>
                        <select name="user_id" class="form-select bg-body border-secondary-subtle py-2.5 rounded-3" required>
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-body">Kategori Peminjaman</label>
                        <select name="loan_type" class="form-select bg-body border-secondary-subtle py-2.5 rounded-3" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="semester">Semester</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-body">Pilih Buku</label>
                        <select name="book_id[]" class="form-select bg-body border-secondary-subtle py-2.5 rounded-3" multiple required>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }} (Stok: {{ $book->stock }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Tahan tombol <kbd>Ctrl</kbd> atau <kbd>Cmd</kbd> untuk memilih lebih dari satu buku.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-body">Tanggal Pinjam</label>
                        <input type="date" name="borrow_date" value="{{ now()->format('Y-m-d') }}" class="form-control bg-body border-secondary-subtle py-2.5 rounded-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-body">Tanggal Kembali</label>
                        <input type="date" name="return_date" class="form-control bg-body border-secondary-subtle py-2.5 rounded-3">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary-subtle py-3 px-4 bg-body-tertiary rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4 rounded-3 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> Simpan Permohonan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('searchPeminjaman').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            if (!row.classList.contains('empty-row')) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            }
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeTab = localStorage.getItem('activePeminjamanTab');
    if (activeTab) {
        let trigger = document.querySelector('[data-bs-target="' + activeTab + '"]');
        if (trigger) {
            new bootstrap.Tab(trigger).show();
        }
    }
    document.querySelectorAll('.nav-pills button').forEach(function(tab){
        tab.addEventListener('shown.bs.tab', function(e){
            localStorage.setItem('activePeminjamanTab', e.target.getAttribute('data-bs-target'));
        });
    });
});
</script>
@endsection