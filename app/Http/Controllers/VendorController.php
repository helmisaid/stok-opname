<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index() {
        $vendors = DB::select('select * from view_vendor');

        return view('vendor.index', compact('vendors'));
    }

    public function create() {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:Y,N', // Hanya menerima 'Y' atau 'N'
            'status' => 'required|in:A,I' // Hanya menerima 'A' atau 'I'
        ]);

        // Mengambil nilai dari validated data
        $namaVendor = $validatedData['nama_vendor'];
        $badanHukum = $validatedData['badan_hukum'];
        $status = $validatedData['status'];

        // Melakukan insert ke tabel vendor menggunakan raw query
        DB::statement('INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)', [
            $namaVendor,
            $badanHukum,
            $status
        ]);

        // Redirect ke halaman daftar vendor dengan pesan sukses
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function edit($idvendor)
    {
        // Mengambil data vendor berdasarkan ID menggunakan raw query
        $vendor = DB::select('SELECT * FROM vendor WHERE idvendor = ?', [$idvendor]);

        // Jika vendor tidak ditemukan
        if (empty($vendor)) {
            return redirect()->route('vendor.index')->with('error', 'Vendor tidak ditemukan.');
        }

        // Mengirim data vendor ke view edit
        return view('vendor.edit', ['vendor' => $vendor[0]]);
    }

    // Mengupdate data vendor ke database
    public function update(Request $request, $idvendor)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:Y,N', // Hanya menerima 'Y' atau 'N'
            'status' => 'required|in:A,I' // Hanya menerima 'A' atau 'I'
        ]);

        // Mengambil nilai dari validated data
        $namaVendor = $validatedData['nama_vendor'];
        $badanHukum = $validatedData['badan_hukum'];
        $status = $validatedData['status'];

        // Melakukan update data vendor menggunakan raw query
        DB::statement('UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?', [
            $namaVendor,
            $badanHukum,
            $status,
            $idvendor
        ]);

        // Redirect ke halaman daftar vendor dengan pesan sukses
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    // Menghapus vendor dari database
    public function destroy($idvendor)
    {
        // Menghapus vendor berdasarkan ID menggunakan raw query
        DB::statement('DELETE FROM vendor WHERE idvendor = ?', [$idvendor]);

        // Redirect ke halaman daftar vendor dengan pesan sukses
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
