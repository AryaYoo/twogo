@extends('layouts.app')
@section('title', 'Edit Trip')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-2xl font-heading font-bold">Edit Trip ✏️</h1>
    </div>
</div>
@endsection

@section('content')
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
            <x-button type="button" variant="danger" class="w-full" onclick="confirmDelete('delete-trip-form', 'Yakin menghapus trip ini permanen?')">
                Hapus Trip Permanen
            </x-button>
        </form>
    </div>
</x-card>
@endsection
