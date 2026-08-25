<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            color: #000;
            font-size: 14px;
            line-height: 1.7;
        }

        .logo {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo img {
            width: 90px;
            height: auto;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 15px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td {
            padding: 5px;
            vertical-align: top;
        }

        .content {
            text-align: justify;
            margin-top: 20px;
        }

        .signature {
            width: 250px;
            float: right;
            text-align: center;
            margin-top: 60px;
        }

        .qr {
            margin-top: 170px;
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
    </style>

</head>

<body>

    <div class="logo">

        {{-- Ganti dengan logo sekolahmu 
        //<img src="{{ public_path('images/logo.png') }}">--}}

    </div>

    <div class="title">

        SURAT KETERANGAN BEBAS PUSTAKA

    </div>

    <div class="subtitle">

        SMA Negeri 3 Padang

    </div>

    <p>

        Yang bertanda tangan di bawah ini menerangkan bahwa:

    </p>

    <table>

        <tr>
            <td width="160">Nama</td>
            <td width="10">:</td>
            <td>{{ $user->name }}</td>
        </tr>

        <tr>
            <td>NIS / NIP</td>
            <td>:</td>
            <td>{{ $user->nis_nip }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>:</td>
            <td>{{ ucfirst($user->role) }}</td>
        </tr>

    </table>

    <div class="content">

        Berdasarkan data pada Sistem Informasi Perpustakaan (SISPUS),
        yang bersangkutan telah menyelesaikan seluruh kewajiban
        administrasi perpustakaan, tidak memiliki buku yang sedang
        dipinjam, tidak memiliki pengembalian yang menunggu verifikasi,
        serta tidak memiliki laporan buku hilang.

        <br><br>

        Dengan demikian yang bersangkutan dinyatakan:

        <br><br>

        <center>

            <strong style="font-size:18px;">

                BEBAS PUSTAKA

            </strong>

        </center>

    </div>

    <div style="margin-top:70px;">

    <table style="width:100%; text-align:center;">

        <tr>

            <td>

                Mengetahui,

                <br>

                Kepala Perpustakaan

            </td>

            <td>

                Padang,
                {{ now()->translatedFormat('d F Y') }}

                <br><br>

                Pustakawan

            </td>

        </tr>

        <tr>

            <td style="height:90px;">

                {{-- Scan tanda tangan Kepala Perpustakaan nanti di sini --}}

            </td>

            <td>

                {{-- Scan tanda tangan Pustakawan nanti di sini --}}

            </td>

        </tr>

        <tr>

            <td>

                <strong>

                    (...............................)

                </strong>

            </td>

            <td>

                <strong>

                    (...............................)

                </strong>

            </td>

        </tr>

    </table>

</div>

  
    <div class="footer">

        Dokumen ini dibuat secara otomatis oleh Sistem Informasi
        Perpustakaan (SISPUS) SMA Negeri 3 Padang.

    </div>

</body>

</html>