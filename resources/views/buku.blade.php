@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')

{{-- FITUR: Header Halaman Data Buku --}}
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bolder mb-1 text-dark">Data Buku</h3>
            <p class="text-secondary mb-0">Kelola koleksi, kategori, dan pengajuan buku perpustakaan</p>
        </div>
    </div>

{{-- FITUR: Tab Navigasi Antar Halaman (Koleksi Buku, Kategori, Pengajuan) --}}
    <div class="bg-white p-2 rounded-pill shadow-sm d-inline-block mb-4">
        <ul class="nav nav-pills gap-1" id="bookTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#koleksi">
                    <i class="bi bi-book me-2"></i>Koleksi Buku
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#kategori">
                    <i class="bi bi-tags me-2"></i>Kategori
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#approval">
                    <i class="bi bi-hourglass-split me-2"></i>Pengajuan Buku
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">

{{-- FITUR: TAB 1 - Konten Koleksi Buku Utama --}}
        <div class="tab-pane fade show active" id="koleksi">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
                <div class="position-relative" style="width: 350px;">
{{-- FITUR: Form Pencarian Data Buku (Live Search) --}}
                    <form action="{{ route('web.buku') }}" method="GET">
                        <div class="position-relative" style="width:350px;">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input
                                type="search"
                                id="searchBuku"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control form-control-lg bg-white border-0 shadow-sm rounded-pill ps-5"
                                placeholder="Cari judul, penulis, penerbit...">
                        </div>
                    </form>
                </div>
{{-- FITUR: Tombol Trigger untuk Buka Modal Tambah Buku --}}
                <button class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Buku
                </button>
            </div>

