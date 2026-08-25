@extends('layouts.kepala')

@section('title', 'Profil Saya')

@section('content')

<style>
    /* --- STYLE UPGRADE - PREMIUM ACCOUNT MANAGEMENT UI --- */
    .profile-card {
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
        position: relative;
    }
    
    .profile-avatar-wrapper {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto;
    }
    
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #3b82f6);
        color: #ffffff;
        font-weight: 800;
        font-size: 2.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.25);
        border: 4px solid var(--bg-card);
    }

    /* Badge Status Role Aktif */
    .role-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 16px;
        border-radius: 50px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: inline-block;
    }

    /* Form & Input Elements */
    .form-label {
        font-size: 0.8rem;
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
    .info-list-item {
        font-size: 0.9rem;
        color: var(--text-muted);
        padding: 10px 0;
        border-bottom: 1px dashed var(--border-color);
    }
    .info-list-item:last-child {
        border-bottom: none;
    }
</style>

<div class="row align-items-center mb-4 g-3">
    <div class="col-md">
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px; color: var(--text-main);">Pengaturan Profil</h3>
        <p class="text-muted mb-0 small">Kelola informasi data diri, detail kontak, dan konfigurasi keamanan kredensial akun Anda</p>
    </div>
</div>

<div class="container-fluid p-0">
    <div class="row g-4">
        
        <div class="col-12 col-lg-4">
            <div class="card profile-card border-0 shadow-sm rounded-4 p-3 text-center mb-4">
                <div class="card-body">
                    <div class="profile-avatar-wrapper mb-3">
                        <div class="profile-avatar">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>

                    <h4 class="fw-bold mb-2" style="color: var(--text-main); letter-spacing: -0.5px;">
                        {{ $user->name }}
                    </h4>
                    
                    <div class="mb-4">
                        <span class="role-badge">
                            <i class="bi bi-shield-check me-1"></i>{{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="text-start mt-2 border-top pt-2">
                        <div class="info-list-item d-flex justify-content-between">
                            <span><i class="bi bi-card-text me-2"></i>ID / NIP</span>
                            <span class="fw-bold text-dark" style="color: var(--text-main) !important;">{{ $user->nis_nip ?? '-' }}</span>
                        </div>
                        <div class="info-list-item d-flex justify-content-between">
                            <span><i class="bi bi-calendar-check me-2"></i>Status</span>
                            <span class="text-success fw-bold">Aktif Terverifikasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header py-3">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1rem;">
                        <i class="bi bi-person-gear me-2 text-primary"></i>Informasi Data Diri
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('web.profile.update') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-1">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-1">
                                    <label class="form-label">Alamat Email Resmi</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Nomor HP / WhatsApp</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Alamat Domisili Rumah</label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Tulis alamat rumah lengkap saat ini...">{{ $user->address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header py-3" style="border-bottom: 1px solid rgba(16, 185, 129, 0.15);">
                    <h5 class="fw-bold mb-0 text-success" style="font-size: 1rem;">
                        <i class="bi bi-lock-fill me-2"></i>Pembaruan Kata Sandi & Keamanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('web.profile.password') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="old_password" class="form-control" placeholder="Masukkan password yang digunakan sekarang" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-1">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Buat kombinasi password baru" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-1">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ketik ulang password baru Anda" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-success px-4 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection