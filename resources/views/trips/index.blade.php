@extends('layouts.app')
@section('title', 'Daftar Trip')

@section('header')
<div class="flex-1">
    <h1 class="text-2xl font-heading font-bold">Trip Kamu ✈️</h1>
    <p class="text-sm font-medium opacity-80">Rencanakan perjalanan seru berikutnya</p>
</div>
@endsection

@section('content')
<div class="flex flex-col gap-4">
    @forelse($trips as $trip)
    <a href="{{ route('trips.show', $trip) }}" class="block">
        <x-card class="bg-[#FFE156] hover:bg-[#F2D449] transition-colors relative overflow-hidden">
            <!-- Pattern -->
            <div class="absolute -right-10 -bottom-10 opacity-10 transform rotate-12">
                <div class="text-[100px]">🌴</div>
            </div>
            
            <div class="flex justify-between items-start mb-2 relative z-10">
                <h3 class="font-heading font-bold text-xl leading-tight w-3/4">{{ $trip->title }}</h3>
                <x-badge color="{{ $trip->status === 'planning' ? 'pink' : ($trip->status === 'ongoing' ? 'mint' : 'gray') }}">
                    {{ ucfirst($trip->status) }}
                </x-badge>
            </div>
            
            <div class="flex items-center gap-2 text-sm font-medium opacity-90 mb-4 relative z-10">
                <span>📍 {{ $trip->destination }}</span>
                <span>•</span>
                <span>📅 {{ $trip->start_date->format('d M y') }}</span>
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
        icon="🧳" 
        title="Belum ada trip" 
        description="Yuk mulai rencanakan liburan pertamamu!"
    >
        <x-button href="{{ route('trips.create') }}" variant="primary" class="mt-4">Buat Trip Baru</x-button>
    </x-empty-state>
    @endforelse
</div>
@endsection
