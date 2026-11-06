<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kerusakan Lab Komputer</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
            margin: 20px;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-header {
            text-align: center;
        }
        .kop-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
            letter-spacing: 1px;
        }
        .kop-header h2 {
            margin: 2px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .kop-header p {
            margin: 0;
            font-size: 10px;
            color: #333;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 14px;
        }
        .judul-laporan h3 {
            margin: 0;
            font-size: 13px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-laporan p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #555;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        .badge-pending { color: #b7791f; font-weight: bold; }
        .badge-progress { color: #2b6cb0; font-weight: bold; }
        .badge-resolved { color: #2f855a; font-weight: bold; }
        .badge-rejected { color: #c53030; font-weight: bold; }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    @if(request('download') !== 'pdf')
    <!-- Tombol Cetak Browser untuk Mode Web Preview -->
    <div class="no-print" style="margin-bottom: 20px; padding: 12px; background: #FFE500; border: 2px solid #0A0A0A; font-family: sans-serif; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong>🖨️ Mode Pratinjau Laporan</strong> — Bisa langsung cetak, unduh file Excel (langsung bisa diedit), atau unduh PDF.
        </div>
        <div>
            <a href="{{ route('admin.laporan.excel', request()->all()) }}" style="background: #B4FF00; color: #0A0A0A; padding: 6px 12px; text-decoration: none; font-weight: bold; font-size: 12px; border: 2px solid black; margin-right: 8px;">
                📊 UNDUH EXCEL
            </a>
            <a href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}" style="background: #3D5AFE; color: white; padding: 6px 12px; text-decoration: none; font-weight: bold; font-size: 12px; border: 2px solid black; margin-right: 8px;">
                📥 UNDUH PDF
            </a>
            <button onclick="window.print()" style="background: #0A0A0A; color: white; padding: 6px 14px; cursor: pointer; font-weight: bold; font-size: 12px; border: 2px solid black;">
                🖨️ CETAK (PRINT)
            </button>
        </div>
    </div>
    @endif

    <!-- KOP SURAT RESMI -->
    <table class="kop-table">
        <tr>
            <td class="kop-header">
                <h3>PEMERINTAH DAERAH PROVINSI</h3>
                <h2>SMK NEGERI 5 JAKARTA</h2>
                <h3>UNIT LABORATORIUM KOMPUTER & INFORMATIKA</h3>
                <p>Jl. Pisangan Timur Tujuh, Pulo Gadung, Jakarta Timur | Telp: (021) 4720245 | Website: smkn5jkt.sch.id</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <h3>REKAPITULASI LAPORAN KERUSAKAN PERANGKAT LAB</h3>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB @if(request('status') && request('status') !== 'ALL') | Filter Status: {{ request('status') }} @endif</p>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 65px;">Kode</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 110px;">Pelapor</th>
                <th style="width: 75px;">Perangkat</th>
                <th style="width: 70px;">Kategori</th>
                <th>Rincian Kendala</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aduanList as $index => $aduan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center text-bold">{{ $aduan->kode_aduan }}</td>
                    <td class="text-center">{{ $aduan->created_at->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $aduan->user->name }}</strong><br>
                        <small style="color: #666;">{{ $aduan->user->kelas ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                        <strong>{{ $aduan->perangkat->kode_perangkat }}</strong><br>
                        <small>{{ $aduan->perangkat->lokasi }}</small>
                    </td>
                    <td class="text-center">{{ $aduan->kategori->nama_kategori }}</td>
                    <td>
                        <strong>{{ $aduan->judul }}</strong><br>
                        <span style="color: #444;">{{ Str::limit($aduan->deskripsi, 80) }}</span>
                    </td>
                    <td class="text-center">
                        @if($aduan->status === 'PENDING')
                            <span class="badge-pending">PENDING</span>
                        @elseif($aduan->status === 'IN_PROGRESS')
                            <span class="badge-progress">DIPROSES</span>
                        @elseif($aduan->status === 'RESOLVED')
                            <span class="badge-resolved">SELESAI</span>
                        @else
                            <span class="badge-rejected">DITOLAK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #888;">
                        Tidak ada data laporan aduan untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN KOORDINATOR -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p style="margin: 0;">Jakarta, {{ date('d F Y') }}</p>
            <p style="margin: 4px 0 60px 0;">Koordinator Lab Komputer,</p>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">Pak Budi, S.Kom.</p>
            <p style="margin: 2px 0 0 0; color: #555;">NIP. 19850312 201101 1 008</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
