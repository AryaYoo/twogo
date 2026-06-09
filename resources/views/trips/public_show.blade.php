@extends('layouts.app')
@section('title', $trip->title . ' - Itinerary Publik')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ url()->previous() }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">&larr;</a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">{{ $trip->title }}</h1>
        <p class="text-xs opacity-70 font-medium">Itinerary Publik · oleh <a href="{{ route('profile.user', $trip->creator) }}" class="font-bold underline hover:opacity-80">{{ $trip->creator->name }}</a></p>
    </div>
</div>
@endsection

@section('content')

{{-- Trip Info Card --}}
<div class="nb-card bg-[#FFE156] p-4 mb-4">
    <div class="flex items-start justify-between mb-3">
        <div>
            <h2 class="text-2xl font-heading font-bold leading-tight">{{ $trip->title }}</h2>
            <p class="text-sm font-medium opacity-80 mt-1">📍 {{ $trip->destination }}</p>
        </div>
        <span class="text-xs font-bold bg-[#1A1A2E] text-[#FFE156] px-2 py-1 rounded-full">🌍 Publik</span>
    </div>

    @if($trip->description)
    <p class="text-sm font-medium mb-3 opacity-90">{{ Str::before($trip->description, ' [Salin dari') }}</p>
    @endif

    <div class="flex items-center gap-4 text-sm font-bold">
        <span>📅 {{ $trip->start_date ? $trip->start_date->format('d M Y') . ' – ' . $trip->end_date->format('d M Y') : 'Belum ada tanggal' }}</span>
    </div>

    <div class="flex items-center justify-between mt-3 pt-3 border-t-2 border-[#1A1A2E] border-dashed">
        <div class="flex items-center gap-2">
            @foreach($trip->members as $member)
                <x-avatar :user="$member" size="sm" class="border-2 border-[#1A1A2E]" />
            @endforeach
            <a href="{{ route('profile.user', $trip->creator) }}" class="text-xs font-medium opacity-80 font-bold hover:underline">{{ $trip->creator->name }}</a>
        </div>
        {{-- Like Button --}}
        <button id="like-btn" onclick="toggleLike({{ $trip->id }})"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border-2 border-[#1A1A2E] font-bold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all hover:translate-y-[-1px] {{ $isLiked ? 'bg-[#FF6B9D] text-white' : 'bg-white text-[#1A1A2E]' }}">
            <span id="like-icon">{{ $isLiked ? '❤️' : '🤍' }}</span>
            <span id="like-count">{{ $likeCount }}</span>
        </button>
    </div>
</div>

{{-- Budget Summary --}}
<div class="nb-card bg-white p-4 mb-4">
    <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-dashed border-gray-200 pb-2">💰 Alokasi Budget</h3>
    <div class="flex justify-between items-center">
        <div>
            <p class="text-xs font-medium opacity-70">Total Budget</p>
            <p class="text-xl font-bold font-heading">Rp {{ number_format($trip->total_budget, 0, ',', '.') }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-medium opacity-70">Estimasi Kegiatan</p>
            <p class="text-xl font-bold font-heading text-[#FF6B9D]">Rp {{ number_format($totalEstimatedBudget, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Timeline (Read-Only) --}}
@foreach($trip->days as $day)
<div class="mb-5">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 bg-[#1A1A2E] text-[#FFE156] rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center font-bold font-heading text-sm shrink-0">
            H{{ $day->day_number }}
        </div>
        <div>
            <p class="font-heading font-bold">{{ $day->date->format('l, d M Y') }}</p>
        </div>
    </div>

    @php
        $sessions = ['morning' => ['label' => 'Pagi ☀️', 'color' => 'bg-yellow-50'], 'afternoon' => ['label' => 'Siang 🌤️', 'color' => 'bg-orange-50'], 'evening' => ['label' => 'Malam 🌙', 'color' => 'bg-indigo-50'], 'flexible' => ['label' => 'Fleksibel ⚡', 'color' => 'bg-gray-50']];
    @endphp

    @foreach($sessions as $sessionKey => $sessionInfo)
    @php $activities = $day->activities->where('session', $sessionKey); @endphp
    @if($activities->count() > 0)
    <div class="mb-3 ml-5 pl-4 border-l-[3px] border-dashed border-gray-200">
        <p class="text-xs font-bold text-gray-500 mb-2">{{ $sessionInfo['label'] }}</p>
        <div class="flex flex-col gap-2">
            @foreach($activities as $act)
            <div class="nb-card {{ $sessionInfo['color'] }} p-3">
                <div class="flex items-start gap-2">
                    <div class="flex-1">
                        <h4 class="font-bold font-heading">{{ $act->title }}</h4>
                        @if($act->start_time || $act->end_time)
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $act->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $act->start_time)->format('H:i') : '' }}
                            @if($act->start_time && $act->end_time) — @endif
                            {{ $act->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $act->end_time)->format('H:i') : '' }}
                        </p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($act->category)
                            <span class="text-xs font-bold bg-gray-200 px-2 py-0.5 rounded-full border border-gray-400">{{ $act->category }}</span>
                            @endif
                            @if($act->estimated_cost > 0)
                            <span class="text-xs font-bold bg-[#FFE156] px-2 py-0.5 rounded-full border border-[#1A1A2E]">Rp {{ number_format($act->estimated_cost, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if($act->location_name)
                        <p class="text-xs text-gray-600 mt-1">📍 {{ $act->location_name }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
</div>
@endforeach

{{-- Clone to Wishlist CTA --}}
@auth
<div class="sticky bottom-20 left-0 right-0 pb-2">
    @if($alreadyCloned)
    <div class="nb-card bg-[#00D4AA] text-white p-3 text-center font-bold">
        ✅ Sudah tersalin ke Wishlist kamu!
    </div>
    @elseif(Auth::id() !== $trip->user_id)
    <form action="{{ route('trips.clone', $trip) }}" method="POST">
        @csrf
        <button type="submit" class="w-full nb-btn bg-[#FF6B9D] text-white border-[3px] border-[#1A1A2E] font-bold text-lg shadow-[4px_4px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform py-3">
            📋 Salin ke Wishlist Saya
        </button>
    </form>
    @endif
</div>
@endauth

@endsection

@push('scripts')
<script>
    function toggleLike(tripId) {
        fetch(`/trips/${tripId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('like-count').textContent = data.count;
            const btn = document.getElementById('like-btn');
            document.getElementById('like-icon').textContent = data.liked ? '❤️' : '🤍';
            if (data.liked) {
                btn.classList.remove('bg-white', 'text-[#1A1A2E]');
                btn.classList.add('bg-[#FF6B9D]', 'text-white');
            } else {
                btn.classList.remove('bg-[#FF6B9D]', 'text-white');
                btn.classList.add('bg-white', 'text-[#1A1A2E]');
            }
        });
    }
</script>
@endpush
