@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaksi #{{ $rental->id }}</h1>
        <a href="{{ route('admin.laporan') }}" class="btn btn-secondary btn-sm shadow-sm">Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.rentals.update', $rental->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pelanggan</label>
                        <input type="text" class="form-control" value="{{ $rental->user->name }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Unit Motor</label>
                        <select name="motor_id" class="form-control" required>
                            @foreach($motors as $motor)
                                <option value="{{ $motor->id }}" {{ $rental->motor_id == $motor->id ? 'selected' : '' }}>
                                    {{ $motor->merk }} - {{ $motor->nopol }} ({{ $motor->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Pinjam</label>
                        <input type="datetime-local" name="tgl_pinjam" class="form-control" 
                               value="{{ date('Y-m-d\TH:i', strtotime($rental->tgl_pinjam)) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status Transaksi</label>
                        <select name="status_transaksi" class="form-control" required>
                            <option value="menunggu" {{ $rental->status_transaksi == 'menunggu' ? 'selected' : '' }}>MENUNGGU</option>
                            <option value="proses" {{ $rental->status_transaksi == 'proses' ? 'selected' : '' }}>DISEWA</option>
                            <option value="selesai" {{ $rental->status_transaksi == 'selesai' ? 'selected' : '' }}>SELESAI</option>
                            <option value="ditolak" {{ $rental->status_transaksi == 'ditolak' ? 'selected' : '' }}>DITOLAK</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection