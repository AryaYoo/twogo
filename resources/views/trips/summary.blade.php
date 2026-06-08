@extends('layouts.app')
@section('title', 'Ringkasan Perjalanan - ' . $trip->title)

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-2xl font-heading font-bold truncate">Ringkasan Trip 📋</h1>
        <p class="text-sm font-medium opacity-80 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col gap-6">

    <div class="nb-card bg-white p-4">
        <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-dashed border-gray-200 pb-2">Status Itinerary</h3>
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm font-medium opacity-80">Total Kegiatan</p>
                <p class="text-2xl font-bold font-heading">{{ $totalActivities }}</p>
            </div>
            <div>
                <p class="text-sm font-medium opacity-80">Selesai</p>
                <p class="text-2xl font-bold font-heading text-[#00D4AA]">{{ $completedActivities }}</p>
            </div>
            <div>
                <p class="text-sm font-medium opacity-80">Progress</p>
                <p class="text-2xl font-bold font-heading text-[#FF6B9D]">
                    {{ $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0 }}%
                </p>
            </div>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-3 mt-4 border-2 border-[#1A1A2E] overflow-hidden">
            <div class="bg-[#00D4AA] h-full" style="width: {{ $totalActivities > 0 ? ($completedActivities / $totalActivities) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="nb-card bg-[#FFE156] p-4">
        <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-[#1A1A2E] pb-2">Laporan Budget</h3>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center bg-white p-3 rounded-lg border-2 border-[#1A1A2E]">
                <span class="font-bold">Total Budget</span>
                <span class="font-bold font-heading">Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between items-center bg-white p-3 rounded-lg border-2 border-[#1A1A2E]">
                <span class="font-bold">Total Pengeluaran Real</span>
                <span class="font-bold font-heading text-[#FF6B9D]">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between items-center bg-[#1A1A2E] text-white p-3 rounded-lg">
                <span class="font-bold">Sisa Budget</span>
                <span class="font-bold font-heading {{ $remainingBudget == 0 && $totalSpent > $totalBudget ? 'text-[#FF6B9D]' : 'text-[#00D4AA]' }}">
                    Rp {{ number_format($totalBudget - $totalSpent, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="nb-card bg-white p-4">
        <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-dashed border-gray-200 pb-2">Aksi Selanjutnya</h3>
        <div class="flex flex-col gap-3">
            <a href="{{ route('expenses.index', $trip) }}" class="nb-btn nb-btn-ghost justify-between border-2 border-[#1A1A2E] hover:bg-[#FFE156]">
                <span>💰 Lihat Rincian Budget</span>
                <span>&rarr;</span>
            </a>
            @if($trip->status !== 'completed')
            <form action="{{ route('trips.complete', $trip) }}" method="POST" onsubmit="return confirm('Tandai perjalanan ini sebagai selesai?');">
                @csrf
                <button type="submit" class="w-full nb-btn nb-btn-primary bg-[#00D4AA] text-white border-2 border-[#1A1A2E] justify-between">
                    <span>✅ Tandai Trip Selesai</span>
                    <span>&rarr;</span>
                </button>
            </form>
            @endif
        </div>
    </div>

</div>
@endsection
