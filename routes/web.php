<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\KepalaPustakawanController;

// Jalur Login Admin
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get(
    '/login',
    [AdminController::class, 'showLogin']
)->name('web.login');

Route::post(
    '/login',
    [AdminController::class, 'prosesLogin']
)->name('web.login.proses');
// Jalur Secure Admin
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard',
        [AdminController::class, 'dashboard']
    )->name('web.dashboard');

    Route::get('/logout',
        [AdminController::class, 'logout']
    )->name('web.logout');
    
Route::get(
    '/profil',
    [AdminController::class, 'profile']
)->name('web.profile');
Route::post(
    '/profil/update',
    [AdminController::class, 'updateProfile']
)->name('web.profile.update');

Route::post(
    '/profil/password',
    [AdminController::class, 'updatePassword']
)->name('web.profile.password');

//DASHBOARD
//BLOKIR
Route::get(
    '/admin/anggota-terblokir',
    [AdminController::class, 'anggotaTerblokir']
)->name('web.anggota.terblokir');

    // Sirkulasi
    Route::get('/admin/peminjaman', [AdminController::class, 'peminjaman'])->name('web.peminjaman');
    Route::post('/admin/peminjaman/store',
    [AdminController::class, 'storePeminjaman'])
    ->name('web.peminjaman.store');
    Route::put('/admin/peminjaman/{id}/approve',
    [AdminController::class, 'approvePeminjaman'])
    ->name('web.peminjaman.approve');

    Route::put(
    '/exemplar/{id}/lost',
    [AdminController::class,'lostExemplar']
)->name(
    'web.exemplar.lost'
);
Route::put(
    '/exemplar/bulk-lost/{borrowing}',
    [AdminController::class,'bulkLostExemplar']
)->name('web.exemplar.bulkLost');
 Route::put(
    '/pengembalian/{borrowing}/bulk-lost-individu',
    [AdminController::class, 'bulkLostIndividu']
)->name('web.exemplar.bulkLostIndividu');
// Eksemplar Rusak
Route::put('/exemplar/{id}/damaged', [AdminController::class, 'damagedExemplar'])
    ->name('web.exemplar.damaged');

Route::put('/exemplar/bulk-damaged/{borrowing}', [AdminController::class, 'bulkDamagedExemplar'])
    ->name('web.exemplar.bulkDamaged');

Route::put('/admin/buku-rusak/{id}/selesai', [AdminController::class, 'selesaikanBukuRusak'])
    ->name('web.buku.rusak.selesai');
// Approve pustakawan
Route::put(
'/admin/extension/{id}/approve',
[AdminController::class,'approveExtension']
)->name('web.extension.approve');

// Reject pustakawan
Route::put(
'/admin/extension/{id}/reject',
[AdminController::class,'rejectExtension']
)->name('web.extension.reject');

Route::put(
    '/pengembalian/{borrowing}/bulk-damaged-individu',
    [AdminController::class, 'bulkDamagedIndividu']
)->name('web.exemplar.bulkDamagedIndividu');
//SIMULASI
    Route::put('/admin/peminjaman/{id}/return-request',
    [AdminController::class, 'requestReturn'])
    ->name('web.peminjaman.return');

Route::put('/admin/peminjaman/{id}/reject',
    [AdminController::class, 'rejectPeminjaman'])
    ->name('web.peminjaman.reject');
    Route::get('/admin/pengembalian', [AdminController::class, 'pengembalian'])->name('web.pengembalian');
    Route::put('/admin/pengembalian/{id}/approve',
    [AdminController::class, 'approvePengembalian'])
    ->name('web.pengembalian.approve');
    Route::get('/admin/blokir-user', [AdminController::class, 'blokirUser'])->name('web.blokir');

// Data Master & Analisis
    Route::get('/admin/buku', [AdminController::class, 'buku'])->name('web.buku');
    Route::post('/admin/buku/store', [AdminController::class, 'storeBuku'])->name('web.buku.store');
    Route::post('/admin/kategori/store',
    [AdminController::class, 'storeKategori'])
    ->name('web.kategori.store');
    Route::delete('/admin/buku/{id}',
    [AdminController::class, 'destroyBuku'])
    ->name('web.buku.destroy');
    Route::put('/admin/buku/{id}',
    [AdminController::class, 'updateBuku'])
    ->name('web.buku.update');
    Route::delete('/admin/kategori/{id}',
    [AdminController::class, 'destroyKategori'])
    ->name('web.kategori.destroy');
