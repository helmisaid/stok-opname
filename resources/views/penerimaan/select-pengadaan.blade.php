@extends('layouts.admin')

@section('content')
<div class="container card my-4 p-4 shadow-sm">
    <h1 class="mb-4">Pilih Pengadaan</h1>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Pengadaan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengadaans as $pengadaan)
            <tr>
                <td>{{ $pengadaan->idpengadaan }}</td>
                <td>{{ $pengadaan->status }}</td>
                <td>
                    <a href="{{ route('penerimaan.create', $pengadaan->idpengadaan) }}" class="btn btn-primary btn-sm">Pilih</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
