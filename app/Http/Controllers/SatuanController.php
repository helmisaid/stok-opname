<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel satuan
        $satuans = DB::select('SELECT * FROM satuan');

        // Menampilkan halaman index dengan data satuan
        return view('satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_satuan' => 'required|max:45',
            'status' => 'required|in:1,0' // Validasi status: 1 untuk aktif, 0 untuk inaktif
        ]);

        // Mengambil nilai dari validated data
        $namaSatuan = $validatedData['nama_satuan'];
        $status = $validatedData['status'];

        // Melakukan insert ke tabel satuan menggunakan raw query
        DB::statement('INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)', [
            $namaSatuan,
            $status
        ]);

        // Redirect ke halaman daftar satuan dengan pesan sukses
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }


    public function edit($idsatuan)
    {
        // Mengambil data satuan berdasarkan ID menggunakan raw query
        $satuan = DB::select('SELECT * FROM satuan WHERE idsatuan = ?', [$idsatuan]);

        // Jika satuan tidak ditemukan
        if (empty($satuan)) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak ditemukan.');
        }

        // Mengirim data satuan ke view edit
        return view('satuan.edit', ['satuan' => $satuan[0]]);
    }

    // Mengupdate data satuan ke database
    public function update(Request $request, $idsatuan)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_satuan' => 'required|max:45',
            'status' => 'required|in:1,0' // Validasi status: 1 untuk aktif, 0 untuk inaktif
        ]);

        // Mengambil nilai dari validated data
        $namaSatuan = $validatedData['nama_satuan'];
        $status = $validatedData['status'];

        // Melakukan update data satuan menggunakan raw query
        DB::statement('UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?', [
            $namaSatuan,
            $status,
            $idsatuan
        ]);

        // Redirect ke halaman daftar satuan dengan pesan sukses
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }


    // Menghapus satuan dari database
    public function destroy($idsatuan)
    {
        // Menghapus satuan berdasarkan ID menggunakan raw query
        DB::statement('DELETE FROM satuan WHERE idsatuan = ?', [$idsatuan]);

        // Redirect ke halaman daftar satuan dengan pesan sukses
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
