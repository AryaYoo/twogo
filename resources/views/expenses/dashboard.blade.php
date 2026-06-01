@extends('layouts.app')
@section('title', 'Budget Tracker')

@section('header')
<div class="flex items-center gap-3 w-full">
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Budget Tracker 💰</h1>
        <p class="text-xs font-medium opacity-80 truncate">Daftar perjalanan yang sudah selesai</p>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col gap-4">
    @forelse($completedTrips as $trip)
    @php
        $totalSpent = $trip->expenses->sum('amount');
        $isSplitBudget = $trip->expenses->where('title', 'Split Budget')->isNotEmpty();
    @endphp
    <a href="{{ route('expenses.index', $trip) }}" class="block">
        <x-card class="bg-white hover:bg-[#FFE156] transition-colors relative overflow-hidden">
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div>
                    <h3 class="font-heading font-bold text-lg leading-tight">{{ $trip->title }}</h3>
                    <p class="text-xs font-medium opacity-80 mt-1">{{ $trip->start_date->format('d M y') }} — {{ $trip->end_date->format('d M y') }}</p>
                </div>
                <span class="text-xs font-bold bg-[#00D4AA] text-[#1A1A2E] px-3 py-1 rounded-full border-[3px] border-[#1A1A2E]">Selesai</span>
            </div>

            <div class="flex items-center justify-between gap-3 relative z-10">
                <div>
                    <div class="text-xs font-bold opacity-70">Total Budget</div>
                    <div class="font-bold">Rp {{ number_format($trip->total_budget, 0, ',', '.') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold opacity-70">Terpakai</div>
                    <div class="font-bold text-[#FF6B9D]">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($isSplitBudget)
            <div class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-[#1A1A2E] bg-[#FFE156] px-3 py-2 rounded-xl border-[3px] border-[#1A1A2E]">
                💸 Budget hasil split bill
            </div>
            @endif
        </x-card>
    </a>
    @empty
    <x-empty-state
        icon="📦"
        title="Belum ada perjalanan selesai"
        description="Selesaikan perjalanan di halaman trip agar muncul di Budget Tracker."
    >
        <x-button href="{{ route('trips.index') }}" variant="primary" class="mt-4">Lihat Trip</x-button>
    </x-empty-state>
    @endforelse
</div>
@endsection
