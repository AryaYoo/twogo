@extends('layouts.app')
@section('title', 'Profil Saya')

@section('header')
<div class="flex-1">
    <h1 class="text-2xl font-heading font-bold">Profil Kamu 🧑‍🚀</h1>
</div>
@endsection

@section('content')
<div class="flex flex-col items-center mb-8">
    <div class="relative mb-4">
        <x-avatar :user="$user" size="xl" class="border-4 shadow-[4px_4px_0px_#1A1A2E]" />
        <a href="{{ route('profile.edit') }}" class="absolute bottom-0 right-0 w-8 h-8 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center shadow-[2px_2px_0px_#1A1A2E] hover:-translate-y-1 transition-transform cursor-pointer">
            ✏️
        </a>
    </div>
    <h2 class="text-2xl font-heading font-bold">{{ $user->name }}</h2>
    <p class="text-sm font-medium opacity-70">{{ $user->email }}</p>
    
    @if($user->bio)
    <p class="mt-3 text-center text-sm font-medium max-w-xs">{{ $user->bio }}</p>
    @endif
</div>

<div class="flex flex-col gap-3 mb-8">
    <a href="{{ route('friends.index') }}" class="block">
        <x-card class="bg-white flex items-center justify-between hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="text-2xl">👥</div>
                <div class="font-bold">Daftar Teman</div>
            </div>
            <div class="font-bold opacity-50">&rarr;</div>
        </x-card>
    </a>
    
    <a href="{{ route('profile.edit') }}" class="block">
        <x-card class="bg-white flex items-center justify-between hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="text-2xl">⚙️</div>
                <div class="font-bold">Edit Profil</div>
            </div>
            <div class="font-bold opacity-50">&rarr;</div>
        </x-card>
    </a>
</div>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <x-button type="submit" variant="danger" class="w-full text-lg">Keluar Aplikasi</x-button>
</form>
@endsection
