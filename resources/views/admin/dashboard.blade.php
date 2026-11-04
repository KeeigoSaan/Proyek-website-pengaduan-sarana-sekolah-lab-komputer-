@extends('layouts.app', ['title' => 'Admin Dashboard - SIGAP-LAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display text-3xl md:text-4xl text-[#0A0A0A] uppercase tracking-tight">
                PAPAN KELOLA ADUAN
            </h1>
            <p class="text-xs md:text-sm font-semibold text-neutral-600 mt-1">
                Kelola status perbaikan dan tanggapi laporan kerusakan lab secara terpusat
            </p>
        </div>
        <!-- Action & Search bar -->
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.laporan.excel') }}" class="btn-neo btn-neo-lime text-xs px-4 py-2 shadow-[3px_3px_0_#0A0A0A] hover:bg-[#FFE500]">
                📊 EXPORT EXCEL (CSV)
            </a>
            <a href="{{ route('admin.laporan.cetak') }}" target="_blank" class="btn-neo btn-neo-white text-xs px-4 py-2 shadow-[3px_3px_0_#0A0A0A] hover:bg-[#FFE500]">
                🖨️ CETAK (PDF)
            </a>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center w-full sm:w-72">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari judul, siswa, atau PC..." 
                    class="w-full bg-[#FFFFFF] border-[3px] border-[#0A0A0A] px-3 py-2 text-xs font-bold focus:outline-none"
                >
                <button type="submit" class="btn-neo btn-neo-yellow px-4 py-2 text-xs border-[3px] border-l-0">
                    CARI
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
        <div class="neo-box p-3 bg-white">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block">TOTAL</span>
            <span class="font-display text-2xl text-[#0A0A0A]">{{ $counts['total'] }}</span>
        </div>
        <div class="neo-box p-3 bg-white border-b-4 border-b-[#FFE500]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block">PENDING</span>
            <span class="font-display text-2xl text-[#0A0A0A]">{{ $counts['PENDING'] }}</span>
        </div>
        <div class="neo-box p-3 bg-white border-b-4 border-b-[#3D5AFE]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block">DIPROSES</span>
            <span class="font-display text-2xl text-[#0A0A0A]">{{ $counts['IN_PROGRESS'] }}</span>
        </div>
        <div class="neo-box p-3 bg-white border-b-4 border-b-[#B4FF00]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block">SELESAI</span>
            <span class="font-display text-2xl text-[#0A0A0A]">{{ $counts['RESOLVED'] }}</span>
        </div>
        <div class="neo-box p-3 bg-white border-b-4 border-b-[#FF3EA5]">
            <span class="font-display text-[10px] tracking-wider text-neutral-500 uppercase block">DITOLAK</span>
            <span class="font-display text-2xl text-[#0A0A0A]">{{ $counts['REJECTED'] }}</span>
        </div>
    </div>

    <!-- Kanban Board 4 Columns -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
        
        <!-- KOLOM 1: PENDING -->
        <div class="neo-box bg-[#F5F3EE] p-3 flex flex-col gap-3">
            <div class="bg-[#FFE500] border-2 border-[#0A0A0A] p-2.5 flex items-center justify-between shadow-[2px_2px_0_#0A0A0A]">
                <span class="font-display text-xs text-black uppercase tracking-wider">⏳ PENDING</span>
                <span class="font-display text-xs bg-black text-white px-2 py-0.5 rounded-sm">
                    {{ $columns['PENDING']->count() }}
                </span>
            </div>

            <div class="flex flex-col gap-3 min-h-[150px]">
                @forelse($columns['PENDING'] as $c)
                    @include('admin._card', ['aduan' => $c])
                @empty
                    <div class="text-center py-8 text-neutral-400 font-bold text-xs">
                        Tidak ada aduan pending
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: IN PROGRESS -->
        <div class="neo-box bg-[#F5F3EE] p-3 flex flex-col gap-3">
            <div class="bg-[#3D5AFE] text-white border-2 border-[#0A0A0A] p-2.5 flex items-center justify-between shadow-[2px_2px_0_#0A0A0A]">
                <span class="font-display text-xs uppercase tracking-wider">🔧 DIPROSES</span>
                <span class="font-display text-xs bg-black text-white px-2 py-0.5 rounded-sm">
                    {{ $columns['IN_PROGRESS']->count() }}
                </span>
            </div>

            <div class="flex flex-col gap-3 min-h-[150px]">
                @forelse($columns['IN_PROGRESS'] as $c)
                    @include('admin._card', ['aduan' => $c])
                @empty
                    <div class="text-center py-8 text-neutral-400 font-bold text-xs">
                        Tidak ada yang sedang diproses
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 3: RESOLVED -->
        <div class="neo-box bg-[#F5F3EE] p-3 flex flex-col gap-3">
            <div class="bg-[#B4FF00] border-2 border-[#0A0A0A] p-2.5 flex items-center justify-between shadow-[2px_2px_0_#0A0A0A]">
                <span class="font-display text-xs text-black uppercase tracking-wider">✅ SELESAI</span>
                <span class="font-display text-xs bg-black text-white px-2 py-0.5 rounded-sm">
                    {{ $columns['RESOLVED']->count() }}
                </span>
            </div>

            <div class="flex flex-col gap-3 min-h-[150px]">
                @forelse($columns['RESOLVED'] as $c)
                    @include('admin._card', ['aduan' => $c])
                @empty
                    <div class="text-center py-8 text-neutral-400 font-bold text-xs">
                        Belum ada yang selesai
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 4: REJECTED -->
        <div class="neo-box bg-[#F5F3EE] p-3 flex flex-col gap-3">
            <div class="bg-[#FF3EA5] text-white border-2 border-[#0A0A0A] p-2.5 flex items-center justify-between shadow-[2px_2px_0_#0A0A0A]">
                <span class="font-display text-xs uppercase tracking-wider">✕ DITOLAK</span>
                <span class="font-display text-xs bg-black text-white px-2 py-0.5 rounded-sm">
                    {{ $columns['REJECTED']->count() }}
                </span>
            </div>

            <div class="flex flex-col gap-3 min-h-[150px]">
                @forelse($columns['REJECTED'] as $c)
                    @include('admin._card', ['aduan' => $c])
                @empty
                    <div class="text-center py-8 text-neutral-400 font-bold text-xs">
                        Tidak ada aduan ditolak
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
