@extends('layouts.app')
@section('title', 'Wishlist Destinasi')

@section('header')
<div class="flex items-center gap-3 w-full">
    <div class="flex-1 overflow-hidden">
        <h1 class="text-2xl font-heading font-bold truncate">Wishlist 📍</h1>
        <p class="text-sm font-medium opacity-80 truncate">{{ $trip->title }}</p>
    </div>
    <button onclick="openModal('addWishlistModal')" class="nb-btn nb-btn-primary nb-btn-sm whitespace-nowrap">
        + Tambah
    </button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-2 gap-3">
    @forelse($wishlists as $item)
        <div class="nb-card p-3 bg-white flex flex-col h-full relative">
            
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold {{ 
                    $item->priority === 'wajib' ? 'text-[#FF6B9D]' : 
                    ($item->priority === 'pengen' ? 'text-[#00D4AA]' : 'text-gray-500') 
                }} uppercase tracking-wider">
                    {{ str_replace('_', ' ', $item->priority) }}
                </span>
                
                <form action="{{ route('wishlists.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus dari wishlist?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 font-bold">&times;</button>
                </form>
            </div>
            
            <h3 class="font-heading font-bold leading-tight mb-2 flex-1">{{ $item->name }}</h3>
            
            <div class="flex flex-col gap-2 mt-auto pt-2">
                @if($item->location_name)
                    <a href="{{ $item->location_url ?? '#' }}" target="_blank" class="text-xs text-[#4361EE] hover:underline truncate inline-block">
                        📍 {{ $item->location_name }}
                    </a>
                @endif
                
                <div class="flex justify-between items-center mt-2 border-t-2 border-dashed border-gray-200 pt-2">
                    <form action="{{ route('wishlists.vote', $item) }}" method="POST">
                        @csrf
                        @php
                            $votes = $item->votes ?? [];
                            $hasVoted = in_array(Auth::id(), $votes);
                        @endphp
                        <button type="submit" class="flex items-center gap-1 bg-{{ $hasVoted ? '[#FFE156]' : 'gray-100' }} border-2 border-[#1A1A2E] rounded-full px-2 py-0.5 text-xs font-bold hover:scale-105 transition-transform">
                            <span>👍</span>
                            <span>{{ count($votes) }}</span>
                        </button>
                    </form>
                    
                    <span class="text-xl">
                        @switch($item->category)
                            @case('wisata') 🏖️ @break
                            @case('kuliner') 🍜 @break
                            @case('belanja') 🛍️ @break
                            @default ✨
                        @endswitch
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-2">
            <x-empty-state 
                icon="💡" 
                title="Wishlist Masih Kosong" 
                description="Simpan ide tempat seru di sini sebelum menyusun itinerary."
            >
                <button onclick="openModal('addWishlistModal')" class="nb-btn nb-btn-primary mt-4">Tambah Ide Pertama</button>
            </x-empty-state>
        </div>
    @endforelse
</div>

<!-- Modal Tambah Wishlist -->
<x-modal id="addWishlistModal" title="Tambah Ide Destinasi">
    <form action="{{ route('wishlists.store', $trip) }}" method="POST">
        @csrf
        
        <x-input 
            name="name" 
            label="Nama Tempat / Aktivitas" 
            placeholder="Universal Studio / Beli Oleh-oleh" 
            required="true"
        />
        
        <div class="nb-form-group">
            <label class="nb-label">Kategori <span class="text-red-500">*</span></label>
            <select name="category" class="nb-select" required>
                <option value="wisata">🏖️ Wisata</option>
                <option value="kuliner">🍜 Kuliner / Cafe</option>
                <option value="belanja">🛍️ Belanja</option>
                <option value="lainnya">✨ Lainnya</option>
            </select>
        </div>
        
        <div class="nb-form-group">
            <label class="nb-label">Prioritas <span class="text-red-500">*</span></label>
            <select name="priority" class="nb-select" required>
                <option value="wajib">🔥 Wajib Banget!</option>
                <option value="pengen">😊 Pengen Kesini</option>
                <option value="kalau_sempat">🤷‍♀️ Kalau Sempat Aja</option>
            </select>
        </div>
        
        <x-input 
            name="location_name" 
            label="Area / Lokasi" 
            placeholder="Kuta, Bali" 
        />
        
        <x-input 
            name="location_url" 
            label="Link Info / Maps (Opsional)" 
            placeholder="https://..." 
        />
        
        <x-input 
            type="textarea"
            name="description" 
            label="Kenapa pengen ke sini? (Opsional)" 
            placeholder="Kata orang kopinya enak..." 
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="pink" class="w-full text-lg">Simpan ke Wishlist 💖</x-button>
        </div>
    </form>
</x-modal>
@endsection
