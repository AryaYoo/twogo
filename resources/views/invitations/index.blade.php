@extends('layouts.app')
@section('title', 'Notifikasi Saya')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Notifikasi</h1>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col h-full">
    {{-- Tabs --}}
    <div class="flex gap-2 mb-5 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]">
        <button id="tab-invitations-btn" onclick="switchNotificationTab('invitations')"
            class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 notification-tab-btn active-tab" data-tab="invitations">
            🔔 Undangan
            @if($invitations->count() > 0)
                <span class="ml-1 bg-[#1A1A2E] text-[#FFE156] text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $invitations->count() }}</span>
            @endif
        </button>
        <button id="tab-activities-btn" onclick="switchNotificationTab('activities')"
            class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 notification-tab-btn" data-tab="activities">
            ⚡ Aktivitas
        </button>
    </div>

    {{-- Tab: Invitations --}}
    <div id="tab-invitations" class="notification-tab-content flex flex-col gap-4">
        @forelse($invitations as $inv)
            <x-card class="mb-4">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-heading font-bold text-base">{{ $inv->trip->title }}</h3>
                        <p class="text-xs opacity-80">Diundang oleh: <span class="font-bold">{{ $inv->inviter->name }}</span></p>
                        @if($inv->trip->start_date)
                            <p class="text-sm mt-2 font-medium opacity-90">Tanggal trip: {{ $inv->trip->start_date->format('d M Y') }} — {{ $inv->trip->end_date->format('d M Y') }}</p>
                        @else
                            <p class="text-sm mt-2 italic text-[#FF6B9D] font-medium">Trip Wishlist (belum ada tanggal)</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 shrink-0">
                        <form action="{{ route('invitations.accept_inapp', $inv) }}" method="POST">
                            @csrf
                            <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm w-full">Terima</button>
                        </form>
                        <form action="{{ route('invitations.decline_inapp', $inv) }}" method="POST">
                            @csrf
                            <button type="submit" class="nb-btn nb-btn-ghost nb-btn-sm w-full">Tolak</button>
                        </form>
                    </div>
                </div>
            </x-card>
        @empty
            <x-empty-state icon="📭" title="Tidak ada undangan" description="Belum ada undangan masuk saat ini." />
        @endforelse
    </div>

    {{-- Tab: Activities --}}
    <div id="tab-activities" class="notification-tab-content hidden flex flex-col gap-3">
        @forelse($activities as $act)
            @php
                $data = $act->data;
                $icon = $data['icon'] ?? '🔔';
                $message = $data['message'] ?? '';
                $link = $data['link'] ?? '#';
                $time = $act->created_at->diffForHumans();
            @endphp
            <a href="{{ $link }}" class="block">
                <x-card class="bg-white hover:bg-gray-50 transition-colors p-3.5 flex items-center gap-3">
                    <div class="w-10 h-10 shrink-0 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-full flex items-center justify-center text-xl shadow-[2px_2px_0px_#1A1A2E]">
                        {{ $icon }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-[#1A1A2E] leading-snug">{{ $message }}</p>
                        <p class="text-[10px] opacity-60 font-bold mt-1">{{ $time }}</p>
                    </div>
                    <span class="text-lg shrink-0 opacity-40">→</span>
                </x-card>
            </a>
        @empty
            <x-empty-state icon="⚡" title="Belum ada aktivitas" description="Semua aktivitas terbaru kamu akan muncul di sini." />
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<style>
    .notification-tab-btn { color: #1A1A2E; background: transparent; }
    .active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
    function switchNotificationTab(tab) {
        document.querySelectorAll('.notification-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.notification-tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
    }
</script>
@endpush
