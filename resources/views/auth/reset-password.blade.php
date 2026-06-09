@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
<div class="nb-card max-w-sm mx-auto w-full">
    <h2 class="text-xl font-heading font-bold mb-6 text-center">Buat Password Baru 🔐</h2>
    
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <x-input 
            type="email"
            name="email" 
            label="Email" 
            value="{{ $email ?? old('email') }}"
            required="true"
        />
        
        <x-input 
            type="password"
            name="password" 
            label="Password Baru" 
            placeholder="••••••••" 
            required="true"
        />
        
        <x-input 
            type="password"
            name="password_confirmation" 
            label="Konfirmasi Password Baru" 
            placeholder="••••••••" 
            required="true"
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="mint" class="w-full">Reset Password</x-button>
        </div>
    </form>
</div>
@endsection
