@extends('layouts.app')
@section('title', 'Daftar Trip')

@section('header')
<div class="flex-1">
    <h1 class="text-xl font-heading font-bold">Trip Kamu ✈️</h1>
    <p class="text-sm font-medium opacity-80">Rencanakan perjalanan seru berikutnya</p>
</div>
@endsection

@section('content')

{{-- Tab Navigation --}}
<div class="flex gap-2 mb-5 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]">
    <button id="tab-trip-btn"
        onclick="switchTab('trip')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 tab-btn active-tab"
        data-tab="trip">
        🗓️ Trip
        @if($trips->count() > 0)
            <span class="ml-1 bg-[#1A1A2E] text-[#FFE156] text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $trips->count() }}</span>
        @endif
    </button>
    <button id="tab-wishlist-btn"
        onclick="switchTab('wishlist')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 tab-btn"
        data-tab="wishlist">
        💖 Wishlist
        @if($wishlists->count() > 0)
            <span class="ml-1 bg-[#FF6B9D] text-white text-xs font-bold px-1.5 py-0.5 rounded-full border border-[#1A1A2E]">{{ $wishlists->count() }}</span>
        @endif
    </button>
</div>

{{-- TAB: TRIP --}}
<div id="tab-trip" class="tab-content flex flex-col gap-4">
    @forelse($trips as $trip)
    <a href="{{ route('trips.show', $trip) }}" class="block">
        <x-card class="bg-[#FFE156] hover:bg-[#F2D449] transition-colors relative overflow-hidden">
            {{-- Pattern --}}
            <div class="absolute -right-10 -bottom-10 opacity-10 transform rotate-12">
                <div class="text-[100px]">🌴</div>
            </div>
            
            <div class="flex justify-between items-start mb-2 relative z-10">
                <h3 class="font-heading font-bold text-xl leading-tight w-3/4">{{ $trip->title }}</h3>
                <x-badge color="{{ $trip->status === 'planning' ? 'pink' : ($trip->status === 'ongoing' ? 'mint' : 'gray') }}">
                    {{ ucfirst($trip->status) }}
                </x-badge>
            </div>
            
            <div class="flex items-center gap-2 text-sm font-medium opacity-90 mb-4 relative z-10 flex-wrap">
                <span>📍 {{ $trip->destination }}</span>
                <span>•</span>
                <span>📅 {{ $trip->start_date->format('d M y') }} — {{ $trip->end_date->format('d M y') }}</span>
                @if($trip->clonedFrom)
                    <span>•</span>
                    <span class="text-xs bg-[#4361EE] text-white px-2 py-0.5 rounded border border-[#1A1A2E] font-bold">Salin dari {{ '@' . $trip->clonedFrom->creator->name }}</span>
                @endif
            </div>
            
            <div class="flex justify-between items-end relative z-10">
                <div class="flex -space-x-2">
                    @foreach($trip->members as $member)
                    <x-avatar :user="$member" size="sm" class="border-2 border-[#1A1A2E]" />
                    @endforeach
                </div>
                
                <div class="text-right">
                    <div class="text-xs font-bold opacity-80">Budget</div>
                    <div class="font-bold">Rp {{ number_format($trip->total_budget, 0, ',', '.') }}</div>
                </div>
            </div>
        </x-card>
    </a>
    @empty
    <x-empty-state 
        icon="🗓️" 
        title="Belum ada trip" 
        description="Yuk mulai rencanakan liburan pertamamu!"
    >
        <x-button href="{{ route('trips.create') }}" variant="primary" class="mt-4">Buat Trip Baru</x-button>
    </x-empty-state>
    @endforelse
</div>

{{-- TAB: WISHLIST --}}
<div id="tab-wishlist" class="tab-content hidden flex flex-col gap-4">
    @forelse($wishlists as $trip)
    <a href="{{ route('trips.show', $trip) }}" class="block">
        <x-card class="bg-white hover:bg-[#FFF5F8] transition-colors relative overflow-hidden border-[#FF6B9D]">
            <div class="absolute -right-10 -bottom-10 opacity-10 transform rotate-12">
                <div class="text-[100px]">💭</div>
            </div>

            <div class="flex justify-between items-start mb-2 relative z-10">
                <h3 class="font-heading font-bold text-xl leading-tight w-3/4">{{ $trip->title }}</h3>
                <span class="text-xs font-bold bg-[#FF6B9D] text-white px-2 py-0.5 rounded-full border-2 border-[#1A1A2E]">Wishlist</span>
            </div>

            <div class="flex items-center gap-2 text-sm font-medium opacity-70 mb-4 relative z-10 flex-wrap">
                <span>📍 {{ $trip->destination }}</span>
                <span>•</span>
                <span class="italic">Tanggal belum ditentukan</span>
                @if($trip->clonedFrom)
                    <span>•</span>
                    <span class="text-xs bg-[#4361EE] text-white px-2 py-0.5 rounded border border-[#1A1A2E] font-bold">Salin dari {{ '@' . $trip->clonedFrom->creator->name }}</span>
                @endif
            </div>

            <div class="flex justify-between items-end relative z-10">
                <div class="flex -space-x-2">
                    @foreach($trip->members as $member)
                    <x-avatar :user="$member" size="sm" class="border-2 border-[#1A1A2E]" />
                    @endforeach
                </div>
                <div class="flex items-center gap-1 text-xs font-bold text-[#FF6B9D] bg-[#FFF0F5] px-3 py-1 rounded-full border-2 border-[#FF6B9D]">
                    ✏️ Isi Tanggal
                </div>
            </div>
        </x-card>
    </a>
    @empty
    <x-empty-state
        icon="💖"
        title="Wishlist Masih Kosong"
        description="Simpan impian tripmu di sini! Isi tanggal kosong saat buat trip baru."
    >
        <x-button href="{{ route('trips.create') }}" variant="primary" class="mt-4">+ Tambah ke Wishlist</x-button>
    </x-empty-state>
    @endforelse
</div>

@endsection

@push('scripts')
<style>
    .tab-btn { color: #1A1A2E; background: transparent; }
    .active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
    }

    // Jika baru redirect dari wishlist creation, buka tab wishlist
    @if(session('success') && str_contains(session('success'), 'Wishlist'))
        document.addEventListener('DOMContentLoaded', () => switchTab('wishlist'));
    @endif
</script>
@endpush