{{-- FITUR: Tabel Penampil Daftar Koleksi Buku --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableBuku">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th width="80" class="ps-4 py-3 border-0">Cover</th>
                                    <th class="py-3 border-0">Informasi Buku</th>
                                    <th class="py-3 border-0">Kategori</th>
                                    <th class="py-3 border-0">Stok</th>
                                    <th class="py-3 border-0">Exemplar</th>
                                    <th width="160" class="py-3 border-0 text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $buku)
                                <tr>
{{-- FITUR: UI Logika Penampil Gambar Cover Buku atau Placeholder Jika Kosong --}}
                                    <td class="ps-4 py-3">
                                        @if($buku->cover)
                                            <img src="{{ asset('storage/' . $buku->cover) }}" class="rounded-3 shadow-sm" width="60" height="85" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex justify-content-center align-items-center rounded-3 shadow-sm" style="width:60px;height:85px;">
                                                <i class="bi bi-journal-text text-secondary fs-3"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
{{-- FITUR: Informasi Detail Penulis, Penerbit, dan Badge Indikator Ebook --}}
                                    <td class="py-3">
                                        <h6 class="fw-bold text-dark mb-1">{{ $buku->title }}</h6>
                                        <div class="text-muted small mb-1">
                                            <i class="bi bi-person me-1"></i>{{ $buku->author }}
                                        </div>
                                        <div class="text-muted small mb-2">
                                            <i class="bi bi-building me-1"></i>{{ $buku->publisher }} &bull; {{ $buku->publication_year }}
                                        </div>
                                        <div>
                                            @if(!empty($buku->ebook_file))
                                                <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1">
                                                    🟢 Ebook Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold px-2 py-1">
                                                    ⚪ Tidak Ada Ebook
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                                            {{ $buku->category->name ?? '-' }}
                                        </span>
                                    </td>

{{-- FITUR: Logika Badge Indikator Ketersediaan Stok --}}
                                    <td class="py-3">
                                        @if($buku->stock > 5)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">
                                                Tersedia : {{ $buku->stock }}
                                            </span>
                                        @elseif($buku->stock > 0)
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">
                                                Sisa : {{ $buku->stock }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-semibold">
                                                Habis
                                            </span>
                                        @endif
                                    </td>

{{-- FITUR: Tombol Trigger Modal Lihat Daftar Exemplar Fisik Buku --}}
                                    <td class="py-3">
                                        <button class="btn btn-sm btn-light text-info rounded-pill px-3 fw-semibold border" data-bs-toggle="modal" data-bs-target="#exemplar{{ $buku->id }}">
                                            <i class="bi bi-eye me-1"></i> Lihat
                                        </button>
                                    </td>

{{-- FITUR: Tombol Aksi (Lihat PDF 👁, Edit ✏️, Hapus 🗑️) --}}
                                    <td class="py-3 text-end pe-4">
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            {{-- Tombol Lihat PDF hanya muncul jika ebook_file ada --}}
                                            @if(!empty($buku->ebook_file))
                                                <a href="{{ asset('storage/' . $buku->ebook_file) }}" target="_blank" class="btn btn-sm btn-light text-info shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="Lihat Ebook">
                                                    👁
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-light text-warning shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" data-bs-toggle="modal" data-bs-target="#editBuku{{ $buku->id }}" title="Edit">
                                                ✏️
                                            </button>
                                            <form action="{{ route('web.buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light text-danger shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="Hapus">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
{{-- FITUR: UI Tampilan Jika Tabel Buku Kosong --}}
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="p-4">
                                            <i class="bi bi-inboxes fs-1 text-muted mb-3 d-block"></i>
                                            <h5 class="fw-bold text-dark">Belum Ada Buku</h5>
                                            <p class="text-muted">Mulai tambahkan koleksi buku pertama Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
          
                </div>
               
{{-- FITUR: Navigasi Halaman / Pagination Buku --}}
       {{ $books->links('pagination::bootstrap-5') }}
            </div>
              
        </div>
  
{{-- FITUR: TAB 2 - Konten Manajemen Kategori Buku --}}
        <div class="tab-pane fade" id="kategori">
            <div class="d-flex justify-content-end mb-3">
{{-- FITUR: Tombol Tambah Kategori Baru --}}
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKategori">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
                </button>
            </div>

{{-- FITUR: Tabel Penampil Data Kategori --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th width="80" class="ps-4 py-3 border-0">No</th>
                                <th class="py-3 border-0">Nama Kategori</th>
                                <th width="120" class="py-3 border-0 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $item)
                            <tr>
                                <td class="ps-4 py-3 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                                        {{ $item->name }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4">
{{-- FITUR: Form Hapus Data Kategori --}}
                                    <form action="{{ route('web.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Kategori dan semua buku terkait akan dihapus. Lanjutkan?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger shadow-sm rounded-circle" style="width: 35px; height: 35px;">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">Belum ada kategori terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

{{-- FITUR: TAB 3 - Konten Riwayat Approval / Pengajuan Buku --}}
        <div class="tab-pane fade" id="approval">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                   <div class="p-4 border-bottom border-light">

    <h5 class="fw-bold text-dark mb-3">
        Riwayat Pengajuan Buku
    </h5>

{{-- FITUR: Form Pencarian dan Filter Tingkat Lanjut untuk Status Approval --}}
    <form method="GET">
<input type="hidden"
       name="tab"
       id="approvalTab"
       value="{{ request('tab', 'approval') }}">
        <div class="row g-3">

            <div class="col-md-5">

                <input
                    type="text"
                    class="form-control"
                    name="approval_search"
                    value="{{ request('approval_search') }}"
                    placeholder="Cari judul buku...">

            </div>

            <div class="col-md-3">
{{-- FITUR: Dropdown Filter Berdasarkan Status (Pending/Approved/Rejected) --}}
                <select
                    name="approval_status"
                    class="form-select">

                    <option value="">Semua Status</option>

                    <option value="pending"
                        {{ request('approval_status')=='pending' ? 'selected' : '' }}>
                        Menunggu
                    </option>

                    <option value="approved"
                        {{ request('approval_status')=='approved' ? 'selected' : '' }}>
                        Disetujui
                    </option>

                    <option value="rejected"
                        {{ request('approval_status')=='rejected' ? 'selected' : '' }}>
                        Ditolak
                    </option>

                </select>

            </div>

            <div class="col-md-2">
{{-- FITUR: Dropdown Filter Berdasarkan Tipe Aksi (Create/Update/Delete) --}}
                <select
                    name="approval_action"
                    class="form-select">

                    <option value="">Semua Aksi</option>

                    <option value="create"
                        {{ request('approval_action')=='create' ? 'selected' : '' }}>
                        Create
                    </option>

                    <option value="update"
                        {{ request('approval_action')=='update' ? 'selected' : '' }}>
                        Update
                    </option>

                    <option value="delete"
                        {{ request('approval_action')=='delete' ? 'selected' : '' }}>
                        Delete
                    </option>

                </select>

            </div>

            <div class="col-md-2">

                <button class="btn btn-primary w-100">

                    <i class="bi bi-search"></i>

                    Cari

                </button>

            </div>

        </div>

    </form>

</div>
{{-- FITUR: Tabel Penampil Data Riwayat Approval / Pengajuan --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th class="ps-4 py-3 border-0">No</th>
                                    <th class="py-3 border-0">Judul Buku</th>
                                    <th class="py-3 border-0">Aksi</th>
                                    <th class="py-3 border-0">Status</th>
                                    <th class="py-3 border-0">Alasan</th>
                                    <th class="py-3 border-0 pe-4">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvals as $item)
                                <tr>
                                    <td class="ps-4 py-3 text-secondary fw-semibold">{{ $loop->iteration }}</td>
                                    <td class="py-3 fw-bold text-dark">{{ $item->book_data['title'] ?? '-' }}</td>
                                    <td class="py-3 text-muted">{{ ucfirst($item->action) }}</td>
{{-- FITUR: Badge Label Indikator Status Persetujuan --}}
                                    <td class="py-3">
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">Menunggu</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-semibold">Ditolak</span>
                                        @endif
                                    </td>
                                    
{{-- FITUR: Kolom Alasan Penolakan dengan Format Alert Profesional --}}
                                    <td class="py-3">
                                        @if($item->status == 'rejected')
                                            <div class="alert alert-danger py-2 px-3 mb-0 small" style="max-width: 280px;">
                                                <strong>Alasan:</strong><br>
                                                {{ $item->rejection_reason }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="py-3 text-muted pe-4">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada pengajuan buku.</td>
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

{{-- FITUR: Modal Pop-Up Form Pengisian Data Buku Baru --}}
<div class="modal fade" id="modalTambahBuku" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('web.buku.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <div>
                    <h4 class="fw-bold mb-1">Tambah Buku Baru</h4>
                    <p class="text-muted small mb-0">Lengkapi informasi detail untuk koleksi buku.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Judul Buku</label>
                        <input type="text" name="title" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Penulis</label>
                        <input type="text" name="author" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Penerbit</label>
                        <input type="text" name="publisher" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Tahun Terbit</label>
                        <input type="number" name="publication_year" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Kategori</label>
                        <select name="category_id" class="form-select form-select-lg bg-light border-0" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Stok Awal</label>
                        <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary small">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" class="form-control bg-light border-0"></textarea>
                    </div>
{{-- FITUR: Input Upload Gambar Cover Buku --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary small">Cover Buku</label>
                        <input type="file" name="cover" class="form-control form-control-lg bg-light border-0">
                    </div>

{{-- FITUR: Checkbox Memiliki Ebook --}}
                    <div class="col-12 pt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hasEbookAdd" name="has_ebook" value="1" onchange="toggleEbookInput('Add')">
                            <label class="form-check-label fw-semibold text-dark" for="hasEbookAdd">
                                Memiliki Ebook
                            </label>
                        </div>
                    </div>

{{-- FITUR: Upload PDF (Muncul Jika Dicentang) --}}
                    <div class="col-12 d-none" id="ebookInputContainerAdd">
                        <label class="form-label fw-semibold text-secondary small">Upload PDF</label>
                        <input type="file" name="ebook_file" accept=".pdf" class="form-control form-control-lg bg-light border-0">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- FITUR: Modal Pop-Up Form Input Penambahan Kategori Baru --}}
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('web.kategori.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-bottom-0 px-4 pt-4">
                <h5 class="fw-bold mb-0">Tambah Kategori</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <label class="form-label fw-semibold text-secondary small">Nama Kategori</label>
                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Cth: Fiksi, Sains..." required>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

{{-- FITUR: Looping Untuk Menghasilkan Modal Edit dan Exemplar Untuk Setiap Buku Secara Dinamis --}}
@foreach($books as $buku)
{{-- FITUR: Modal Pop-Up Edit dan Update Data Buku --}}
<div class="modal fade" id="editBuku{{ $buku->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('web.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h4 class="fw-bold mb-0">Edit Data Buku</h4>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Judul Buku</label>
                        <input type="text" name="title" class="form-control form-control-lg bg-light border-0" value="{{ $buku->title }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Penulis</label>
                        <input type="text" name="author" class="form-control form-control-lg bg-light border-0" value="{{ $buku->author }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Penerbit</label>
                        <input type="text" name="publisher" class="form-control form-control-lg bg-light border-0" value="{{ $buku->publisher }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Tahun Terbit</label>
                        <input type="number" name="publication_year" class="form-control form-control-lg bg-light border-0" value="{{ $buku->publication_year }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Kategori</label>
                        <select name="category_id" class="form-select form-select-lg bg-light border-0" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $buku->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Stok</label>
                        <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" value="{{ $buku->stock }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary small">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" class="form-control bg-light border-0">{{ $buku->description }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary small">Ganti Cover (Opsional)</label>
                        <input type="file" name="cover" class="form-control form-control-lg bg-light border-0">
                    </div>

{{-- FITUR: Preview Ebook Saat Ini di Modal Edit --}}
                    @if(!empty($buku->ebook_file))
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary small">PDF Saat Ini</label>
                        <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                                <span class="small fw-semibold text-dark">{{ basename($buku->ebook_file) }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $buku->ebook_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> Lihat PDF
                            </a>
                        </div>
                    </div>
                    @endif

{{-- FITUR: Checkbox Memiliki Ebook (Edit) --}}
                    <div class="col-12 pt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hasEbookEdit{{ $buku->id }}" name="has_ebook" value="1" {{ !empty($buku->ebook_file) ? 'checked' : '' }} onchange="toggleEbookInput('Edit{{ $buku->id }}')">
                            <label class="form-check-label fw-semibold text-dark" for="hasEbookEdit{{ $buku->id }}">
                                Memiliki Ebook
                            </label>
                        </div>
                    </div>

{{-- FITUR: Upload PDF (Edit) --}}
                    <div class="col-12 {{ empty($buku->ebook_file) ? 'd-none' : '' }}" id="ebookInputContainerEdit{{ $buku->id }}">
                        <label class="form-label fw-semibold text-secondary small">Upload PDF Baru (Opsional)</label>
                        <input type="file" name="ebook_file" accept=".pdf" class="form-control form-control-lg bg-light border-0">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning rounded-pill px-4 text-white shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- FITUR: Modal Pop-Up Tabel Status Daftar Fisik (Exemplar) per Buku --}}
<div class="modal fade" id="exemplar{{ $buku->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <div>
                    <h5 class="fw-bold mb-1">Detail Exemplar</h5>
                    <p class="text-muted small mb-0">{{ $buku->title }}</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-borderless table-striped align-middle mb-0">
                        <thead class="bg-light text-secondary small">
                            <tr>
                                <th class="ps-3 py-3">Kode Buku</th>
                                <th class="pe-3 py-3 text-end">Status Fisik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buku->exemplars as $exemplar)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">{{ $exemplar->code }}</td>
                                <td class="pe-3 text-end">
                                   @if($exemplar->status == 'available')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Tersedia</span>
                                    @elseif($exemplar->status == 'borrowed')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Dipinjam</span>
                                    @elseif($exemplar->status == 'lost')
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Hilang</span>
                                    @elseif($exemplar->status == 'damaged')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Rusak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted small">Belum ada exemplar untuk buku ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- FITUR: Javascript Action & Pencarian (Search Debounce dan Auto Submit) + Toggle Ebook --}}
<script>
function toggleEbookInput(suffix) {
    const checkbox = document.getElementById('hasEbook' + suffix);
    const container = document.getElementById('ebookInputContainer' + suffix);
    if (checkbox && container) {
        if (checkbox.checked) {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }
}

const search = document.getElementById('searchBuku');
const approvalSearch = document.querySelector('[name="approval_search"]');

if (approvalSearch) {
    let timer;
    approvalSearch.addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
}

document.querySelectorAll('[name="approval_status"], [name="approval_action"]').forEach(item => {
    item.addEventListener('change', function () {
        this.form.submit();
    });
});

let timer;
if (search) {
    search.addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
}
</script>

@endsection