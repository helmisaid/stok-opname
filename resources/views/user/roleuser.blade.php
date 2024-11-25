@extends('layouts.admin')

@section('content')
<div class="container card p-4 ">
    <h1 class="">Tambahkan Role</h1>
    <form action="{{ route('role.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_role">Nama Role</label>
            <input type="text" class="form-control" id="nama_role" name="nama_role" placeholder="Masukkan nama role" required>
            @if ($errors->has('nama_role'))
                <small class="text-danger">{{ $errors->first('nama_role') }}</small>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

</div>

<div class="container card mb-5">
    <h1 class="my-2">Data Role</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped  table-bordered mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Role</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <th scope="row">{{ $role->idrole }}</th>
                <td>{{ $role->nama_role }}</td>
                <td>
                    <a href="{{ route('role.edit', $role->idrole) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('role.destroy', $role->idrole) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus role ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
