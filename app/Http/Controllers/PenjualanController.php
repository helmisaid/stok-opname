<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PenjualanController extends Controller
{
    public function index()
{
    // Query raw untuk menghitung harga satuan
    $barangs = DB::select("
        SELECT
            barang.idbarang,
            barang.nama,
            barang.harga AS harga_asli,
            IFNULL(barang.harga + (barang.harga * (margin_penjualan.persen / 100)), barang.harga) AS harga
        FROM barang
        LEFT JOIN margin_penjualan ON margin_penjualan.status = 1
    ");

    // dd($barangs);

    return view('penjualan.index', compact('barangs'));
}

public function store(Request $request)
{
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

    $idMarginPenjualan = DB::select("
        SELECT idmargin_penjualan
        FROM margin_penjualan
        WHERE status = 1
        ORDER BY idmargin_penjualan DESC
        LIMIT 1
    ");

    // Cek jika idmargin_penjualan ditemukan
    if (empty($idMarginPenjualan)) {
        return redirect()->route('penjualan.index')->with('error', 'Tidak ada margin penjualan yang aktif.');
    }

    $idMarginPenjualan = $idMarginPenjualan[0]->idmargin_penjualan;

    // Ambil nilai PPN dari input (misalnya 10%)
    $ppnPercentage = $request->input('ppn', 0); // Default 10% jika tidak ada input

    // Hitung PPN berdasarkan nilai yang diberikan
    $ppn = $subtotal * ($ppnPercentage / 100);

    // Hitung total nilai (subtotal + ppn)
    $total = $subtotal + $ppn;

    // Insert into penjualan
    DB::insert("
        INSERT INTO penjualan (iduser, subtotal_nilai, ppn, total_nilai, idmargin_penjualan, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ", [ $userId, $subtotal, $ppn, $total, $idMarginPenjualan ]);

    $penjualanId = DB::getPdo()->lastInsertId();

    // Insert detail_penjualan
    foreach ($items as $item) {
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $subTotal = $harga * $jumlah;

        DB::insert("
            INSERT INTO detail_penjualan (penjualan_idpenjualan, barang_idbarang, harga_satuan, jumlah, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ", [$penjualanId, $item['idbarang'], $harga, $jumlah, $subTotal]);
    }

    return redirect()->route('penjualan.index')->with('success', 'penjualan berhasil ditambahkan');
}
}
