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
            return redirect()->route('dashboard');
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
        $stmt =$pdo->prepare("
            SELECT u.id_user, u.username, u.password, r.nama_role
            FROM user u
            LEFT JOIN role r ON u.id_role = r.id_role
            WHERE u.username = :username
            LIMIT 1
        ");
        $stmt->execute(['username' =>$request->username]);
        $user =$stmt->fetch(\PDO::FETCH_OBJ);

        if ($user && (Hash::check($request->password,$user->password) || $request->password ===$user->password)) {
            session([
                'id_user'  => $user->id_user,
                'username' => $user->username,
                'role'     => $user->nama_role ?? 'Admin',
            ]);

            if (strtolower($user->nama_role ?? '') === 'kasir') {
                return redirect()->route('penjualan.index')->with('success', 'Selamat datang, ' . $user->username . '!');
            }

            return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai ' . $user->username);
        }

        return back()->withErrors(['login_error' => 'Username atau password salah!'])->withInput();
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }
}
