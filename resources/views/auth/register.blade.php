@extends('layouts.app', ['title' => 'Aktivasi Akun Siswa'])

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-[#F5F3EE]">
    <div class="w-full max-w-md">
        <!-- Logo / Wordmark -->
        <div class="text-center mb-6">
            <div class="inline-block bg-[#FFE500] border-[3px] border-[#0A0A0A] px-4 py-1.5 mb-2 shadow-[3px_3px_0_#0A0A0A]">
                <span class="font-display text-[11px] tracking-[0.2em] text-[#0A0A0A]">
                    SMK NEGERI 5
                </span>
            </div>
            <h1 class="font-display text-4xl leading-none tracking-tight text-[#0A0A0A] block">
                SIGAP-LAB
            </h1>
            <p class="text-xs text-[#0A0A0A] mt-1 font-bold tracking-wider uppercase">
                AKTIVASI AKUN RESMI SISWA
            </p>
        </div>

        <!-- Card Registrasi -->
        <div class="neo-box p-6 md:p-8 bg-white shadow-[6px_6px_0_#0A0A0A]">
            <div class="border-b-2 border-neutral-200 pb-3 mb-4">
                <span class="inline-block bg-[#B4FF00] border-2 border-black px-2 py-0.5 text-[10px] font-display uppercase tracking-wider mb-1">
                    VERIFIKASI DATA RESMI
                </span>
                <h2 class="font-display text-xl text-[#0A0A0A] uppercase">
                    DAFTAR AKUN SISWA
                </h2>
                <p class="text-[11px] font-semibold text-neutral-600 mt-0.5">
                    Masukkan NIS dan Akun Belajar yang diberikan sekolah untuk membuat password.
                </p>
            </div>

            @if($errors->any())
                <div class="bg-[#FF3EA5] text-white border-2 border-[#0A0A0A] p-3 mb-4 font-bold text-xs shadow-[3px_3px_0_#0A0A0A]">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                
                <!-- Input NIS -->
                <div>
                    <label class="block font-display text-[11px] tracking-wider uppercase mb-1 text-[#0A0A0A]">
                        NOMOR INDUK SISWA (NIS) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nis" 
                        value="{{ old('nis') }}" 
                        placeholder="Contoh: 10246"
                        required
                        class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-2.5 font-bold text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors"
                    >
                    <span class="text-[10px] font-bold text-neutral-500">Sesuai kartu pelajar resmi sekolah</span>
                </div>

                <!-- Input Email Akun Belajar -->
                <div>
                    <label class="block font-display text-[11px] tracking-wider uppercase mb-1 text-[#0A0A0A]">
                        EMAIL AKUN BELAJAR <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email_belajar" 
                        value="{{ old('email_belajar') }}" 
                        placeholder="nama@smk.belajar.id"
                        required
                        class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-2.5 font-bold text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors"
                    >
                    <span class="text-[10px] font-bold text-neutral-500">Wajib menggunakan domain @smk.belajar.id</span>
                </div>

                <!-- Input No WA -->
                <div>
                    <label class="block font-display text-[11px] tracking-wider uppercase mb-1 text-[#0A0A0A]">
                        NO. WHATSAPP (OPSIONAL)
                    </label>
                    <input 
                        type="text" 
                        name="no_telp" 
                        value="{{ old('no_telp') }}" 
                        placeholder="08xxxxxxxxxx"
                        class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-2.5 font-bold text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors"
                    >
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-display text-[11px] tracking-wider uppercase mb-1 text-[#0A0A0A]">
                            PASSWORD BARU <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Min. 6 digit"
                            required
                            class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-2.5 font-bold text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors"
                        >
                    </div>

                    <div>
                        <label class="block font-display text-[11px] tracking-wider uppercase mb-1 text-[#0A0A0A]">
                            KONFIRMASI <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="Ulangi password"
                            required
                            class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-2.5 font-bold text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-neo btn-neo-yellow w-full py-3 mt-2 text-sm shadow-[4px_4px_0_#0A0A0A]">
                    VERIFIKASI & AKTIFKAN AKUN →
                </button>
            </form>

            <div class="mt-4 pt-3 border-t border-neutral-200 text-center">
                <a href="{{ route('login') }}" class="text-xs font-bold text-neutral-600 hover:text-black underline">
                    ← Sudah punya akun? Kembali ke Halaman Login
                </a>
            </div>
        </div>

        <!-- Info Verifikasi -->
        <div class="mt-4 p-3 border-2 border-dashed border-[#0A0A0A] bg-white text-xs">
            <span class="font-display text-[10px] uppercase block text-neutral-500 mb-1">
                💡 CARA KERJA VERIFIKASI:
            </span>
            <p class="text-[11px] text-neutral-700 leading-snug">
                Sistem secara otomatis mencocokkan NIS dan Email Akun Belajar dengan data resmi sekolah. Nama lengkap dan kelas kamu akan terisi otomatis setelah terverifikasi.
            </p>
        </div>
    </div>
</div>
@endsection
