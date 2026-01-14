@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Motor</th>
                    <th>Lama Sewa</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $r)
                <tr>
                    <td>{{ $r->user->name }}</td>
                    <td>{{ $r->motor->merk }}</td>
                    <td>{{ $r->lama_sewa ?? '-' }} Hari</td>
                    <td>Rp {{ number_format($r->total_bayar) }}</td>
                    <td><span class="badge badge-success">{{ $r->status_transaksi }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection