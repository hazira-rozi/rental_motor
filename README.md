Markdown# 🛵 Aplikasi Rental Motor (UKK RPL 2026)

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-SB%20Admin%202-563D7C?style=for-the-badge&logo=bootstrap)
![MySQL](https://img.shields.io/badge/Database-MySQL-00758F?style=for-the-badge&logo=mysql)

Sistem Informasi Manajemen Penyewaan Sepeda Motor berbasis Web. Aplikasi ini disusun sebagai proyek **Uji Kompetensi Keahlian (UKK)** Kompetensi Keahlian Rekayasa Perangkat Lunak (RPL) SMKN 1 Singkarak Tahun Pelajaran 2025/2026.

## ⚠️ Disclaimer (Untuk menjaga kerahasiaan soal, proyek ini bukan merupakan jawaban dari paket manapun)
Proyek ini disusun dengan kasus setara dengan soal UKK 2026 dan bukan merupakan bocoran dari paket soal yang telah dirilis oleh Kemdikdasmen

---

## 📋 Fitur Utama

### 1. Hak Akses (Authentication)
* **Login Admin:** Sistem keamanan terenkripsi.
* **Logout:** Dilengkapi konfirmasi modal untuk mencegah kesalahan klik.

### 2. Manajemen Inventaris (Motor)
* **CRUD Data:** Tambah, Edit, Hapus data motor.
* **Status Real-time:** Status motor otomatis berubah menjadi *"Tidak Tersedia"* saat sedang disewa.
* **Validasi:** Mencegah input Nomor Polisi ganda.

### 3. Transaksi & Logika Bisnis
* **Pencatatan Sewa:** Mengaitkan User, Motor, dan Tanggal Pinjam.
* **Pengembalian Cerdas:**
    * Otomatis menghitung durasi sewa (selisih hari).
    * Otomatis menghitung total denda/biaya (Durasi x Harga).
    * Logika *Minimum Charge*: Sewa kurang dari 24 jam tetap dihitung 1 hari.

---

## 🛠️ Persyaratan Sistem

Pastikan komputer Anda memiliki:
* **PHP** >= 8.2
* **Composer**
* **MySQL** (via XAMPP/Laragon)
* **Web Browser** (Chrome/Edge/Firefox)

---

## 🚀 Cara Instalasi (Panduan Siswa)

Ikuti langkah ini untuk menjalankan proyek di komputer lokal:

### 1. Clone Repository
```bash
git clone [https://github.com/hazira-rozi/rental-motor-ukk.git](https://github.com/hazira-rozi/rental-motor-ukk.git)
cd rental-motor-ukk
2. Install LibraryDownload semua dependency Laravel:Bashcomposer install
3. Konfigurasi EnvironmentSalin file konfigurasi dan generate kunci keamanan:Bashcp .env.example .env
php artisan key:generate
4. Setup DatabaseBuka phpMyAdmin.Buat database baru bernama: db_rental_2026.Buka file .env, atur koneksi database:Ini, TOMLDB_DATABASE=db_rental_2026
DB_USERNAME=root
DB_PASSWORD=
5. Migrasi & Data AwalBuat tabel dan akun admin otomatis:Bashphp artisan migrate:fresh --seed
6. Jalankan AplikasiBashphp artisan serve
Buka browser dan akses: http://127.0.0.1:8000👤 Akun Login DefaultGunakan akun berikut untuk masuk ke sistem:RoleEmailPasswordAdministratoradmin@sekolah.idrahasiaCatatan: Password 'rahasia' telah dienkripsi menggunakan Bcrypt di database.📂 Peta File PentingBerikut adalah lokasi file yang sering dimodifikasi:Logika Bisnis (Controller):app/Http/Controllers/RentalController.php (Hitung Denda & Durasi)app/Http/Controllers/MotorController.php (Manajemen Motor)Tampilan (Views):resources/views/layouts/app.blade.php (Layout Utama)resources/views/admin/ (Folder Halaman Admin)Database:database/migrations/ (Struktur Tabel)⚠️ DisclaimerAplikasi ini dibuat untuk tujuan pendidikan dan ujian praktik.SMKN 1 Singkarak - XII RPL