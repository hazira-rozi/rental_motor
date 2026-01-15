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

### Clone Repository
```bash
git clone [https://github.com/hazira-rozi/rental-motor-ukk.git](https://github.com/hazira-rozi/rental-motor-ukk.git)

