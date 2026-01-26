@extends('layouts.app')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Sewa Saya</h6>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Motor</th>
                        <th>Tgl Pinjam</th>
                        <th>Lama Sewa</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myRentals as $rental)
                        <tr>
                            <td>
                                <strong>{{ $rental->motor->merk }}</strong><br>
                                <small>{{ $rental->motor->nopol }}</small>
                            </td>
                            <td>{{ date('d M Y', strtotime($rental->tgl_pinjam)) }}</td>
                            <td>
                                {{-- STATUS BADGE --}}
                                @if ($rental->status_transaksi == 'menunggu')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($rental->status_transaksi == 'proses')
                                    <span class="badge badge-primary">Dipinjam</span>
                                @elseif($rental->status_transaksi == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($rental->status_transaksi == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                {{-- LOGIKA TOMBOL CETAK --}}
                                @if ($rental->status_transaksi == 'selesai')
                                    {{-- Tampilkan Total Bayar --}}
                                    <div class="mb-1 text-success font-weight-bold">
                                        Rp {{ number_format($rental->total_bayar) }}
                                    </div>
                                    {{-- Tombol Cetak --}}
                                    <a href="{{ route('user.struk', $rental->id) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-print"></i> Struk
                                    </a>
                                @elseif($rental->status_transaksi == 'proses')
                                    <small class="text-muted">Sedang berjalan</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada riwayat sewa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
