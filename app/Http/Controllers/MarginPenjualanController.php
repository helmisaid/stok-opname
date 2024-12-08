<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MarginPenjualanController extends Controller
{
    public function index()
{
    // Menggunakan raw query untuk mengambil data margin penjualan
    $margin_penjualans = DB::select('SELECT * FROM margin_penjualan');

    return view('margin-penjualan.index', compact('margin_penjualans'));
}

public function store(Request $request)
{
    $userId = Session::get('user.iduser');

    // Validasi input
    $request->validate([
        'persen' => 'required|numeric|min:0',
        'status' => 'required|in:0,1',
    ]);

    // Ambil data dari request
    $persen = $request->input('persen');
    $status = $request->input('status');

    // Query untuk menyisipkan data ke tabel margin_penjualan
    DB::insert("
        INSERT INTO margin_penjualan (persen, status, iduser,  created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())
    ", [$persen, $status, $userId]);

    // Redirect dengan pesan sukses
    return redirect()->route('marginpenjualan.index')->with('success', 'Margin penjualan berhasil ditambahkan.');
}

public function edit($id)
    {
        // Mengambil data margin penjualan berdasarkan ID
        $margin_penjualan = DB::select('SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        // Pastikan data ditemukan
        if (empty($margin_penjualan)) {
            return redirect()->route('marginpenjualan.index')->with('error', 'Data margin penjualan tidak ditemukan.');
        }

        // Mengambil data margin penjualan untuk form edit
        $margin_penjualan = $margin_penjualan[0];

        return view('margin-penjualan.edit', compact('margin_penjualan'));
    }

    // Fungsi untuk memperbarui margin penjualan
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'persen' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        // Ambil data dari request
        $persen = $request->input('persen');
        $status = $request->input('status');

        // Query untuk memperbarui data margin penjualan
        DB::update('
            UPDATE margin_penjualan
            SET persen = ?, status = ?, updated_at = NOW()
            WHERE idmargin_penjualan = ?
        ', [$persen, $status, $id]);

        // Redirect dengan pesan sukses
        return redirect()->route('marginpenjualan.index')->with('success', 'Margin penjualan berhasil diperbarui.');
    }

    // Fungsi untuk menghapus margin penjualan
    public function destroy($id)
    {
        // Query untuk menghapus data margin penjualan berdasarkan ID
        DB::delete('DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        // Redirect dengan pesan sukses
        return redirect()->route('marginpenjualan.index')->with('success', 'Margin penjualan berhasil dihapus.');
    }

}
