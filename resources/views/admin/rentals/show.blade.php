@extends('layouts.app')
@section('content')
<div class="card shadow col-lg-8 mx-auto">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #{{ $rental->id }}</h6>
        <button onclick="window.print()" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i> Cetak Struk</button>
    </div>
    <div class="card-body" id="printable">
        <div class="text-center">
            <h3>STRUK RENTAL MOTOR</h3>
            <hr>
        </div>
        <div class="row">
            <div class="col-6">
                <p><strong>Pelanggan:</strong> {{ $rental->user->name }}</p>
                <p><strong>Motor:</strong> {{ $rental->motor->merk }}</p>
            </div>
            <div class="col-6 text-right">
                <p><strong>Tanggal:</strong> {{ $rental->tgl_pinjam }}</p>
                <p><strong>Status:</strong> {{ strtoupper($rental->status_transaksi) }}</p>
            </div>
        </div>
        <hr>
        <h4 class="text-right">Total: Rp {{ number_format($rental->total_bayar ?? 0) }}</h4>
    </div>
</div>

<style>
@media print {
    .btn, .sidebar, .navbar, .card-header { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection