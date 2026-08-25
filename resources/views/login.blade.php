<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pustakawan & Kepala Perpus - SISPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- FITUR: Styling CSS Kustom Split Screen, Variabel Brand, dan Efek Interaktif Form Login --}}
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .split-container {
            min-height: 100vh;
            display: flex;
        }

        /* --- SISI KIRI: BRANDING VISUAL (Sembunyi di Mobile) --- */
        .brand-side {
            background: var(--brand-gradient);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            overflow: hidden;
        }

        /* Efek Lampu/Aura Modern di Background */
        .brand-side::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, rgba(0,0,0,0) 70%);
            filter: blur(50px);
        }

        .brand-content {
            position: relative;
            z-index: 2;
            margin-auto: 0;
            max-width: 480px;
        }

        .brand-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 99px;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .brand-title {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .brand-subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-top: 1rem;
            line-height: 1.6;
        }

        .brand-footer {
            position: relative;
            z-index: 2;
            color: #64748b;
            font-size: 0.85rem;
        }

        /* --- SISI KANAN: FORM LOGIN --- */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: #ffffff;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .form-header h2 {
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        /* Desain Input dengan Icon di Dalam */
        .input-group-modern {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group-modern i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 5;
            transition: color 0.2s;
        }

        .input-group-modern .form-control {
            padding: 14px 16px 14px 48px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .input-group-modern .form-control:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group-modern .form-control:focus + i.input-icon {
            color: var(--brand-primary);
        }

        /* Khusus Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            z-index: 5;
            transition: color 0.2s;
        }
        .password-toggle:hover {
            color: var(--brand-primary);
        }

        /* Tombol Modern Solid */
        .btn-login {
            background: var(--brand-primary);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .btn-login:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* Responsive Breakpoints */
        @media (max-width: 767.98px) {
            .brand-side {
                display: none !important;
            }
            .form-side {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="split-container row g-0">
        
        {{-- FITUR: Sisi Kiri - Panel Branding Visual dan Informasi SISPUS[cite: 10] --}}
        <div class="brand-side col-md-6 col-lg-7 d-flex">
            <div>
                <div class="brand-logo-badge">
                    <i class="bi bi-book-half"></i> S S P S — v2.0
                </div>
            </div>
            
            <div class="brand-content">
                <h1 class="brand-title">Sistem Informasi Perpustakaan</h1>
                <p class="brand-subtitle">
                    Selamat datang kembali di SISPUS SMAN 3 Padang. Kelola sirkulasi buku dan manajemen pustaka dengan lebih cepat, cerdas, dan efisien dalam satu dasbor terintegrasi.
                </p>
            </div>
            
            <div class="brand-footer">
                &copy; 2026 SMAN 3 Padang. All rights reserved.
            </div>
        </div>

        {{-- FITUR: Sisi Kanan - Panel Formulir Masuk (Login) Akun Pustakawan & Kepala Perpus[cite: 10] --}}
        <div class="form-side col-12 col-md-6 col-lg-5">
            <div class="form-wrapper">
                
                <div class="form-header mb-4">
                    <h2>Masuk Akun</h2>
                    <p class="text-muted small">Khusus Pustakawan & Kepala Perpustakaan</p>
                </div>

                {{-- FITUR: Blok Notifikasi Peringatan Kesalahan Validasi Login[cite: 10] --}}
                @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center rounded-3 small py-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
                @endif

                {{-- FITUR: Form Utama Pengisian Kredensial Akses (NIS/NIP dan Kata Sandi)[cite: 10] --}}
                <form action="{{ route('web.login.proses') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">NIP / Nomor Pegawai</label>
                        <div class="input-group-modern">
                            <input type="text" 
                                   name="nis_nip" 
                                   class="form-control" 
                                   value="{{ old('nis_nip') }}" 
                                   placeholder="Masukkan NIP anda"
                                   required 
                                   autocomplete="off">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                        <div class="input-group-modern">
                            <input type="password" 
                                   name="password" 
                                   id="passwordInput"
                                   class="form-control" 
                                   placeholder="Masukkan kata sandi"
                                   required>
                            <i class="bi bi-shield-lock input-icon"></i>
                            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>
<div class="mb-4">
    <label class="form-label small fw-semibold text-secondary">
        Kode Keamanan
    </label>

    <!-- Tampilan Kode -->
    <div class="d-flex align-items-center mb-2">
        <div class="border rounded px-4 py-2 bg-light fw-bold fs-5 text-primary"
             style="letter-spacing:4px;">
            {{ $captcha }}
        </div>
    </div>

    <!-- Input -->
    <input
        type="text"
        name="captcha"
        class="form-control"
        placeholder="Masukkan kode keamanan"
        autocomplete="off"
        required>
</div>
                    <button type="submit" class="btn btn-login w-100">
                        Masuk Aplikasi <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

{{-- FITUR: Skrip JavaScript untuk Fungsionalitas Toggle Visibilitas Kata Sandi[cite: 10] --}}
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#passwordInput');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>