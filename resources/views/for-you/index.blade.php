@extends('layouts.app')
@section('title', 'For You')

@section('header')
<div class="flex items-center gap-3 w-full">
    <div class="flex-1 overflow-hidden">
        <h1 class="text-2xl font-heading font-bold">For You ✨</h1>
        <p class="text-sm font-medium opacity-80">Coming soon...</p>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="text-7xl mb-6 animate-bounce">🚀</div>
    <h2 class="font-heading font-bold text-2xl mb-3 text-[#1A1A2E]">Segera Hadir!</h2>
    <p class="text-base font-medium opacity-60 max-w-xs leading-relaxed">
        Halaman ini lagi dalam pengerjaan. Nanti di sini bakal ada rekomendasi trip, destinasi keren, dan konten seru buat kamu!
    </p>
    <div class="mt-10 nb-card bg-[#FFE156] p-5 w-full max-w-xs text-left shadow-[4px_4px_0px_#1A1A2E]">
        <p class="font-heading font-bold text-sm text-[#1A1A2E] mb-2">🔮 Yang akan hadir:</p>
        <ul class="space-y-2 text-sm font-medium text-[#1A1A2E]">
            <li class="flex items-center gap-2"><span class="text-base">🏖️</span> Rekomendasi destinasi</li>
            <li class="flex items-center gap-2"><span class="text-base">🍜</span> Kuliner wajib coba</li>
            <li class="flex items-center gap-2"><span class="text-base">💡</span> Inspirasi itinerary</li>
            <li class="flex items-center gap-2"><span class="text-base">👫</span> Trip populer TwoGo-ers</li>
        </ul>
    </div>
</div>
@endsection
