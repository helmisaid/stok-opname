<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PengadaanController extends Controller
{
        public function index()
{
    $pengadaans = DB::select("
    SELECT
        p.idpengadaan,
        v.nama_vendor,
        u.username AS nama_user, -- Tambahkan kolom nama user
        p.subtotal_nilai,
        p.ppn,
        p.total_nilai,
        p.status,
        p.timestamp
    FROM pengadaan p
    JOIN vendor v ON p.vendor_idvendor = v.idvendor
    JOIN user u ON p.user_iduser = u.iduser -- Join ke tabel users
");

$vendors = DB::select("SELECT * FROM vendor");
$barangs = DB::select("SELECT * FROM barang");

return view('pengadaan.index', compact('pengadaans', 'vendors', 'barangs'));

}


    public function store(Request $request)
{
    $vendorId = $request->input('vendor_id');
    $items = $request->input('items');
    $userId = Session::get('user.iduser'); // Ambil ID pengguna dari session manual

    $subtotal = 0;

    // Hitung subtotal dari detail_pengadaan
    foreach ($items as $item) {
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $subTotal = $harga * $jumlah;
        $subtotal += $subTotal;
    }

    // Hitung PPN 10% dari subtotal
    $ppn = $subtotal * 0.10;

    // Hitung total nilai (subtotal + ppn)
    $total = $subtotal + $ppn;

    // Insert into pengadaan
    DB::insert("
        INSERT INTO pengadaan (vendor_idvendor, user_iduser, subtotal_nilai, ppn, total_nilai, status, timestamp)
        VALUES (?, ?, ?, ?, ?, '0', NOW())
    ", [$vendorId, $userId, $subtotal, $ppn, $total]);

    $pengadaanId = DB::getPdo()->lastInsertId();

    // Insert detail_pengadaan
    foreach ($items as $item) {
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $subTotal = $harga * $jumlah;

        DB::insert("
            INSERT INTO detail_pengadaan (idpengadaan, idbarang, harga_satuan, jumlah, sub_total)
            VALUES (?, ?, ?, ?, ?)
        ", [$pengadaanId, $item['idbarang'], $harga, $jumlah, $subTotal]);
    }

    return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil ditambahkan');
}

public function destroy($id)
{
    try {
        // Mulai transaksi
        DB::beginTransaction();

        // Hapus data di detail_pengadaan berdasarkan idpengadaan
        DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);

        // Hapus data di pengadaan berdasarkan idpengadaan
        DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);

        // Commit transaksi
        DB::commit();

        return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil dihapus.');
    } catch (\Exception $e) {
        // Rollback jika terjadi kesalahan
        DB::rollBack();

        return redirect()->route('pengadaan.index')->with('error', 'Terjadi kesalahan saat menghapus pengadaan: ' . $e->getMessage());
    }
}


public function detail($idPengadaan)
{
    $details = DB::select("
        SELECT
            b.nama AS nama_barang,
            dp.harga_satuan,
            dp.jumlah,
            dp.sub_total
        FROM detail_pengadaan dp
        JOIN barang b ON dp.idbarang = b.idbarang
        WHERE dp.idpengadaan = ?
    ", [$idPengadaan]);

    return response()->json($details);
}


}

