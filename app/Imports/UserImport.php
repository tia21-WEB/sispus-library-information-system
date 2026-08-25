<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati header
        unset($rows[0]);

        foreach ($rows as $row) {

            // Pastikan jumlah kolom cukup
            if (count($row) < 6) {
                continue;
            }

            $name    = trim($row[0] ?? '');
            $nis     = trim($row[1] ?? '');
            $role    = strtolower(trim($row[2] ?? ''));
            $email   = trim($row[3] ?? '');
            $phone   = trim($row[4] ?? '');
            $address = trim($row[5] ?? '');

            // Nama wajib
            if ($name == '') {
                continue;
            }

            // NIS wajib
            if ($nis == '') {
                continue;
            }

            // Role hanya siswa/guru
            if (!in_array($role, ['siswa', 'guru'])) {
                continue;
            }

            // NIS tidak boleh duplikat
            if (User::where('nis_nip', $nis)->exists()) {
                continue;
            }

            // Email kosong tidak masalah
            // Kalau diisi harus valid
            if ($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            User::create([

                'name' => $name,

                'nis_nip' => $nis,

                'role' => $role,

                'is_active' => true,

                'email' => $email ?: null,

                'phone' => $phone ?: null,

                'address' => $address ?: null,

                'password' => $nis,

                'points' => 0,

                'badge' => 'Bronze',

            ]);
        }
    }
}