<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        // Jika sudah login, langsung lempar ke dashboard
        if (session()->has('operator_id')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    // Proses data login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Menggunakan nama tabel baru: m_users dan m_roles
        $operator = DB::table('m_users')
            ->join('m_roles', 'm_users.role_id', '=', 'm_roles.id')
            ->select('m_users.*', 'm_roles.name as role_name', 'm_roles.display_name')
            ->where('m_users.username', $request->username)
            ->first();

        // Cek apakah operator ditemukan dan passwordnya cocok
        if ($operator && Hash::check($request->password, $operator->password)) {
            
            // Ambil daftar permissions berdasarkan role_id
            $permissions = DB::table('m_role_permission')
                ->join('m_permissions', 'm_role_permission.permission_id', '=', 'm_permissions.id')
                ->where('m_role_permission.role_id', $operator->role_id)
                ->pluck('m_permissions.name')
                ->toArray();

            // Simpan ke Session Kustom
            session([
                'operator_id'   => $operator->id,
                'operator_name' => $operator->name,
                'operator_role' => $operator->role_name,
                'role_display'  => $operator->display_name,
                'permissions'   => $permissions,
            ]);

            return redirect()->route('dashboard')->with('success', 'Berhasil login!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    // Proses logout
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/')->with('success', 'Berhasil logout.');
    }
}
