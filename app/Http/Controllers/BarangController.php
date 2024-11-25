<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
{
    $barangs = DB::select("
        SELECT
            barang.idbarang,
            barang.nama,
            barang.jenis,
            barang.idsatuan,
            barang.status,
            barang.harga,
            GetLatestStock(barang.idbarang) AS stock -- Panggil fungsi GetLatestStock
        FROM
            barang
        ORDER BY
            barang.idbarang
    ");

    $satuans = DB::select("SELECT * FROM satuan"); // Ambil data satuan untuk dropdown
    return view('barang.index', compact('barangs', 'satuans'));
}


    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:A,B,C', // Validasi jenis barang
            'nama' => 'required|max:45',
            'idsatuan' => 'required|exists:satuan,idsatuan', // Validasi satuan barang
            'harga' => 'required|numeric|min:0', // Validasi harga
        ]);

        // Panggil Stored Procedure
        DB::statement("CALL InsertBarang(?, ?, ?, ?, ?)", [
            $request->input('jenis'),
            $request->input('nama'),
            $request->input('idsatuan'),
            1, // Status default (aktif)
            $request->input('harga')
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }
}