//ANGGOTA
    Route::get('/admin/anggota', [AdminController::class, 'anggota'])->name('web.anggota');
    Route::get('/admin/anggota',
    [AdminController::class, 'anggota'])
    ->name('web.anggota');
    Route::get('/admin/anggota/{id}',
    [AdminController::class, 'detailAnggota'])
    ->name('web.anggota.detail');
    Route::get(
    '/anggota/template',
    [AdminController::class, 'downloadTemplate']
)->name('anggota.template');
Route::post(
    '/anggota/import',
    [AdminController::class, 'importExcel']
)->name('anggota.import');
Route::post(
    '/anggota/{id}/toggle',
    [AdminController::class,'toggleStatus']
)->name('web.anggota.toggle');
//leaderboard
    Route::get('/admin/gamifikasi', [AdminController::class, 'gamifikasi'])->name('web.gamifikasi');
    Route::get('/admin/gamifikasi',
    [AdminController::class, 'gamifikasi'])
    ->name('web.gamifikasi');
Route::post('/leaderboard/tutup-periode',
    [AdminController::class,'closeRewardPeriod'])
    ->name('leaderboard.closePeriod');
    Route::post(
    '/leaderboard/periode',
    [AdminController::class,'storeRewardPeriod']
)->name('leaderboard.storePeriod');
//laporan
 Route::get('/admin/laporan',
    [AdminController::class, 'laporan'])
    ->name('web.laporan');

Route::get('/admin/laporan/pdf',
    [AdminController::class, 'downloadLaporanPdf'])
    ->name('web.laporan.pdf');
Route::put(
    '/admin/buku-hilang/{id}/selesai',
    [AdminController::class,
    'selesaikanBukuHilang']
)->name(
    'web.buku.hilang.selesai'
);
Route::get(
    '/admin/notifications/clear',
    [AdminController::class, 'clearNotifications']
)->name('web.notification.clear');

    //KEPALA PUSTAKAWAN
    Route::get(
    '/kepala/dashboard',
    [KepalaPustakawanController::class,'dashboard']
)->name('kepala.dashboard');
Route::get(
    '/kepala/pustakawan',
    [KepalaPustakawanController::class,'pustakawan']
)->name('kepala.pustakawan');
Route::get(
    '/kepala/pustakawan/create',
    [KepalaPustakawanController::class,'createPustakawan']
)->name('kepala.pustakawan.create');
Route::post(
    '/kepala/pustakawan/store',
    [KepalaPustakawanController::class,'storePustakawan']
)->name('kepala.pustakawan.store');
Route::get(
    '/kepala/pustakawan/{id}/edit',
    [KepalaPustakawanController::class,'editPustakawan']
)->name('kepala.pustakawan.edit');

Route::put(
    '/kepala/pustakawan/{id}/update',
    [KepalaPustakawanController::class,'updatePustakawan']
)->name('kepala.pustakawan.update');
Route::delete(
    '/kepala/pustakawan/{id}/delete',
    [KepalaPustakawanController::class,'deletePustakawan']
)->name('kepala.pustakawan.delete');

//BUKu KEPALA PUSTAKAWAN

Route::get(
    '/kepala/buku',
    [KepalaPustakawanController::class,'buku']
)->name('kepala.buku');
//ANGGOTA KEPALA PUSTAKAWAN
Route::get(
    '/kepala/anggota',
    [KepalaPustakawanController::class,'anggota']
)->name('kepala.anggota');
//TRANSAKSI KEPALA PUSTAKAWAN
Route::get(
    '/kepala/transaksi',
    [KepalaPustakawanController::class,'transaksi']
)->name('kepala.transaksi');
//APPROVAL KEPALA PUSTAKAWAN
Route::get(
    '/kepala/approval',
    [KepalaPustakawanController::class,'approval']
)->name('kepala.approval');
Route::post(
    '/kepala/approval/{id}/approve',
    [KepalaPustakawanController::class,'approveBook']
)->name('kepala.approval.approve');

Route::post(
    '/kepala/approval/{id}/reject',
    [KepalaPustakawanController::class,'rejectBook']
)->name('kepala.approval.reject');
//LAPORAN KEPALA PUSTAKAWAN
Route::get(
    '/kepala/laporan',
    [KepalaPustakawanController::class,'laporan']
)->name('kepala.laporan');
Route::get(
    '/kepala/laporan/pdf',
    [KepalaPustakawanController::class,'downloadLaporanPdf']
)->name('kepala.laporan.pdf');
//PROFIL KEPALA PUSTAKAWAN
Route::get(
    '/kepala/profile',
    [KepalaPustakawanController::class,'profile']
)->name('kepala.profile');
Route::get(
    '/kepala/notifications/clear',
    [KepalaPustakawanController::class,'clearNotifications']
)->name('kepala.notification.clear');
});