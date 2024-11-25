<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    public function index()
{
    // Menggunakan raw query untuk mengambil data margin penjualan
    $margin_penjualans = DB::select('SELECT * FROM margin_penjualan');

    return view('margin-penjualan.index', compact('margin_penjualans'));
}

}
