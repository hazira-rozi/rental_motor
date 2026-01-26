@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
        <h1 class="h3 mb-0 text-gray-800">Laporan Keuangan & Transaksi</h1>
        
        <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak Laporan
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 no-print">
            <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Transaksi Selesai</h6>
        </div>
        <div class="card-body">
            
            <div class="mb-3 d-none d-print-block">
                <h3>Laporan Rental Motor</h3>
                <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Motor</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Lama</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPemasukan = 0; @endphp
                        @forelse($rentals as $key => $rental)
                            @php $totalPemasukan += $rental->total_bayar; @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $rental->user->name }}
                                </td>
                                <td>
                                    {{ $rental->motor->merk }} 
                                    <small>({{ $rental->motor->nopol }})</small>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($rental->tgl_pinjam)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($rental->tgl_kembali)) }}</td>
                                <td>{{ $rental->lama_sewa }} Hari</td>
                                <td align="right">Rp {{ number_format($rental->total_bayar) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data transaksi yang selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold bg-light">
                            <td colspan="6" class="text-right">TOTAL PENDAPATAN:</td>
                            <td align="right">Rp {{ number_format($totalPemasukan) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-none d-print-block mt-5">
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4 text-center">
                        <p>Mengetahui,<br>Pemilik Rental</p>
                        <br><br><br>
                        <p>( .................................... )</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan Sidebar, Navbar, dan Tombol saat print */
        .no-print, .sidebar, nav, footer {
            display: none !important;
        }
        /* Tampilkan elemen khusus print */
        .d-print-block {
            display: block !important;
        }
        /* Atur lebar card agar full kertas */
        .card {
            border: none;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0;
        }
    }
</style>
@endsection