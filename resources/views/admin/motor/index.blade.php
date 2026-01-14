@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Inventaris Motor</h1>
    <a href="{{ route('motor.create') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Motor Baru
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Merk Motor</th>
                        <th>No. Polisi</th>
                        <th>Harga Sewa/Hari</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($motors as $index => $motor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $motor->merk }}</td>
                        <td><span class="badge badge-dark">{{ $motor->nopol }}</span></td>
                        <td>Rp {{ number_format($motor->harga_sewa, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'tersedia' => 'success',
                                    'disewa' => 'danger',
                                    'booking' => 'warning'
                                ][$motor->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $statusClass }} text-uppercase">
                                {{ $motor->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('motor.destroy', $motor->id) }}" method="POST" onsubmit="return confirm('Hapus motor ini?')">
                                <a href="{{ route('motor.edit', $motor->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection