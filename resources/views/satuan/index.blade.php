@extends('layouts.admin')

@section('content')
<!-- Form untuk menambahkan Satuan -->
<div class="container card p-4 shadow-sm">
    <h1 class="mb-4">Tambahkan Satuan</h1>
    <form action="{{ route('satuan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_satuan">Nama Satuan</label>
            <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Masukkan nama satuan" required>
            @if ($errors->has('nama_satuan'))
                <small class="text-danger">{{ $errors->first('nama_satuan') }}</small>
            @endif
        </div>

        <!-- Field untuk status -->
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
            @if ($errors->has('status'))
                <small class="text-danger">{{ $errors->first('status') }}</small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Tabel untuk menampilkan data satuan -->
<div class="container card my-5 p-4 shadow-sm">
    <h1 class="mb-4">Data Satuan</h1>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Satuan</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($satuans as $satuan)
            <tr>
                <th scope="row">{{ $satuan->idsatuan }}</th>
                <td>{{ $satuan->nama_satuan }}</td>
                <td>{{ $satuan->status }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <a href="{{ route('satuan.edit', $satuan->idsatuan) }}" class="btn btn-warning btn-sm">Edit</a>

                    <!-- Form untuk tombol Delete -->
                    <form action="{{ route('satuan.destroy', $satuan->idsatuan) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus satuan ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection


