<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Import Gate
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void

{
    // Gate untuk Admin (Akses CRUD) 
    Gate::define('is-admin', function (User $user) {
        return $user->role === 'admin';
    });

    // Gate untuk Pelanggan (Akses Transaksi) 
    Gate::define('is-pelanggan', function (User $user) {
        return $user->role === 'pelanggan';
    });
}
}
