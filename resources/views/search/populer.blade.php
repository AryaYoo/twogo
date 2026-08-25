@extends('layouts.app')
@section('title', 'Trip Populer')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('search') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">Trip Populer 🌟</h1>
    </div>
</div>
@endsection

@section('content')

<div class="flex flex-col items-center text-center py-8 gap-6">

    {{-- Icon & badge --}}
    <div class="relative">
        <div class="w-24 h-24 bg-[#FFB830] rounded-2xl border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-center text-5xl">
            🌟
        </div>
        <span class="absolute -top-2 -right-2 bg-[#4361EE] text-white text-[9px] font-bold px-2 py-1 rounded-full border-2 border-white shadow leading-none">COMING SOON</span>
    </div>

    {{-- Title --}}
    <div>
        <h2 class="font-heading font-bold text-2xl mb-2">Trip Populer</h2>
        <p class="text-sm font-medium opacity-60 leading-relaxed max-w-xs">
            Kumpulan destinasi & itinerary <strong class="opacity-100">pilihan tim TwoGo</strong>
            yang bisa kamu jadikan referensi perjalananmu berikutnya.
        </p>
    </div>

    {{-- Feature preview cards --}}
    <div class="w-full flex flex-col gap-3 max-w-sm">
        <div class="nb-card bg-white p-4 text-left flex gap-4 items-center">
            <div class="w-10 h-10 bg-[#FFFBEB] rounded-lg border-2 border-[#FFB830] flex items-center justify-center text-xl shrink-0">🏷️</div>
            <div>
                <p class="font-bold text-sm">Kurasi Tim TwoGo</p>
                <p class="text-xs opacity-60 leading-snug">Hanya trip dengan tag khusus dari developer yang masuk daftar ini</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-4 items-center">
            <div class="w-10 h-10 bg-[#FFFBEB] rounded-lg border-2 border-[#FFB830] flex items-center justify-center text-xl shrink-0">📋</div>
            <div>
                <p class="font-bold text-sm">Salin ke Wishlist</p>
                <p class="text-xs opacity-60 leading-snug">Langsung salin itinerary favorit ke wishlistmu dengan satu klik</p>
            </div>
        </div>
        <div class="nb-card bg-white p-4 text-left flex gap-4 items-center">
            <div class="w-10 h-10 bg-[#FFFBEB] rounded-lg border-2 border-[#FFB830] flex items-center justify-center text-xl shrink-0">🗺️</div>
            <div>
                <p class="font-bold text-sm">Berbagai Destinasi</p>
                <p class="text-xs opacity-60 leading-snug">Lokal & mancanegara untuk semua jenis budget perjalanan</p>
            </div>
        </div>
    </div>

    {{-- Coming soon banner --}}
    <div class="nb-card bg-[#FFFBEB] border-[#FFB830] p-4 w-full max-w-sm text-sm font-medium text-[#92400E] leading-relaxed">
        🛠️ Tim kami sedang menyiapkan koleksi trip terbaik. Segera hadir!
    </div>

</div>

@endsection
