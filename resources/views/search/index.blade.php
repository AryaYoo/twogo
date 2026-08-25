@extends('layouts.app')

@section('title', 'Search')

@section('header')
<h1 class="text-xl font-heading font-bold">Search 🔍</h1>
@endsection

@section('content')

@php
    $activeTab = request('tab', 'search');
@endphp

{{-- ===== TAB NAV ===== --}}
<div class="grid grid-cols-4 gap-2 mb-5">
    @php
        $tabs = [
            'search'   => ['icon' => '🔍', 'label' => 'Cari',          'soon' => false],
            'code'     => ['icon' => '🎟️', 'label' => 'Kode Trip',     'soon' => false],
            'partner'  => ['icon' => '🤝', 'label' => 'Open Partner',  'soon' => true],
            'popular'  => ['icon' => '🌟', 'label' => 'Trip Populer',  'soon' => true],
        ];
    @endphp

    @foreach($tabs as $key => $tab)
    @php
        $isActive = $activeTab === $key;
        $base = 'relative flex flex-col items-center justify-center gap-1 py-2.5 px-1 rounded-xl border-[2.5px] border-[#1A1A2E] transition-all duration-150 cursor-pointer font-heading font-bold text-[10px] shadow-[2px_2px_0px_#1A1A2E]';
        $style = $isActive
            ? $base . ' bg-[#FFE156] text-[#1A1A2E] translate-y-[-2px]'
            : $base . ' bg-white text-[#1A1A2E] opacity-70 hover:opacity-100 hover:translate-y-[-1px]';
    @endphp
    <a href="{{ route('search', ['tab' => $key]) }}" class="{{ $style }}">
        <span class="text-xl leading-none">{{ $tab['icon'] }}</span>
        <span class="leading-tight text-center">{{ $tab['label'] }}</span>
        @if($tab['soon'])
            <span class="absolute -top-1.5 -right-1 bg-[#4361EE] text-white text-[7px] font-bold px-1 py-0.5 rounded-full border border-white leading-none">SOON</span>
        @endif
    </a>
    @endforeach
</div>

{{-- ============================= --}}
{{-- TAB: CARI                     --}}
{{-- ============================= --}}
@if($activeTab === 'search')

<x-card class="mb-5 bg-[#1A1A2E] text-white">
    <form action="{{ route('search', ['tab' => 'search']) }}" method="GET" class="flex gap-2">
        <input type="hidden" name="tab" value="search">
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
    <div class="flex flex-col items-center justify-center py-12 text-center">
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
                                <span class="text-[#FF6B9D] flex items-center gap-0.5">❤️ {{ $trip->likes->count() }}</span>
                                <span class="text-[#4361EE] flex items-center gap-0.5">📋 {{ $trip->clones()->count() }}</span>
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

{{-- ============================= --}}
{{-- TAB: KODE PERJALANAN          --}}
{{-- ============================= --}}
@elseif($activeTab === 'code')

<div class="flex flex-col gap-4">

    {{-- Hero --}}
    <div class="nb-card bg-[#1A1A2E] text-white p-5 flex flex-col items-center text-center gap-2">
        <div class="text-5xl">🎟️</div>
        <h2 class="font-heading font-bold text-lg">Masukkan Kode Perjalanan</h2>
        <p class="text-sm opacity-70 leading-relaxed max-w-xs">
            Punya kode dari temanmu? Masukkan di sini untuk langsung bergabung ke trip mereka!
        </p>
    </div>

    {{-- Form --}}
    <x-card>
        <form action="{{ route('invitations.join_code') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            @if($errors->has('invite_code'))
                <div class="bg-red-50 border-[2px] border-red-400 rounded-lg p-3 text-sm text-red-600 font-medium flex gap-2 items-start">
                    <span>❌</span>
                    <span>{{ $errors->first('invite_code') }}</span>
                </div>
            @endif

            <div class="nb-form-group">
                <label class="nb-label" for="invite_code">Kode Perjalanan (6 huruf)</label>
                <input
                    id="invite_code"
                    type="text"
                    name="invite_code"
                    value="{{ old('invite_code') }}"
                    placeholder="Contoh: ABCD12"
                    maxlength="6"
                    autocomplete="off"
                    autofocus
                    class="nb-input uppercase tracking-[0.3em] font-mono font-bold text-center text-xl placeholder:tracking-normal placeholder:font-normal placeholder:text-sm placeholder:font-sans"
                />
                <p class="text-xs text-gray-500 mt-1.5 font-medium text-center">Kode tidak case-sensitive · otomatis dikonversi ke huruf besar</p>
            </div>

            <x-button type="submit" variant="primary" class="w-full text-base">
                🚀 Bergabung ke Trip
            </x-button>
        </form>
    </x-card>

    {{-- Info --}}
    <div class="nb-card bg-[#E1FCEF] border-[#00D4AA] p-4 flex gap-3 items-start">
        <div class="text-xl shrink-0">💡</div>
        <div class="text-sm font-medium text-gray-700 leading-relaxed">
            Kode perjalanan bisa ditemukan di halaman <strong>Kelola Undangan</strong> milik trip yang ingin kamu ikuti. Minta temanmu untuk berbagi kodenya!
        </div>
    </div>

