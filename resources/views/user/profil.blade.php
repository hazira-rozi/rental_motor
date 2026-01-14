@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4 col-md-6">
        <div class="card-body text-center">
            <img class="img-profile rounded-circle mb-3" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" style="width: 100px;">
            <form action="{{ route('user.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group text-left">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="form-group text-left">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="form-group text-left">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                </div>
                <div class="form-group text-left">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Profil</button>
            </form>
        </div>
    </div>
</div>
@endsection