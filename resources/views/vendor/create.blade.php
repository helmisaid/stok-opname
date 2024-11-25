@extends('layouts.admin')

@section('content')
<div class="container card p-4 m-6 shadow-sm">
    <h1 class="mb-4">Tambahkan Vendor</h1>
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
@endsection
