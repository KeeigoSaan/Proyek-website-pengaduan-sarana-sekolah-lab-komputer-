# ⚡ SIGAP-LAB — Sistem Aduan Laboratorium Komputer SMK

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Style-Neobrutalism-FFE500?style=for-the-badge&logoColor=black" alt="Neobrutalism" />
</p>

---

## 📌 Tentang Proyek

**SIGAP-LAB** adalah aplikasi web pelaporan dan manajemen kendala sarana laboratorium komputer (PC, proyektor, printer, jaringan internet) yang dirancang khusus untuk lingkungan **SMK (Sekolah Menengah Kejuruan)**. 

Aplikasi ini mengatasi kendala pelaporan manual yang kerap tercecer dengan menyediakan sistem pelaporan terstruktur berfoto bukti, alur kerja teknisi berbasis **Kanban Board**, diskusi dua arah, serta pencetakan laporan pertanggungjawaban resmi (PDF & Excel/CSV).

Tampilan antarmuka mengusung konsep desain modern **Neobrutalism** (kontras tinggi, outline tegas, bayangan solid, dan tipografi tebal) yang ramah pengguna baik di desktop maupun layar sentuh ponsel.

---

## 👥 Pengguna & Hak Akses (Multi-Role)

| Peran (Role) | Kredensial Login | Aktivitas Utama |
| :--- | :--- | :--- |
| **Siswa / Pelapor** | NIS atau Email Belajar + Password | • Registrasi mandiri terverifikasi ganda (NIS + Domain Akun Belajar)<br>• Membuat laporan kerusakan sarana lab disertai foto fisik<br>• Memantau perkembangan status perbaikan secara *real-time*<br>• Berkomunikasi via thread komentar dengan teknisi lab |
| **Admin / Teknisi Lab** | Email Dinas + Password | • Mengelola aduan dengan papan alur **Kanban** 4 kolom<br>• Memperbarui status (*Pending*, *Diproses*, *Selesai*, *Ditolak*) dengan riwayat otomatis<br>• Menghapus aduan tidak valid beserta file buktinya<br>• Mengekspor rekapitulasi data ke **Excel (CSV)** & mencetak **PDF resmi ber-Kop Surat** |

---

## 🚀 Fitur Utama

1. **🔐 Autentikasi Fleksibel & Registrasi Terverifikasi Ganda:**
   - Login fleksibel mendeteksi otomatis input berupa **NIS** (siswa) atau **Email** (admin).
   - Registrasi siswa aman dengan validasi ke master data sekolah (`siswa_sekolah`) dan verifikasi domain `@smk.belajar.id`.
2. **📋 Pelaporan Kerusakan Lengkap (Siswa):**
   - Kode tiket aduan otomatis (`LAP-001`, `LAP-002`, dst.).
   - Pilihan unit perangkat komputer lab (PC-01 s/d PC-24, Printer, Proyektor).
   - Pengelompokan kategori (*Hardware*, *Software*, *Jaringan*, *Lainnya*).
   - Unggah foto bukti fisik (JPG, PNG, WEBP, JFIF maks 2MB).
3. **📊 Kanban Board Manajemen Status (Admin):**
   - 4 Kolom visual: **⏳ PENDING**, **🔧 DIPROSES**, **✅ SELESAI**, **✕ DITOLAK**.
   - Perubahan status cepat (*Quick Status Dropdown*) langsung dari kartu Kanban.
   - Setiap perubahan status otomatis tercatat di log riwayat (*Audit Trail*).
4. **💬 Diskusi Interaktif Dua Arah:**
   - Thread tanggapan langsung antara siswa pelapor dan teknisi pada detail laporan.
   - Proteksi keamanan: Siswa hanya dapat membuka dan merespons tiket aduan miliknya sendiri.
5. **🖨️ Ekspor & Pelaporan Lengkap:**
   - **Ekspor Excel (CSV):** Dilengkapi *UTF-8 BOM* agar rapi di Microsoft Excel dan proteksi *CSV formula injection*.
   - **Cetak Laporan PDF:** Menggunakan `barryvdh/laravel-dompdf` berstandar dokumen resmi sekolah lengkap dengan Kop Surat dan tanda tangan koordinator lab.
6. **🗑️ Manajemen Pembersihan Data:**
   - Fitur hapus aduan permanen untuk Admin yang otomatis membersihkan berkas foto fisik di penyimpanan.

---

## 🛠️ Spesifikasi Teknologi

- **Backend:** Laravel 12.x (PHP 8.2+)
- **Frontend:** Blade Templates, Tailwind CSS (Neobrutalism Design System), Google Fonts (*Archivo Black* & *Archivo*)
- **Database:** MySQL
- **PDF Engine:** `barryvdh/laravel-dompdf`
- **Testing:** PHPUnit / Laravel Feature Tests (`RoleSecurityTest`)

---

## 💻 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal (XAMPP):

### 1. Clone Repositori
```bash
git clone https://github.com/KeeigoSaan/Proyek-website-pengaduan-sarana-sekolah-lab-komputer-.git
cd Proyek-website-pengaduan-sarana-sekolah-lab-komputer-
```

### 2. Pasang Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database MySQL pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigap_lab
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

### 4. Generate Kunci Aplikasi & Link Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Migrasi & Isi Data Awal (Seeder)
Pastikan service **MySQL** di XAMPP sudah berjalan (*Start*), lalu jalankan:
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server Pengembangan
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Demo (Bawaan Seeder)

Untuk keperluan pengujian atau demonstrasi penguji, gunakan akun default berikut:

| Peran | Akun / NIS / Email | Password |
| :--- | :--- | :--- |
| **Admin / Teknisi Lab** | `admin@smk.sch.id` | `admin123` |
| **Siswa Terdaftar (Keigo)** | `10245` *(atau `keigo@smk.belajar.id`)* | `siswa123` |
| **Siswa Baru (Uji Registrasi)** | NIS: `10246` / Email: `siti.nuraini@smk.belajar.id` | *(Buat saat registrasi)* |

---

## 🧪 Menjalankan Pengujian (Testing)

Untuk memastikan seluruh hak akses peran dan keamanan berjalan normal:
```bash
php artisan test
```

---

## 👨‍💻 Pengembang

Proyek Akhir Web - Kejuruan Rekayasa Perangkat Lunak (RPL):
- **Nama Peserta Didik:** KEIGO AVREL PURNOMO
- **Kelas:** XII RPL A
- **Sekolah:** SMK Negeri 5 Solo

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pendidikan dan ujian kompetensi kejuruan di bawah lisensi [MIT License](LICENSE).
