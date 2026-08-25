<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# SISPUS — Sistem Informasi Perpustakaan

SISPUS (Sistem Informasi Perpustakaan) merupakan sistem informasi berbasis
web dan mobile yang dikembangkan untuk mendukung pengelolaan perpustakaan,
mulai dari pengelolaan data buku hingga proses peminjaman dan pengembalian.

## Overview

SISPUS terdiri dari aplikasi web sebagai sisi pengelolaan sistem dan
aplikasi mobile sebagai sisi pengguna. Keduanya terintegrasi melalui
REST API untuk memungkinkan pertukaran data antara aplikasi dan server.

## Features

### Web

- Pengelolaan data pengguna
- Pengelolaan kategori buku
- Pengelolaan buku dan eksemplar
- Pengelolaan transaksi peminjaman
- Pengelolaan pengembalian buku
- Pengelolaan data perpustakaan
- Dashboard dan informasi statistik

### Mobile

- Autentikasi pengguna
- Menampilkan katalog buku
- Pencarian dan informasi buku
- Peminjaman buku
- Riwayat peminjaman
- QR Code
- Notifikasi
- Rekomendasi buku

### Recommendation System

Sistem menerapkan algoritma **Apriori** untuk menganalisis pola
peminjaman buku. Hasil analisis digunakan untuk menemukan keterkaitan
antarbuku dan menghasilkan rekomendasi berdasarkan pola peminjaman.

## Technology Stack

### Web

- Laravel
- PHP
- MySQL
- Blade
- REST API

### Mobile

- Flutter
- Dart
- Firebase

### Design

- Figma
https://www.figma.com/design/schNPmBV0bXHH9GRQnvIld/TA?node-id=0-1&t=YS3085oASO34m0OB-1

## System Architecture

SISPUS menggunakan arsitektur yang menghubungkan aplikasi web dan
mobile dengan backend melalui REST API.

        ┌──────────────────┐
        │   Web Application │
        │     Laravel      │
        └────────┬─────────┘
                 │
                 │
             REST API
                 │
                 ▼
        ┌──────────────────┐
        │      Backend     │
        │     Laravel      │
        └────────┬─────────┘
                 │
                 ▼
        ┌──────────────────┐
        │     Database     │
        │      MySQL       │
        └────────┬─────────┘
                 ▲
                 │
             REST API
                 │
        ┌────────┴─────────┐
        │ Mobile Application│
        │  Flutter / Dart  │
        └──────────────────┘
#Documentation
Web Application
<img width="733" height="385" alt="image" src="https://github.com/user-attachments/assets/be8773da-91bd-4b77-b233-487adac51d12" />
<img width="827" height="434" alt="image" src="https://github.com/user-attachments/assets/73d9a6bf-cf4a-4620-a730-cab2ddd38d32" />
<img width="754" height="465" alt="image" src="https://github.com/user-attachments/assets/3bb1d970-fa0d-4db0-b2d5-c627947a9f17" />
<img width="754" height="396" alt="image" src="https://github.com/user-attachments/assets/a7045e58-b000-4f0e-82a8-5b4b41048826" />
<img width="737" height="430" alt="image" src="https://github.com/user-attachments/assets/44dd2ff6-e2e0-4825-a22d-22f8af61c4e2" />
<img width="679" height="356" alt="image" src="https://github.com/user-attachments/assets/20f0c0fe-88f7-481e-a21b-f02dc030c01a" />

Mobile Application
<img width="343" height="743" alt="image" src="https://github.com/user-attachments/assets/e5220aaf-da39-49fd-ac5c-d6d9bc97b75e" /><img width="314" height="757" alt="image" src="https://github.com/user-attachments/assets/0f3941b0-7227-438a-91bf-7cd7f4dce882" />
<img width="369" height="800" alt="image" src="https://github.com/user-attachments/assets/f6a77869-e8d0-479f-a714-b70d78745818" />
<img width="474" height="1149" alt="image" src="https://github.com/user-attachments/assets/7e8b8f3a-11f5-499b-a37a-ed05456ceb26" />
<img width="304" height="660" alt="image" src="https://github.com/user-attachments/assets/fd97e720-9742-4171-8cbc-74fba23681b8" />
<img width="404" height="876" alt="image" src="https://github.com/user-attachments/assets/1751e00c-a55d-4834-970e-d1da41aaa4b5" />
<img width="414" height="899" alt="image" src="https://github.com/user-attachments/assets/adb48a8a-d938-495b-9b85-50fb72c33324" />
Project Status

Completed

Developer

Muftia Maulani Nabila

S1 Pendidikan Teknik Informatika
