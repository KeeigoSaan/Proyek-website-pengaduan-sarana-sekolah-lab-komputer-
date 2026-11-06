<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SiswaSekolah;
use App\Models\Perangkat;
use App\Models\KategoriKerusakan;
use App\Models\Aduan;
use App\Models\Tanggapan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Admin
        $admin = User::create([
            'name'     => 'Pak Budi (Teknisi)',
            'email'    => 'admin@smk.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'kelas'    => 'Koordinator Lab',
            'no_telp'  => '081234567890',
        ]);

        // 2. Data Master Siswa Sekolah (Dari TU / Akun Belajar)
        $masterSiswa = [
            [
                'nis'           => '10245',
                'nama'          => 'Keigo Avrel Purnomo',
                'kelas'         => 'XII RPL 2',
                'email_belajar' => 'keigo@smk.belajar.id',
            ],
            [
                'nis'           => '10246',
                'nama'          => 'Siti Nuraini',
                'kelas'         => 'XII RPL 2',
                'email_belajar' => 'siti.nuraini@smk.belajar.id',
            ],
            [
                'nis'           => '10247',
                'nama'          => 'Rafi Aditya',
                'kelas'         => 'XI RPL 1',
                'email_belajar' => 'rafi.aditya@smk.belajar.id',
            ],
            [
                'nis'           => '10248',
                'nama'          => 'Dwi Cahyono',
                'kelas'         => 'XI TKJ 2',
                'email_belajar' => 'dwi.cahyono@smk.belajar.id',
            ],
        ];

        foreach ($masterSiswa as $ms) {
            SiswaSekolah::create($ms);
        }

        // 3. User Siswa yang Sudah Terdaftar (Akun Demo)
        $siswa = User::create([
            'name'     => 'Keigo Avrel Purnomo',
            'nis'      => '10245',
            'email'    => 'keigo@smk.belajar.id',
            'password' => Hash::make('siswa123'),
            'role'     => 'siswa',
            'kelas'    => 'XII RPL 2',
            'no_telp'  => '089876543210',
        ]);

        // 4. Kategori Kerusakan
        $katHardware = KategoriKerusakan::create(['nama_kategori' => 'Hardware', 'deskripsi' => 'Kerusakan fisik seperti monitor, keyboard, mouse, ram, dll.']);
        $katSoftware = KategoriKerusakan::create(['nama_kategori' => 'Software', 'deskripsi' => 'Masalah OS, instalasi software, driver, crash aplikasi.']);
        $katJaringan = KategoriKerusakan::create(['nama_kategori' => 'Jaringan', 'deskripsi' => 'Kabel LAN lepas, tidak dapat IP, internet lambat/putus.']);
        $katLainnya  = KategoriKerusakan::create(['nama_kategori' => 'Lainnya', 'deskripsi' => 'Keluhan fasilitas meja, kursi, stopkontak, listrik.']);

        // 4. Perangkat Lab
        $perangkats = [];
        for ($i = 1; $i <= 24; $i++) {
            $kode = 'PC-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $perangkats[$kode] = Perangkat::create([
                'kode_perangkat' => $kode,
                'nama_perangkat' => 'Komputer ' . $kode,
                'lokasi'         => 'Lab Komputer 1',
                'status'         => 'baik',
            ]);
        }

        $perangkats['PRINTER-01'] = Perangkat::create([
            'kode_perangkat' => 'PRINTER-01',
            'nama_perangkat' => 'Printer Epson L3110',
            'lokasi'         => 'Lab Komputer 1',
            'status'         => 'baik',
        ]);

        $perangkats['PROYEKTOR-01'] = Perangkat::create([
            'kode_perangkat' => 'PROYEKTOR-01',
            'nama_perangkat' => 'Proyektor BenQ',
            'lokasi'         => 'Lab Komputer 1',
            'status'         => 'baik',
        ]);

        // 5. Data Aduan dikosongkan agar pengguna dapat menginput aduan asli dengan foto bukti
    }
}