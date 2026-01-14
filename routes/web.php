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

    // Menampilkan & Memproses Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// --- GRUP AUTH (Halaman setelah login) ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- AREA ADMIN (Akses khusus Admin) ---
    Route::middleware('can:is-admin')->prefix('admin')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');

        // CRUD Inventaris Motor (Resourceful)
        Route::resource('motor', MotorController::class);

        // CRUD Anggota/User
        Route::resource('user', UserController::class);

        // PENGELOLAAN TRANSAKSI & LAPORAN
        Route::get('/laporan', [RentalController::class, 'index'])->name('admin.laporan');
        
        // Fitur Create Transaksi Manual oleh Admin
        Route::get('/rentals/create', [RentalController::class, 'create'])->name('admin.rentals.create');
        Route::post('/rentals/store', [RentalController::class, 'storeAdmin'])->name('admin.rentals.store');

        // Aksi Approval & Kontrol Transaksi
        Route::patch('/rentals/{id}/approve', [RentalController::class, 'approve'])->name('admin.rentals.approve');
        Route::patch('/rentals/{id}/reject', [RentalController::class, 'reject'])->name('admin.rentals.reject');
        Route::patch('/rentals/{id}/return', [RentalController::class, 'returnMotor'])->name('admin.rentals.return');
        
        // Detail, Edit & Hapus Transaksi
        Route::get('/rentals/{id}', [RentalController::class, 'show'])->name('admin.rentals.show');
        Route::get('/rentals/{id}/edit', [RentalController::class, 'edit'])->name('admin.rentals.edit');
        Route::put('/rentals/{id}', [RentalController::class, 'update'])->name('admin.rentals.update');
        Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('admin.rentals.destroy');
    });

    // --- AREA PELANGGAN (Akses khusus Pelanggan) ---
    Route::middleware('can:is-pelanggan')->prefix('user')->group(function () {
        
        // Dashboard Pelanggan (List Motor)
        Route::get('/dashboard', [RentalController::class, 'userDashboard'])->name('user.dashboard');
        
        // Riwayat Sewa Pelanggan
        Route::get('/riwayat', [RentalController::class, 'history'])->name('user.history');
        
        // Pengajuan Sewa (Store)
        Route::post('/pinjam', [RentalController::class, 'store'])->name('rental.pinjam');
        
        // Lihat Detail/Struk oleh Pelanggan
        Route::get('/riwayat/{id}', [RentalController::class, 'show'])->name('rental.show');

        Route::get('/profil', [RentalController::class, 'editProfil'])->name('user.profil');
    Route::put('/profil/update', [RentalController::class, 'updateProfil'])->name('user.profil.update');

    });
});