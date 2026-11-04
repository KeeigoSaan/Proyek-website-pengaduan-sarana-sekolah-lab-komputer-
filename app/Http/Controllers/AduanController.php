<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Perangkat;
use App\Models\KategoriKerusakan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AduanController extends Controller
{
    public function siswaDashboard(Request $request)
    {
        $user = Auth::user();
        $query = Aduan::with(['perangkat', 'kategori'])
            ->where('user_id', $user->id)
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('kode_aduan', 'like', "%{$q}%")
                    ->orWhereHas('perangkat', fn($p) => $p->where('kode_perangkat', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        $aduanList = $query->get();

        $counts = [
            'total'      => Aduan::where('user_id', $user->id)->count(),
            'pending'    => Aduan::where('user_id', $user->id)->where('status', 'PENDING')->count(),
            'inProgress' => Aduan::where('user_id', $user->id)->where('status', 'IN_PROGRESS')->count(),
            'resolved'   => Aduan::where('user_id', $user->id)->where('status', 'RESOLVED')->count(),
        ];

        return view('siswa.dashboard', compact('aduanList', 'counts'));
    }

    public function create()
    {
        $perangkatList = Perangkat::orderBy('kode_perangkat')->get();
        $kategoriList  = KategoriKerusakan::all();

        return view('siswa.create', compact('perangkatList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'perangkat_id' => 'required|exists:perangkat,id',
            'kategori_id'  => 'required|exists:kategori_kerusakan,id',
            'deskripsi'    => 'required|string|min:20',
            'foto_bukti'   => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
        ], [
            'judul.required'        => 'Judul aduan wajib diisi.',
            'perangkat_id.required' => 'Pilih perangkat komputer yang bermasalah.',
            'kategori_id.required'  => 'Pilih kategori kerusakan.',
            'deskripsi.required'    => 'Deskripsi kerusakan wajib diisi.',
            'deskripsi.min'         => 'Deskripsi minimal 20 karakter agar jelas bagi teknisi.',
            'foto_bukti.image'      => 'File bukti harus berupa gambar (JPG, PNG, WEBP).',
            'foto_bukti.max'        => 'Ukuran foto maksimal 2 MB.',
        ]);

        $count = Aduan::count() + 1;
        $kodeAduan = 'LAP-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('foto_aduan', 'public');
        }

        $aduan = Aduan::create([
            'kode_aduan'   => $kodeAduan,
            'user_id'      => Auth::id(),
            'perangkat_id' => $request->perangkat_id,
            'kategori_id'  => $request->kategori_id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'foto_bukti'   => $fotoPath,
            'status'       => 'PENDING',
        ]);

        return redirect()->route('aduan.show', $aduan->id)->with('success', 'Laporan kerusakan berhasil dikirim! Kode: ' . $kodeAduan);
    }

    public function adminDashboard(Request $request)
    {
        $query = Aduan::with(['user', 'perangkat', 'kategori'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('kode_aduan', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('kelas', 'like', "%{$q}%"))
                    ->orWhereHas('perangkat', fn($p) => $p->where('kode_perangkat', 'like', "%{$q}%"));
            });
        }

        $allAduan = $query->get();

        $columns = [
            'PENDING'     => $allAduan->where('status', 'PENDING'),
            'IN_PROGRESS' => $allAduan->where('status', 'IN_PROGRESS'),
            'RESOLVED'    => $allAduan->where('status', 'RESOLVED'),
            'REJECTED'    => $allAduan->where('status', 'REJECTED'),
        ];

        $counts = [
            'total'       => Aduan::count(),
            'PENDING'     => Aduan::where('status', 'PENDING')->count(),
            'IN_PROGRESS' => Aduan::where('status', 'IN_PROGRESS')->count(),
            'RESOLVED'    => Aduan::where('status', 'RESOLVED')->count(),
            'REJECTED'    => Aduan::where('status', 'REJECTED')->count(),
        ];

        return view('admin.dashboard', compact('columns', 'counts'));
    }

    public function show($id)
    {
        $aduan = Aduan::with(['user', 'perangkat', 'kategori', 'tanggapan.user'])->findOrFail($id);

        // Security check: siswa can only see their own aduan
        if (Auth::user()->role === 'siswa' && $aduan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat laporan ini.');
        }

        return view('aduan.show', compact('aduan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDING,IN_PROGRESS,RESOLVED,REJECTED',
        ]);

        $aduan = Aduan::findOrFail($id);
        $oldStatus = $aduan->status;
        $aduan->status = $request->status;
        $aduan->save();

        // Automatically record system/admin response note
        Tanggapan::create([
            'aduan_id' => $aduan->id,
            'user_id'  => Auth::id(),
            'catatan'  => 'Status laporan diubah dari ' . $oldStatus . ' menjadi ' . $request->status . '.',
        ]);

        return back()->with('success', 'Status laporan berhasil diubah menjadi ' . $request->status);
    }

    public function storeTanggapan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000',
        ], [
            'catatan.required' => 'Pesan tanggapan tidak boleh kosong.',
        ]);

        $aduan = Aduan::findOrFail($id);

        if (Auth::user()->role === 'siswa' && $aduan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak memberikan tanggapan pada laporan ini.');
        }

        Tanggapan::create([
            'aduan_id' => $aduan->id,
            'user_id'  => Auth::id(),
            'catatan'  => $request->catatan,
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    public function exportExcel(Request $request)
    {
        $query = Aduan::with(['user', 'perangkat', 'kategori'])->latest();

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }

        $aduanList = $query->get();

        $filename = 'laporan-kerusakan-lab-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($aduanList) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar terbaca sempurna di Microsoft Excel (tidak rusak aksen/karakter)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Kolom Excel
            fputcsv($file, [
                'No',
                'Kode Aduan',
                'Tanggal Lapor',
                'Nama Pelapor',
                'Kelas',
                'Kode Perangkat',
                'Nama Perangkat',
                'Lokasi',
                'Kategori',
                'Judul Laporan',
                'Deskripsi Kerusakan',
                'Status'
            ], ';');

            foreach ($aduanList as $index => $aduan) {
                fputcsv($file, [
                    $index + 1,
                    $aduan->kode_aduan,
                    $aduan->created_at->format('d/m/Y H:i'),
                    $aduan->user->name ?? '-',
                    $aduan->user->kelas ?? '-',
                    $aduan->perangkat->kode_perangkat ?? '-',
                    $aduan->perangkat->nama_perangkat ?? '-',
                    $aduan->perangkat->lokasi ?? '-',
                    $aduan->kategori->nama_kategori ?? '-',
                    $aduan->judul,
                    preg_replace("/\r|\n/", " ", $aduan->deskripsi),
                    $aduan->status,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetakLaporan(Request $request)
    {
        $query = Aduan::with(['user', 'perangkat', 'kategori'])->latest();

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }

        $aduanList = $query->get();

        if ($request->get('download') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_pdf', compact('aduanList'));
            return $pdf->download('laporan-kerusakan-lab-' . date('Ymd-His') . '.pdf');
        }

        return view('admin.laporan_pdf', compact('aduanList'));
    }
}
