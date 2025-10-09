<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showFormRegistrasi()
    {

        return view('pages.auth.registrasi');
    }

    public function submitFormRegistrasi(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);

        try {
            // Buat user
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()]);
        }
    }

    public function showFormLogin()
    {
        return view('pages.auth.login');
    }

    public function submitFormLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha'  => 'required|captcha'
        ], [
            'captcha.required' => 'Silakan isi captcha.',
            'captcha.captcha' => 'Captcha tidak valid, silakan coba lagi.'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Coba autentikasi fleksibel: email atau username
        $base = ['password' => $password];
        $attempted = false;

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            // Jika format email, coba via kolom email
            $attempted = Auth::attempt(['email' => $username] + $base, $remember);
        } else {
            // Jika bukan email, coba via kolom username lebih dulu
            $attempted = Auth::attempt(['username' => $username] + $base, $remember)
                    || Auth::attempt(['email' => $username] + $base, $remember); // fallback kalau user menulis email tanpa '@', dll.
        }

        if ($attempted) {
            $request->session()->regenerate();

            // contoh: jika mau arahkan berdasarkan role_id
            // $roleId = Auth::user()->role_id;

            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'username' => 'Login gagal, username atau password salah.',
        ])->onlyInput('username', 'remember');
    }

    public function logout(Request $request)
    {
        // Log out user
        Auth::logout();

        // Hancurkan sesi dan data pengguna
        $request->session()->invalidate();

        // Regenerasi token CSRF untuk mencegah serangan
        $request->session()->regenerateToken();

        // Flush seluruh data sesi, termasuk 'remember me' token
        $request->session()->flush();

        // Arahkan user ke halaman login setelah logout
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
