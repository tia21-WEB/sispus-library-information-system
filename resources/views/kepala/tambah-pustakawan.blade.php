@extends('layouts.kepala')

@section('title', 'Tambah Pustakawan')

@section('content')

<style>
    /* --- STYLE UPGRADE - PREMIUM SAAS FORM UI --- */
    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .form-control {
        background-color: var(--bg-main);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        background-color: var(--bg-main);
        color: var(--text-main);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px var(--primary-light);
    }
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--border-color);
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-sm">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Pendaftaran Akun Pustakawan</h3>
        <p class="text-muted mb-0 small">Registrasi identitas staf perpustakaan baru untuk otentikasi sistem</p>
    </div>
    <div class="col-sm-auto text-sm-end">
        <a href="{{ route('kepala.pustakawan') }}" class="btn btn-light border d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 text-muted">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 max-width-container">
    <div class="card-header py-3">
        <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
            <i class="bi bi-person-plus-fill me-2 text-primary"></i>Isi Formulir Akun Administrasi
        </h5>
    </div>
    <div class="card-body p-4 p-md-5">
        
        <form action="{{ route('kepala.pustakawan.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-person me-1"></i>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Ketik nama lengkap beserta gelar..." required autocomplete="off">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-card-text me-1"></i>Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nis_nip" class="form-control" placeholder="Ketik nomor NIP resmi petugas..." required autocomplete="off">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-envelope me-1"></i>Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@sman3padang.sch.id" required autocomplete="off">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-telephone me-1"></i>Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" placeholder="Contoh: 081234567xxx" autocomplete="off">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-lock me-1"></i>Kata Sandi Akun (Password)</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat kata sandi akun yang kuat untuk keamanan sistem..." required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><i class="bi bi-geo-alt me-1"></i>Alamat Domisili Rumah</label>
                        <textarea name="address" class="form-control" placeholder="Tuliskan nama jalan, kelurahan, kecamatan, kota tempat tinggal petugas saat ini..."></textarea>
                    </div>
                </div>

                <div class="col-12 pt-2 border-top mt-5 d-flex gap-2 justify-content-end">
                    <a href="{{ route('kepala.pustakawan') }}" class="btn btn-light border text-muted px-4 py-2.5 rounded-3">
                        Batalkan
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Data Akun
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection