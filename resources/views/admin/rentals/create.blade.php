@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi Baru</h1>
        <a href="{{ route('admin.laporan') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.rentals.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pilih Pelanggan</label>
                        <select name="user_id" class="form-control select2" required>
                            <option value="">-- Cari Pelanggan --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Pilih Motor (Tersedia)</label>
                        <select name="motor_id" class="form-control" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach($motors as $motor)
                                <option value="{{ $motor->id }}">{{ $motor->merk }} - {{ $motor->nopol }} (Rp {{ number_format($motor->harga_sewa) }}/hari)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Pinjam</label>
                        <input type="datetime-local" name="tgl_pinjam" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Mulai Sewa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection