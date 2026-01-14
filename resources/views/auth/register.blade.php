@extends('layouts.auth') {{-- Sesuaikan dengan layout login Anda --}}

@section('content')
<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Daftar Akun Baru!</h1>
            </div>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Alamat Email" required>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Daftar Akun</button>
            </form>
            <hr>
            <div class="text-center">
                <a class="small" href="{{ route('login') }}">Sudah punya akun? Login!</a>
            </div>
        </div>
    </div>
</div>
@endsection