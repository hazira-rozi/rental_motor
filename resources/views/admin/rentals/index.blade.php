@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Manajemen Transaksi Rental</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Peminjaman</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Pelanggan</th>
                            <th>Unit Motor</th>
                            <th>Status & Biaya</th>
                            <th style="width: 20%;">Aksi (Pilihan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $key => $rental)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            
                            {{-- KOLOM PELANGGAN --}}
                            <td>
                                <strong>{{ $rental->user->name }}</strong><br>
                                <small class="text-muted">
                                    {{ date('d M Y', strtotime($rental->tgl_pinjam)) }}
                                </small>
                            </td>

                            {{-- KOLOM MOTOR --}}
                            <td>
                                {{ $rental->motor->merk }} 
                                <span class="badge badge-secondary">{{ $rental->motor->nopol }}</span>
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td>
                                @if($rental->status_transaksi == 'menunggu')
                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                @elseif($rental->status_transaksi == 'proses')
                                    <span class="badge badge-primary">Sedang Dipinjam</span>
                                    <br><small>Sejak: {{ date('d/m', strtotime($rental->tgl_pinjam)) }}</small>
                                @elseif($rental->status_transaksi == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                    <br><strong class="text-success">Total: Rp {{ number_format($rental->total_bayar) }}</strong>
                                @elseif($rental->status_transaksi == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI (LOGIKA UTAMA) --}}
                            <td>
                                {{-- KASUS 1: STATUS MENUNGGU (Butuh Persetujuan) --}}
                                @if($rental->status_transaksi == 'menunggu')
                                    <div class="d-flex">
                                        <form action="{{ route('admin.rentals.approve', $rental->id) }}" method="POST" class="mr-1">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success btn-sm" title="Terima" onclick="return confirm('Setujui peminjaman ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.rentals.reject', $rental->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-danger btn-sm" title="Tolak" onclick="return confirm('Tolak peminjaman ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>

                                {{-- KASUS 2: STATUS PROSES (Motor harus dikembalikan) --}}
                                @elseif($rental->status_transaksi == 'proses')
                                    <a href="{{ route('rentals.return', $rental->id) }}" 
                                       class="btn btn-info btn-sm btn-block"
                                       onclick="return confirm('Proses pengembalian motor? Status akan menjadi SELESAI.')">
                                        <i class="fas fa-undo"></i> Proses Kembali
                                    </a>

                                {{-- KASUS 3: STATUS SELESAI (Bisa Cetak Struk) --}}
                                @elseif($rental->status_transaksi == 'selesai')
                                    {{-- TOMBOL CETAK STRUK --}}
                                    <a href="{{ route('admin.rentals.struk', $rental->id) }}" 
                                       target="_blank" 
                                       class="btn btn-warning btn-sm btn-block text-dark font-weight-bold">
                                        <i class="fas fa-print"></i> CETAK STRUK
                                    </a>

                                {{-- KASUS 4: DITOLAK --}}
                                @else
                                    <button class="btn btn-secondary btn-sm btn-block" disabled>Closed</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection