@extends('layouts.app', ['title' => 'Detail Aduan ' . $aduan->kode_aduan])

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('siswa.dashboard') }}" class="inline-flex items-center gap-2 font-display text-xs text-[#0A0A0A] hover:underline uppercase tracking-wider">
            ← KEMBALI KE {{ Auth::user()->role === 'admin' ? 'PAPAN KELOLA' : 'DASHBOARD' }}
        </a>
    </div>

    <!-- Main Detail Card -->
    <div class="neo-box p-6 md:p-8 bg-white mb-8">
        <!-- Top bar: Kode & Status Badge -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-neutral-200 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-black bg-[#F5F3EE] px-3 py-1 border-2 border-black shadow-[2px_2px_0_#0A0A0A]">
                    {{ $aduan->kode_aduan }}
                </span>
                <span class="text-xs font-bold px-2.5 py-1 border-2 border-black 
                    @if(($aduan->kategori?->nama_kategori ?? '') === 'Hardware') bg-[#3D5AFE] text-white 
                    @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Software') bg-[#FFE500] text-black 
                    @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Jaringan') bg-[#FF3EA5] text-white 
                    @else bg-[#B4FF00] text-black @endif">
                    {{ $aduan->kategori?->nama_kategori ?? 'Umum' }}
                </span>
            </div>

            <!-- Status Badge -->
            <div>
                @if($aduan->status === 'PENDING')
                    <span class="font-display text-xs px-3 py-1 bg-[#FFE500] text-black border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                        MENUNGGU (PENDING)
                    </span>
                @elseif($aduan->status === 'IN_PROGRESS')
                    <span class="font-display text-xs px-3 py-1 bg-[#3D5AFE] text-white border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                        SEDANG DIPROSES
                    </span>
                @elseif($aduan->status === 'RESOLVED')
                    <span class="font-display text-xs px-3 py-1 bg-[#B4FF00] text-black border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                        SELESAI (RESOLVED)
                    </span>
                @else
                    <span class="font-display text-xs px-3 py-1 bg-[#FF3EA5] text-white border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                        DITOLAK (REJECTED)
                    </span>
                @endif
            </div>
        </div>

        <!-- Title -->
        <h1 class="font-display text-2xl md:text-3xl text-[#0A0A0A] mb-3">
            {{ $aduan->judul }}
        </h1>

        <!-- Meta info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-[#F5F3EE] p-4 border-2 border-[#0A0A0A] mb-6 text-xs font-semibold">
            <div>
                <span class="text-neutral-500 block text-[10px] uppercase font-display">PELAPOR:</span>
                <span class="text-black font-bold">{{ $aduan->user?->name ?? 'Anonim' }}</span>
                @if($aduan->user?->kelas)
                    <span class="text-neutral-600 block">Kelas: {{ $aduan->user->kelas }}</span>
                @endif
            </div>

            <div>
                <span class="text-neutral-500 block text-[10px] uppercase font-display">PERANGKAT:</span>
                <span class="text-black font-bold">🖥️ {{ $aduan->perangkat?->kode_perangkat ?? '-' }}</span>
                <span class="text-neutral-600 block">{{ $aduan->perangkat?->nama_perangkat ?? '-' }} ({{ $aduan->perangkat?->lokasi ?? '-' }})</span>
            </div>

            <div>
                <span class="text-neutral-500 block text-[10px] uppercase font-display">TANGGAL LAPORAN:</span>
                <span class="text-black font-bold">{{ $aduan->created_at->format('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="font-display text-xs tracking-wider uppercase text-neutral-500 mb-2">
                RINCIAN KENDALA:
            </h3>
            <div class="bg-[#FFFFFF] border-2 border-[#0A0A0A] p-4 text-sm leading-relaxed text-[#0A0A0A] whitespace-pre-line font-medium shadow-[2px_2px_0_#0A0A0A]">
                {{ $aduan->deskripsi }}
            </div>
        </div>

        <!-- Foto Bukti -->
        @if($aduan->foto_bukti)
            <div class="mb-6">
                <h3 class="font-display text-xs tracking-wider uppercase text-neutral-500 mb-2">
                    FOTO BUKTI KERUSAKAN:
                </h3>
                <div class="border-3 border-[#0A0A0A] p-2 inline-block bg-[#F5F3EE] shadow-[4px_4px_0_#0A0A0A]">
                    <img 
                        src="{{ asset('storage/' . $aduan->foto_bukti) }}" 
                        alt="Foto Bukti" 
                        class="max-h-80 w-auto object-cover border border-black"
                    >
                </div>
            </div>
        @endif

        <!-- Panel Perubahan Status untuk Admin -->
        @if(Auth::user()->role === 'admin')
            <div class="mt-8 pt-6 border-t-2 border-neutral-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                    <h3 class="font-display text-xs tracking-wider uppercase text-[#0A0A0A]">
                        🛠️ AKSI STATUS (ADMIN / TEKNISI):
                    </h3>
                    <form action="{{ route('admin.aduan.destroy', $aduan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aduan {{ $aduan->kode_aduan }} secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-neo btn-neo-pink text-[11px] px-3 py-1.5 shadow-[2px_2px_0_#0A0A0A] hover:bg-red-600">
                            🗑️ HAPUS ADUAN
                        </button>
                    </form>
                </div>
                <form action="{{ route('admin.aduan.status', $aduan->id) }}" method="POST" class="flex flex-wrap gap-2">
                    @csrf
                    <button type="submit" name="status" value="PENDING" class="btn-neo text-xs px-3 py-2 border-2 {{ $aduan->status === 'PENDING' ? 'bg-[#FFE500] ring-2 ring-black' : 'bg-white hover:bg-[#FFE500]' }}">
                        ⏳ PENDING
                    </button>
                    <button type="submit" name="status" value="IN_PROGRESS" class="btn-neo text-xs px-3 py-2 border-2 {{ $aduan->status === 'IN_PROGRESS' ? 'bg-[#3D5AFE] text-white ring-2 ring-black' : 'bg-white hover:bg-[#3D5AFE] hover:text-white' }}">
                        🔧 DIPROSES
                    </button>
                    <button type="submit" name="status" value="RESOLVED" class="btn-neo text-xs px-3 py-2 border-2 {{ $aduan->status === 'RESOLVED' ? 'bg-[#B4FF00] text-black ring-2 ring-black' : 'bg-white hover:bg-[#B4FF00]' }}">
                        ✅ SELESAI
                    </button>
                    <button type="submit" name="status" value="REJECTED" class="btn-neo text-xs px-3 py-2 border-2 {{ $aduan->status === 'REJECTED' ? 'bg-[#FF3EA5] text-white ring-2 ring-black' : 'bg-white hover:bg-[#FF3EA5] hover:text-white' }}">
                        ✕ TOLAK
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Section Tanggapan / Riwayat Diskusi -->
    <div class="neo-box p-6 md:p-8 bg-white">
        <h2 class="font-display text-xl text-[#0A0A0A] uppercase mb-4 flex items-center gap-2">
            💬 RIWAYAT TANGGAPAN & TINDAKAN
            <span class="text-xs bg-[#FFE500] text-black px-2 py-0.5 border border-black">
                {{ $aduan->tanggapan->count() }}
            </span>
        </h2>

        <!-- Thread List -->
        <div class="space-y-4 mb-6">
            @forelse($aduan->tanggapan as $t)
                <div class="p-4 border-2 border-[#0A0A0A] {{ ($t->user?->role ?? '') === 'admin' ? 'bg-[#F5F3EE] ml-0 md:ml-6' : 'bg-white mr-0 md:mr-6' }} shadow-[3px_3px_0_#0A0A0A]">
                    <div class="flex items-center justify-between mb-2 pb-2 border-b border-neutral-300">
                        <div class="flex items-center gap-2">
                            <span class="font-display text-xs text-[#0A0A0A]">
                                {{ $t->user?->name ?? 'User Terhapus' }}
                            </span>
                            <span class="text-[10px] font-bold px-2 py-0.5 border border-black uppercase {{ ($t->user?->role ?? '') === 'admin' ? 'bg-[#FFE500] text-black' : 'bg-white text-black' }}">
                                {{ ($t->user?->role ?? '') === 'admin' ? 'TEKNISI / ADMIN' : 'SISWA' }}
                            </span>
                        </div>
                        <span class="text-[10px] font-semibold text-neutral-500">
                            {{ $t->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <p class="text-xs md:text-sm text-neutral-800 leading-relaxed whitespace-pre-line font-medium">
                        {{ $t->catatan }}
                    </p>
                </div>
            @empty
                <div class="p-6 text-center text-xs font-semibold text-neutral-500 border-2 border-dashed border-neutral-300">
                    Belum ada tanggapan atau catatan teknisi untuk laporan ini.
                </div>
            @endforelse
        </div>

        <!-- Form Tambah Tanggapan -->
        <form action="{{ route('aduan.tanggapan', $aduan->id) }}" method="POST" class="pt-4 border-t-2 border-neutral-200">
            @csrf
            <label class="block font-display text-xs tracking-wider uppercase mb-1.5 text-[#0A0A0A]">
                TULIS TANGGAPAN / CATATAN BARU:
            </label>
            <textarea 
                name="catatan" 
                rows="3" 
                placeholder="{{ Auth::user()->role === 'admin' ? 'Contoh: Sudah diperiksa, kabel RAM sudah dibersihkan dan PC sudah normal kembali.' : 'Tulis pesan atau pertanyaan tambahan ke teknisi...' }}"
                required
                class="w-full bg-[#F5F3EE] border-[3px] border-[#0A0A0A] p-3 font-bold text-xs md:text-sm text-[#0A0A0A] focus:outline-none focus:bg-white transition-colors mb-3"
            ></textarea>
            <button type="submit" class="btn-neo btn-neo-yellow px-6 py-2.5 text-xs shadow-[3px_3px_0_#0A0A0A]">
                KIRIM TANGGAPAN →
            </button>
        </form>
    </div>
</div>
@endsection
