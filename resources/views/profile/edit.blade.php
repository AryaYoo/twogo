@extends('layouts.app')
@section('title', 'Edit Profil')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('profile.show') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1">
        <h1 class="text-xl font-heading font-bold">Edit Profil ✏️</h1>
    </div>
</div>
@endsection

@section('content')
<x-card>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="flex justify-center mb-6">
            <div class="relative">
                <x-avatar :user="$user" size="lg" id="avatarPreview" />
                <label for="avatarUpload" class="absolute bottom-0 right-0 w-8 h-8 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center shadow-[2px_2px_0px_#1A1A2E] cursor-pointer hover:bg-gray-100 transition-colors">
                    📷
                </label>
                <input type="file" id="avatarUpload" name="avatar" class="hidden" accept="image/*">
            </div>
        </div>
        
        <x-input name="name" label="Nama Lengkap" value="{{ $user->name }}" required="true" />
        <x-input type="email" name="email" label="Email" value="{{ $user->email }}" required="true" />
        <x-input name="phone" label="No. Handphone" value="{{ $user->phone }}" placeholder="08123456789" />
        <x-input type="textarea" name="bio" label="Bio Singkat" value="{{ $user->bio }}" placeholder="Traveler santai yang suka nyari makanan enak..." />
        
        <div class="mt-6 flex gap-4">
            <x-button type="submit" variant="mint" class="flex-1">Simpan Profil</x-button>
        </div>
    </form>
</x-card>
@endsection
