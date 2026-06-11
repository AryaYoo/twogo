<div>
{{-- Tier-Up Popup Notification --}}
@if($tierUp)
<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="nb-card max-w-sm w-full text-center p-6 relative"
         style="background: {{ $tierUp['bg'] }}; color: {{ $tierUp['text'] ?? '#1A1A2E' }};">
        <div class="text-6xl mb-3 animate-bounce">{{ $tierUp['emoji'] }}</div>
        <h2 class="text-2xl font-heading font-bold mb-1">Tier Naik!</h2>
        <p class="font-medium text-lg mb-1">{{ $tierUp['from'] }}</p>
        <p class="text-3xl font-bold my-2">↓</p>
        <p class="font-heading text-2xl font-bold mb-4">{{ $tierUp['to'] }}</p>
        <p class="text-sm font-medium opacity-80 mb-5">Selamat! Kamu telah naik tier. Terus jelajahi dunia bersama TwoGo!</p>
        <button wire:click="dismissTierUp"
                class="w-full py-3 px-6 bg-[#1A1A2E] text-white font-bold font-heading rounded-xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_rgba(255,255,255,0.3)] hover:translate-y-[-2px] transition-transform">
            🎉 Mantap!
        </button>
    </div>
</div>
@endif

{{-- Level Card --}}
@php $tier = $levelInfo['tier']; @endphp
<a href="{{ route('profile.gamification', $user) }}" class="block mb-4">
    <div class="nb-card p-4 relative overflow-hidden transition-transform hover:translate-y-[-2px] cursor-pointer"
         style="background: {{ $tier['card_bg'] }}; color: {{ $tier['card_text'] }}; border-color: #1A1A2E;">

        {{-- Background decoration --}}
        <div class="absolute -right-6 -bottom-6 text-[90px] opacity-10 select-none pointer-events-none leading-none">
            {{ $tier['emoji'] }}
        </div>

        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl">{{ $tier['emoji'] }}</span>
                        <span class="font-heading font-bold text-lg leading-tight">{{ $tier['name'] }}</span>
                    </div>
                    <p class="text-xs font-medium opacity-80 mt-0.5">Level {{ $levelInfo['level'] }}</p>
                </div>
                <div class="text-right">
                    <p class="font-heading font-bold text-2xl">{{ number_format($user->xp) }}</p>
                    <p class="text-xs font-medium opacity-80">XP Total</p>
                </div>
            </div>

            {{-- XP Bar --}}
            <div class="relative">
                <div class="flex justify-between text-xs font-bold mb-1 opacity-80">
                    <span>{{ $levelInfo['xpInLevel'] }} XP</span>
                    <span>
                        @if($levelInfo['tierMax'])
                            {{ $levelInfo['xpToNextTier'] }} XP ke tier berikutnya
                        @else
                            Tier Tertinggi! 👑
                        @endif
                    </span>
                </div>
                <div class="w-full h-3 bg-black/20 rounded-full border-2 border-black/20 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width: {{ $levelInfo['tierPercent'] }}%; background: {{ $tier['card_text'] === '#FFFFFF' ? 'rgba(255,255,255,0.8)' : '#1A1A2E' }};">
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
</div>
