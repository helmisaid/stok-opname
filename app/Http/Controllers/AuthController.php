<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm () {

        return view('auth.login');
    }

    public function login(Request $request)
{
    $username = $request->input('username');
    $password = $request->input('password');

    // Query dengan join untuk mengambil username, password, dan nama_role
    $user = DB::selectOne("
        SELECT u.iduser, u.username, u.password, r.nama_role
        FROM user u
        JOIN role r ON u.idrole = r.idrole
        WHERE u.username = ?
    ", [$username]);

    // Verifikasi password
    if ($user && Hash::check($password, $user->password)) {
        // Simpan data user dan nama role ke session
        session([
            'user' => [
                'iduser' => $user->iduser,
                'username' => $user->username,
                'role' => $user->nama_role,
            ]
        ]);

        return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }

    return back()->withErrors(['login_error' => 'Username atau password salah']);
}

public function logout()
{
    session()->forget('user'); // Hapus session
    return redirect()->route('login')->with('success', 'Anda telah logout.');
}

}
