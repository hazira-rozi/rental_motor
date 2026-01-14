<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Motor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Administrator Toko',
            'username' => 'admin',
            'email' => 'admin@rental.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telp' => '08123456789',
            'alamat' => 'Kantor Pusat Rental'
        ]);

        // 2. Buat Akun Pelanggan (Siswa/User)
        User::create([
            'name' => 'Budi Sudarsono',
            'username' => 'budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_telp' => '08987654321',
            'alamat' => 'Jl. Merdeka No. 10'
        ]);

        // 3. Buat Data Motor Awal
        Motor::create([
            'nopol' => 'B 1234 ABC',
            'merk' => 'Honda Vario 160',
            'harga_sewa' => 100000,
            'status' => 'tersedia'
        ]);

        Motor::create([
            'nopol' => 'B 5678 DEF',
            'merk' => 'Yamaha NMAX',
            'harga_sewa' => 150000,
            'status' => 'tersedia'
        ]);

        Motor::create([
            'nopol' => 'B 9012 GHI',
            'merk' => 'Honda Beat',
            'harga_sewa' => 75000,
            'status' => 'tersedia'
        ]);
    }
}