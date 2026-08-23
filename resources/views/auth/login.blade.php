@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="relative max-w-sm mx-auto w-full mt-4 group">
    <!-- Offset background block for neo-brutalism pop -->
    <div class="absolute inset-0 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-2xl rotate-2 transition-transform duration-300"></div>
    
    <!-- Main form card -->
    <div class="relative bg-[#FFFBEB] border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl p-6 md:p-8 z-10">
        <h1 class="text-xs font-heading font-bold text-slate-400 tracking-tight text-center mb-2">TwoGo<span class="text-[#FF6B9D]">.</span></h1>
        <h2 class="text-2xl font-heading font-extrabold mb-6 text-center text-[#1A1A2E]">Selamat Datang<br>Kembali 👋</h2>
    
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
        
        <div class="mt-5 flex items-center justify-between">
            <span class="w-[30%] border-b-[2px] border-slate-300"></span>
            <span class="text-xs text-center text-slate-500 font-bold uppercase">atau</span>
            <span class="w-[30%] border-b-[2px] border-slate-300"></span>
        </div>
        
        <div class="mt-5">
            <a href="{{ route('auth.google') }}" class="w-full py-3 bg-white hover:bg-slate-50 border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl flex items-center justify-center gap-3 font-heading font-extrabold transition-all active:translate-y-[3px] active:shadow-none text-[#1A1A2E]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>
        </div>
    </form>
    
    <p class="text-center mt-6 font-medium text-sm">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="text-[#4361EE] hover:underline font-bold">Daftar sekarang</a>
    </p>
    </div>
</div>
@endsection
