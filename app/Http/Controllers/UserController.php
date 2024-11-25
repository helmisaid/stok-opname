<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    // Menggunakan raw query untuk mengambil data user dan role
    $users = DB::select('
        SELECT u.*, r.nama_role
        FROM user u
        JOIN role r ON u.idrole = r.idrole
    ');

    // Mengambil semua role
    $roles = DB::select('SELECT * FROM role');

    return view('user.user', compact('users', 'roles'));
}

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'username' => 'required|unique:user,username|max:45',
        'password' => 'required|min:6',
        'idrole' => 'required|exists:role,idrole',
    ]);

    // Insert ke database menggunakan raw query
    DB::insert("
        INSERT INTO user (username, password, idrole)
        VALUES (?, ?, ?)
    ", [
        $request->username,
        Hash::make($request->password),
        $request->idrole,
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}

// Tampilkan halaman edit user
public function edit($id)
{
    // Ambil data user berdasarkan id
    $user = DB::selectOne("SELECT * FROM user WHERE iduser = ?", [$id]);

    // Ambil data role untuk dropdown
    $roles = DB::select("SELECT * FROM role");

    return view('user.edit', compact('user', 'roles'));
}

// Update data user
public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'username' => 'required|max:45|unique:user,username,' . $id . ',iduser',
        'idrole' => 'required|exists:role,idrole',
    ]);

    // Jika password diisi, hash dan update
    if ($request->password) {
        DB::update("
            UPDATE user
            SET username = ?, password = ?, idrole = ?
            WHERE iduser = ?
        ", [
            $request->username,
            Hash::make($request->password),
            $request->idrole,
            $id,
        ]);
    } else {
        // Jika password tidak diisi, update data kecuali password
        DB::update("
            UPDATE user
            SET username = ?, idrole = ?
            WHERE iduser = ?
        ", [
            $request->username,
            $request->idrole,
            $id,
        ]);
    }

    return redirect()->route('user.user')->with('success', 'User berhasil diperbarui');
}

// Hapus data user
public function destroy($id)
{
    DB::delete("DELETE FROM user WHERE iduser = ?", [$id]);

    return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
}
}
