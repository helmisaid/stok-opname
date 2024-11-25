@extends('layouts.admin')

@section('content')
<!-- Form untuk menambahkan Barang -->
<div class="container card p-4 shadow-sm">
    <h1 class="mb-4">Tambahkan Barang</h1>
    <form action="{{ route('barang.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama barang" required>
            @if ($errors->has('nama'))
                <small class="text-danger">{{ $errors->first('nama') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="jenis">Jenis Barang</label>
            <select class="form-control" id="jenis" name="jenis" required>
                <option value="">-- Pilih Jenis Barang --</option>
                <option value="A">Jenis A</option>
                <option value="B">Jenis B</option>
                <option value="C">Jenis C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="idsatuan">Satuan</label>
            <select class="form-control" id="idsatuan" name="idsatuan" required>
                <option value="">-- Pilih Satuan --</option>
                <!-- Ambil data satuan dari controller -->
                @foreach ($satuans as $satuan)
                    <option value="{{ $satuan->idsatuan }}">{{ $satuan->nama_satuan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan harga barang" required>
            @if ($errors->has('harga'))
                <small class="text-danger">{{ $errors->first('harga') }}</small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Tabel untuk menampilkan data Barang -->
<div class="container card my-5 p-4 shadow-sm">
    <h1 class="mb-4">Data Barang</h1>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>ID Satuan</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Stok Terbaru</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $barang)
            <tr>
                <td>{{ $barang->idbarang }}</td>
                <td>{{ $barang->nama }}</td>
                <td>{{ $barang->jenis }}</td>
                <td>{{ $barang->idsatuan }}</td>
                <td>{{ $barang->status }}</td>
                <td>{{ number_format($barang->harga, 0, ',', '.') }}</td>
                <td>{{ $barang->stock ?? 0 }}</td>
                <td>
                    <a href="{{ route('barang.edit', $barang->idbarang) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('barang.destroy', $barang->idbarang) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
