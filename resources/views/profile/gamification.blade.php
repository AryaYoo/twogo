@extends('layouts.app')
@section('title', 'Level & Misi')

@section('header')
<div class="flex items-center gap-2">
    <a href="{{ $user->id === Auth::id() ? route('profile.show') : route('profile.user', $user) }}" class="w-9 h-9 bg-white border-[2.5px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">&larr;</a>
    <h1 class="text-lg font-heading font-bold">Level & Misi</h1>
</div>
@endsection

@section('content')
@php
    use App\Services\GamificationService;
    $levelInfo   = GamificationService::getLevelInfo($user->xp);
    $tier        = $levelInfo['tier'];
    $allTiers    = GamificationService::getAllTiers();
    $missions    = GamificationService::getMissions($user);
    $bestPartners = GamificationService::getBestPartners($user);
    $isOwn       = (Auth::id() === $user->id);
@endphp

{{-- Tabs --}}
<div class="flex gap-2 mb-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]" id="gamif-tabs">
    <button id="tab-missions-btn" onclick="switchGamifTab('missions')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 gamif-tab-btn active-tab" data-tab="missions">
        🎯 Level & Misi ({{ count($missions) }})
    </button>
    <button id="tab-partners-btn" onclick="switchGamifTab('partners')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 gamif-tab-btn" data-tab="partners">
        👫 Best Partner ({{ $bestPartners->count() }})
    </button>
</div>

{{-- Tab: Level & Missions --}}
<div id="tab-missions" class="gamif-tab-content">
    {{-- Hero Level Card --}}
    <div class="nb-card p-5 mb-5 relative overflow-hidden"
         style="background: {{ $tier['card_bg'] }}; color: {{ $tier['card_text'] }}; border-color: #1A1A2E;">
        <div class="absolute -right-8 -bottom-8 text-[120px] opacity-10 select-none leading-none">{{ $tier['emoji'] }}</div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="text-4xl">{{ $tier['emoji'] }}</span>
                    <h2 class="font-heading font-bold text-2xl mt-1">{{ $tier['name'] }}</h2>
                    <p class="text-sm font-medium opacity-80">Level {{ $levelInfo['level'] }}</p>
                </div>
                <div class="text-right">
                    <p class="font-heading font-bold text-3xl">{{ number_format($user->xp) }}</p>
                    <p class="text-xs font-medium opacity-80">Total XP</p>
                </div>
            </div>

            {{-- Level bar --}}
            <div class="mb-2">
                <div class="flex justify-between text-xs font-bold mb-1 opacity-90">
                    <span>{{ $levelInfo['xpInLevel'] }}/100 XP di level ini</span>
                    <span>{{ $levelInfo['percent'] }}%</span>
                </div>
                <div class="w-full h-4 bg-black/20 rounded-full border-2 border-black/20 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width: {{ $levelInfo['percent'] }}%; background: {{ $tier['card_text'] === '#FFFFFF' ? 'rgba(255,255,255,0.85)' : '#1A1A2E' }};">
                    </div>
                </div>
            </div>

            {{-- Tier progress bar --}}
            @if($levelInfo['tierMax'])
            <div>
                <div class="flex justify-between text-xs font-bold mb-1 opacity-80">
                    <span>Progress ke tier selanjutnya</span>
                    <span>{{ $levelInfo['xpToNextTier'] }} XP lagi</span>
                </div>
                <div class="w-full h-2.5 bg-black/20 rounded-full border border-black/20 overflow-hidden">
                    <div class="h-full rounded-full"
                         style="width: {{ $levelInfo['tierPercent'] }}%; background: {{ $tier['card_text'] === '#FFFFFF' ? 'rgba(255,255,255,0.6)' : '#1A1A2E' }}; opacity: 0.7;">
                    </div>
                </div>
            </div>
            @else
            <p class="text-sm font-bold opacity-80 mt-1">👑 {{ $isOwn ? 'Kamu sudah di tier tertinggi!' : 'Sudah di tier tertinggi!' }}</p>
            @endif
        </div>
    </div>

    {{-- Tier Roadmap --}}
    <div class="nb-card bg-white p-4 mb-5">
        <h3 class="font-heading font-bold text-base mb-3">🗺️ Roadmap Tier</h3>
        <div class="flex flex-col gap-2">
            @foreach($allTiers as $t)
            @php $isCurrent = ($t['name'] === $tier['name']); $isPassed = ($user->xp >= $t['min']); @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl border-2 {{ $isCurrent ? 'border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'border-gray-200' }}"
                 style="{{ $isCurrent ? 'background:' . $t['card_bg'] . '; color:' . $t['card_text'] . ';' : ($isPassed ? 'opacity:0.6;' : '') }}">
                <span class="text-2xl">{{ $t['emoji'] }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-heading font-bold text-sm">{{ $t['name'] }}</p>
                    <p class="text-xs opacity-70">{{ number_format($t['min']) }} {{ $t['max'] ? '– ' . number_format($t['max']) : '+'}} XP</p>
                </div>
                @if($isCurrent)
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-black/20">{{ $isOwn ? 'Kamu di sini' : 'Di sini' }}</span>
                @elseif($isPassed)
                    <span class="text-lg">✅</span>
                @else
                    <span class="text-xs font-bold opacity-50">🔒</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- List Misi --}}
    <h3 class="font-heading font-bold text-base mb-3">🎯 List Misi</h3>
    <div class="flex flex-col gap-3">
        @foreach($missions as $mission)
        <div class="nb-card bg-white p-4 flex items-start gap-3 {{ $mission['done'] ? 'opacity-80' : '' }}">
            <div class="text-3xl shrink-0">{{ $mission['emoji'] }}</div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-heading font-bold text-sm {{ $mission['done'] ? 'line-through opacity-60' : '' }}">{{ $mission['title'] }}</h4>
                    <span class="text-xs font-bold ml-2 shrink-0 {{ $mission['done'] ? 'bg-[#00D4AA] text-[#1A1A2E]' : 'bg-[#FFE156] text-[#1A1A2E]' }} px-2 py-0.5 rounded-full border border-[#1A1A2E]">
                        +{{ $mission['xp'] }} XP
                    </span>
                </div>
                <p class="text-xs opacity-70 mb-2">{{ $mission['description'] }}</p>
                {{-- Progress bar --}}
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden border border-gray-300">
                    <div class="h-full rounded-full transition-all duration-500 {{ $mission['done'] ? 'bg-[#00D4AA]' : 'bg-[#4361EE]' }}"
                         style="width: {{ $mission['target'] > 0 ? min(100, round(($mission['progress'] / $mission['target']) * 100)) : 100 }}%">
                    </div>
                </div>
                <div class="flex justify-between items-center mt-1">
                    <span class="text-[10px] font-bold text-[#4361EE] bg-[#4361EE]/10 px-2 py-0.5 rounded border border-[#4361EE]/20 shadow-[1px_1px_0px_#4361EE]">
                        ✨ Selesai: {{ $mission['total_count'] }}x
                    </span>
                    <p class="text-xs font-bold text-[#1A1A2E]">{{ $mission['progress'] }}/{{ $mission['target'] }}</p>
                </div>
            </div>
            @if($mission['done'])
            <span class="text-2xl shrink-0">✅</span>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- Tab: Best Partners --}}
