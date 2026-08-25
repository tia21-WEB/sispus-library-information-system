@extends('layouts.kepala')

@section('content')

<style>
    /* --- STYLE FORM PREMIUM --- */
    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #6c757d; 
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-control {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        background-color: #ffffff;
        border-color: #0d6efd; 
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    }
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
</style>

<div class="container-fluid p-0 mb-5">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md">
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.8px;">Edit Pustakawan</h3>
            <p class="text-muted mb-0 small">Perbarui data diri dan informasi kontak pustakawan yang terdaftar.</p>
        </div>
        <div class="col-md-auto text-md-end">
            <a href="javascript:history.back()" class="btn btn-light bg-white border shadow-sm rounded-3 px-3 py-2 fw-medium d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="mb-0 fw-bold d-flex align-items-center text-dark" style="font-size: 1.05rem;">
                <i class="bi bi-pencil-square text-primary me-2"></i> Formulir Perubahan Data
            </h5>
        </div>
        
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('kepala.pustakawan.update', $pustakawan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ $pustakawan->name }}" 
                                class="form-control" 
                                placeholder="Masukkan nama lengkap" 
                                required autocomplete="off">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ $pustakawan->email }}" 
                                class="form-control" 
                                placeholder="contoh@email.com" 
                                required autocomplete="off">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">No HP / WhatsApp</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ $pustakawan->phone }}" 
                                class="form-control" 
                                placeholder="Masukkan nomor HP aktif">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea 
                                name="address" 
                                class="form-control" 
                                placeholder="Tuliskan alamat domisili lengkap pustakawan di sini...">{{ $pustakawan->address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-save2-fill"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>

@endsection