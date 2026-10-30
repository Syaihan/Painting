<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
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

        $operator = DB::table('m_users')
            ->join('m_roles', 'm_users.role_id', '=', 'm_roles.id')
            ->select('m_users.*', 'm_roles.name as role_name', 'm_roles.display_name')
            ->where('m_users.username', $request->username)
            ->first();

        if ($operator && Hash::check($request->password, $operator->password)) {
            $permissions = DB::table('m_role_permission')
                ->join('m_permissions', 'm_role_permission.permission_id', '=', 'm_permissions.id')
                ->where('m_role_permission.role_id', $operator->role_id)
                ->pluck('m_permissions.name')
                ->toArray();

            session([
                'operator_id' => $operator->id,
                'operator_name' => $operator->name,
                'operator_username' => $operator->username,
                'operator_role' => $operator->role_name,
                'role_display' => $operator->display_name,
                'permissions' => $permissions,
            ]);

            // Catat histori login ke database
            SessionLog::create([
                't_log_session_history_username' => $operator->username,
                't_log_session_history_name' => $operator->name,
                't_log_session_history_action' => 'login',
                't_log_session_history_ip' => $request->ip(),
                't_log_session_history_browser' => $request->userAgent(),
                't_log_session_history_timestamp' => now('Asia/Jakarta'),
            ]);

            return redirect('/')->with('success', 'Berhasil login!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    // Proses logout
    public function logout(Request $request)
    {
        // Catat histori logout ke database (sebelum session dihapus)
        if (session('operator_id')) {
            SessionLog::create([
                't_log_session_history_username' => session('operator_username', '-'),
                't_log_session_history_name' => session('operator_name', '-'),
                't_log_session_history_action' => 'logout',
                't_log_session_history_ip' => $request->ip(),
                't_log_session_history_browser' => $request->userAgent(),
                't_log_session_history_timestamp' => now('Asia/Jakarta'),
            ]);
        }

        $request->session()->flush();

        return redirect('/')->with('success', 'Berhasil logout.');
    }

    // Tampilkan Halaman Terpusat Manajemen User & Registrasi (Khusus Admin)
    public function indexManajemenUser()
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $users = DB::table('m_users')
            ->join('m_roles', 'm_users.role_id', '=', 'm_roles.id')
            ->select('m_users.*', 'm_roles.name as role_name', 'm_roles.display_name')
            ->get();

        $roles = DB::table('m_roles')->get();
        $activeMenu = 'Manajemen User';

        return view('auth.manajemen-user', compact('users', 'roles', 'activeMenu'));
    }

    // Proses simpan akun baru / registrasi (Khusus Admin)
    public function register(Request $request)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'username' => 'required|unique:m_users,username',
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:m_roles,id',
        ], [
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::table('m_users')->insert([
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'created_at' => now('Asia/Jakarta'),
            'updated_at' => now('Asia/Jakarta'),
        ]);

        return redirect()->route('manajemen.user')->with('success', 'Akun baru berhasil dibuat!');
    }

    // Proses Hapus User (Khusus Admin)
    public function deleteUser($id)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        if (session('operator_id') == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang aktif digunakan.');
        }

        DB::table('m_users')->where('id', $id)->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    // Proses Update User / Edit (Khusus Admin)
    public function updateUser(Request $request, $id)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:m_users,username,'.$id,
            'role_id' => 'required|exists:m_roles,id',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'updated_at' => now('Asia/Jakarta'),
        ];

        // Jika password diisi, update passwordnya. Jika kosong, abaikan.
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('m_users')->where('id', $id)->update($updateData);

        return redirect()->route('manajemen.user')->with('success', 'Data user berhasil diperbarui!');
    }

    // Tampilkan Halaman Histori Session / Riwayat Login-Logout (Khusus Admin)
    public function historiSession(Request $request)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $search = $request->input('search');

        $logs = SessionLog::query()
            ->when($search, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('t_log_session_history_username', 'like', "%{$keyword}%")
                        ->orWhere('t_log_session_history_name', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('t_log_session_history_timestamp', 'desc')
            ->paginate(15)
            ->withQueryString();

        $activeMenu = 'Histori Session';

        return view('auth.histori-session', compact('logs', 'search', 'activeMenu'));
    }

    // Tampilkan Halaman Manajemen Operator (Khusus Admin)
    public function indexOperator()
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $operators = DB::table('m_operator')->orderBy('nama', 'asc')->get();

        $activeMenu = 'Operator';

        return view('auth.operator', compact('operators', 'activeMenu'));
    }

    // Proses simpan operator baru (Khusus Admin)
    public function storeOperator(Request $request)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama' => 'required|string|max:50|unique:m_operator,nama',
            'grup' => 'required|string|max:2',
        ], [
            'nama.required' => 'Nama operator wajib diisi.',
            'nama.unique' => 'Nama operator sudah digunakan, silakan gunakan nama lain.',
            'grup.required' => 'Grup operator wajib diisi.',
        ]);

        DB::table('m_operator')->insert([
            'nama' => $request->nama,
            'grup' => $request->grup,
        ]);

        $this->clearOperatorCache();

        return redirect()->route('operator.index')->with('success', 'Operator berhasil ditambahkan!');
    }

    // Proses update operator (Khusus Admin)
    public function updateOperator(Request $request, $id)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama' => 'required|string|max:50|unique:m_operator,nama,'.$id,
            'grup' => 'required|string|max:2',
        ], [
            'nama.required' => 'Nama operator wajib diisi.',
            'nama.unique' => 'Nama operator sudah digunakan, silakan gunakan nama lain.',
            'grup.required' => 'Grup operator wajib diisi.',
        ]);

        DB::table('m_operator')->where('id', $id)->update([
            'nama' => $request->nama,
            'grup' => $request->grup,
        ]);

        $this->clearOperatorCache();

        return redirect()->route('operator.index')->with('success', 'Data operator berhasil diperbarui!');
    }

    // Proses hapus operator (Khusus Admin)
    public function deleteOperator($id)
    {
        if (session('operator_role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        DB::table('m_operator')->where('id', $id)->delete();

        $this->clearOperatorCache();

        return redirect()->route('operator.index')->with('success', 'Operator berhasil dihapus.');
    }

    /**
     * Hapus cache daftar operator agar data operator terbaru
     * langsung muncul pada dropdown menu scan (nglog / console).
     */
    private function clearOperatorCache()
    {
        Cache::forget('operators_role_A');
        Cache::forget('operators_role_B');
        Cache::forget('operators_role_');
    }
}
