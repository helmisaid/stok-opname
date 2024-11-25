@extends('layouts.admin')

@section('content')
<div class="container card my-4 p-4 shadow-sm">
    <h1 class="mb-4">History Penerimaan Barang</h1>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID Penerimaan</th>
                <th>Tanggal Penerimaan</th>
                <th>Nama Barang</th>
                <th>Jumlah Terima</th>
                <th>Harga Satuan</th>
                <th>Sub Total</th>
                <th>Vendor</th>
                <th>ID Pengadaan</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historyPenerimaan as $history)
            <tr>
                <td>{{ $history->idpenerimaan }}</td>
                <td>{{ $history->tanggal_penerimaan }}</td>
                <td>{{ $history->nama_barang }}</td>
                <td>{{ $history->jumlah_terima }}</td>
                <td>{{ number_format($history->harga_satuan_terima, 0, ',', '.') }}</td>
                <td>{{ number_format($history->sub_total_terima, 0, ',', '.') }}</td>
                <td>{{ $history->nama_vendor }}</td>
                <td>{{ $history->idpengadaan }}</td>
                <td>{{ $history->nama_user }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
