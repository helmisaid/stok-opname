<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Edit Satuan</title>
</head>
<body>
    <div class="container my-2 pt-5">
        <a href="{{ route('satuan.index') }}" class="btn btn-primary">Back</a>
    </div>

    <!-- Form untuk mengedit data satuan -->
    <div class="container card p-4 m-6 shadow-sm">
        <h1 class="mb-4">Edit Satuan</h1>
        <form action="{{ route('satuan.update', $satuan->idsatuan) }}" method="POST">
            @csrf
            @method('PUT') <!-- Metode PUT untuk update -->

            <div class="form-group">
                <label for="nama_satuan">Nama Satuan</label>
                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" value="{{ $satuan->nama_satuan }}" placeholder="Masukkan nama satuan" required>
                @if ($errors->has('nama_satuan'))
                    <small class="text-danger">{{ $errors->first('nama_satuan') }}</small>
                @endif
            </div>

            <!-- Field untuk status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="1" {{ $satuan->status == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $satuan->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @if ($errors->has('status'))
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                @endif
            </div>

            <button type="submit" class="btn btn-success">Perbarui</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
