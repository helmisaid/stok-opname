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
            'jenis' => 'required|in:A,B,C', 
            'nama' => 'required|max:45',
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'harga' => 'required|numeric|min:0',
        ]);

        // Panggil Stored Procedure
        DB::statement("CALL InsertBarang(?, ?, ?, ?, ?)", [
            $request->input('jenis'),
            $request->input('nama'),
            $request->input('idsatuan'),
            1,
            $request->input('harga')
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
{
    $barang = DB::selectOne("SELECT * FROM barang WHERE idbarang = ?", [$id]);

    if (!$barang) {
        return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
    }

    $satuans = DB::select("SELECT * FROM satuan");
    return view('barang.edit', compact('barang', 'satuans'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'jenis' => 'required|in:A,B,C',
        'nama' => 'required|max:45',
        'idsatuan' => 'required|exists:satuan,idsatuan',
        'harga' => 'required|numeric|min:0',
    ]);

    // Cek apakah barang ada
    $barang = DB::selectOne("SELECT * FROM barang WHERE idbarang = ?", [$id]);

    if (!$barang) {
        return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
    }

    // Update data barang dengan query MySQL biasa
    DB::update("
        UPDATE barang
        SET nama = ?, jenis = ?, idsatuan = ?, harga = ?
        WHERE idbarang = ?
    ", [
        $request->input('nama'),
        $request->input('jenis'),
        $request->input('idsatuan'),
        $request->input('harga'),
        $id
    ]);

    return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
}

}
