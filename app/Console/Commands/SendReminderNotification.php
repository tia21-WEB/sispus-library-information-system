<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Services\FirebaseService;
use Carbon\Carbon;

class SendReminderNotification extends Command
{
    protected $signature = 'notification:reminder';

    protected $description = 'Kirim reminder H-1 pengembalian buku';

    public function handle()
    {
        $borrowings = Borrowing::with('user')
            ->where('status', 'dipinjam')
            ->whereDate('return_date', Carbon::tomorrow())
            ->where('reminder_sent', false)
            ->get();

        foreach ($borrowings as $borrowing) {

            if (!empty($borrowing->user->fcm_token)) {

                FirebaseService::send(
                    $borrowing->user->fcm_token,
                    '⏰ Pengingat Pengembalian',
                    'Besok adalah batas akhir pengembalian buku yang Anda pinjam.'
                );

            }

            $borrowing->update([
                'reminder_sent' => true
            ]);
        }

        $this->info('Reminder selesai dikirim.');

        $blockedBorrowings = Borrowing::with('user')
    ->where('status', 'dipinjam')
    ->whereDate('return_date', '<', today())
    ->where('block_notification_sent', false)
    ->get();

foreach ($blockedBorrowings as $borrowing) {

    if (!empty($borrowing->user->fcm_token)) {

        FirebaseService::send(
            $borrowing->user->fcm_token,
            '🚫 Akun Diblokir',
            'Akun Anda diblokir sementara karena melewati batas waktu pengembalian buku. Silakan segera mengembalikan buku agar dapat melakukan peminjaman kembali.'
        );

    }

    $borrowing->update([
        'block_notification_sent' => true
    ]);
}
    }
}