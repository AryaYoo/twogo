@extends('layouts.app')
@section('title', 'Undang Teman')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Undang Partner 🤝</h1>
    </div>
</div>
@endsection

@section('content')

@if($trip->members->count() >= 2)
    <x-card class="bg-[#EF4444] text-white text-center py-8">
        <div class="text-5xl mb-4">🚫</div>
        <h2 class="font-heading font-bold text-2xl mb-2">Trip Penuh!</h2>
        <p class="font-medium opacity-90 max-w-xs mx-auto">Sesuai namanya, TwoGo difokuskan untuk 2 orang saja. Kuota trip ini sudah maksimal.</p>
    </x-card>
@else
    <!-- Invite via Code -->
    <x-card class="bg-[#FFE156] text-center p-8 mb-6 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -right-6 -top-6 text-6xl opacity-20 transform rotate-12">🎫</div>
        
        <h3 class="font-heading font-bold text-lg mb-2 relative z-10">Bagikan Kode Invite</h3>
        <p class="text-sm font-medium mb-6 relative z-10 opacity-80">Kasih kode ini ke partnermu untuk join trip.</p>
        
        <div class="bg-white border-[3px] border-[#1A1A2E] rounded-xl py-4 px-6 inline-block shadow-[4px_4px_0px_#1A1A2E] relative z-10 cursor-pointer hover:bg-gray-50 transition-colors" onclick="copyCode()">
            <span id="invite-code" class="font-heading font-bold text-3xl tracking-widest text-[#4361EE]">{{ $trip->invite_code }}</span>
            <div class="text-[10px] uppercase font-bold text-gray-500 mt-1">Tap untuk Copy</div>
        </div>
        
        <script>
            function copyCode() {
                navigator.clipboard.writeText('{{ $trip->invite_code }}');
                showToast('Kode berhasil dicopy!', 'success');
            }
        </script>
    </x-card>

    <!-- Join with Code Form (For others) -->
    <h3 class="font-heading font-bold text-lg mb-3">Atau Punya Kode Invite?</h3>
    <x-card>
        <form action="{{ route('invitations.join_code') }}" method="POST" class="flex gap-2">
            @csrf
            <input 
                type="text" 
                name="invite_code" 
                placeholder="Masukkan 6 digit kode..." 
                class="flex-1 border-[3px] border-[#1A1A2E] rounded-sm px-3 py-2 text-[#1A1A2E] font-bold uppercase"
                required minlength="6" maxlength="6"
            >
            <x-button type="submit" variant="mint" class="shrink-0">Gabung</x-button>
        </form>
        @error('invite_code')
            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
        @enderror
    </x-card>
    
    <!-- Invite a friend directly -->
    <h3 class="font-heading font-bold text-lg mt-6 mb-3">Undang Teman</h3>
    <x-card>
        <form action="{{ route('invitations.send', $trip) }}" method="POST" class="flex gap-2 items-center">
            @csrf
            <select name="invited_user_id" class="flex-1 border-[3px] border-[#1A1A2E] rounded-sm px-3 py-2" required>
                <option value="">Pilih teman untuk diundang</option>
                @foreach($availableFriends as $f)
                    <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->email }})</option>
                @endforeach
            </select>
            <x-button type="submit" variant="mint" class="shrink-0">Kirim Undangan</x-button>
        </form>
        @error('invited_user_id')
            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
        @enderror
    </x-card>
@endif
@endsection
