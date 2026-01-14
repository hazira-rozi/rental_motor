@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sewa Motor</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <p>Selamat Datang, <strong>{{ Auth::user()->name }}</strong>. Silakan pilih motor yang tersedia di bawah ini.</p>
    </div>
</div>

<div class="row">
    @forelse ($motors as $motor)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ $motor->merk }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $motor->nopol }}</div>
                            <div class="mt-2 text-gray-600 small">Harga: Rp {{ number_format($motor->harga_sewa) }}/hari</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <form action="{{ route('rental.pinjam') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="motor_id" value="{{ $motor->id }}">
                        <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm" 
                                onclick="return confirm('Yakin ingin menyewa motor ini?')">
                            Sewa Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning">Maaf, saat ini tidak ada motor yang tersedia untuk disewa.</div>
        </div>
    @endforelse
</div>
@endsection