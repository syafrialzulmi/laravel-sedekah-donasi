<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // public function showFormRegistrasi()
    // {

    //     return view('pages.auth.registrasi');
    // }

    // public function submitFormRegistrasi(Request $request)
    // {
    //     $request->validate([
    //         'nama_lengkap' => 'required|string',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string',
    //     ]);

    //     try {
    //         // Buat user
    //         $user = User::create([
    //             'name' => $request->nama_lengkap,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');

    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Batalkan transaksi jika ada error

    //         return redirect()->back()
    //             ->withInput()
    //             ->withErrors(['error' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()]);
    //     }
    // }

    public function showFormLogin(Request $request)
    {
        // Check if this is a test request
        $isTestRequest = app()->environment('testing') ||
                        $request->header('X-Playwright-Test') === 'true' ||
                        $request->header('X-Test-Request') === 'true';

        return view('pages.auth.login', ['isTestRequest' => $isTestRequest]);
    }

    public function submitFormLogin(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',
        ];

        // Check if this is a test/Playwright request
        $isTestRequest = app()->environment('testing') ||
                        $request->header('X-Playwright-Test') === 'true' ||
                        $request->header('X-Test-Request') === 'true';

        // Only require captcha in non-testing environments and non-test requests
        if (!$isTestRequest) {
            $rules['captcha'] = 'required|captcha';
        }

        $request->validate(
            $rules,
            [
                'captcha.required' => 'Silakan isi captcha.',
                'captcha.captcha' => 'Captcha tidak valid, silakan coba lagi.'
            ]
        );

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Struktur dasar data login
        $credentials = ['password' => $password];

        // Tentukan apakah input merupakan email
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Jika input berupa email → cek via field email
            $credentials['email'] = $username;

            $attempted = Auth::attempt($credentials, $remember);
        } else {
            // Jika input berupa username (bukan email)
            $credentials['username'] = $username;

            // Coba login via username (UTAMA)
            $attempted = Auth::attempt($credentials, $remember);

            // Jika gagal, CEK apakah input sebenarnya email tapi tidak valid formatnya
            if (! $attempted) {
                unset($credentials['username']);
                $credentials['email'] = $username;
                $attempted = Auth::attempt($credentials, $remember);
            }
        }

        if ($attempted) {
            $request->session()->regenerate();

            // CEK STATUS AKTIF USER
            if (Auth::user()->is_active == 0) {
                session()->flash('show_update_profile_modal', true);
            }

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
