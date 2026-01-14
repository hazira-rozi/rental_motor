@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Motor</h1>
        <a href="{{ route('motor.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('motor.update', $motor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Merk/Tipe Motor</label>
                    <input type="text" name="merk" class="form-control" value="{{ $motor->merk }}" required>
                </div>
                <div class="form-group">
                    <label>Nomor Polisi (Nopol)</label>
                    <input type="text" name="nopol" class="form-control" value="{{ $motor->nopol }}" required>
                </div>
                <div class="form-group">
                    <label>Harga Sewa per Hari (Rp)</label>
                    <input type="number" name="harga_sewa" class="form-control" value="{{ $motor->harga_sewa }}" required>
                </div>
                <div class="form-group">
                    <label>Status Unit</label>
                    <select name="status" class="form-control">
                        <option value="tersedia" {{ $motor->status == 'tersedia' ? 'selected' : '' }}>TERSEDIA</option>
                        <option value="booking" {{ $motor->status == 'booking' ? 'selected' : '' }}>BOOKING</option>
                        <option value="disewa" {{ $motor->status == 'disewa' ? 'selected' : '' }}>DISEWA</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning text-dark">Update Data</button>
            </form>
        </div>
    </div>
</div>
@endsection