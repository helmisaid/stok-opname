<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
{
    $pengadaans = DB::select("
    SELECT
        p.idpengadaan,
        v.nama_vendor,
        u.username AS nama_user,
        p.subtotal_nilai,
        p.ppn,
        p.total_nilai,
        p.status,
        p.timestamp
    FROM
        pengadaan p
    JOIN
        vendor v ON p.vendor_idvendor = v.idvendor
    JOIN
        user u ON p.user_iduser = u.iduser
    ORDER BY
        p.timestamp DESC
    ");

    return view('penerimaan.index', compact('pengadaans'));
}


    public function create($idPengadaan)
{
    // Ambil data barang pengadaan
    $barangPengadaan = DB::select("
    SELECT
        dp.idbarang,
        b.nama AS nama_barang,
        dp.jumlah AS jumlah_pesan,
        b.harga,
        COALESCE(SUM(
            CASE WHEN p.idpengadaan = ? THEN dpr.jumlah_terima ELSE 0 END
        ), 0) AS total_terima
    FROM
        detail_pengadaan dp
    LEFT JOIN detail_penerimaan dpr ON dp.idbarang = dpr.barang_idbarang
    LEFT JOIN penerimaan p ON dpr.idpenerimaan = p.idpenerimaan
    JOIN barang b ON dp.idbarang = b.idbarang
    WHERE
        dp.idpengadaan = ?
    GROUP BY
        dp.idbarang, b.nama, dp.jumlah, b.harga
", [$idPengadaan, $idPengadaan]);



    // Hitung kolom sisa untuk setiap barang
    foreach ($barangPengadaan as &$barang) {
        $barang->sisa = $barang->jumlah_pesan - $barang->total_terima;
    }

    // Ambil history penerimaan
    $historyPenerimaan = DB::select("
        SELECT
            p.idpenerimaan,
            p.created_at,
            dp.barang_idbarang,
            b.nama AS nama_barang,
            dp.jumlah_terima,
            dp.harga_satuan_terima,
            dp.sub_total_terima
        FROM
            penerimaan p
        JOIN
            detail_penerimaan dp ON p.idpenerimaan = dp.idpenerimaan
        JOIN
            barang b ON dp.barang_idbarang = b.idbarang
        WHERE
            p.idpengadaan = ?
        ORDER BY
            p.created_at DESC
    ", [$idPengadaan]);

    return view('penerimaan.create', compact('barangPengadaan', 'idPengadaan', 'historyPenerimaan'));
}



public function store(Request $request)
{
    $idPengadaan = $request->input('id_pengadaan');
    $items = $request->input('barang'); // Mengambil input array 'barang'

    // Validasi input array
    if (!is_array($items) || empty($items)) {
        return redirect()->back()->withErrors(['error' => 'Tidak ada data barang yang diterima.']);
    }

    $idPenerimaan = null;

    foreach ($items as $idBarang => $item) {
        if (!isset($item['terima'])) {
            continue; // Skip barang yang tidak dicentang
        }

        $jumlahTerima = (int)($item['jumlah_terima'] ?? 0);
        $hargaSatuan = (int)($item['harga_satuan'] ?? 0);

        // Ambil data barang dari database
        $barangPengadaan = DB::selectOne("
            SELECT
                dp.idbarang,
                dp.jumlah AS jumlah_pesan,
                COALESCE(SUM(dpr.jumlah_terima), 0) AS total_terima
            FROM
                detail_pengadaan dp
            LEFT JOIN detail_penerimaan dpr ON dp.idbarang = dpr.barang_idbarang
                AND dpr.idpenerimaan IN (
                    SELECT p.idpenerimaan
                    FROM penerimaan p
                    WHERE p.idpengadaan = ?
                )
            WHERE
                dp.idbarang = ? AND dp.idpengadaan = ?
            GROUP BY dp.idbarang, dp.jumlah
        ", [$idPengadaan, $idBarang, $idPengadaan]);

        if (!$barangPengadaan) {
            return redirect()->back()->withErrors(['error' => 'Barang tidak ditemukan dalam pengadaan.']);
        }

        $sisa = $barangPengadaan->jumlah_pesan - $barangPengadaan->total_terima;

        // Validasi jumlah terima
        if ($jumlahTerima > $sisa || $jumlahTerima <= 0) {
            return redirect()->back()->withErrors(['error' => 'Jumlah terima tidak valid untuk barang ID ' . $idBarang]);
        }

        // Insert ke tabel penerimaan jika belum ada
        if (!$idPenerimaan) {
            $idPenerimaan = DB::table('penerimaan')->insertGetId([
                'created_at' => now(),
                'status' => '1',
                'idpengadaan' => $idPengadaan,
                'iduser' => session('user.iduser') // Asumsikan iduser disimpan di session
            ]);
        }

        // Insert detail penerimaan
        DB::table('detail_penerimaan')->insert([
            'idpenerimaan' => $idPenerimaan,
            'barang_idbarang' => $idBarang,
            'jumlah_terima' => $jumlahTerima,
            'harga_satuan_terima' => $hargaSatuan,
            'sub_total_terima' => $jumlahTerima * $hargaSatuan
        ]);
    }

    // Update status pengadaan jika semua barang telah diterima
    $totalBarang = DB::selectOne("
        SELECT
            (SELECT SUM(jumlah) FROM detail_pengadaan WHERE idpengadaan = ?) AS total_pesan,
            COALESCE(SUM(dpr.jumlah_terima), 0) AS total_diterima
        FROM
            detail_pengadaan dp
        LEFT JOIN detail_penerimaan dpr ON dp.idbarang = dpr.barang_idbarang
            AND dpr.idpenerimaan IN (
                SELECT p.idpenerimaan
                FROM penerimaan p
                WHERE p.idpengadaan = ?
            )
        WHERE dp.idpengadaan = ?
    ", [$idPengadaan, $idPengadaan, $idPengadaan]);

    if ($totalBarang->total_pesan == $totalBarang->total_diterima) {
        DB::table('pengadaan')->where('idpengadaan', $idPengadaan)->update(['status' => 1]);

    }

    return redirect()->back()->with('success', 'Penerimaan berhasil ditambahkan.');
}




    // fungsi histori penerimaan
    public function history()
{
    $historyPenerimaan = DB::select("
        SELECT
            p.idpenerimaan,
            p.created_at AS tanggal_penerimaan,
            dp.barang_idbarang,
            b.nama AS nama_barang,
            dp.jumlah_terima,
            dp.harga_satuan_terima,
            dp.sub_total_terima,
            v.nama_vendor,
            pg.idpengadaan,
            u.username AS nama_user
        FROM
            penerimaan p
        JOIN
            detail_penerimaan dp ON p.idpenerimaan = dp.idpenerimaan
        JOIN
            barang b ON dp.barang_idbarang = b.idbarang
        JOIN
            pengadaan pg ON p.idpengadaan = pg.idpengadaan
        JOIN
            vendor v ON pg.vendor_idvendor = v.idvendor
        JOIN
            user u ON p.iduser = u.iduser
        ORDER BY
            p.created_at DESC
    ");

    return view('penerimaan.historypenerimaan', compact('historyPenerimaan'));
}


}
