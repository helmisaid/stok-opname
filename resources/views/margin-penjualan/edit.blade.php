@extends('layouts.admin')

@section('content')
<!-- Form untuk mengedit Margin Penjualan -->
<div class="container card p-4 shadow-sm">
    <h1 class="mb-4">Edit Margin Penjualan</h1>
    <form action="{{ route('marginpenjualan.update', $margin_penjualan->idmargin_penjualan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="persen">Persen Margin Penjualan</label>
            <input type="number" class="form-control" id="persen" name="persen" value="{{ old('persen', $margin_penjualan->persen) }}" required>
            @if ($errors->has('persen'))
                <small class="text-danger">{{ $errors->first('persen') }}</small>
            @endif
        </div>

        <div class="form-group">
            <label for="status">Status Margin Penjualan</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1" {{ $margin_penjualan->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $margin_penjualan->status == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
