@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
{{-- FITUR: Layout Container & Header Halaman --}}
<div class="container-fluid py-2">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md">
            <h4 class="fw-bold text-dark mb-1">Data Anggota</h4>
            <p class="text-muted mb-0 small">Daftar dan manajemen seluruh anggota perpustakaan</p>
        </div>
        <div class="col-12 col-md-auto">

            <div class="d-flex gap-2 mb-2 justify-content-end">
                {{-- FITUR: Tombol Unduh Format Template Excel --}}
                <a href="{{ route('anggota.template') }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download"></i> Unduh Template
                </a>

                {{-- FITUR: Tombol Trigger Buka Modal Import Excel --}}
                <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#importModal">
                    <i class="bi bi-upload"></i> Impor Data
                </button>
            </div>

            {{-- FITUR: Form Input Pencarian Data Anggota (Live Search) --}}
            <form action="{{ route('web.anggota') }}" method="GET">
                <div class="input-group shadow-sm" style="min-width:280px;max-width:320px;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="search"
                        id="searchAnggota"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control border-start-0 ps-1"
                        placeholder="Cari nama, email, NIS/NIP...">
                </div>
            </form>
        </div>
    </div>

    {{-- FITUR: Navigasi Tab Siswa & Guru --}}
    @php
        // Daftar role yang akan dijadikan tab (Pastikan nama key sesuai dengan data role di database)
        $tabs = [
            'siswa' => 'Siswa',
            'guru'  => 'Guru'
        ];
    @endphp

    <ul class="nav nav-pills mb-3" id="anggotaTab" role="tablist">
        @foreach($tabs as $roleKey => $roleName)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                        id="tab-{{ $roleKey }}" 
                        data-bs-toggle="pill" 
                        data-bs-target="#pane-{{ $roleKey }}" 
                        type="button" 
                        role="tab" 
                        aria-controls="pane-{{ $roleKey }}" 
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="bi {{ $roleKey == 'guru' ? 'bi-person-workspace' : 'bi-person-badge' }} me-1"></i> 
                    {{ $roleName }}
                </button>
            </li>
        @endforeach
    </ul>

    {{-- FITUR: Konten Tab (Tabel Utama) --}}
    <div class="tab-content" id="anggotaTabContent">
        @foreach($tabs as $roleKey => $roleName)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                 id="pane-{{ $roleKey }}" 
                 role="tabpanel" 
                 aria-labelledby="tab-{{ $roleKey }}" 
                 tabindex="0">
                 
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary small text-uppercase fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Anggota</th>
                                        <th class="py-3">NIS / NIP</th>
                                        {{-- Kolom Role Dihapus --}}
                                        <th class="py-3">Total Pinjam</th>
                                        <th class="py-3">Badge</th>
                                        <th class="py-3">Status</th>
                                        <th class="pe-4 py-3 text-end" style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Memfilter koleksi berdasarkan rolenya
                                        $filteredUsers = $users->filter(function($user) use ($roleKey) {
                                            return strtolower($user->role) === $roleKey;
                                        });
                                    @endphp

                                    @forelse($filteredUsers as $user)
                                    <tr>
                                        {{-- FITUR: UI Avatar Berupa 2 Huruf Inisial --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center justify-content-center rounded-circle fw-bold bg-primary-subtle text-primary border border-primary-subtle shadow-sm" 
                                                     style="width: 38px; height: 38px; font-size: 0.85rem;">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
                                                    <small class="text-muted d-block" style="font-size: 0.8rem;">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <span class="text-secondary font-monospace small">{{ $user->nis_nip ?? '-' }}</span>
                                        </td>
                                        
                                        {{-- Baris Role TD Dihapus --}}
                                        
                                        <td>
                                            <span class="fw-bold text-dark">{{ $user->borrowings_count }}</span>
                                            <small class="text-muted">Buku</small>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1.5" style="font-size: 0.75rem;">
                                                <i class="bi bi-star-fill me-1"></i>{{ $user->badge ?? 'Reguler' }}
                                            </span>
                                        </td>
                                        
                                        {{-- FITUR: Logika Status Aktif / Diblokir --}}
                                        <td>
                                            @if(!$user->is_active)
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">
                                                    ● Nonaktif
                                                </span>
                                            @else
                                                @php
                                                    $blocked = $user->borrowings()
                                                        ->where(function ($q) {
                                                            $q->where(function ($sub) {
                                                                $sub->where('status', 'dipinjam')
                                                                    ->whereDate('return_date', '<', now()->subDay());
                                                            })
                                                            ->orWhere('status', 'hilang');
                                                        })
                                                        ->exists();
                                                @endphp

                                                @if($blocked)
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">
                                                        ● Diblokir
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">
                                                        ● Aktif
                                                    </span>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- FITUR: Tombol Aksi Lihat Detail --}}
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('web.anggota.detail', $user->id) }}" 
                                               class="btn btn-sm btn-light border rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm" 
                                               title="Lihat Detail" style="width: 34px; height: 34px;">
                                                <i class="bi bi-eye text-info fs-6"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <div class="mb-2">
                                                <i class="bi bi-people text-muted opacity-25" style="font-size: 3rem;"></i>
                                            </div>
                                            <h6 class="mb-1 fw-semibold text-secondary">Belum Ada {{ $roleName }}</h6>
                                            <small class="text-muted">Data anggota untuk kategori ini masih kosong.</small>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        @endforeach
    </div>

    {{-- FITUR: Navigasi Halaman / Pagination diletakkan di luar Tab --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- FITUR: Script Interaksi --}}
<script>
    // Debounce untuk Pencarian Live Search
    const search = document.getElementById('searchAnggota');
    let timer;

    search.addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            this.form.submit();
        }, 500);
    });

    // Menjaga agar Tab yang aktif tidak reset saat pagination atau pencarian dijalankan
    document.addEventListener("DOMContentLoaded", function() {
        let activeTab = localStorage.getItem('activeTabAnggota');
        if (activeTab) {
            let tabElement = document.querySelector(activeTab);
            if(tabElement) {
                let tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }

        let tabEls = document.querySelectorAll('button[data-bs-toggle="pill"]');
        tabEls.forEach(function(tabEl) {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeTabAnggota', '#' + event.target.id);
            });
        });
    });
</script>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('anggota.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <small class="text-muted">Gunakan template yang telah diunduh.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection