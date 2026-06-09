@extends('layouts.app')
@section('title', $trip->start_date ? 'Edit Trip' : 'Tetapkan Tanggal')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">{{ $trip->start_date ? 'Edit Trip ✏️' : 'Tetapkan Tanggal 📅' }}</h1>
    </div>
</div>
@endsection

@section('content')

@if(!$trip->start_date)
{{-- ===== WISHLIST MODE: hanya isi tanggal ===== --}}
<div class="flex flex-col gap-4">

    {{-- Info banner --}}
    <div class="nb-card bg-[#FFF0F5] border-[#FF6B9D] p-4 flex gap-3 items-start">
        <div class="text-2xl">💖</div>
        <div>
            <h2 class="font-heading font-bold text-base text-[#1A1A2E] mb-1">Tetapkan tanggal trip</h2>
            <p class="text-sm font-medium text-gray-600 leading-relaxed">
                Trip <strong>"{{ $trip->title }}"</strong> masih berstatus Wishlist. Isi tanggal berangkat dan pulang untuk mulai menyusun itinerary!
            </p>
        </div>
    </div>

    {{-- Date form --}}
    <x-card>
        <form action="{{ route('trips.update', $trip) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <p class="text-xs font-bold opacity-50 uppercase tracking-wide mb-3">Tanggal Perjalanan</p>
                <div class="grid grid-cols-2 gap-4">
                    <x-input
                        type="date"
                        name="start_date"
                        label="✈️ Berangkat"
                        required="true"
                    />
                    <x-input
                        type="date"
                        name="end_date"
                        label="🏠 Pulang"
                        required="true"
                    />
                </div>
            </div>

            <x-button type="submit" variant="primary" class="w-full text-base">
                🚀 Aktifkan Trip Sekarang
            </x-button>
        </form>
    </x-card>

    {{-- Danger zone --}}
    <div class="nb-card bg-white p-4">
        <h3 class="font-heading font-bold text-sm mb-1 text-red-500">Danger Zone</h3>
        <p class="text-xs font-medium mb-3 opacity-70">Menghapus trip akan menghapus semua data terkait secara permanen.</p>
        <form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="button" variant="danger" class="w-full" onclick="openModal('deleteTripModal')">
                Hapus Wishlist
            </x-button>
        </form>
    </div>

</div>

@else
{{-- ===== NORMAL TRIP MODE: edit lengkap ===== --}}
<x-card>
    <form action="{{ route('trips.update', $trip) }}" method="POST">
        @csrf
        @method('PUT')
        
        <x-input 
            name="title" 
            label="Nama Trip" 
            value="{{ $trip->title }}"
            required="true"
        />
        
        <x-input 
            name="destination" 
            label="Destinasi Utama" 
            value="{{ $trip->destination }}"
            required="true"
        />
        
        <x-input 
            type="number"
            name="total_budget" 
            label="Total Budget" 
            value="{{ $trip->total_budget }}" 
        />
        
        <x-input 
            type="textarea"
            name="description" 
            label="Deskripsi / Catatan" 
            value="{{ $trip->description }}" 
        />
        
        <div class="mt-6 flex gap-4">
            <x-button type="submit" variant="mint" class="flex-1">Simpan Perubahan</x-button>
        </div>
    </form>
    
    <div class="mt-8 border-t-[3px] border-[#1A1A2E] pt-6">
        <h3 class="font-heading font-bold text-lg mb-2 text-red-500">Danger Zone</h3>
        <p class="text-sm font-medium mb-4 opacity-80">Menghapus trip akan menghapus semua aktivitas, budget, dan dokumen terkait.</p>
        
        <form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="button" variant="danger" class="w-full" onclick="openModal('deleteTripModal')">
                Hapus Trip Permanen
            </x-button>
        </form>
    </div>
</x-card>
@endif

<x-modal id="deleteTripModal" title="Hapus Trip?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">🚨</div>
        <h3 class="font-heading font-bold text-xl mb-2 text-[#1A1A2E]">Hapus Permanen?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">
            Tindakan ini tidak dapat dibatalkan. Semua <strong class="text-red-500">aktivitas</strong>, <strong class="text-red-500">budget</strong>, dan <strong class="text-red-500">dokumen</strong> yang terkait akan ikut terhapus.
        </p>
        
        <div class="flex gap-3">
            <button type="button" onclick="closeModal('deleteTripModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] hover:bg-gray-100 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                Kembali
            </button>
            <button type="button" onclick="document.getElementById('delete-trip-form').submit();" class="flex-1 nb-btn bg-red-500 text-white border-2 border-[#1A1A2E] hover:bg-red-600 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                Ya, Hapus
            </button>
        </div>
    </div>
</x-modal>
@endsection
