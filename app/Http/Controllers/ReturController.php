<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        return view('retur.index');
    }

    public function loadDetail(Request $request)
    {
        // Validasi ID Pengadaan
        $idPengadaan = $request->input('idpengadaan');

        if (!$idPengadaan) {
            return response()->json(['message' => 'ID Pengadaan tidak ditemukan'], 400); // Pesan error jika ID tidak ditemukan
        }

        // Query gabungan untuk mengambil data penerimaan dan detail penerimaan dalam satu query
        $data = DB::table('penerimaan')
            ->join('detail_penerimaan', 'penerimaan.idpenerimaan', '=', 'detail_penerimaan.idpenerimaan')
            ->join('barang', 'detail_penerimaan.barang_idbarang', '=', 'barang.idbarang')
            ->join('user', 'penerimaan.iduser', '=', 'user.iduser') // Menggabungkan dengan tabel users
            ->where('penerimaan.idpengadaan', $idPengadaan)
            ->select(
                'penerimaan.idpenerimaan',
                'penerimaan.created_at',
                'penerimaan.status',  // Menambahkan status penerimaan
                'user.username as nama_user', // Mengambil nama user yang melakukan penerimaan
                'detail_penerimaan.jumlah_terima',
                'detail_penerimaan.harga_satuan_terima',
                'detail_penerimaan.sub_total_terima',
                'barang.nama as nama_barang',
                'detail_penerimaan.barang_idbarang'
            )
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Detail penerimaan tidak ditemukan untuk ID Pengadaan ini'], 404); // Menangani jika tidak ada data ditemukan
        }

        // Memisahkan data penerimaan dan detail penerimaan
        $penerimaanData = [];
        $detailData = [];

        foreach ($data as $row) {
            // Menyimpan data penerimaan hanya sekali per ID penerimaan
            if (!isset($penerimaanData[$row->idpenerimaan])) {
                $penerimaanData[$row->idpenerimaan] = [
                    'idpenerimaan' => $row->idpenerimaan,
                    'tanggal_penerimaan' => $row->created_at,
                    'status' => $row->status, // Menyimpan status
                    'nama_user' => $row->nama_user, // Menyimpan nama user
                    'details' => []
                ];
            }

            // Menambahkan detail penerimaan ke dalam array details untuk penerimaan yang bersangkutan
            $penerimaanData[$row->idpenerimaan]['details'][] = [
                'nama_barang' => $row->nama_barang,
                'jumlah_terima' => $row->jumlah_terima,
                'harga_satuan' => $row->harga_satuan_terima,
                'sub_total' => $row->sub_total_terima,
                'idbarang' => $row->barang_idbarang
            ];
        }

        return response()->json([
            'penerimaan' => array_values($penerimaanData), // Mengirimkan data penerimaan dan detailnya
        ], 200);
    }


    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'idpenerimaan' => 'required|exists:penerimaan,idpenerimaan',
        'barang' => 'required|array|min:1', // Ensure there are selected items
        'barang.*.iddetail_penerimaan' => 'required|exists:detail_penerimaan,iddetail_penerimaan',
        'barang.*.jumlah_retur' => 'required|integer|min:1', // Validate jumlah retur for each item
        'alasan' => 'required|string|max:255',
    ]);

    try {
        DB::beginTransaction(); // Start a transaction for data consistency

        // Loop through each selected barang and handle the return logic
        foreach ($request->input('barang') as $item) {
            // Check if the quantity to return does not exceed the received quantity
            $detailPenerimaan = DB::table('detail_penerimaan')
                ->where('iddetail_penerimaan', $item['iddetail_penerimaan'])
                ->first();

            if (!$detailPenerimaan || $item['jumlah_retur'] > $detailPenerimaan->jumlah_terima) {
                return redirect()->route('retur.index')->with('error', 'Jumlah retur melebihi jumlah yang diterima.');
            }

            // Insert data into retur table
            $returId = DB::table('retur')->insertGetId([
                'idpenerimaan' => $request->input('idpenerimaan'),
                'alasan' => $request->input('alasan'),
                'iduser' => session('user.iduser'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert details into detail_retur table
            DB::table('detail_retur')->insert([
                'retur_id' => $returId,
                'iddetail_penerimaan' => $item['iddetail_penerimaan'],
                'jumlah_retur' => $item['jumlah_retur'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update the stock and penerimaan status accordingly (if needed)
            // This part can include triggers to update the stock in the Kartu Stok or other tables if necessary
        }

        DB::commit(); // Commit the transaction

        return redirect()->route('retur.index')->with('success', 'Retur barang berhasil diproses.');
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback if there’s any error

        return redirect()->route('retur.index')->with('error', 'Terjadi kesalahan, coba lagi.');
    }
}


}
