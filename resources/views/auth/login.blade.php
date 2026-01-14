@extends('layouts.auth')

@section('content')
<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
                <p>Aplikasi Rental Motor UKK 2026</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success small">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger small">
                    Username atau Password salah.
                </div>
            @endif

            <form class="user" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" class="form-control form-control-user" placeholder="Masukkan Username..." required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login Sekarang
                </button>
            </form>
            <hr>
            <div class="text-center">
                <a class="small" href="{{ route('register') }}">Belum punya akun? Daftar Anggota!</a>
            </div>
        </div>
    </div>
</div>
@endsection