</div>

{{-- ============================= --}}
{{-- TAB: OPEN PARTNER             --}}
{{-- ============================= --}}
@elseif($activeTab === 'partner')

<div class="flex flex-col items-center text-center py-10 gap-5">
    <div class="relative">
        <div class="text-7xl">🤝</div>
        <span class="absolute -top-1 -right-3 bg-[#4361EE] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">SOON</span>
    </div>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-2">Open Partner</h2>
        <p class="text-sm font-medium opacity-60 leading-relaxed max-w-xs">
            Fitur ini akan memungkinkan kamu <strong class="opacity-100">membuka tripmu</strong> untuk mencari partner perjalanan — baik dari lingkaran pertemanan maupun publik luas.
        </p>
    </div>

    <div class="w-full max-w-xs flex flex-col gap-3">
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">👥</span>
            <div>
                <p class="font-bold text-sm">Cari dari teman</p>
                <p class="text-xs opacity-60">Temukan partner dari lingkaran pertemananmu</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">🌍</span>
            <div>
                <p class="font-bold text-sm">Cari secara publik</p>
                <p class="text-xs opacity-60">Buka tripmu ke semua pengguna TwoGo</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">🔔</span>
            <div>
                <p class="font-bold text-sm">Notifikasi otomatis</p>
                <p class="text-xs opacity-60">Dapat notifikasi saat ada yang tertarik</p>
            </div>
        </div>
    </div>

    <div class="nb-card bg-[#EEF2FF] border-[#4361EE] p-4 max-w-xs text-sm font-medium text-[#4361EE] leading-relaxed">
        🛠️ Sedang dalam pengembangan. Stay tuned untuk update berikutnya!
    </div>
</div>

{{-- ============================= --}}
{{-- TAB: TRIP POPULER             --}}
{{-- ============================= --}}
@elseif($activeTab === 'popular')

<div class="flex flex-col items-center text-center py-10 gap-5">
    <div class="relative">
        <div class="text-7xl">🌟</div>
        <span class="absolute -top-1 -right-3 bg-[#4361EE] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">SOON</span>
    </div>
    <div>
        <h2 class="font-heading font-bold text-2xl mb-2">Trip Populer</h2>
        <p class="text-sm font-medium opacity-60 leading-relaxed max-w-xs">
            Kumpulan destinasi dan itinerary <strong class="opacity-100">pilihan tim TwoGo</strong> yang bisa kamu jadikan referensi perjalananmu berikutnya.
        </p>
    </div>

    <div class="w-full max-w-xs flex flex-col gap-3">
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">🏷️</span>
            <div>
                <p class="font-bold text-sm">Kurasi Tim TwoGo</p>
                <p class="text-xs opacity-60">Hanya trip dengan tag "Populer" yang masuk</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">📋</span>
            <div>
                <p class="font-bold text-sm">Salin ke Wishlist</p>
                <p class="text-xs opacity-60">Langsung salin itinerary ke wishlistmu</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-3 items-start">
            <span class="text-xl">🗺️</span>
            <div>
                <p class="font-bold text-sm">Berbagai Destinasi</p>
                <p class="text-xs opacity-60">Lokal & mancanegara untuk semua budget</p>
            </div>
        </div>
    </div>

    <div class="nb-card bg-[#EEF2FF] border-[#4361EE] p-4 max-w-xs text-sm font-medium text-[#4361EE] leading-relaxed">
        🛠️ Tim kami sedang menyiapkan koleksi trip terbaik. Segera hadir!
    </div>
</div>

@endif

@endsection

@push('scripts')
<script>
    // Auto-uppercase kode perjalanan
    var codeInput = document.getElementById('invite_code');
    if (codeInput) {
        codeInput.addEventListener('input', function () {
            var pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        });
    }
</script>
@endpush
