<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::select('SELECT * FROM role');
        return view('user.roleuser', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_role' => 'required|max:45',
        ]);

        $roleName = $validatedData['nama_role'];

        DB::statement('INSERT INTO role (nama_role) VALUES (?)', [$roleName]);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($idrole)
    {
        $role = DB::select('SELECT * FROM role WHERE idrole = ?', [$idrole]);

        if (empty($role)) {
            return redirect()->route('role.index')->with('error', 'Role tidak ditemukan.');
        }

        return view('user.roleuseredit', ['role' => $role[0]]);
    }

    public function update(Request $request, $idrole)
    {
        $validatedData = $request->validate([
            'nama_role' => 'required|max:45',
        ]);

        $roleName = $validatedData['nama_role'];

        DB::statement('UPDATE role SET nama_role = ? WHERE idrole = ?', [$roleName, $idrole]);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($idrole)
    {
        DB::statement('DELETE FROM role WHERE idrole = ?', [$idrole]);

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }

}
