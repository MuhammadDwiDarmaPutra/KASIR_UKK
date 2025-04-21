# Aplikasi Kasir - Ujikom 2025

Aplikasi ini merupakan sistem kasir berbasis web yang dibuat untuk memenuhi tugas Uji Kompetensi Keahlian (UKK) tahun 2025. Aplikasi ini memungkinkan admin dan kasir untuk mengelola data produk, transaksi pembelian, serta mencetak laporan pembayaran dalam bentuk PDF atau Excel.

## 📌 Fitur Utama

- Login multi-level (Admin & Kasir)
- CRUD Data Produk
- CRUD Data User / Petugas
- Input Transaksi Pembelian
- Cetak Struk Pembayaran
- Export Laporan ke PDF dan Excel
- Dashboard Ringkasan Transaksi

## 🛠️ Teknologi yang Digunakan

- **Laravel** (Framework PHP)
- **PHP** 8.x
- **MySQL** / MariaDB
- **Bootstrap 5** (Frontend UI)
- **Blade Template Engine**
- **Laravel DOMPDF** (Export PDF)
- **Laravel Excel** (Export Excel)

## 🚀 Cara Menjalankan Project

1. Clone repository ini
2. Jalankan `composer install`
3. Salin file `.env.example` menjadi `.env`
4. Sesuaikan konfigurasi database di file `.env`
5. Jalankan perintah berikut:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed (jika ada data awal)
php artisan serve
