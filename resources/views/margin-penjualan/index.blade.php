@extends('layouts.admin')

@section('content')
<!-- Form untuk menambahkan Margin Penjualan -->
<div class="container card p-4 shadow-sm">
    <h1 class="mb-4">Tambahkan Margin Penjualan</h1>
    <form action="" method="POST">
        @csrf

        <div class="form-group">
            <label for="persen">Persen Margin Penjualan</label>
            <input type="number" class="form-control" id="persen" name="persen" placeholder="Masukkan persen margin" required>
            @if ($errors->has('persen'))
                <small class="text-danger">{{ $errors->first('persen') }}</small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Tabel untuk menampilkan data Margin Penjualan -->
<div class="container card my-5 p-4 shadow-sm">
    <h1 class="mb-4">Data Margin Penjualan</h1>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Persen Margin</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($margin_penjualans as $margin_penjualan)
            <tr>
                <th scope="row">{{ $margin_penjualan->idmargin_penjualan }}</th>
                <td>{{ $margin_penjualan->persen }}</td>
                <td>
                    <a href="" class="btn btn-warning btn-sm">Edit</a>
                    <form action="" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus margin penjualan ini?')">Delete</button>
                    </form>
                    {{-- <a href="{{ route('margin_penjualan.edit', $margin_penjualan->idmargin_penjualan) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('margin_penjualan.destroy', $margin_penjualan->idmargin_penjualan) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus margin penjualan ini?')">Delete</button>
                    </form> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection