@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Motor Baru</h1>
        <a href="{{ route('motor.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('motor.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Merk/Tipe Motor</label>
                    <input type="text" name="merk" class="form-control" placeholder="Contoh: Honda Vario 160" required>
                </div>
                <div class="form-group">
                    <label>Nomor Polisi (Nopol)</label>
                    <input type="text" name="nopol" class="form-control" placeholder="Contoh: B 1234 ABC" required>
                </div>
                <div class="form-group">
                    <label>Harga Sewa per Hari (Rp)</label>
                    <input type="number" name="harga_sewa" class="form-control" placeholder="Contoh: 75000" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Motor</button>
            </form>
        </div>
    </div>
</div>
@endsection