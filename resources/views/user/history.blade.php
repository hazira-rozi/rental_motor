@section('content')
<div class="card shadow mb-4">
    <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Riwayat Sewa Saya</h6></div>
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
                @foreach($myRentals as $rental)
                <tr>
                    <td>{{ $rental->motor->merk }}</td>
                    <td>{{ date('d/m/Y', strtotime($rental->tgl_pinjam)) }}</td>
                    <td>{{ $rental->lama_sewa ?? 'Sedang Berjalan' }}</td>
                    <td>Rp {{ number_format($rental->total_bayar) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection