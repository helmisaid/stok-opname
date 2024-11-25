@extends('layouts.admin')

@section('content')
<div class="container card p-4 my-5 shadow-sm">
    <h1>Edit Role</h1>

    <!-- Form untuk mengedit vendor -->
    <form action="{{ route('role.update', $role->idrole) }}" method="POST">
        @csrf <!-- Token untuk keamanan -->
        @method('PUT') <!-- Menentukan method PUT untuk update -->

        <div class="form-group">
            <label for="nama_role">Nama Role</label>
            <input type="text" class="form-control @error('nama_role') is-invalid @enderror" id="nama_role" name="nama_role" value="{{ old('nama_role', $role->nama_role) }}" required>
            @error('nama_role')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
