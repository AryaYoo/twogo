@extends('layouts.app')
@section('title', 'Teman')

@section('header')

<div class="flex items-center gap-3">
    <a href="{{ route('profile.show') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <h1 class="text-xl font-heading font-bold">Teman Kamu 👥</h1>
</div>
@endsection

@section('content')

<!-- Search Form -->
<x-card class="mb-6 bg-[#1A1A2E] text-white">
    <form action="{{ route('friends.search') }}" method="GET" class="flex gap-2">
        <input 
            type="text" 
            name="q" 
            value="{{ $query ?? '' }}" 
            placeholder="Cari nama atau email teman..." 
            class="flex-1 rounded-sm px-3 py-2 text-[#1A1A2E] font-medium"
            required minlength="3"
        >
        <x-button type="submit" variant="mint" class="shrink-0">Cari</x-button>
    </form>
</x-card>

@if(isset($searchResults))
    <div class="mb-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-heading font-bold text-lg">Hasil Pencarian</h3>
            <a href="{{ route('friends.index') }}" class="text-sm font-bold text-[#4361EE] hover:underline">Tutup</a>
        </div>
        
        <div class="flex flex-col gap-3">
            @forelse($searchResults as $user)
                <x-card class="flex justify-between items-center">
                    <a href="{{ route('profile.user', $user) }}" class="flex items-center gap-3 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                        <x-avatar :user="$user" />
                        <div class="min-w-0">
                            <div class="font-bold truncate">{{ $user->name }}</div>
                            <div class="text-xs opacity-70 truncate">{{ $user->email }}</div>
                        </div>
                    </a>
                    
                    @if($user->friendship_status === 'none')
                        <form action="{{ route('friends.request', $user) }}" method="POST">
                            @csrf
                            <x-button type="submit" variant="pink" size="sm">Tambah</x-button>
                        </form>
                    @elseif($user->friendship_status === 'pending')
                        @if($user->friendship_initiator === Auth::id())
                            <span class="text-xs font-bold text-gray-500">Menunggu...</span>
                        @else
                            <span class="text-xs font-bold text-[#4361EE]">Cek Request</span>
                        @endif
                    @elseif($user->friendship_status === 'accepted')
                        <span class="text-xs font-bold text-[#00D4AA]">Teman</span>
                    @endif
                </x-card>
            @empty
                <div class="text-center py-6 text-sm font-medium opacity-70">
                    Tidak ada user yang cocok dengan "{{ $query }}"
                </div>
            @endforelse
        </div>
    </div>
@endif

@if(!isset($searchResults))

    <!-- Pending Requests -->
    @if($pendingRequests->count() > 0)
        <h3 class="font-heading font-bold text-lg mb-3 flex items-center gap-2">
            Permintaan Masuk
            <span class="bg-[#FF6B9D] text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingRequests->count() }}</span>
        </h3>
        
        <div class="flex flex-col gap-3 mb-8">
            @foreach($pendingRequests as $req)
                <x-card class="bg-[#FFFBEB] flex justify-between items-center border-[3px] border-[#FF6B9D]">
                    <a href="{{ route('profile.user', $req->user) }}" class="flex items-center gap-3 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                        <x-avatar :user="$req->user" />
                        <div class="min-w-0">
                            <div class="font-bold truncate">{{ $req->user->name }}</div>
                            <div class="text-xs opacity-70">Ingin berteman denganmu</div>
                        </div>
                    </a>
                    
                    <div class="flex gap-2">
                        <form action="{{ route('friends.accept', $req) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-sm bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">✓</button>
                        </form>
                        <form action="{{ route('friends.decline', $req) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-sm bg-[#EF4444] text-white border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">&times;</button>
                        </form>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
    
    <!-- Friend List -->
    <h3 class="font-heading font-bold text-lg mb-3">Daftar Teman</h3>
    
    <div class="flex flex-col gap-3">
        @forelse($friends as $friend)
            <x-card class="flex justify-between items-center">
                <a href="{{ route('profile.user', $friend) }}" class="flex items-center gap-3 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                    <x-avatar :user="$friend" />
                    <div class="min-w-0">
                        <div class="font-bold truncate">{{ $friend->name }}</div>
                        <div class="text-xs opacity-70 truncate">{{ $friend->email }}</div>
                    </div>
                </a>
                
                <form action="{{ route('friends.remove', $friend) }}" method="POST" onsubmit="return confirm('Hapus dari daftar teman?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-sm bg-red-500 text-white border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform">&times;</button>
                </form>
            </x-card>
        @empty
            <x-empty-state 
                icon="🤝" 
                title="Belum ada teman" 
                description="Cari temanmu di kotak pencarian di atas untuk mulai merencanakan trip bareng."
            />
        @endforelse
    </div>

@endif

@endsection
