@extends('layouts.app')
@section('title', 'Search')

@section('header')
<h1 class="text-xl font-heading font-bold">Search 🔍</h1>
@endsection

@section('content')

<p class="text-sm font-medium opacity-60 mb-5">Pilih salah satu fitur di bawah ini</p>

<div class="grid grid-cols-2 gap-4">

    {{-- Cari --}}
    <a href="{{ route('search.cari') }}"
       class="nb-card bg-white flex flex-col items-center justify-center gap-3 py-7 px-3 text-center hover:bg-[#FFE156] hover:translate-y-[-3px] transition-all duration-200 group">
        <div class="w-14 h-14 bg-[#FF6B9D] rounded-xl flex items-center justify-center text-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#FFE156] group-hover:shadow-[3px_3px_0px_#1A1A2E] transition-all">
            🔍
        </div>
        <div>
            <p class="font-heading font-bold text-base text-[#1A1A2E]">Cari</p>
            <p class="text-[11px] font-medium opacity-60 leading-tight mt-0.5">Cari trip, destinasi,<br>atau pengguna</p>
        </div>
    </a>

    {{-- Kode Perjalanan --}}
    <a href="{{ route('search.kode') }}"
       class="nb-card bg-white flex flex-col items-center justify-center gap-3 py-7 px-3 text-center hover:bg-[#FFE156] hover:translate-y-[-3px] transition-all duration-200 group">
        <div class="w-14 h-14 bg-[#00D4AA] rounded-xl flex items-center justify-center text-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#FFE156] group-hover:shadow-[3px_3px_0px_#1A1A2E] transition-all">
            🎟️
        </div>
        <div>
            <p class="font-heading font-bold text-base text-[#1A1A2E]">Kode Perjalanan</p>
            <p class="text-[11px] font-medium opacity-60 leading-tight mt-0.5">Masukkan kode untuk<br>bergabung ke trip</p>
        </div>
    </a>

    {{-- Open Partner --}}
    <a href="{{ route('search.partner') }}"
       class="nb-card bg-white flex flex-col items-center justify-center gap-3 py-7 px-3 text-center hover:bg-[#EEF2FF] hover:translate-y-[-3px] transition-all duration-200 group relative overflow-hidden">
        <span class="absolute top-2 right-2 bg-[#4361EE] text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full border-[1.5px] border-white leading-none">SOON</span>
        <div class="w-14 h-14 bg-[#4361EE] rounded-xl flex items-center justify-center text-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] opacity-80 group-hover:opacity-100 transition-all">
            🤝
        </div>
        <div>
            <p class="font-heading font-bold text-base text-[#1A1A2E]">Open Partner</p>
            <p class="text-[11px] font-medium opacity-60 leading-tight mt-0.5">Cari partner perjalanan<br>yang cocok</p>
        </div>
    </a>

    {{-- Trip Populer --}}
    <a href="{{ route('search.populer') }}"
       class="nb-card bg-white flex flex-col items-center justify-center gap-3 py-7 px-3 text-center hover:bg-[#FFFBEB] hover:translate-y-[-3px] transition-all duration-200 group relative overflow-hidden">
        <span class="absolute top-2 right-2 bg-[#4361EE] text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full border-[1.5px] border-white leading-none">SOON</span>
        <div class="w-14 h-14 bg-[#FFB830] rounded-xl flex items-center justify-center text-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] opacity-80 group-hover:opacity-100 transition-all">
            🌟
        </div>
        <div>
            <p class="font-heading font-bold text-base text-[#1A1A2E]">Trip Populer</p>
            <p class="text-[11px] font-medium opacity-60 leading-tight mt-0.5">Destinasi pilihan<br>tim TwoGo</p>
        </div>
    </a>

</div>

@endsection
