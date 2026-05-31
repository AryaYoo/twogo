@extends('layouts.app')
@section('title', 'Catat Pengeluaran')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('expenses.index', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-2xl font-heading font-bold">Catat Pengeluaran 💸</h1>
    </div>
</div>
@endsection

@section('content')
<x-card>
    <form action="{{ route('expenses.store', $trip) }}" method="POST">
        @csrf
        
        <x-input 
            name="title" 
            label="Untuk Apa?" 
            placeholder="Makan Seafood di Jimbaran" 
            required="true"
        />
        
        <x-input 
            type="number"
            name="amount" 
            label="Total Biaya (Rp)" 
            placeholder="250000" 
            required="true"
        />
        
        <div class="nb-form-group">
            <label class="nb-label">Kategori <span class="text-red-500">*</span></label>
            <select name="category" class="nb-select" required>
                <option value="akomodasi">🏨 Akomodasi</option>
                <option value="transportasi">🚗 Transportasi</option>
                <option value="kuliner">🍜 Kuliner</option>
                <option value="tiket">🎫 Tiket Wisata</option>
                <option value="belanja">🛍️ Belanja / Oleh-oleh</option>
                <option value="lainnya">✨ Lainnya</option>
            </select>
        </div>
        
        <x-input 
            type="date"
            name="expense_date" 
            label="Tanggal Pengeluaran" 
            value="{{ date('Y-m-d') }}"
            required="true"
        />
        
        <div class="nb-form-group">
            <label class="nb-label">Pembagian (Split) <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <label class="border-[3px] border-[#1A1A2E] rounded-lg p-3 text-center cursor-pointer hover:bg-gray-50 flex flex-col items-center gap-1 has-[:checked]:bg-[#FFE156] transition-colors">
                    <input type="radio" name="split_type" value="equal" class="hidden" checked>
                    <span class="text-xl">🍕</span>
                    <span class="font-bold text-sm">Bagi Rata</span>
                </label>
                
                <label class="border-[3px] border-[#1A1A2E] rounded-lg p-3 text-center cursor-pointer hover:bg-gray-50 flex flex-col items-center gap-1 has-[:checked]:bg-[#FFE156] transition-colors">
                    <input type="radio" name="split_type" value="solo" class="hidden">
                    <span class="text-xl">👤</span>
                    <span class="font-bold text-sm">Bayar Sendiri</span>
                </label>
            </div>
            <p class="text-xs mt-2 opacity-70 font-medium">Asumsi: Kamu yang membayar penuh saat transaksi ini.</p>
        </div>
        
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full text-lg">Catat & Hitung 🧮</x-button>
        </div>
    </form>
</x-card>
@endsection
