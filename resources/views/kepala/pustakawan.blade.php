@extends('layouts.kepala')

@section('title', 'Kelola Pustakawan')

@section('content')

<style>
    /* --- STYLE UPGRADE - SINKRONISASI SAAS PREMIUM --- */
    .form-control {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 0.9rem;
    }
    .form-control:focus {
        background-color: var(--bg-card);
        color: var(--text-main);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    /* Table Adjustments */
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
        padding: 14px 16px;
        color: var(--text-main);
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }

    /* Initials User Avatar Placeholder */
    .pustakawan-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #a5b4fc);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-sm">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Kelola Staf Pustakawan</h3>
        <p class="text-muted mb-0 small">Manajemen hak akses akun dan data administrasi petugas perpustakaan</p>
    </div>
    <div class="col-sm-auto text-sm-end">
        <a href="{{ route('kepala.pustakawan.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm py-2.5 rounded-3">
            <i class="bi bi-plus-circle-fill fs-6"></i> Tambah Pustakawan
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="input-group shadow-sm" style="border-radius: 12px;">
            <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: var(--border-color);">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="searchPustakawan" class="form-control border-start-0 ps-0" placeholder="Ketik kata kunci untuk menyaring nama atau alamat email staf pustakawan..." style="border-radius: 0 12px 12px 0;">
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header py-3">
        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
            <i class="bi bi-person-badge-fill me-2 text-primary"></i>Daftar Petugas Aktif
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="pustakawanTable">
                <thead>
                    <tr>
                        <th class="ps-4" width="60">No</th>
                        <th>Identitas Petugas / Nama</th>
                        <th>Alamat Email Resmi</th>
                        <th>Nomor Telepon / HP</th>
                        <th class="pe-4 text-end" width="160">Aksi Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pustakawan as $item)
                    <tr>
                        <td class="ps-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="pustakawan-avatar">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold" style="color: var(--text-main);">{{ $item->name }}</div>
                                    <small class="text-muted">NIP: {{ $item->nis_nip ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-medium text-secondary">{{ $item->email }}</td>
                        <td class="text-muted small">{{ $item->phone ?? '-' }}</td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('kepala.pustakawan.edit', $item->id) }}" class="btn btn-warning btn-sm px-3 rounded-3 fw-semibold text-dark shadow-sm d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('kepala.pustakawan.delete', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pustakawan ini dari sistem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bi bi-people text-muted" style="font-size: 3.5rem; opacity: 0.5;"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main) !important;">Belum Ada Staf</h6>
                            <p class="small text-muted mb-0">Silakan klik tombol "Tambah Pustakawan" untuk mendaftarkan akun administrasi baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchPustakawan').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#pustakawanTable tbody tr');

        rows.forEach(row => {
            if(row.cells.length > 1) { // Melewati baris empty state
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            }
        });
    });
</script>

@endsection