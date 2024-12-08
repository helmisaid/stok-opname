<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Middleware\SessionAuthMiddleware;
use App\Http\Controllers\MarginPenjualanController;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/map', function () {
    return view('map');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Tambahkan dashboard route (contoh)
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(SessionAuthMiddleware
::class);




Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index'); // Menampilkan semua vendor
Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create'); // Form untuk menambahkan vendor
Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store'); // Menyimpan vendor baru
Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit'); // Form untuk mengedit vendor
Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update'); // Mengupdate vendor yang sudah ada
Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy'); // Menghapus vendor

Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index'); // Menampilkan semua satuan
Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create'); // Form untuk menambahkan satuan
Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store'); // Menyimpan satuan baru
Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit'); // Form untuk mengedit satuan
Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update'); // Mengupdate satuan yang sudah ada
Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy'); // Menghapus vendor

Route::get('/role', [RoleController::class, 'index'])->name('role.index'); // Menampilkan semua role
Route::get('/role/create', [RoleController::class, 'create'])->name('role.create'); // Form untuk menambahkan role
Route::post('/role', [RoleController::class, 'store'])->name('role.store'); // Menyimpan role baru
Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit'); // Form untuk mengedit role
Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update'); // Mengupdate role yang sudah ada
Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy'); // Menghapus vendor

Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Menampilkan semua users
Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Form untuk menambahkan users
Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Menyimpan users baru
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Form untuk mengedit users
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); // Mengupdate users yang sudah ada
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Menghapus vendor

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index'); // Menampilkan semua barang
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create'); // Form untuk menambahkan barang
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store'); // Menyimpan barang baru
Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit'); // Form untuk mengedit barang
Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update'); // Mengupdate barang yang sudah ada
Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy'); // Menghapus vendor


// Route margin Penjualan

Route::get('/marginpenjualan', [MarginPenjualanController::class, 'index'])->name('marginpenjualan.index'); // Menampilkan semua marginpenjualan
Route::get('/marginpenjualan/create', [MarginPenjualanController::class, 'create'])->name('marginpenjualan.create'); // Form untuk menambahkan marginpenjualan
Route::post('/marginpenjualan', [MarginPenjualanController::class, 'store'])->name('marginpenjualan.store'); // Menyimpan marginpenjualan baru
Route::get('/marginpenjualan/{id}/edit', [MarginPenjualanController::class, 'edit'])->name('marginpenjualan.edit'); // Form untuk mengedit marginpenjualan
Route::put('/marginpenjualan/{id}', [MarginPenjualanController::class, 'update'])->name('marginpenjualan.update'); // Mengupdate marginpenjualan yang sudah ada
Route::delete('/marginpenjualan/{id}', [MarginPenjualanController::class, 'destroy'])->name('marginpenjualan.destroy'); // Menghapus vendor






Route::resource('pengadaan', PengadaanController::class);

Route::resource('penjualan', PenjualanController::class);

Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
Route::get('/penerimaan/create/{idpengadaan}', [PenerimaanController::class, 'create'])->name('penerimaan.create');
Route::post('/penerimaan/store', [PenerimaanController::class, 'store'])->name('penerimaan.store');


Route::get('/pengadaan/detail/{idPengadaan}', [PengadaanController::class, 'detail']);

Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
Route::post('/retur/load-detail', [ReturController::class, 'loadDetail'])->name('retur.loadDetail');
Route::post('/retur/store', [ReturController::class, 'store'])->name('retur.store');


