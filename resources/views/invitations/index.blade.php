@extends('layouts.app')
@section('title', 'Undangan Saya')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Undangan Masuk</h1>
    </div>
</div>
@endsection

@section('content')
    @forelse($invitations as $inv)
        <x-card class="mb-4">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h3 class="font-heading font-bold">{{ $inv->trip->title }}</h3>
                    <p class="text-xs opacity-80">Diundang oleh: {{ $inv->inviter->name }}</p>
                    <p class="text-sm mt-2">Tanggal trip: {{ $inv->trip->start_date->format('d M Y') }} — {{ $inv->trip->end_date->format('d M Y') }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <form action="{{ route('invitations.accept_inapp', $inv) }}" method="POST">
                        @csrf
                        <button type="submit" class="nb-btn nb-btn-primary">Terima</button>
                    </form>
                    <form action="{{ route('invitations.decline_inapp', $inv) }}" method="POST">
                        @csrf
                        <button type="submit" class="nb-btn nb-btn-ghost">Tolak</button>
                    </form>
                </div>
            </div>
        </x-card>
    @empty
        <x-empty-state icon="📭" title="Tidak ada undangan" description="Belum ada undangan masuk saat ini." />
    @endforelse
@endsection
