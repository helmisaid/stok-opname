@extends('layouts.admin')

@section('content')
<div class="container card my-4 p-4 shadow-sm">
    <h1 class="mb-4"></h1>

    <!-- Tabel Data Penerimaan -->
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID Pengadaan</th>
                <th>Vendor</th>
                <th>Subtotal</th>
                <th>PPN</th>
                <th>Total</th>
                <th>Status</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengadaans as $pengadaan)
            <tr>
                <td>{{ $pengadaan->idpengadaan }}</td>
                <td>{{ $pengadaan->nama_vendor }}</td>
                <td>{{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</td>
                <td>{{ number_format($pengadaan->ppn, 0, ',', '.') }}</td>
                <td>{{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</td>
                <td>
                    @if ($pengadaan->status == 1)
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Belum Selesai</span>
                    @endif
                </td>
                <td>{{ $pengadaan->nama_user }}</td>
                <td>{{ $pengadaan->timestamp }}</td>
                <td>
                    <!-- Tombol Penerimaan -->
                    <a href="{{ route('penerimaan.create', $pengadaan->idpengadaan) }}" class="btn btn-primary btn-sm">Penerimaan</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
