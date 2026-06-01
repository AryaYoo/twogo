@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
<div class="nb-card max-w-sm mx-auto w-full">
    <h2 class="text-2xl font-heading font-bold mb-6 text-center">Buat Akun Baru 🚀</h2>
    
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <x-input 
            name="name" 
            label="Nama Akun" 
            placeholder="Yohanes" 
            required="true"
        />
        
        <x-input 
            type="email"
            name="email" 
            label="Email" 
            placeholder="yohanes@example.com" 
            required="true"
        />
        
        <x-input 
            type="password"
            name="password" 
            label="Password" 
            placeholder="••••••••" 
            required="true"
        />
        
        <x-input 
            type="password"
            name="password_confirmation" 
            label="Konfirmasi Password" 
            placeholder="••••••••" 
            required="true"
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full text-lg">Daftar Sekarang</x-button>
        </div>
    </form>
    
    <p class="text-center mt-6 font-medium text-sm">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="text-[#4361EE] hover:underline font-bold">Masuk di sini</a>
    </p>
</div>
@endsection
