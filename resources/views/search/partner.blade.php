@extends('layouts.app')
@section('title', 'Open Partner')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('search') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Open Partner 🤝</h1>
    </div>
</div>
@endsection

@section('content')

{{-- Hero Banner --}}
<div class="mb-5 p-4 bg-[#4361EE] text-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] relative overflow-hidden">
    <div class="absolute -right-4 -bottom-4 text-7xl opacity-20 transform rotate-12 select-none">
        🌍
    </div>
    <div class="relative z-10">
        <span class="px-2.5 py-0.5 bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E] inline-block mb-1.5">
            KOMUNITAS TWOGO
        </span>
        <h2 class="text-lg font-heading font-extrabold leading-tight">Cari & Gabung Trip Seru</h2>
        <p class="text-xs font-medium text-blue-100 mt-1 leading-relaxed max-w-xs">
            Temukan partner perjalanan yang sefrekuensi. Lihat rencana turnya dan kirimkan permohonan gabung!
        </p>
    </div>
</div>

{{-- Search & Filter Form --}}
<div class="mb-5 p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3">
    <form action="{{ route('search.partner') }}" method="GET" class="space-y-3">
        {{-- Main search input --}}
        <div>
            <label class="block font-heading font-bold text-xs text-[#1A1A2E] mb-1">Cari Trip</label>
            <input 
                type="search" 
                name="q" 
                value="{{ request('q') }}"
                placeholder="Nama trip / aktivitas..."
                class="nb-input w-full text-xs font-medium"
            >
        </div>

        {{-- Filter Grid: Lokasi, Bulan, Tahun --}}
        <div class="grid grid-cols-3 gap-2">
            {{-- Lokasi / Destinasi --}}
            <div>
                <label class="block font-heading font-bold text-[11px] text-[#1A1A2E] mb-1 truncate">📍 Lokasi</label>
                <input 
                    type="text" 
                    name="location" 
                    list="destinationList"
                    value="{{ request('location') }}"
                    placeholder="Semua"
                    class="nb-input w-full text-xs font-medium px-2 py-2 truncate"
                >
                <datalist id="destinationList">
                    @foreach($availableDestinations as $dest)
                        <option value="{{ $dest }}"></option>
                    @endforeach
                </datalist>
            </div>

            {{-- Bulan --}}
            <div>
                <label class="block font-heading font-bold text-[11px] text-[#1A1A2E] mb-1 truncate">📅 Bulan</label>
                <select name="month" class="nb-input w-full text-xs font-medium bg-white px-2 py-2 truncate">
                    <option value="">Semua</option>
                    @php
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div>
                <label class="block font-heading font-bold text-[11px] text-[#1A1A2E] mb-1 truncate">🗓️ Tahun</label>
                <select name="year" class="nb-input w-full text-xs font-medium bg-white px-2 py-2 truncate">
                    <option value="">Semua</option>
                    @php
                        $currentYear = (int) date('Y');
                        $years = [$currentYear - 1, $currentYear, $currentYear + 1, $currentYear + 2];
                    @endphp
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                            {{ $yr }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 pt-1">
            <button type="submit" class="flex-1 py-2.5 bg-[#FFE156] hover:bg-[#F2D449] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                <span>🔍</span>
                <span>Terapkan Filter</span>
            </button>

            @if(request()->hasAny(['q', 'location', 'month', 'year']))
                <a href="{{ route('search.partner') }}" class="py-2.5 px-3 bg-white hover:bg-slate-100 active:translate-y-[1px] text-slate-700 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] transition-all text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Active Filter Tags --}}
    @if(request()->hasAny(['q', 'location', 'month', 'year']))
        <div class="flex items-center gap-1.5 flex-wrap pt-2 border-t border-slate-100 text-[11px] font-bold text-slate-600">
            <span class="text-slate-400">Filter Aktif:</span>
            @if(request('q'))
                <span class="px-2 py-0.5 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] rounded-md">
                    "{{ request('q') }}"
                </span>
            @endif
            @if(request('location'))
                <span class="px-2 py-0.5 bg-[#00D4AA] text-[#1A1A2E] border border-[#1A1A2E] rounded-md">
                    📍 {{ request('location') }}
                </span>
            @endif
            @if(request('month'))
                <span class="px-2 py-0.5 bg-[#FF6B9D] text-white border border-[#1A1A2E] rounded-md">
                    📅 {{ $months[request('month')] ?? request('month') }}
                </span>
            @endif
            @if(request('year'))
                <span class="px-2 py-0.5 bg-[#4361EE] text-white border border-[#1A1A2E] rounded-md">
                    🗓️ {{ request('year') }}
                </span>
            @endif
        </div>
    @endif
</div>

{{-- Trip List --}}
<div class="space-y-4">
    @forelse($trips as $trip)
        @php
            $isOwner = Auth::check() && ($trip->user_id === Auth::id());
            $isMember = Auth::check() && $trip->members->contains('id', Auth::id());
            $hasPendingRequest = in_array($trip->id, $myPendingTripIds);
        @endphp

        <div class="p-4 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3 relative overflow-hidden">
            {{-- Header info --}}
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap mb-1">
                        <span class="px-2 py-0.5 bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E]">
                            🤝 Lowongan Partner
                        </span>
                        @if($trip->is_public)
                            <span class="px-2 py-0.5 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E]">
                                🌍 Publik
                            </span>
                        @endif
                    </div>
                    <h3 class="font-heading font-extrabold text-lg text-[#1A1A2E] leading-tight truncate">
                        {{ $trip->title }}
                    </h3>
                    <p class="text-xs font-bold text-[#1A1A2E] flex items-center gap-1 mt-0.5">
                        <span>📍</span>
                        <span class="truncate">{{ $trip->destination }}</span>
                    </p>
                </div>

                {{-- Budget --}}
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-bold text-slate-700 block">Est. Budget</span>
                    <span class="text-xs font-heading font-extrabold text-[#1A1A2E]">
                        Rp {{ number_format($trip->total_budget, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Date & Creator --}}
            <div class="flex items-center justify-between gap-2 text-xs pt-1 border-t-2 border-[#1A1A2E]/20 flex-wrap">
                <div class="flex items-center gap-2">
                    <x-avatar :user="$trip->creator" size="sm" class="border-2 border-[#1A1A2E]" />
                    <div>
                        <span class="font-heading font-bold text-[#1A1A2E] text-xs">{{ $trip->creator->name }}</span>
                        <span class="text-[10px] font-bold text-slate-700 block">Host</span>
                    </div>
                </div>

                <div class="text-right font-medium text-slate-800 text-xs">
                    @if($trip->start_date)
                        📅 {{ $trip->start_date->format('d M') }} — {{ $trip->end_date->format('d M Y') }}
                    @else
                        <span class="italic text-[#FF6B9D] font-bold">💖 Wishlist (Fleksibel)</span>
                    @endif
                </div>
            </div>

            {{-- Host Note --}}
            @if($trip->open_partner_note)
                <div class="p-2.5 bg-white border-2 border-[#1A1A2E] rounded-lg text-xs text-slate-800">
                    <span class="font-bold text-[#1A1A2E] block mb-0.5">💬 Catatan Host:</span>
                    <p class="italic font-medium leading-relaxed">"{{ $trip->open_partner_note }}"</p>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 pt-2 border-t-2 border-[#1A1A2E]/20">
                <a href="{{ route('trips.public_show', $trip) }}" class="flex-1 py-2 px-3 bg-white hover:bg-slate-50 border-2 border-[#1A1A2E] rounded-lg font-heading font-bold text-xs text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] text-center transition-all">
                    📋 Lihat Itinerary
                </a>

                @if($isOwner)
                    <span class="py-2 px-3 bg-white border-2 border-slate-300 rounded-lg font-heading font-bold text-xs text-slate-600 text-center">
                        👑 Trip Kamu
                    </span>
                @elseif($isMember)
                    <span class="py-2 px-3 bg-white border-2 border-green-400 rounded-lg font-heading font-bold text-xs text-green-700 text-center">
                        ✅ Anggota
                    </span>
                @elseif($hasPendingRequest)
                    <span class="py-2 px-3 bg-white border-2 border-[#1A1A2E] rounded-lg font-heading font-extrabold text-xs text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] text-center">
                        ⏳ Permohonan Terkirim
                    </span>
                @else
                    <button
                        type="button"
                        onclick="openModal('requestModal_{{ $trip->id }}')"
                        class="flex-1 py-2 px-3 bg-[#00D4AA] hover:bg-[#00B894] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-lg font-heading font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] text-center transition-all cursor-pointer"
                    >
                        🤝 Kirim Permintaan
                    </button>
                @endif
            </div>
        </div>

        {{-- Modal Kirim Permintaan --}}
        @if(!$isOwner && !$isMember && !$hasPendingRequest)
            <x-modal id="requestModal_{{ $trip->id }}" title="Ajukan Jadi Partner 🤝">
                <form action="{{ route('partner-requests.send', $trip) }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Info Singkat Trip --}}
                    <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl flex items-center gap-3">
                        <x-avatar :user="$trip->creator" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                        <div class="min-w-0">
                            <h4 class="font-heading font-bold text-sm text-[#1A1A2E] truncate">{{ $trip->title }}</h4>
                            <p class="text-xs text-slate-600">Host: <span class="font-bold">{{ $trip->creator->name }}</span> · 📍 {{ $trip->destination }}</p>
                        </div>
                    </div>

                    {{-- Textarea Pesan Permohonan --}}
                    <div>
                        <label for="message_{{ $trip->id }}" class="block font-heading font-bold text-xs text-[#1A1A2E] mb-1">
                            Pesan Permohonan ke Host:
                        </label>
                        <textarea
                            name="message"
                            id="message_{{ $trip->id }}"
                            rows="3"
                            maxlength="600"
                            placeholder="Hai! Saya tertarik ikut trip ini karena suka wisata kuliner dan fotografi. Budget dan tanggal saya sangat cocok..."
                            class="nb-input w-full text-xs font-medium"
                            required
                        ></textarea>
                        <p class="text-[10px] text-slate-500 mt-1">Sampaikan perkenalan singkat & alasan kamu cocok menjadi partner perjalanan ini.</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            onclick="closeModal('requestModal_{{ $trip->id }}')"
                            class="flex-1 py-2.5 bg-white hover:bg-gray-100 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="flex-1 py-2.5 bg-[#FFE156] hover:bg-[#F2D449] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                        >
                            Kirim Permintaan
                        </button>
                    </div>
                </form>
            </x-modal>
        @endif
    @empty
        <x-empty-state 
            icon="🤝"
            title="Belum ada lowongan partner"
            description="Saat ini belum ada trip yang berstatus Open Partner. Buka tripmu sendiri atau cari destinasi lain!"
        />
    @endforelse

    {{-- Pagination --}}
    @if($trips->hasPages())
        <div class="pt-4">
            {{ $trips->links() }}
        </div>
    @endif
</div>

@endsection
