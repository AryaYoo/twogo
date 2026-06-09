@extends('layouts.app')
@section('title', 'Buat Trip Baru')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.index') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">Buat Trip Baru ✨</h1>
    </div>
</div>
@endsection

@section('content')
<x-card>
    <form action="{{ route('trips.store') }}" method="POST">
        @csrf
        
        <x-input 
            name="title" 
            label="Nama Trip (mis. Honeymoon Bali)" 
            placeholder="Ke Jepang Bareng Bestie" 
            required="true"
        />
        
        <x-input 
            name="destination" 
            label="Destinasi Utama" 
            placeholder="Bali, Indonesia" 
            required="true"
        />
        
        <div class="nb-form-group">
            <div class="flex items-center justify-between mb-1">
                <label class="nb-label mb-0">Tanggal Berangkat & Pulang</label>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <x-input 
                    type="date"
                    name="start_date" 
                    label="Berangkat" 
                />
                
                <x-input 
                    type="date"
                    name="end_date" 
                    label="Pulang" 
                />
            </div>
            <p class="text-xs font-medium text-[#FF6B9D] mt-2 flex items-start gap-1">
                <span>💖</span>
                <span>Kosongkan tanggal untuk menyimpan ke <strong>Wishlist</strong> — isi tanggal nanti kalau udah fix!</span>
            </p>
        </div>
        
        <x-input 
            type="number"
            name="total_budget" 
            label="Total Budget (Opsional)" 
            placeholder="5000000" 
        />
        
        <x-input 
            type="textarea"
            name="description" 
            label="Deskripsi / Catatan" 
            placeholder="Trip santai aja ga usah ngoyo..." 
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full text-lg">Mulai Rencanakan! 🚀</x-button>
        </div>
    </form>
</x-card>
@endsection
