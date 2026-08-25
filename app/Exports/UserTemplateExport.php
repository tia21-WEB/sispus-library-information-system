<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class UserTemplateExport implements FromArray
{
    public function array(): array
    {
        return [

            [
                'Nama',
                'NIS/NIP',
                'Role',
                'Email',
                'No HP',
                'Alamat'
            ],

            [
                'Ahmad Fauzan',
                '231001',
                'siswa',
                '',
                '08123456789',
                'Padang'
            ],

            [
                'Rina Putri',
                '19871234',
                'guru',
                'rina@gmail.com',
                '081398765432',
                'Padang Barat'
            ]

        ];
    }
}