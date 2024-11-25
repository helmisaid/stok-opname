@extends('layouts.admin')

@section('content')
<div class="container card p-4 ">
    <h1 class="">Tambahkan Vendor</h1>
    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_vendor">Nama Vendor</label>
            <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" placeholder="Masukkan nama vendor" required>
            @if ($errors->has('nama_vendor'))
                <small class="text-danger">{{ $errors->first('nama_vendor') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="badan_hukum">Badan Hukum</label>
            <select class="form-control" id="badan_hukum" name="badan_hukum" required>
                <option value="">-- Pilih Badan Hukum --</option>
                <option value="Y">Y (Yayasan)</option>
                <option value="N">N (Perusahaan)</option>
            </select>
            @if ($errors->has('badan_hukum'))
                <small class="text-danger">{{ $errors->first('badan_hukum') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="A">A (Aktif)</option>
                <option value="I">I (Inaktif)</option>
            </select>
            @if ($errors->has('status'))
                <small class="text-danger">{{ $errors->first('status') }}</small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

</div>

<div  class="container card my-5">
    <h1 class="my-2">Data Vendor</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Vendor</th>
                <th scope="col">Badan Hukum</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th> <!-- Tambahkan kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($vendors as $ven)
            <tr>
                <th scope="row">{{ $ven->idvendor }}</th>
                <td>{{ $ven->nama_vendor }}</td>
                <td>{{ $ven->badan_hukum }}</td>
                <td>{{ $ven->status }}</td>
                <td>
                    <a href="{{ route('vendor.edit', $ven->idvendor) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('vendor.destroy', $ven->idvendor) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus vendor ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('jspage')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
@endsection

@section('csspage')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> --}}
@endsection
