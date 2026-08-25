<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    // 1. FUNGSI GENERATE QR (Dipanggil saat Pustakawan menyetujui peminjaman)
    public function approveBorrowing($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        // Generate token unik untuk QR Code pengembalian
        $borrowing->update([
            'status' => 'borrowed',
            'qr_code_token' => Str::random(32), // Token unik 32 karakter
        ]);

        return response()->json(['message' => 'Peminjaman disetujui, QR siap digunakan untuk pengembalian']);
    }

    // 2. FUNGSI VERIFIKASI PENGEMBALIAN & GAMIFIKASI (Pustakawan klik verifikasi)
    public function verifyReturn($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        // A. Update status transaksi
        $borrowing->update([
            'status' => 'returned',
            'return_date' => Carbon::now(),
        ]);

        // B. LOGIKA GAMIFIKASI: Tambah Poin
        $user = User::find($borrowing->user_id);
        $user->increment('points', 10); // Tambah 10 poin per buku

        // C. LOGIKA UPDATE BADGE (Auto-leveling)
        $this->updateBadge($user);

        return response()->json(['message' => 'Buku diterima, poin berhasil ditambahkan!']);
    }

    // 3. FUNGSI AUTO-LEVELING BADGE
    private function updateBadge($user)
    {
        if ($user->points >= 150) {
            $user->badge = 'Cendekiawan Kanza'; // Gold
        } elseif ($user->points >= 50) {
            $user->badge = 'Penjelajah Jendela Dunia'; // Silver
        } else {
            $user->badge = 'Kutu Buku Pemula'; // Bronze
        }
        $user->save();
    }
}