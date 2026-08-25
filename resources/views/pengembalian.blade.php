@extends('layouts.admin')

@section('title', 'Verifikasi Pengembalian')

@section('content')
<style>
    /* Desain modern & kustomisasi mikro-interaksi */
    .card { transition: all 0.3s ease; }
    .table tbody tr { transition: background-color 0.2s ease; }
    .nav-pills .nav-link { color: var(--bs-secondary-color); font-weight: 500; transition: all 0.2s ease; }
    .nav-pills .nav-link.active { box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15); }
    .book-detail-box { transition: transform 0.2s; }
    .book-detail-box:hover { transform: translateY(-2px); }
    /* Transisi warna baris tabel saat diklik Hilang/Rusak */
    .exemplar-row { transition: background-color 0.25s; }
</style>

<!-- HEADER -->
<div class="row align-items-center g-3 mb-4">
    <div class="col-12 col-md-6">
        <h3 class="fw-bold text-body mb-1">Verifikasi Pengembalian</h3>
        <p class="text-body-secondary mb-0">Kelola dan tinjau pengajuan pengembalian buku civitas sekolah</p>
    </div>
    <div class="col-12 col-md-6 d-flex justify-content-md-end">
        <div class="position-relative" style="width: 320px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-tertiary"></i>
            <input type="text" id="searchPengembalian" class="form-control bg-body border-secondary-subtle ps-5 py-2" placeholder="Cari siswa, guru, buku...">
        </div>
    </div>
</div>

<!-- TABS NAVIGATION -->
<ul class="nav nav-pills gap-2 mb-4 p-1 bg-body-tertiary rounded-3 d-inline-flex" role="tablist">
    <li class="nav-item">
        <button class="nav-link active rounded-2 px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#siswa">
            <i class="bi bi-people-fill"></i> Siswa
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link rounded-2 px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#guru">
            <i class="bi bi-person-badge-fill"></i> Guru
        </button>
    </li>
</ul>

