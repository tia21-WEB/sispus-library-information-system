@extends('layouts.kepala')

@section('title', 'Approval Buku')

@section('content')

<style>
    /* --- PREMIUM SAAS SYSTEM UPGRADE --- */
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
    
    /* Strip Aksentuasi Warna Kartu */
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
    }
    .card-primary::after { background: #2563eb; }
    .card-warning::after { background: #f59e0b; }
    .card-success::after { background: #10b981; }
    .card-danger::after { background: #ef4444; }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Form Controls & Filter */
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
    .input-group-text {
        background-color: var(--hover-bg);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
    }

    /* Fluid Table Styling (Lebar Pas, Bebas Scroll Samping) */
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
        padding: 14px 16px;
    }
    .table td {
        padding: 16px 16px;
        vertical-align: top;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }
    .text-nowrap-forced {
        white-space: nowrap !important;
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Approval Buku</h3>
        <p class="text-muted mb-0 small">Persetujuan penambahan dan perubahan data katalog buku oleh pustakawan</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-primary shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Pengajuan</p>
                        <h2 class="mb-0" style="font-size: 2.2rem; font-weight: 800; color: var(--text-main);">{{ $approvals->count() }}</h2>
                    </div>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-collection-play-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-warning shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pending</p>
                        <h2 class="text-warning mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $approvals->where('status','pending')->count() }}</h2>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-success shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Disetujui</p>
                        <h2 class="text-success mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $approvals->where('status','approved')->count() }}</h2>
                    </div>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="card stat-card card-danger shadow-sm p-1">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Ditolak</p>
                        <h2 class="text-danger mb-0" style="font-size: 2.2rem; font-weight: 800;">{{ $approvals->where('status','rejected')->count() }}</h2>
                    </div>
                    <div class="icon-box bg-danger-subtle text-danger">
                        <i class="bi bi-x-octagon-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 align-items-center">
    <div class="col-12 col-md-5 col-lg-6">
        <div class="input-group shadow-sm" style="border-radius: 12px;">
            <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="searchApproval" class="form-control border-start-0 ps-0" placeholder="Cari judul, pengaju, tipe aksi, atau status..." style="border-radius: 0 12px 12px 0;">
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-4">
        <div class="input-group shadow-sm" style="border-radius: 12px;">
            <span class="input-group-text border-end-0 small" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">
                <i class="bi bi-calendar3 me-1"></i> Bulan
            </span>
            <input type="month" id="filterPeriode" class="form-control" style="border-radius: 0 12px 12px 0; border-color: var(--border-color);" title="Pilih Bulan & Tahun">
        </div>
    </div>
    <div class="col-12 col-md-3 col-lg-2 text-md-end">
        <button type="button" id="btnResetFilter" class="btn btn-light btn-sm rounded-3 px-3 py-2 border w-100" style="height: 45px; font-weight: 600; color: var(--text-main);">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header py-3">
        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
            <i class="bi bi-shield-check-fill me-2 text-primary"></i>Daftar Antrean Verifikasi Data
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="approvalTable">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" width="5%">No</th>
                        <th width="45%">Detail Informasi & Spesifikasi Buku</th>
                        <th class="text-center" width="10%">Operasi</th>
                        <th width="20%">Pengaju / Tanggal</th>
                        <th class="text-center" width="10%">Status</th>
                        <th class="pe-4 text-center" width="10%">Keputusan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $item)
                    <tr data-date="{{ $item->created_at->format('Y-m-d') }}">
                        <td class="ps-4 text-center fw-medium text-secondary">{{ $loop->iteration }}</td>
                        
                        <td>
                            <div class="fw-bold mb-1 text-primary-hover" style="color: var(--text-main); line-height: 1.35; font-size: 0.95rem;">
                                {{ $item->book_data['title'] ?? '-' }}
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                                <span class="text-muted small">
                                    <i class="bi bi-person me-1"></i>{{ $item->book_data['author'] ?? '-' }}
                                </span>
                                <span class="text-muted small">
                                    • {{ $item->book_data['publisher'] ?? '-' }} ({{ $item->book_data['publication_year'] ?? '-' }})
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary small px-2 py-0.5">
                                    Stok : {{ $item->book_data['stock'] ?? 0 }} Eksemplar
                                </span>
                            </div>

                            <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
                                @if(!empty($item->book_data['ebook_file']))
                                    <span class="badge bg-success text-white">
                                        🟢 Ebook Tersedia
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white">
                                        Tidak Ada Ebook
                                    </span>
                                @endif

                                @if(!empty($item->book_data['ebook_file']))
                                    <a href="{{ asset('storage/'.$item->book_data['ebook_file']) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> 👁 Lihat Ebook
                                    </a>
                                @endif
                            </div>
                        </td>

                        <td class="text-center text-nowrap-forced">
                            @if($item->action == 'create')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded fw-bold small">
                                    <i class="bi bi-plus-circle-fill me-1"></i>Tambah
                                </span>
                            @elseif($item->action == 'update')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded fw-bold small">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </span>
                            @elseif($item->action == 'delete')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded fw-bold small">
                                    <i class="bi bi-trash3-fill me-1"></i>Hapus
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5 rounded fw-bold small">
                                    {{ ucfirst($item->action) }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="fw-semibold mb-1" style="color: var(--text-main);">
                                <i class="bi bi-person-badge text-muted me-1"></i>{{ $item->requester->name ?? '-' }}
                            </div>
                            <div class="text-muted small">
                                <i class="bi bi-calendar-event me-1"></i>{{ $item->created_at->format('d M Y') }}
                            </div>
                        </td>

                        <td class="text-center text-nowrap-forced">
                            @if($item->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="bi bi-hourglass me-1"></i>Pending
                                </span>
                            @elseif($item->status == 'approved')
                                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="bi bi-check2 me-1"></i>Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="bi bi-x me-1"></i>Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="pe-4 text-center text-nowrap-forced">
                            @if($item->status == 'pending')
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('kepala.approval.approve', $item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm px-3 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                                            <i class="bi bi-check-lg"></i> Setuju
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm px-3 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                        Tolak
                                    </button>
                                </div>
                            @else
                                <span class="text-muted small d-inline-flex align-items-center gap-1 bg-light border px-2 py-1 rounded">
                                    <i class="bi bi-lock-fill text-secondary"></i> Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-check text-muted" style="font-size: 3.5rem; opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main) !important;">Antrean Clean</h6>
                            <p class="small text-muted mb-0">Belum ada pengajuan perubahan data buku saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL ALASAN PENOLAKAN --}}
@foreach($approvals as $item)
<div class="modal fade"
     id="rejectModal{{ $item->id }}"
     tabindex="-1"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('kepala.approval.reject', $item->id) }}"
                  method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        Alasan Penolakan
                    </h5>
                    <button
                        type="button"
                        class="btn-close shadow-none"
                        data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-semibold text-secondary small">
                        Masukkan alasan penolakan
                    </label>
                    <textarea
                        name="rejection_reason"
                        class="form-control border-0 bg-light"
                        rows="5"
                        required
                        placeholder="Contoh:&#10;Cover buku kurang jelas.&#10;Silakan upload ulang."></textarea>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn btn-danger rounded-pill px-4 shadow-sm">
                        <i class="bi bi-send-fill me-1"></i>
                        Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    const searchInput = document.getElementById('searchApproval');
    const filterPeriodeInput = document.getElementById('filterPeriode');
    const btnReset = document.getElementById('btnResetFilter');

    function filterTable() {
        let searchValue = searchInput.value.toLowerCase();
        let targetPeriode = filterPeriodeInput.value; // Output berupa: "YYYY-MM" (Contoh: "2026-05")
        
        let rows = document.querySelectorAll('#approvalTable tbody tr');

        console.log('--- Memulai Filter Periode ---');
        console.log('Bulan & Tahun yang dicari pengelola:', targetPeriode ? targetPeriode : 'Semua Bulan');

        rows.forEach((row, index) => {
            // Melewati baris empty state (jika tidak ada data sama sekali dari server)
            if(row.cells.length > 1) { 
                let textMatch = row.innerText.toLowerCase().includes(searchValue);
                let rowDate = row.getAttribute('data-date'); // Format murni ISO dari server: YYYY-MM-DD
                let dateMatch = true;

                if (targetPeriode && rowDate) {
                    let rowMonthYear = rowDate.substring(0, 7); // Mengambil 7 karakter pertama (YYYY-MM)
                    
                    // KODE DEBUGGING: Silakan cek hasil log ini di Console (F12) browser Anda
                    console.log(`Baris ke-${index + 1}: Data di Tabel = ${rowMonthYear} | Yang Dicari = ${targetPeriode}`);

                    if (rowMonthYear !== targetPeriode) {
                        dateMatch = false;
                    }
                }

                // Tampilkan jika cocok teks DAN cocok bulan-tahunnya
                row.style.display = (textMatch && dateMatch) ? '' : 'none';
            }
        });
    }

    // Trigger filter secara real-time
    searchInput.addEventListener('keyup', filterTable);
    filterPeriodeInput.addEventListener('change', filterTable);

    // Reset Button Handler
    btnReset.addEventListener('click', function() {
        searchInput.value = '';
        filterPeriodeInput.value = '';
        filterTable();
    });
</script>

@endsection