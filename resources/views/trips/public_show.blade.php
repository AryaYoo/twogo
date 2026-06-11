@extends('layouts.app')
@section('title', $trip->title . ' - Itinerary Publik')

@php
    $formatActivityTime = function (?string $time): string {
        if (!$time) return '';
        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return \Carbon\Carbon::createFromFormat($format, $time)->format('H:i');
            } catch (\Exception) {
                continue;
            }
        }
        return $time;
    };
@endphp

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
            <h2 class="text-xl font-heading font-bold leading-tight">{{ $trip->title }}</h2>
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
        <div class="flex items-center gap-2">
            @auth
            <button id="like-btn" onclick="toggleLike({{ $trip->id }})"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border-2 border-[#1A1A2E] font-bold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all hover:translate-y-[-1px] {{ $isLiked ? 'bg-[#FF6B9D] text-white' : 'bg-white text-[#1A1A2E]' }}">
                <span id="like-icon">{{ $isLiked ? '❤️' : '🤍' }}</span>
                <span id="like-count">{{ $likeCount }}</span>
            </button>
            @else
            <span class="text-sm font-bold text-[#FF6B9D] bg-white px-2.5 py-1.5 rounded-full border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]">❤️ {{ $likeCount }}</span>
            @endauth
            
            <span class="text-sm font-bold text-[#4361EE] bg-white px-2.5 py-1.5 rounded-full border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center gap-1.5" title="Jumlah disalin">
                📋 {{ $trip->clones()->count() }}
            </span>
        </div>
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

{{-- Tabs --}}
<div class="flex gap-2 mb-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]">
    <button id="tab-itinerary-btn" onclick="switchPublicTab('itinerary')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 public-tab-btn active-tab" data-tab="itinerary">
        🗓️ Itinerary
    </button>
    @if($hasDocumentation)
    <button id="tab-docs-btn" onclick="switchPublicTab('docs')"
        class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 public-tab-btn" data-tab="docs">
        📸 Dokumentasi ({{ $documentationItems->count() }})
    </button>
    @endif
</div>

{{-- Tab: Itinerary --}}
<div id="tab-itinerary" class="public-tab-content">
    @forelse($trip->days as $day)
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="font-heading font-bold text-lg bg-[#FFE156] px-4 py-1 border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E]">
                Hari {{ $day->day_number }} • {{ $day->date->translatedFormat('d M y') }}
            </h2>
            <div class="h-1 flex-1 bg-[#1A1A2E]"></div>
        </div>

        <div class="flex flex-col gap-6 pl-2 border-l-[3px] border-[#1A1A2E] ml-4 py-2">
            @foreach(['pagi' => '🌅 PAGI', 'siang' => '🌞 SIANG', 'malam' => '🌙 MALAM'] as $session => $label)
                @php $activities = $day->activities->where('session', $session); @endphp
                @if($activities->count() > 0)
                <div class="relative">
                    <div class="absolute -left-[20px] top-0 w-8 h-8 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center text-sm shadow-[2px_2px_0px_#1A1A2E] z-10">
                        {{ $session === 'pagi' ? '🌅' : ($session === 'siang' ? '🌞' : '🌙') }}
                    </div>

                    <div class="ml-8 mb-3">
                        <h3 class="font-heading font-bold text-sm">{{ $label }}</h3>
                    </div>

                    <div class="ml-8 flex flex-col gap-3 mb-2">
                        @foreach($activities as $act)
                        <div class="nb-card {{ $act->is_completed ? 'bg-gray-100 opacity-80' : 'bg-white' }} p-3">
                            <div class="flex gap-3">
                                <div class="shrink-0 mt-1 w-6 h-6 border-[3px] border-[#1A1A2E] rounded-sm flex items-center justify-center {{ $act->is_completed ? 'bg-[#00D4AA]' : 'bg-white' }}">
                                    @if($act->is_completed)
                                        <span class="text-white text-xs font-bold">✓</span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold font-heading text-base leading-tight {{ $act->is_completed ? 'line-through' : '' }}">{{ $act->title }}</h4>

                                    @if($act->start_time || $act->end_time)
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-[#4361EE] mt-1 mb-2">
                                        <span>🕐</span>
                                        <span>
                                            {{ $formatActivityTime($act->start_time) }}
                                            @if($act->start_time && $act->end_time) — @endif
                                            {{ $formatActivityTime($act->end_time) }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="text-xs font-bold bg-gray-200 px-2 py-0.5 rounded-full border border-gray-400">{{ $act->category }}</span>
                                        @if($act->estimated_cost > 0)
                                        <span class="text-xs font-bold bg-[#FFE156] px-2 py-0.5 rounded-full border border-[#1A1A2E]">
                                            Rp {{ number_format($act->estimated_cost, 0, ',', '.') }}
                                        </span>
                                        @endif
                                        @if($act->is_completed && $act->actual_cost > 0)
                                        <span class="text-xs font-bold bg-[#00D4AA] px-2 py-0.5 rounded-full border border-[#1A1A2E]">
                                            Real: Rp {{ number_format($act->actual_cost, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </div>

                                    @if($act->location_name)
                                    <a href="{{ $act->location_url ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($act->location_name . ' ' . $trip->destination) }}" target="_blank" class="text-sm text-[#4361EE] hover:underline font-medium inline-flex items-center gap-1 mb-1">
                                        📍 {{ $act->location_name }}
                                    </a>
                                    @endif

                                    @if($act->description)
                                    <p class="text-sm opacity-80">{{ $act->description }}</p>
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
    </div>
    @empty
    <x-empty-state icon="🗓️" title="Belum ada itinerary" description="Trip ini belum memiliki jadwal kegiatan." />
    @endforelse
</div>

@if($hasDocumentation)
{{-- Tab: Dokumentasi --}}
<div id="tab-docs" class="public-tab-content hidden">
    <div class="grid grid-cols-2 gap-3">
        @foreach($documentationItems as $item)
        <div class="nb-card bg-white p-2 overflow-hidden">
            @if($item['kind'] === 'photo' || $item['kind'] === 'activity_photo')
                @php
                    $filename = basename($item['file_path']);
                    $dirname = dirname($item['file_path']);
                    $thumbPath = $dirname . '/thumb_' . $filename;
                @endphp
                <button type="button" class="w-full text-left"
                    data-src="{{ asset('storage/' . $item['file_path']) }}"
                    data-title="{{ $item['title'] ?? ($item['kind'] === 'activity_photo' ? 'Dokumentasi Kegiatan' : 'Foto') }}"
                    onclick="openPhotoModal(this.dataset.src, this.dataset.title)">
                    <div class="w-full h-32 bg-gray-200 border-[3px] border-[#1A1A2E] rounded-md mb-2 overflow-hidden">
                        <img src="{{ asset('storage/' . $thumbPath) }}" onerror="this.src='{{ asset('storage/' . $item['file_path']) }}'" class="w-full h-full object-cover" alt="" loading="lazy">
                    </div>
                    <h4 class="font-bold font-heading text-sm truncate">
                        {{ $item['title'] ?? ($item['kind'] === 'activity_photo' ? '📌 Kegiatan' : '📷 Foto') }}
                    </h4>
                </button>
            @else
                <div class="w-full bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-md mb-2 p-3 pb-5 relative min-h-[8rem]">
                    <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-6 h-2 bg-gray-300 border-2 border-[#1A1A2E] rounded-full"></div>
                    <p class="text-sm font-medium leading-relaxed line-clamp-6">{{ $item['content'] }}</p>
                </div>
                @if($item['title'])
                    <p class="font-bold font-heading text-sm truncate">{{ $item['title'] }}</p>
                @endif
            @endif

            <div class="flex justify-between items-center text-[10px] opacity-70 font-bold mt-1">
                <span class="truncate">
                    @if($item['kind'] === 'activity_photo')
                        📌 Kegiatan
                    @elseif($item['kind'] === 'photo')
                        📷 Foto
                    @else
                        📝 Catatan
                    @endif
                </span>
                <span class="shrink-0 ml-1">{{ $item['created_at']->format('d M y') }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Clone to Wishlist CTA --}}
@auth
<div class="sticky bottom-20 left-0 right-0 pb-2 mt-4">
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

@if($hasDocumentation)
<x-modal id="photoModal" title="Preview Foto">
    <div class="flex flex-col items-center p-2">
        <div class="w-full relative mb-2">
            <img id="modalPhotoImage" src="" class="w-full rounded-md border-[3px] border-[#1A1A2E]" alt="Dokumentasi">
            <div class="absolute bottom-2 left-2 right-2 bg-[#1A1A2E]/80 text-white p-2 rounded text-sm font-bold truncate backdrop-blur-sm" id="modalPhotoTitle"></div>
        </div>
    </div>
</x-modal>
@endif

@endsection

@push('scripts')
<style>
    .public-tab-btn { color: #1A1A2E; background: transparent; }
    .active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
    function switchPublicTab(tab) {
        document.querySelectorAll('.public-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.public-tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
    }

    @auth
    function toggleLike(tripId) {
        fetch(`/trips/${tripId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
    @endauth

    @if($hasDocumentation)
    function openPhotoModal(src, title) {
        document.getElementById('modalPhotoImage').src = src;
        document.getElementById('modalPhotoTitle').textContent = title;
        openModal('photoModal');
    }
    @endif
</script>
@endpush
