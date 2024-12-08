@extends('layouts.admin')

@section('content')
<div class="container card my-4 p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Penerimaan Barang</h1>
        <span class="badge bg-primary fs-5">ID Pengadaan: {{ $idPengadaan }}</span>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- Form Penerimaan Barang -->
    <h2 class="mt-5">Tambah Penerimaan</h2>
    <form id="penerimaanForm" action="{{ route('penerimaan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_pengadaan" value="{{ $idPengadaan }}">

        <!-- Tabel Barang yang Dapat Diterima -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Pesan</th>
                    <th>Total Diterima</th>
                    <th>Sisa</th>
                    <th>Jumlah Terima</th>
                    <th>Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                @if ($barangPengadaan && count($barangPengadaan) > 0)
                    @foreach ($barangPengadaan as $barang)
                    <tr>
                        <td>
                            <input type="checkbox" name="barang[{{ $barang->idbarang }}][terima]" value="1">
                        </td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->jumlah_pesan }}</td>
                        <td>{{ $barang->total_terima ?? 0 }}</td>
                        <td>{{ $barang->sisa }}</td>
                        <td>
                            <input type="number" name="barang[{{ $barang->idbarang }}][jumlah_terima]"
                                   class="form-control"
                                   min="1"
                                   max="{{ $barang->sisa }}"
                                   placeholder="Jumlah Terima">
                        </td>
                        <td>
                            <input type="number" name="barang[{{ $barang->idbarang }}][harga_satuan]"
                                   class="form-control"
                                   value="{{ $barang->harga }}" readonly>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada barang untuk pengadaan ini.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
    </form>

    <!-- History Penerimaan -->
    <h2 class="mt-5">History Penerimaan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Penerimaan</th>
                <th>Tanggal Penerimaan</th>
                <th>Nama Barang</th>
                <th>Jumlah Terima</th>
                <th>Harga Satuan</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($historyPenerimaan as $history)
            <tr>
                <td>{{ $history->idpenerimaan }}</td>
                <td>{{ $history->created_at }}</td>
                <td>{{ $history->nama_barang }}</td>
                <td>{{ $history->jumlah_terima }}</td>
                <td>{{ number_format($history->harga_satuan_terima, 0, ',', '.') }}</td>
                <td>{{ number_format($history->sub_total_terima, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada penerimaan untuk pengadaan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
