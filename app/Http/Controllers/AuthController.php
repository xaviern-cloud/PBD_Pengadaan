<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('id_user')) {
            return $this->redirectByRole(session('role', ''));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("
            SELECT u.id_user, u.username, u.password, r.nama_role
            FROM user u
            LEFT JOIN role r ON u.id_role = r.id_role
            WHERE u.username = :username
            LIMIT 1
        ");
        $stmt->execute(['username' => $request->username]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($user && (Hash::check($request->password, $user->password) || $request->password === $user->password)) {
            $roleName = $user->nama_role ?? 'Admin';

            session([
                'id_user'  => $user->id_user,
                'username' => $user->username,
                'role'     => $roleName,
            ]);

            return $this->redirectByRole($roleName)
                ->with('success', 'Berhasil masuk sebagai ' . $user->username . ' (' . $roleName . ')');
        }

        return back()->withErrors(['login_error' => 'Username atau password salah!'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }

    private function redirectByRole(string $role)
    {
        if (str_contains(strtolower($role), 'kasir')) {
            return redirect()->route('penjualan.index');
        }

        return redirect()->route('dashboard');
    }

    public function profile()
    {
        if (!session()->has('id_user')) {
            return redirect()->route('login')->withErrors(['login_error' => 'Silakan login terlebih dahulu.']);
        }

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("
            SELECT u.id_user, u.username, u.id_role, r.nama_role
            FROM user u
            LEFT JOIN role r ON u.id_role = r.id_role
            WHERE u.id_user = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => session('id_user')]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$user) {
            session()->flush();
            return redirect()->route('login');
        }

        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!session()->has('id_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        $idUser = session('id_user');
        $pdo = DB::getPdo();

        // Cek apakah username sudah dipakai oleh user lain
        $cek = $pdo->prepare("SELECT id_user FROM user WHERE username = :username AND id_user != :id LIMIT 1");
        $cek->execute([
            ':username' => trim($request->username),
            ':id'       => $idUser,
        ]);
        if ($cek->fetch()) {
            return back()->with('error', 'Username tersebut sudah digunakan oleh akun lain.');
        }

        if ($request->filled('password')) {
            $stmt = $pdo->prepare("UPDATE user SET username = :username, password = :password WHERE id_user = :id");
            $stmt->execute([
                ':username' => trim($request->username),
                ':password' => Hash::make($request->password),
                ':id'       => $idUser,
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE user SET username = :username WHERE id_user = :id");
            $stmt->execute([
                ':username' => trim($request->username),
                ':id'       => $idUser,
            ]);
        }

        // Perbarui nama di session aktif
        session(['username' => trim($request->username)]);

        return back()->with('success', 'Profil akun berhasil diperbarui!');
    }
}
