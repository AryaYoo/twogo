@extends('layouts.app')

@section('title', 'Search')

@section('header')
<h1 class="text-2xl font-heading font-bold text-center">Search 🔍</h1>
@endsection

@section('content')

<x-card class="mb-6 bg-[#1A1A2E] text-white">
    <form action="{{ route('search') }}" method="GET" class="flex gap-2">
        <input
            type="search"
            name="q"
            value="{{ $query ?? '' }}"
            placeholder="Cari destinasi, trip, atau user..."
            class="flex-1 rounded-sm px-3 py-2 text-[#1A1A2E] font-medium"
            autofocus
            minlength="2"
        >
        <x-button type="submit" variant="mint" class="shrink-0">Cari</x-button>
    </form>
    <p class="text-xs opacity-70 mt-2 font-medium">Min. 2 karakter · trip publik & trip kamu</p>
</x-card>

@if(($query ?? '') === '')
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="text-6xl mb-4">🗺️</div>
        <h2 class="text-xl font-bold font-heading mb-2">Mau cari apa?</h2>
        <p class="text-sm text-gray-600 font-medium max-w-xs">
            Ketik nama destinasi (mis. Bali), judul trip, atau nama pengguna TwoGo.
        </p>
    </div>
@elseif(mb_strlen($query) < 2)
    <x-empty-state icon="⌨️" title="Terlalu pendek" description="Ketik minimal 2 karakter untuk mulai mencari." />
@else

    {{-- Trips --}}
    <div class="mb-8">
        <h3 class="font-heading font-bold text-lg mb-3 flex items-center gap-2">
            Trip & Destinasi
            <span class="bg-[#FFE156] text-[#1A1A2E] text-xs px-2 py-0.5 rounded-full border border-[#1A1A2E]">{{ $trips->count() }}</span>
        </h3>

        <div class="flex flex-col gap-3">
            @forelse($trips as $trip)
                @php
                    $isMember = $trip->members->contains('id', Auth::id());
                    $tripUrl = $trip->is_public
                        ? route('trips.public_show', $trip)
                        : ($isMember ? route('trips.show', $trip) : null);
                @endphp
                <x-card class="bg-white">
                    <div class="flex items-start gap-3">
                        <div class="w-14 h-14 shrink-0 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-md flex items-center justify-center text-2xl">
                            {{ $trip->start_date ? '🗓️' : '💭' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($tripUrl)
                                <a href="{{ $tripUrl }}" class="font-bold font-heading truncate block hover:underline">{{ $trip->title }}</a>
                            @else
                                <h4 class="font-bold font-heading truncate opacity-50">{{ $trip->title }}</h4>
                            @endif
                            <p class="text-sm opacity-80 truncate">📍 {{ $trip->destination }}</p>
                            <div class="flex items-center gap-2 mt-1 text-xs font-medium flex-wrap">
                                <span class="{{ $trip->is_public ? 'text-[#00D4AA]' : 'text-gray-500' }}">
                                    {{ $trip->is_public ? '🌍 Publik' : '🔒 Privat' }}
                                </span>
                                @if($trip->creator)
                                    <a href="{{ route('profile.user', $trip->creator) }}" class="opacity-60 hover:underline">{{ $trip->creator->name }}</a>
                                @endif
                                <span class="text-[#FF6B9D]">❤️ {{ $trip->likes->count() }}</span>
                            </div>
                        </div>
                    </div>
                </x-card>
            @empty
                <div class="text-center py-6 text-sm font-medium opacity-70 nb-card bg-white">
                    Tidak ada trip yang cocok dengan "{{ $query }}"
                </div>
            @endforelse
        </div>
    </div>

    {{-- Users --}}
    <div>
        <h3 class="font-heading font-bold text-lg mb-3 flex items-center gap-2">
            Pengguna
            <span class="bg-[#FF6B9D] text-white text-xs px-2 py-0.5 rounded-full border border-[#1A1A2E]">{{ $users->count() }}</span>
        </h3>

        <div class="flex flex-col gap-3">
            @forelse($users as $user)
                <a href="{{ route('profile.user', $user) }}" class="block">
                    <x-card class="flex items-center gap-3 hover:bg-gray-50 transition-colors">
                        <x-avatar :user="$user" />
                        <div class="flex-1 min-w-0">
                            <div class="font-bold truncate">{{ $user->name }}</div>
                            @if($user->bio)
                                <div class="text-xs opacity-70 truncate">{{ $user->bio }}</div>
                            @else
                                <div class="text-xs opacity-50">Lihat profil publik</div>
                            @endif
                        </div>
                        <span class="text-lg shrink-0">→</span>
                    </x-card>
                </a>
            @empty
                <div class="text-center py-6 text-sm font-medium opacity-70 nb-card bg-white">
                    Tidak ada pengguna yang cocok dengan "{{ $query }}"
                </div>
            @endforelse
        </div>
    </div>

@endif

@endsection
