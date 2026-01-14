<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register'); // Pastikan file view ada di resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed', // Perlu input password_confirmation di view
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return back()->withErrors(['username' => 'Login Gagal.']);
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', [
            'totalMotor' => Motor::count(),
            'totalAnggota' => User::where('role', 'pelanggan')->count(),
            'totalSewa' => Rental::where('status_transaksi', 'proses')->count(),
            'pendapatan' => Rental::where('status_transaksi', 'selesai')->sum('total_bayar'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
