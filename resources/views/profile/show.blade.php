@extends('layouts.app')
@section('title', $isOwn ? 'Profil Saya' : 'Profil ' . $user->name)

@section('header')
<div class="flex items-center gap-3">
    @if(!$isOwn)
    <a href="{{ url()->previous() }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">&larr;</a>
    @endif
    <h1 class="text-xl font-heading font-bold">{{ $isOwn ? 'Profil Kamu 🧑‍🚀' : $user->name }}</h1>
</div>
@endsection

@section('content')

{{-- Instagram-style Profile Header --}}
<div class="nb-card bg-white p-4 mb-4">

    {{-- Top Row: Avatar + Stats --}}
    <div class="flex items-center gap-4 mb-4">
        <div class="shrink-0">
            <x-avatar :user="$user" size="xl" class="border-4 border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E]" />
        </div>
        <div class="flex-1 grid grid-cols-3 text-center gap-1">
            <div>
                <p class="text-2xl font-bold font-heading">{{ $tripsCount }}</p>
                <p class="text-xs font-medium opacity-70">Trip</p>
            </div>
            <div>
                <p class="text-2xl font-bold font-heading">{{ $wishlistCount }}</p>
                <p class="text-xs font-medium opacity-70">Wishlist</p>
            </div>
            <a href="{{ route('friends.index') }}" class="block hover:opacity-80 transition-opacity">
                <p class="text-2xl font-bold font-heading">{{ $friendsCount }}</p>
                <p class="text-xs font-medium opacity-70 underline decoration-dotted">Teman</p>
            </a>
        </div>
    </div>

    {{-- Name & Bio --}}
    <div class="mb-4">
        <h2 class="text-lg font-heading font-bold leading-tight">{{ $user->name }}</h2>
        @if($user->bio)
            <p class="text-sm font-medium opacity-80 mt-0.5">{{ $user->bio }}</p>
        @endif
        @if($user->phone)
            <p class="text-xs opacity-60 mt-0.5">📞 {{ $user->phone }}</p>
        @endif
    </div>

    {{-- Action Buttons (emoji icons) --}}
    @if($isOwn)
    <div class="flex gap-3">
        <a href="{{ route('profile.edit') }}" title="Edit Profil"
        class="w-1/2 h-11 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-md flex items-center justify-center gap-1.5 text-sm">
            ✏️ Edit
        </a>
        <form action="{{ route('logout') }}" method="POST" class="w-1/2 h-11 flex">
            @csrf
            <button type="submit" title="Keluar"
                class="w-full h-full nb-btn bg-red-100 text-red-600 border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-md flex items-center justify-center gap-1.5 text-sm">
                🚪 Logout
            </button>
        </form>
    </div>
    @else
    <div class="flex gap-3">
        @if($friendshipStatus === 'accepted')
            <form action="{{ route('friends.remove', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus {{ $user->name }} dari daftar teman?');">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full h-11 nb-btn bg-red-100 text-red-600 border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-md text-sm">
                    Hapus Teman
                </button>
            </form>
        @elseif($friendshipStatus === 'pending')
            @if($friendshipInitiator === Auth::id())
                <button type="button" disabled
                    class="w-full h-11 nb-btn bg-gray-100 text-gray-500 border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] rounded-md text-sm cursor-not-allowed">
                    Menunggu Konfirmasi...
                </button>
            @else
                <a href="{{ route('friends.index') }}"
                    class="flex-1 h-11 nb-btn bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-md text-sm flex items-center justify-center">
                    Lihat Permintaan
                </a>
            @endif
        @else
            <form action="{{ route('friends.request', $user) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full h-11 nb-btn bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-md text-sm">
                    + Tambah Teman
                </button>
            </form>
        @endif
    </div>
    @endif
</div>

{{-- Tabs: Trip & Wishlist Grid --}}
<div class="flex gap-2 mb-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]">
    <button id="tab-trips-btn" onclick="switchProfileTab('trips')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 profile-tab-btn active-tab" data-tab="trips">
        🗓️ Trip ({{ $tripsCount }})
    </button>
    <button id="tab-wishlist-btn" onclick="switchProfileTab('wishlist')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 profile-tab-btn" data-tab="wishlist">
        💖 Wishlist ({{ $wishlistCount }})
    </button>
</div>

{{-- Tab: Trip Grid --}}
<div id="tab-trips" class="profile-tab-content">
    @if($trips->count() > 0)
    <div class="grid grid-cols-2 gap-3">
        @foreach($trips as $trip)
        <a href="{{ $trip->is_public ? route('trips.public_show', $trip) : ($isOwn ? route('trips.show', $trip) : '#') }}"
           class="nb-card bg-white p-3 hover:bg-gray-50 transition-colors block">
            {{-- Cover emoji --}}
            <div class="w-full h-24 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-md mb-2 flex items-center justify-center text-4xl overflow-hidden">
                🌴
            </div>
            <h4 class="font-bold font-heading text-sm leading-tight mb-1 truncate">{{ $trip->title }}</h4>
            <p class="text-xs opacity-70 truncate mb-2">📍 {{ $trip->destination }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold {{ $trip->is_public ? 'text-[#00D4AA]' : 'text-gray-400' }}">
                    {{ $trip->is_public ? '🌍' : '🔒' }}
                </span>
                <span class="text-xs font-medium text-[#FF6B9D]">❤️ {{ $trip->likes->count() }}</span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="nb-card bg-white p-8 text-center border-dashed">
        <div class="text-4xl mb-2">🗓️</div>
        <h4 class="font-bold font-heading text-lg mb-1">Belum Ada Trip</h4>
        <p class="text-sm opacity-80 font-medium">
            {{ $isOwn ? 'Yuk buat trip pertamamu!' : 'Pengguna ini belum punya trip publik.' }}
        </p>
    </div>
    @endif
</div>

{{-- Tab: Wishlist Grid --}}
<div id="tab-wishlist" class="profile-tab-content hidden">
    @if($wishlists->count() > 0)
    <div class="grid grid-cols-2 gap-3">
        @foreach($wishlists as $trip)
        <a href="{{ $trip->is_public ? route('trips.public_show', $trip) : ($isOwn ? route('trips.show', $trip) : '#') }}"
           class="nb-card bg-white p-3 hover:bg-[#FFF5F8] transition-colors block border-[#FF6B9D]">
            <div class="w-full h-24 bg-[#FFF0F5] border-[3px] border-[#FF6B9D] rounded-md mb-2 flex items-center justify-center text-4xl">
                💭
            </div>
            <h4 class="font-bold font-heading text-sm leading-tight mb-1 truncate">{{ $trip->title }}</h4>
            <p class="text-xs opacity-70 truncate mb-2">📍 {{ $trip->destination }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold {{ $trip->is_public ? 'text-[#00D4AA]' : 'text-gray-400' }}">
                    {{ $trip->is_public ? '🌍' : '🔒' }}
                </span>
                <span class="text-xs font-medium text-[#FF6B9D]">❤️ {{ $trip->likes->count() }}</span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="nb-card bg-white p-8 text-center border-dashed">
        <div class="text-4xl mb-2">💖</div>
        <h4 class="font-bold font-heading text-lg mb-1">Belum Ada Wishlist</h4>
        <p class="text-sm opacity-80 font-medium">
            {{ $isOwn ? 'Buat trip tanpa tanggal untuk ke wishlist.' : 'Pengguna ini belum punya wishlist publik.' }}
        </p>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<style>
    .profile-tab-btn { color: #1A1A2E; background: transparent; }
    .active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
    function switchProfileTab(tab) {
        document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.profile-tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
    }
</script>
@endpush
