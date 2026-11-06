<div class="neo-box p-3 bg-white hover:-translate-y-1 transition-transform flex flex-col justify-between gap-2.5">
    <div>
        <div class="flex items-center justify-between text-[10px] font-bold mb-1">
            <span class="bg-[#F5F3EE] px-1.5 py-0.5 border border-black font-mono">
                {{ $aduan->kode_aduan }}
            </span>
            <span class="px-1.5 py-0.5 border border-black 
                @if(($aduan->kategori?->nama_kategori ?? '') === 'Hardware') bg-[#3D5AFE] text-white 
                @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Software') bg-[#FFE500] text-black 
                @elseif(($aduan->kategori?->nama_kategori ?? '') === 'Jaringan') bg-[#FF3EA5] text-white 
                @else bg-[#B4FF00] text-black @endif">
                {{ $aduan->kategori?->nama_kategori ?? 'Umum' }}
            </span>
        </div>

        <h4 class="font-display text-sm text-[#0A0A0A] line-clamp-2 leading-snug mb-1">
            {{ $aduan->judul }}
        </h4>

        <div class="text-[11px] font-semibold text-neutral-600 mb-1">
            👤 {{ $aduan->user?->name ?? 'Anonim' }} @if($aduan->user?->kelas) ({{ $aduan->user->kelas }}) @endif
        </div>

        <div class="inline-block bg-[#F5F3EE] border border-black px-1.5 py-0.5 text-[10px] font-bold">
            🖥️ {{ $aduan->perangkat?->kode_perangkat ?? '-' }}
        </div>
    </div>

    <!-- Quick Status Changer / Actions -->
    <div class="pt-2 border-t border-neutral-200 flex flex-col gap-1.5">
        <form action="{{ route('admin.aduan.status', $aduan->id) }}" method="POST" class="flex gap-1">
            @csrf
            <select name="status" onchange="this.form.submit()" class="w-full text-[10px] font-bold bg-[#F5F3EE] border border-black px-1 py-1 focus:outline-none cursor-pointer">
                <option value="PENDING" {{ $aduan->status === 'PENDING' ? 'selected' : '' }}>Set: PENDING</option>
                <option value="IN_PROGRESS" {{ $aduan->status === 'IN_PROGRESS' ? 'selected' : '' }}>Set: DIPROSES</option>
                <option value="RESOLVED" {{ $aduan->status === 'RESOLVED' ? 'selected' : '' }}>Set: SELESAI</option>
                <option value="REJECTED" {{ $aduan->status === 'REJECTED' ? 'selected' : '' }}>Set: TOLAK</option>
            </select>
        </form>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('aduan.show', $aduan->id) }}" class="btn-neo btn-neo-white text-[10px] py-1 border shadow-[1px_1px_0_#0A0A0A] hover:bg-[#FFE500] text-center flex-1">
                DETAIL & TANGGAPAN →
            </a>
            <form action="{{ route('admin.aduan.destroy', $aduan->id) }}" method="POST" onsubmit="return confirm('Hapus permanen aduan {{ $aduan->kode_aduan }}?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus Aduan Ini" class="btn-neo btn-neo-pink text-[10px] py-1 px-2 border shadow-[1px_1px_0_#0A0A0A] hover:bg-red-600">
                    🗑️
                </button>
            </form>
        </div>
    </div>
</div>
