<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SiswaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('siswa.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ], [
            'login.required'    => 'NIS atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginValue = $request->input('login');

        // Deteksi apakah input login berupa Email atau NIS
        $loginType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        $authAttempt = Auth::attempt([
            $loginType => $loginValue,
            'password' => $request->password,
        ], $request->boolean('remember'));

        if ($authAttempt) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('siswa.dashboard'));
        }

        return back()->withErrors([
            'login' => 'NIS/Email atau password salah.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('siswa.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nis'           => ['required', 'string'],
            'email_belajar' => ['required', 'email'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'no_telp'       => ['nullable', 'string', 'max:15'],
        ], [
            'nis.required'           => 'NIS wajib diisi.',
            'email_belajar.required' => 'Email Akun Belajar wajib diisi.',
            'email_belajar.email'    => 'Format email tidak valid.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        $nisInput = trim($request->nis);
        $emailInput = strtolower(trim($request->email_belajar));

        // 1. Verifikasi Format Domain Akun Belajar
        if (!str_ends_with($emailInput, '@smk.belajar.id') && !str_ends_with($emailInput, '@belajar.id')) {
            return back()->withErrors([
                'email_belajar' => 'Email harus menggunakan Akun Belajar resmi (@smk.belajar.id atau @belajar.id).',
            ])->withInput();
        }

        // 2. Verifikasi ke Master Data Siswa Sekolah (NIS & Email Belajar Berpasangan)
        $masterSiswa = SiswaSekolah::where('nis', $nisInput)->first();

        if (!$masterSiswa) {
            return back()->withErrors([
                'nis' => 'NIS tidak ditemukan dalam data resmi siswa sekolah.',
            ])->withInput();
        }

        if (strtolower(trim($masterSiswa->email_belajar)) !== $emailInput) {
            return back()->withErrors([
                'email_belajar' => 'Email Akun Belajar tidak cocok dengan pemilik NIS ' . $nisInput . '!',
            ])->withInput();
        }

        // 3. Cek apakah NIS atau Email ini sudah pernah registrasi sebelumnya
        $existingUser = User::where('nis', $nisInput)->orWhere('email', $emailInput)->first();
        if ($existingUser) {
            return back()->withErrors([
                'nis' => 'Akun untuk siswa ini sudah pernah didaftarkan. Silakan langsung login!',
            ])->withInput();
        }

        // 4. Buat Akun Siswa Resmi (Nama & Kelas otomatis diambil dari data master sekolah)
        $newUser = User::create([
            'name'     => $masterSiswa->nama,
            'nis'      => $masterSiswa->nis,
            'email'    => $masterSiswa->email_belajar,
            'kelas'    => $masterSiswa->kelas,
            'no_telp'  => $request->no_telp,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        // Login otomatis setelah pendaftaran sukses
        Auth::login($newUser);
        $request->session()->regenerate();

        return redirect()->route('siswa.dashboard')->with('success', 'Selamat datang ' . $newUser->name . '! Akun siswa kamu berhasil diverifikasi dan terdaftar.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar sistem.');
    }
}
