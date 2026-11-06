@extends('layouts.app', ['title' => 'Dashboard Siswa'])

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display text-3xl md:text-4xl text-[#0A0A0A] uppercase tracking-tight">
                ADUAN SAYA
            </h1>
            <p class="text-xs md:text-sm font-semibold text-neutral-600 mt-1">
                Pantau status aduan kerusakan perangkat lab yang kamu laporkan
            </p>
        </div>
        <div>
            <a href="{{ route('siswa.aduan.create') }}" class="btn-neo btn-neo-yellow px-5 py-3 text-xs md:text-sm shadow-[4px_4px_0_#0A0A0A]">
                + BUAT ADUAN BARU
            </a>
        </div>
    </div>

    <!-- Summary Count Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Total -->
        <div class="neo-box p-4 bg-white">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block mb-1">
                TOTAL ADUAN
            </span>
            <span class="font-display text-3xl md:text-4xl text-[#0A0A0A]">
                {{ $counts['total'] }}
            </span>
        </div>

        <!-- Pending -->
        <div class="neo-box p-4 bg-white border-l-[8px] border-l-[#FFE500]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block mb-1">
                MENUNGGU
            </span>
            <span class="font-display text-3xl md:text-4xl text-[#0A0A0A]">
                {{ $counts['pending'] }}
            </span>
        </div>

        <!-- In Progress -->
        <div class="neo-box p-4 bg-white border-l-[8px] border-l-[#3D5AFE]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block mb-1">
                SEDANG DIPROSES
            </span>
            <span class="font-display text-3xl md:text-4xl text-[#0A0A0A]">
                {{ $counts['inProgress'] }}
            </span>
        </div>

        <!-- Resolved -->
        <div class="neo-box p-4 bg-white border-l-[8px] border-l-[#B4FF00]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block mb-1">
                SELESAI
            </span>
            <span class="font-display text-3xl md:text-4xl text-[#0A0A0A]">
                {{ $counts['resolved'] }}
            </span>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="neo-box p-4 mb-6 bg-white flex flex-col md:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('siswa.dashboard') }}" class="w-full md:w-80 flex items-center">
            <input 
                type="text" 
                name="q" 
                value="{{ request('q') }}" 
                placeholder="Cari judul atau PC..." 
                class="w-full bg-[#F5F3EE] border-2 border-[#0A0A0A] px-3 py-2 text-xs font-bold focus:outline-none"
            >
            <button type="submit" class="btn-neo btn-neo-yellow px-3 py-2 text-xs border-2 ml-1">
                CARI
            </button>
        </form>

        <div class="flex flex-wrap gap-2 text-xs">
            <a href="{{ route('siswa.dashboard') }}" class="px-3 py-1.5 border-2 border-[#0A0A0A] font-bold {{ !request('status') || request('status') === 'ALL' ? 'bg-[#0A0A0A] text-white' : 'bg-white text-black hover:bg-[#F5F3EE]' }}">
                SEMUA
            </a>
            <a href="{{ route('siswa.dashboard', ['status' => 'PENDING', 'q' => request('q')]) }}" class="px-3 py-1.5 border-2 border-[#0A0A0A] font-bold {{ request('status') === 'PENDING' ? 'bg-[#FFE500] text-black' : 'bg-white text-black hover:bg-[#FFE500]' }}">
                PENDING
            </a>
            <a href="{{ route('siswa.dashboard', ['status' => 'IN_PROGRESS', 'q' => request('q')]) }}" class="px-3 py-1.5 border-2 border-[#0A0A0A] font-bold {{ request('status') === 'IN_PROGRESS' ? 'bg-[#3D5AFE] text-white' : 'bg-white text-black hover:bg-[#3D5AFE] hover:text-white' }}">
                DIPROSES
            </a>
            <a href="{{ route('siswa.dashboard', ['status' => 'RESOLVED', 'q' => request('q')]) }}" class="px-3 py-1.5 border-2 border-[#0A0A0A] font-bold {{ request('status') === 'RESOLVED' ? 'bg-[#B4FF00] text-black' : 'bg-white text-black hover:bg-[#B4FF00]' }}">
                SELESAI
            </a>
        </div>
    </div>

    <!-- Complaint List -->
    <div class="flex flex-col gap-4">
        @forelse($aduanList as $aduan)
            <div class="neo-box p-5 bg-white hover:translate-x-1 hover:-translate-y-1 transition-transform">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <!-- Left: Foto Bukti Preview + Detail Info -->
                    <div class="flex flex-col sm:flex-row gap-4 flex-1 items-start">
                        <!-- Thumbnail Foto Bukti -->
                        @if($aduan->foto_bukti)
                            <div class="shrink-0 group">
                                <a href="{{ route('aduan.show', $aduan->id) }}" class="block" title="Klik untuk lihat detail dan foto penuh">
                                    <div class="w-24 h-24 sm:w-28 sm:h-28 bg-[#F5F3EE] border-[3px] border-[#0A0A0A] shadow-[3px_3px_0_#0A0A0A] overflow-hidden relative group-hover:shadow-[4px_4px_0_#FFE500] transition-all">
                                        <img 
                                            src="{{ asset('storage/' . $aduan->foto_bukti) }}" 
                                            alt="Foto Bukti {{ $aduan->kode_aduan }}" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-200"
                                            loading="lazy"
                                        >
                                        <span class="absolute bottom-0 inset-x-0 bg-[#0A0A0A]/90 text-[#FFE500] text-[9px] font-display text-center py-0.5 tracking-wider uppercase">
                                            📷 BUKTI FOTO
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="shrink-0 hidden sm:flex w-24 h-24 sm:w-28 sm:h-28 bg-[#F5F3EE] border-2 border-dashed border-neutral-300 items-center justify-center text-center p-2">
                                <div>
                                    <span class="text-2xl block opacity-40">📷</span>
                                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-tight">Tanpa Foto</span>
                                </div>
                            </div>
                        @endif

                        <!-- Detail Info -->
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-black bg-[#F5F3EE] px-2 py-0.5 border border-[#0A0A0A]">
                                    {{ $aduan->kode_aduan }}
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 border border-[#0A0A0A] 
                                    @if(($aduan->kategori?->nama_kategori ?? '') === 'Hardware') bg-[#3D5AFE] text-white 
                                    @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Software') bg-[#FFE500] text-black 
                                    @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Jaringan') bg-[#FF3EA5] text-white 
                                    @else bg-[#B4FF00] text-black @endif">
                                    {{ $aduan->kategori?->nama_kategori ?? 'Umum' }}
                                </span>
                                <span class="text-xs font-black px-2 py-0.5 border border-[#0A0A0A] bg-white">
                                    🖥️ {{ $aduan->perangkat?->kode_perangkat ?? '-' }}
                                </span>
                            </div>

                            <h3 class="font-display text-lg text-[#0A0A0A]">
                                {{ $aduan->judul }}
                            </h3>

                            <p class="text-xs text-neutral-600 line-clamp-2">
                                {{ $aduan->deskripsi }}
                            </p>

                            <div class="text-[11px] font-bold text-neutral-400">
                                Dilaporkan pada: {{ $aduan->created_at->format('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Status & Action -->
                    <div class="flex md:flex-col items-center md:items-end justify-between gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-neutral-200 shrink-0">
                        @if($aduan->status === 'PENDING')
                            <span class="font-display text-xs px-3 py-1 bg-[#FFE500] text-black border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                                PENDING
                            </span>
                        @elseif($aduan->status === 'IN_PROGRESS')
                            <span class="font-display text-xs px-3 py-1 bg-[#3D5AFE] text-white border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                                DIPROSES
                            </span>
                        @elseif($aduan->status === 'RESOLVED')
                            <span class="font-display text-xs px-3 py-1 bg-[#B4FF00] text-black border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                                SELESAI
                            </span>
                        @else
                            <span class="font-display text-xs px-3 py-1 bg-[#FF3EA5] text-white border-2 border-[#0A0A0A] shadow-[2px_2px_0_#0A0A0A]">
                                DITOLAK
                            </span>
                        @endif

                        <a href="{{ route('aduan.show', $aduan->id) }}" class="btn-neo btn-neo-white text-xs px-4 py-2 border-2 shadow-[2px_2px_0_#0A0A0A] hover:bg-[#FFE500]">
                            LIHAT DETAIL →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="neo-box p-12 text-center bg-white">
                <span class="text-4xl block mb-2">📋</span>
                <h3 class="font-display text-lg text-[#0A0A0A] mb-1">BELUM ADA LAPORAN KERUSAKAN</h3>
                <p class="text-xs text-neutral-500 mb-4">Jika kamu menemukan kendala pada komputer lab, silakan buat laporan baru.</p>
                <a href="{{ route('siswa.aduan.create') }}" class="btn-neo btn-neo-yellow text-xs px-4 py-2">
                    + LAPORKAN KERUSAKAN SEKARANG
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
