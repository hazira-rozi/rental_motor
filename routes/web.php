<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Rental Motor (UKK RPL 2026)
|--------------------------------------------------------------------------
*/

// --- GRUP GUEST (Halaman sebelum login) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// --- GRUP AUTH (Halaman setelah login) ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ====================================================
    // AREA ADMIN (Akses khusus Admin)
    // ====================================================
    Route::middleware('can:is-admin')->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');

        // 1. MANAJEMEN DATA MASTER
        // Resource Motor
        Route::resource('motor', MotorController::class);
        
        // Manajemen User (Admin & Pelanggan)
        // Route khusus untuk melihat daftar pelanggan (sesuai request sebelumnya)
        Route::get('/user', [UserController::class, 'index'])->name('users.index'); 
        Route::resource('user', UserController::class)->except(['index']); // Resource sisanya

        // 2. MANAJEMEN TRANSAKSI / RENTAL
        // List Peminjaman (Utama)
        Route::get('/rentals', [RentalController::class, 'index'])->name('admin.rentals');
        
        // Laporan (Bisa diarahkan ke index yang sama atau method khusus cetak)
        Route::get('/laporan', [RentalController::class, 'index'])->name('laporan');

        // Aksi Approval & Pengembalian (Gunakan PATCH agar aman)
        Route::patch('/rentals/{id}/approve', [RentalController::class, 'approve'])->name('admin.rentals.approve');
        Route::patch('/rentals/{id}/reject', [RentalController::class, 'reject'])->name('admin.rentals.reject');
        
        // Route untuk mengembalikan motor (sesuai method di controller: returnMotor)
        Route::get('/rentals/{id}/return', [RentalController::class, 'returnMotor'])->name('rentals.return');

        // CRUD Transaksi Manual (Opsional jika admin ingin input manual)
        Route::get('/rentals/create', [RentalController::class, 'create'])->name('admin.rentals.create');
        Route::post('/rentals', [RentalController::class, 'storeAdmin'])->name('admin.rentals.store');
        Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('admin.rentals.destroy');

        //Laporan
        Route::get('/laporan', [RentalController::class, 'laporan'])->name('admin.rentals.laporan');
        Route::get('/rentals/{id}/struk', [RentalController::class, 'printStruk'])->name('admin.rentals.struk');
    });


    // ====================================================
    // AREA PELANGGAN (Akses khusus Pelanggan)
    // ====================================================
    Route::middleware('can:is-pelanggan')->prefix('user')->group(function () {
        
        // Dashboard (Katalog Motor)
        Route::get('/dashboard', [RentalController::class, 'userDashboard'])->name('user.dashboard');
        
        // Riwayat Transaksi Saya
        Route::get('/history', [RentalController::class, 'history'])->name('user.history');
        
        // Proses Sewa (Action Form)
        // Penting: Mengarah ke method 'pinjam' di controller
        Route::post('/rental/pinjam', [RentalController::class, 'pinjam'])->name('rental.pinjam');
        
        // Detail Transaksi
        Route::get('/transaksi/{id}', [RentalController::class, 'show'])->name('rental.show');

     

        Route::get('/riwayat/{id}/struk', [RentalController::class, 'printStruk'])->name('user.struk');
    });
});