<div class="tab-content">
    {{-- ================================= --}}
    {{-- TAB SISWA --}}
    {{-- ================================= --}}
    <div class="tab-pane fade show active" id="siswa">
        <div class="card shadow-sm border-0 rounded-4 mb-4 bg-body text-body">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-info-subtle text-info rounded-2"><i class="bi bi-arrow-return-left"></i></span>
                Pengajuan Pengembalian Siswa
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablePengembalianSiswa" class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="220">Siswa</th>
                                <th>Buku / Eksemplar</th>
                                <th width="160">Periode</th>
                                <th width="180">Status</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaPengembalian as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-3 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📚 {{ $detail->book->title }}</div>
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
                                        <span><i class="bi bi-calendar-check text-success me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</span>
                                        <span><i class="bi bi-calendar-x text-danger me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Kembali
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalIndividu{{ $item->id }}">
                                        Kelola
                                    </button>
                                    <form action="{{ route('web.pengembalian.approve',$item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm w-100">
                                            Verifikasi
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="6" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-inbox text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan pengembalian siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================= --}}
    {{-- TAB GURU --}}
    {{-- ================================= --}}
    <div class="tab-pane fade" id="guru">
        <div class="card shadow-sm border-0 rounded-4 bg-body text-body mb-4">
            <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3 fw-bold d-flex align-items-center gap-2">
                <span class="p-1.5 bg-info-subtle text-info rounded-2"><i class="bi bi-arrow-return-left"></i></span>
                Pengajuan Pengembalian Guru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablePengembalianGuru" class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small fw-semibold uppercase border-bottom">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th width="200">Guru</th>
                                <th>Buku</th>
                                <th width="200">Eksemplar</th>
                                <th width="120">Jenis</th>
                                <th width="140">Periode</th>
                                <th width="160">Status</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guruPengembalian as $index => $item)
                            <tr>
                                <td class="text-center fw-medium text-body-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-body">{{ $item->user->name }}</div>
                                    <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle mt-1">{{ $item->user->nis_nip }}</span>
                                </td>
                                <td>
                                    @foreach($item->details as $detail)
                                    <div class="book-detail-box border border-secondary-subtle rounded-3 p-2 mb-2 bg-body-tertiary shadow-sm">
                                        <div class="fw-bold text-primary mb-1">📚 {{ $detail->book->title }}</div>
                                        <div class="small text-body-secondary">Jumlah: {{ $detail->qty }}</div>
                                    </div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($item->is_collective)
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle px-2 py-1 rounded-2">
                                            {{ $item->borrowedExemplars->count() }} Eksemplar
                                        </span>
                                        <div class="small text-body-tertiary mt-1"><i class="bi bi-info-circle me-1"></i>Kelola untuk detail</div>
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
                                        <span><i class="bi bi-calendar-check text-success me-1"></i>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M') }}</span>
                                        <span><i class="bi bi-calendar-x text-danger me-1"></i>{{ \Carbon\Carbon::parse($item->return_date)->format('d M') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-2">
                                        Menunggu Kembali
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        @if($item->is_collective)
                                            <button class="btn btn-outline-primary btn-sm rounded-3 w-100 d-inline-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#modalExemplar{{ $item->id }}">
                                                <i class="bi bi-sliders"></i> Kelola
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary btn-sm rounded-3 w-100 d-inline-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#modalIndividu{{ $item->id }}">
                                                <i class="bi bi-sliders"></i> Kelola
                                            </button>
                                        @endif
                                        <form action="{{ route('web.pengembalian.approve', $item->id) }}" method="POST" class="w-100">
                                            @csrf @method('PUT')
                                            <button class="btn btn-success btn-sm rounded-3 w-100 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm">
                                                <i class="bi bi-check2-circle"></i> Verifikasi
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="8" class="text-center py-5 text-body-secondary">
                                    <i class="bi bi-inbox text-body-secondary fs-2 mb-2 d-block"></i>
                                    Tidak ada pengajuan pengembalian guru
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


{{-- ================================= --}}
{{-- MODAL AREA PENGELOLAAN EKSEMPLAR  --}}
{{-- ================================= --}}

{{-- 1. MODAL INDIVIDU (TABEL) --}}
@foreach(collect($siswaPengembalian)->merge($guruPengembalian) as $item)
@if(!$item->is_collective)
<div class="modal fade" id="modalIndividu{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 bg-body text-body shadow-lg">
            <div class="modal-header border-bottom border-secondary-subtle py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-body mb-1">
                        Kelola Pengembalian Individu
                    </h5>
                    <p class="text-body-secondary small mb-0">
                        Atur status buku jika terjadi kerusakan atau kehilangan.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form data-route-lost="{{ route('web.exemplar.bulkLostIndividu', $item->id) }}" 
                  data-route-damaged="{{ route('web.exemplar.bulkDamagedIndividu', $item->id) ?? '#' }}" 
                  onsubmit="submitPengembalian(event, this, 'individu')">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-3 d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            Pilih status jika ada buku yang <b>Hilang</b> atau <b>Rusak</b>. 
                            Sisanya akan otomatis dianggap dikembalikan <b>Normal</b> saat diverifikasi.
                        </div>
                    </div>

                    {{-- FITUR: UI Tabel Untuk Modal Individu --}}
                    <div class="table-responsive border border-secondary-subtle rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small fw-semibold">
                                <tr>
                                    <th class="py-3 px-3">Judul Buku</th>
                                    <th class="py-3 text-center">Eksemplar</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center" style="width: 170px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->details as $detail)
                                <tr class="exemplar-row" data-item-id="{{ $detail->id }}" data-status="normal">
                                    <td class="px-3">
                                        <div class="fw-bold text-primary">{{ $detail->book->title }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace px-2 py-1">
                                            {{ $detail->exemplar->code }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($detail->exemplar->status == 'lost' || $detail->exemplar->status == 'damaged')
                                            <span class="badge {{ $detail->exemplar->status == 'lost' ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-1.5 rounded-pill">
                                                Sudah {{ ucfirst($detail->exemplar->status) }}
                                            </span>
                                        @else
                                            {{-- Badge Status Interaktif --}}
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1.5 rounded-pill status-badge">
                                                🟢 Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        @if($detail->exemplar->status != 'lost' && $detail->exemplar->status != 'damaged')
                                            {{-- Tombol Aksi --}}
                                            <div class="d-flex gap-1 justify-content-center state-normal">
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Tandai Hilang" onclick="updateTableItemStatus(this, 'lost')">
                                                    Hilang
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning text-dark" title="Tandai Rusak" onclick="updateTableItemStatus(this, 'damaged')">
                                                    Rusak
                                                </button>
                                            </div>
                                            {{-- Tombol Batal --}}
                                            <div class="d-none state-cancel">
                                                <button type="button" class="btn btn-sm btn-light border border-secondary-subtle w-100" onclick="updateTableItemStatus(this, 'normal')">
                                                    <i class="bi bi-arrow-return-left"></i> Batal
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer border-top border-secondary-subtle py-3 px-4 bg-body-tertiary rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-floppy"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

{{-- 2. MODAL KOLEKTIF GURU (TABEL) --}}
@foreach($guruPengembalian as $item)
@if($item->is_collective)
<div class="modal fade" id="modalExemplar{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 bg-body text-body shadow-lg">
            <div class="modal-header border-bottom border-secondary-subtle py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-body mb-1">Kelola Eksemplar Kolektif</h5>
                    <p class="text-body-secondary small mb-0">Atur status buku jika terjadi kerusakan atau kehilangan.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form data-route-lost="{{ route('web.exemplar.bulkLost', $item->id) ?? '#' }}" 
                  data-route-damaged="{{ route('web.exemplar.bulkDamagedExemplar', $item->id) ?? '#' }}" 
                  onsubmit="submitPengembalian(event, this, 'kolektif')">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-3 d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                        <div>
                            Pilih status jika ada buku yang <b>Hilang</b> atau <b>Rusak</b>. 
                            Sisanya akan otomatis dianggap dikembalikan <b>Normal</b> saat diverifikasi.
                        </div>
                    </div>

                    {{-- FITUR: UI Tabel Untuk Modal Kolektif --}}
                    <div class="table-responsive border border-secondary-subtle rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small fw-semibold">
                                <tr>
                                    <th class="py-3 px-3">Judul Buku</th>
                                    <th class="py-3 text-center">Eksemplar</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center" style="width: 170px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->borrowedExemplars as $borrowed)
                                <tr class="exemplar-row" data-item-id="{{ $borrowed->id }}" data-status="normal">
                                    <td class="px-3">
                                        <div class="fw-bold text-primary">{{ $borrowed->exemplar->book->title ?? 'Buku Tidak Diketahui' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace px-2 py-1">
                                            {{ $borrowed->exemplar->code }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($borrowed->status == 'lost' || $borrowed->status == 'damaged')
                                            <span class="badge {{ $borrowed->status == 'lost' ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-1.5 rounded-pill">
                                                Sudah {{ ucfirst($borrowed->status) }}
                                            </span>
                                        @else
                                            {{-- Badge Status Interaktif --}}
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1.5 rounded-pill status-badge">
                                                🟢 Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        @if($borrowed->status != 'lost' && $borrowed->status != 'damaged')
                                            {{-- Tombol Aksi --}}
                                            <div class="d-flex gap-1 justify-content-center state-normal">
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Tandai Hilang" onclick="updateTableItemStatus(this, 'lost')">
                                                    Hilang
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning text-dark" title="Tandai Rusak" onclick="updateTableItemStatus(this, 'damaged')">
                                                    Rusak
                                                </button>
                                            </div>
                                            {{-- Tombol Batal --}}
                                            <div class="d-none state-cancel">
                                                <button type="button" class="btn btn-sm btn-light border border-secondary-subtle w-100" onclick="updateTableItemStatus(this, 'normal')">
                                                    <i class="bi bi-arrow-return-left"></i> Batal
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer border-top border-secondary-subtle py-3 px-4 bg-body-tertiary rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-floppy"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
    // Pencarian dan Sinkronisasi Tab
    document.getElementById('searchPengembalian').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let activeTab = document.querySelector('.tab-pane.active tbody');
        if(!activeTab) return;
        activeTab.querySelectorAll('tr').forEach(row => {
            if (!row.classList.contains('empty-row')) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            }
        });
    });

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
            document.getElementById('searchPengembalian').dispatchEvent(new Event('keyup'));
        });
    });

    // Manipulasi Tampilan Tabel (Normal / Hilang / Rusak)
    function updateTableItemStatus(buttonElement, status) {
        // Ambil elemen tr (baris) terdekat
        const row = buttonElement.closest('tr');
        row.setAttribute('data-status', status);

        // Elemen-elemen yang akan diubah di dalam baris
        const statusBadge = row.querySelector('.status-badge');
        const stateNormal = row.querySelector('.state-normal');
        const stateCancel = row.querySelector('.state-cancel');

        // Hapus warna background sebelumnya
        row.classList.remove('table-danger', 'table-warning');
        
        if (status === 'lost') {
            row.classList.add('table-danger');
            statusBadge.className = 'badge bg-danger px-3 py-1.5 rounded-pill status-badge';
            statusBadge.innerHTML = '🟥 Hilang';
            stateNormal.classList.add('d-none');
            stateCancel.classList.remove('d-none');
        } else if (status === 'damaged') {
            row.classList.add('table-warning');
            statusBadge.className = 'badge bg-warning text-dark px-3 py-1.5 rounded-pill status-badge';
            statusBadge.innerHTML = '🟧 Rusak';
            stateNormal.classList.add('d-none');
            stateCancel.classList.remove('d-none');
        } else {
            // Kembali ke Normal
            statusBadge.className = 'badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1.5 rounded-pill status-badge';
            statusBadge.innerHTML = '🟢 Normal';
            stateNormal.classList.remove('d-none');
            stateCancel.classList.add('d-none');
        }
    }

    // LOGIKA PENYIMPANAN AJAX
    async function submitPengembalian(event, form, type) {
        event.preventDefault(); 

        const btnSubmit = form.querySelector('button[type="submit"]');
        const originalBtnHtml = btnSubmit.innerHTML;

        const lostIds = [];
        const damagedIds = [];

        // Kumpulkan ID dari tabel berdasarkan 'data-status' pada 'tr.exemplar-row'
        form.querySelectorAll('.exemplar-row[data-status="lost"]').forEach(row => {
            lostIds.push(row.getAttribute('data-item-id'));
        });
        form.querySelectorAll('.exemplar-row[data-status="damaged"]').forEach(row => {
            damagedIds.push(row.getAttribute('data-item-id'));
        });

        if (lostIds.length === 0 && damagedIds.length === 0) {
            if(!confirm("Tidak ada buku yang ditandai rusak/hilang. Tutup modal ini?")) return;
            const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
            modal.hide();
            return;
        }

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

        const csrfToken = form.querySelector('input[name="_token"]').value;
        const routeLost = form.getAttribute('data-route-lost');
        const routeDamaged = form.getAttribute('data-route-damaged');
        
        const paramName = (type === 'individu') ? 'detail_ids[]' : 'borrowed_exemplar_ids[]';

        try {
            if (lostIds.length > 0) {
                const formDataLost = new FormData();
                formDataLost.append('_token', csrfToken);
                formDataLost.append('_method', 'PUT');
                lostIds.forEach(id => formDataLost.append(paramName, id));

                await fetch(routeLost, { method: 'POST', body: formDataLost });
            }

            if (damagedIds.length > 0) {
                const formDataDamaged = new FormData();
                formDataDamaged.append('_token', csrfToken);
                formDataDamaged.append('_method', 'PUT');
                damagedIds.forEach(id => formDataDamaged.append(paramName, id));

                await fetch(routeDamaged, { method: 'POST', body: formDataDamaged });
            }

            window.location.reload();

        } catch (error) {
            console.error("Gagal memproses pengembalian", error);
            alert("Terjadi kesalahan sistem saat menghubungi server. Silakan coba lagi.");
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalBtnHtml;
        }
    }
</script>
@endsection