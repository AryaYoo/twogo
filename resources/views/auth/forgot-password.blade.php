@extends('layouts.guest')
@section('title', 'Lupa Password')

@section('content')
<div class="nb-card max-w-sm mx-auto w-full">
    <h2 class="text-xl font-heading font-bold mb-2 text-center">Lupa Password? 🧐</h2>
    <p class="text-sm text-center mb-6 font-medium opacity-80">
        Tenang, kami akan mengirimkan link untuk mereset password ke emailmu.
    </p>
    
    @if (session('status'))
        <div class="mb-4 p-3 bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold text-sm rounded-lg shadow-[2px_2px_0px_#1A1A2E]">
            {{ session('status') }}
        </div>
    @endif
    
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <x-input 
            type="email"
            name="email" 
            label="Email" 
            placeholder="john@example.com" 
            required="true"
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="pink" class="w-full">Kirim Link Reset</x-button>
        </div>
    </form>
    
    <p class="text-center mt-6 font-medium text-sm">
        Ingat passwordnya? 
        <a href="{{ route('login') }}" class="text-[#4361EE] hover:underline font-bold">Masuk di sini</a>
    </p>
</div>
@endsection
