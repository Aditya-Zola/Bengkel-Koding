<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->role == 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role == 'dokter') {
                    return redirect()->route('dokter.dashboard');
                } else {
                    return redirect()->route('pasien.dashboard');
                }
            }
            return back()->withErrors(['email' => 'Email atau Password Salah !']);
        }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:225'],
            'alamat' => ['required', 'string', 'max:225'],
            'no_ktp' => ['required', 'string', 'max:30'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:225', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            // Wajib: Hash password sebelum disimpan
            'password' => Hash::make($request->password),
            // Wajib: Tetapkan role default untuk pengguna yang mendaftar
            'role' => 'pasien',
        ]);

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus status autentikasi dari sesi

        $request->session()->invalidate(); // Menghapus semua data sesi
        $request->session()->regenerateToken(); // Meregenerasi token CSRF

        // Arahkan kembali ke halaman utama atau login
        return redirect('/login');
    }
}
