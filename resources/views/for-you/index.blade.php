@extends('layouts.app')
@section('title', 'For You')

@section('header')
<div class="flex items-center gap-3 w-full">
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold">For You ✨</h1>
        <p class="text-sm font-medium opacity-80">Update trip & wishlist publik kamu dan temanmu</p>
    </div>
</div>
@endsection

@section('content')

@if($feed->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="text-6xl mb-4">👫</div>
        <h2 class="font-heading font-bold text-xl mb-2">Belum Ada Update</h2>
        <p class="text-sm font-medium opacity-70 max-w-xs leading-relaxed mb-6">
            Buat trip atau wishlist dan atur sebagai publik — atau tambah teman yang juga membagikan perjalanan mereka!
        </p>
        <a href="{{ route('trips.create') }}" class="nb-btn nb-btn-primary">Buat Trip</a>
    </div>
@else
    <div class="flex flex-col gap-4">
        @foreach($feed as $item)
            @php
                $trip = $item['trip'];
                $user = $item['user'];
                $isWishlist = $item['type'] === 'wishlist';
            @endphp
            <x-card class="bg-white p-0 overflow-hidden">
                {{-- Header: who did what --}}
                <div class="flex items-center gap-3 p-3 border-b-2 border-[#1A1A2E] border-dashed">
                    <a href="{{ $item['is_own'] ? route('profile.show') : route('profile.user', $user) }}" class="shrink-0">
                        <x-avatar :user="$user" size="sm" class="border-2 border-[#1A1A2E]" />
                    </a>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium leading-snug">
                            @if($item['is_own'])
                                <span class="font-bold">Kamu</span>
                            @else
                                <a href="{{ route('profile.user', $user) }}" class="font-bold hover:underline">{{ $user->name }}</a>
                            @endif
                            @if($isWishlist)
                                membuat wishlist baru
                            @else
                                merencanakan trip baru
                            @endif
                        </p>
                        <p class="text-xs opacity-60">{{ $item['created_at']->diffForHumans() }}</p>
                    </div>
                    <span class="text-xl shrink-0">{{ $isWishlist ? '💭' : '🗓️' }}</span>
                </div>

                {{-- Trip card --}}
                <div class="block p-3 hover:bg-gray-50 transition-colors trip-interaction-card relative overflow-hidden select-none cursor-pointer"
                    data-url="{{ route('trips.public_show', $trip) }}"
                    data-like-url="{{ route('trips.like', $trip) }}"
                    data-clone-url="{{ route('trips.clone', $trip) }}">
                    <div class="flex gap-3 relative z-10 pointer-events-none">
                        <div class="w-16 h-16 shrink-0 {{ $isWishlist ? 'bg-[#FFF0F5] border-[#FF6B9D]' : 'bg-[#FFE156]' }} border-[3px] border-[#1A1A2E] rounded-md flex items-center justify-center text-2xl">
                            {{ $isWishlist ? '💭' : '🌴' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold font-heading truncate">{{ $trip->title }}</h3>
                            <p class="text-sm opacity-80 truncate">📍 {{ $trip->destination }}</p>
                            @if(!$isWishlist && $trip->start_date)
                                <p class="text-xs opacity-70 mt-1">
                                    📅 {{ $trip->start_date->format('d M Y') }} – {{ $trip->end_date->format('d M Y') }}
                                </p>
                            @endif
                            <div class="flex items-center gap-3 mt-1 text-xs font-bold">
                                <span class="text-[#00D4AA]">🌍 Publik</span>
                                <span class="text-[#FF6B9D] like-count-text">❤️ {{ $trip->likes->count() }}</span>
                                <span class="text-[#4361EE]">📋 {{ $trip->clones()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
@include('components.trip-interaction-scripts')
@endpush
