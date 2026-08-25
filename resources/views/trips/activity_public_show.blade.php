@extends('layouts.app')
@section('title', $activity->title . ' · ' . $trip->title)

@section('header')
<div class="flex items-center gap-2 w-full">
    <a href="{{ url()->previous() }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-lg font-heading font-bold truncate">📸 Detail Aktivitas</h1>
        <p class="text-xs font-medium opacity-60 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')

{{-- ===== FOTO BESAR ===== --}}
@if($activity->photo)
<div class="nb-card p-0 overflow-hidden mb-4 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl">
    <img
        src="{{ Storage::url($activity->photo) }}"
        alt="{{ $activity->title }}"
        class="w-full object-cover"
        style="max-height: 300px;"
    >
    <div class="px-4 py-2 bg-[#E1FCEF] border-t-[3px] border-[#1A1A2E] flex items-center gap-2">
        <span class="text-base">✅</span>
        <span class="text-sm font-bold text-[#00875A]">Dokumentasi Perjalanan</span>
    </div>
</div>
@endif

{{-- ===== INFO UTAMA ===== --}}
<div class="nb-card bg-white p-4 mb-4 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl">

    {{-- Badge kategori + sesi --}}
    <div class="flex items-center gap-2 mb-3 flex-wrap">
        <span class="text-xs font-bold bg-[#FFE156] px-3 py-1 rounded-full border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]">
            {{ $activity->category_icon }} {{ ucfirst($activity->category) }}
        </span>
        <span class="text-xs font-bold bg-white px-3 py-1 rounded-full border-2 border-[#1A1A2E]">
            {{ $activity->session_icon }} {{ ucfirst($activity->session) }}
        </span>
        @if($activity->start_time || $activity->end_time)
        <span class="text-xs font-medium text-gray-600">
            🕐 {{ $activity->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $activity->start_time)->format('H:i') : '' }}
            @if($activity->start_time && $activity->end_time) — @endif
            {{ $activity->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $activity->end_time)->format('H:i') : '' }}
        </span>
        @endif
    </div>

    {{-- Judul aktivitas --}}
    <h2 class="font-heading font-bold text-xl text-[#1A1A2E] leading-tight mb-1">
        {{ $activity->title }}
    </h2>

    {{-- Tanggal hari --}}
    <p class="text-xs font-medium text-gray-500 mb-3">
        📅 Hari {{ $activity->day->day_number }}
        @if($activity->day->date)
            · {{ \Carbon\Carbon::parse($activity->day->date)->translatedFormat('d F Y') }}
        @endif
    </p>

    <div class="border-t-2 border-dashed border-gray-200 my-3"></div>

    {{-- Biaya --}}
    @if($activity->actual_cost !== null && $activity->actual_cost > 0)
    <div class="flex items-center gap-3 mb-3 flex-wrap">
        @if($activity->estimated_cost > 0)
        <div class="flex-1 bg-[#FFF3C4] border-2 border-[#1A1A2E] rounded-xl p-3">
            <p class="text-[10px] font-bold opacity-50 uppercase tracking-wide mb-0.5">Estimasi</p>
            <p class="font-heading font-bold text-sm">Rp {{ number_format($activity->estimated_cost, 0, ',', '.') }}</p>
        </div>
        @endif
        <div class="flex-1 bg-[#E1FCEF] border-2 border-[#00D4AA] rounded-xl p-3">
            <p class="text-[10px] font-bold opacity-50 uppercase tracking-wide mb-0.5">Biaya Real</p>
            <p class="font-heading font-bold text-sm text-[#00875A]">Rp {{ number_format($activity->actual_cost, 0, ',', '.') }}</p>
        </div>
    </div>
    @endif

    {{-- Deskripsi / catatan --}}
    @if($activity->description)
    <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-3">
        <p class="text-[10px] font-bold opacity-50 uppercase tracking-wide mb-1">Catatan</p>
        <p class="text-sm font-medium leading-relaxed">{{ $activity->description }}</p>
    </div>
    @endif

</div>

{{-- ===== LOKASI ===== --}}
@if($activity->location_name || $activity->location_url)
@php
    $mapsUrl = $activity->location_url
        ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($activity->location_name . ' ' . $trip->destination);
@endphp
<div class="nb-card bg-white p-4 mb-4 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl">
    <p class="text-[10px] font-bold opacity-50 uppercase tracking-wide mb-3">📍 Lokasi</p>

    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="block group">
        @if($activity->location_name)
        <div class="flex items-center justify-between gap-2 mb-3">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-lg shrink-0">📍</span>
                <p class="font-heading font-bold text-sm truncate">{{ $activity->location_name }}</p>
            </div>
            <span class="shrink-0 text-xs font-bold text-[#4361EE] bg-[#EEF2FF] border border-[#4361EE] px-2 py-0.5 rounded-full group-hover:bg-[#4361EE] group-hover:text-white transition-colors whitespace-nowrap">
                Buka ↗
            </span>
        </div>

        <div class="relative w-full overflow-hidden border-[3px] border-[#1A1A2E] rounded-xl shadow-[3px_3px_0px_#1A1A2E]" style="height: 180px;">
            <iframe
                src="https://maps.google.com/maps?q={{ urlencode($activity->location_name . ' ' . $trip->destination) }}&output=embed&hl=id&z=15"
                width="100%"
                height="100%"
                style="border:0; pointer-events: none;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Peta {{ $activity->location_name }}"
            ></iframe>
            <div class="absolute inset-0 bg-transparent cursor-pointer"></div>
        </div>
        <p class="text-center text-[10px] text-gray-400 mt-2 font-medium">
            Ketuk peta untuk membuka di Google Maps →
        </p>
        @endif
    </a>
</div>
@endif

{{-- ===== LINK KE TRIP ===== --}}
<div class="nb-card bg-[#1A1A2E] text-white p-4 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#FF6B9D] rounded-2xl flex items-center justify-between gap-3">
    <div class="min-w-0">
        <p class="text-[10px] font-bold opacity-50 uppercase tracking-wide mb-0.5">Bagian dari Trip</p>
        <p class="font-heading font-bold text-sm truncate">{{ $trip->title }}</p>
        @if($trip->destination)
        <p class="text-xs opacity-70 truncate">📍 {{ $trip->destination }}</p>
        @endif
    </div>
    <a
        href="{{ route('trips.public_show', $trip) }}"
        class="shrink-0 px-3 py-2 bg-[#FFE156] text-[#1A1A2E] border-2 border-[#FFE156] rounded-xl font-heading font-extrabold text-xs hover:translate-y-[-2px] transition-transform shadow-[2px_2px_0px_rgba(0,0,0,0.3)] flex items-center gap-1"
    >
        <span>Lihat Trip</span>
        <span>→</span>
    </a>
</div>

@endsection
