@extends('layouts.admin')

@section('content')
<!-- Form untuk mengedit Barang -->
<div class="container card p-4 shadow-sm">
    <h1 class="mb-4">Edit Barang</h1>
    <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" required>
            @if ($errors->has('nama'))
                <small class="text-danger">{{ $errors->first('nama') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="jenis">Jenis Barang</label>
            <select class="form-control" id="jenis" name="jenis" required>
                <option value="">-- Pilih Jenis Barang --</option>
                <option value="A" {{ old('jenis', $barang->jenis) == 'A' ? 'selected' : '' }}>Jenis A</option>
                <option value="B" {{ old('jenis', $barang->jenis) == 'B' ? 'selected' : '' }}>Jenis B</option>
                <option value="C" {{ old('jenis', $barang->jenis) == 'C' ? 'selected' : '' }}>Jenis C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="idsatuan">Satuan</label>
            <select class="form-control" id="idsatuan" name="idsatuan" required>
                <option value="">-- Pilih Satuan --</option>
                @foreach ($satuans as $satuan)
                    <option value="{{ $satuan->idsatuan }}"
                        {{ old('idsatuan', $barang->idsatuan) == $satuan->idsatuan ? 'selected' : '' }}>
                        {{ $satuan->nama_satuan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga"
                value="{{ old('harga', $barang->harga) }}" required>
            @if ($errors->has('harga'))
                <small class="text-danger">{{ $errors->first('harga') }}</small>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