<div id="tab-partners" class="gamif-tab-content hidden">
    @if($bestPartners->isNotEmpty())
    <div class="flex flex-col gap-3">
        @foreach($bestPartners as $i => $partner)
        <a href="{{ route('profile.user', $partner['user']) }}" class="nb-card bg-white p-4 flex items-center gap-3 hover:bg-gray-50 transition-colors">
            {{-- Rank Badge --}}
            <div class="shrink-0 w-8 h-8 flex items-center justify-center font-heading font-bold text-sm rounded-full border-[2px] border-[#1A1A2E]
                {{ $i === 0 ? 'bg-[#FFE156]' : ($i === 1 ? 'bg-gray-200' : ($i === 2 ? 'bg-orange-200' : 'bg-white')) }}">
                {{ $i + 1 }}
            </div>
            {{-- Avatar --}}
            <x-avatar :user="$partner['user']" size="md" class="border-2 border-[#1A1A2E]" />
            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold text-sm truncate">{{ $partner['user']->name }}</p>
                <p class="text-xs opacity-70">XP bersama</p>
            </div>
            {{-- XP shared --}}
            <div class="shrink-0 text-right">
                <p class="font-heading font-bold text-base text-[#4361EE]">+{{ number_format($partner['shared_xp']) }}</p>
                <p class="text-xs opacity-60">XP</p>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="nb-card bg-white p-8 text-center border-dashed">
        <div class="text-4xl mb-2">👫</div>
        <h4 class="font-bold font-heading text-lg mb-1">Belum Ada Partner</h4>
        <p class="text-sm opacity-70">Selesaikan trip bersama teman untuk mendapatkan bonus XP berdua!</p>
    </div>
    @endif
</div>

<div class="h-24"></div>
@endsection

@push('scripts')
<style>
    .gamif-tab-btn { color: #1A1A2E; background: transparent; }
    .gamif-tab-btn.active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
function switchGamifTab(tab) {
    document.querySelectorAll('.gamif-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.gamif-tab-btn').forEach(btn => btn.classList.remove('active-tab'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
}
</script>
@endpush
