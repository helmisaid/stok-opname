@extends('layouts.admin')

@section('content')
<div class="container card p-4 my-5 shadow-sm">
    <h1>Edit Vendor</h1>

    <!-- Form untuk mengedit vendor -->
    <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
        @csrf <!-- Token untuk keamanan -->
        @method('PUT') <!-- Menentukan method PUT untuk update -->

        <div class="form-group">
            <label for="nama_vendor">Nama Vendor</label>
            <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror" id="nama_vendor" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
            @error('nama_vendor')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="badan_hukum">Badan Hukum</label>
            <input type="text" class="form-control @error('badan_hukum') is-invalid @enderror" id="badan_hukum" name="badan_hukum" value="{{ old('badan_hukum', $vendor->badan_hukum) }}" required>
            @error('badan_hukum')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status', $vendor->status) }}" required>
            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
