@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="nb-card max-w-sm mx-auto w-full">
    <h2 class="text-2xl font-heading font-bold mb-6 text-center">Selamat Datang Kembali 👋</h2>
    
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <x-input 
            type="email"
            name="email" 
            label="Email" 
            placeholder="john@example.com" 
            required="true"
        />
        
        <x-input 
            type="password"
            name="password" 
            label="Password" 
            placeholder="••••••••" 
            required="true"
        />
        
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 border-[2px] border-[#1A1A2E] rounded-sm accent-[#FFE156]">
                <span class="text-sm font-medium">Ingat Saya</span>
            </label>
            
            <a href="{{ route('password.request') }}" class="text-sm text-[#4361EE] hover:underline font-bold">Lupa Password?</a>
        </div>
        
        <div>
            <x-button type="submit" variant="primary" class="w-full text-lg">Masuk</x-button>
        </div>
    </form>
    
    <p class="text-center mt-6 font-medium text-sm">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="text-[#4361EE] hover:underline font-bold">Daftar sekarang</a>
    </p>
</div>
@endsection
