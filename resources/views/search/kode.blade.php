@extends('layouts.app')
@section('title', 'Kode Perjalanan')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('search') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <h1 class="text-xl font-heading font-bold">Kode Perjalanan 🎟️</h1>
</div>
@endsection

@section('content')

<div class="flex flex-col gap-4">

    {{-- Hero --}}
    <div class="nb-card bg-white text-[#1A1A2E] p-5 flex flex-col items-center text-center gap-2">
        <div class="text-5xl">🎟️</div>
        <h2 class="font-heading font-bold text-lg">Masukkan Kode Perjalanan</h2>
        <p class="text-sm opacity-70 leading-relaxed max-w-xs">
            Punya kode dari temanmu? Masukkan di sini untuk langsung bergabung ke trip mereka!
        </p>
    </div>

    {{-- Form --}}
    <x-card>
        <form action="{{ route('invitations.join_code') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            @if($errors->has('invite_code'))
                <div class="bg-red-50 border-[2px] border-red-400 rounded-lg p-3 text-sm text-red-600 font-medium flex gap-2 items-start">
                    <span>❌</span>
                    <span>{{ $errors->first('invite_code') }}</span>
                </div>
            @endif

            <div class="nb-form-group">
                <label class="nb-label" for="invite_code">Kode Perjalanan (6 karakter)</label>
                <input
                    id="invite_code"
                    type="text"
                    name="invite_code"
                    value="{{ old('invite_code') }}"
                    placeholder="Contoh: ABCD12"
                    maxlength="6"
                    autocomplete="off"
                    autofocus
                    class="nb-input uppercase tracking-[0.3em] font-mono font-bold text-center text-xl placeholder:tracking-normal placeholder:font-normal placeholder:text-sm placeholder:font-sans"
                />
                <p class="text-xs text-gray-500 mt-1.5 font-medium text-center">
                    Tidak case-sensitive · otomatis dikonversi ke huruf besar
                </p>
            </div>

            <x-button type="submit" variant="primary" class="w-full text-base">
                🚀 Bergabung ke Trip
            </x-button>
        </form>
    </x-card>

    {{-- Info --}}
    <div class="nb-card bg-[#E1FCEF] border-[#00D4AA] p-4 flex gap-3 items-start">
        <div class="text-xl shrink-0">💡</div>
        <div class="text-sm font-medium text-gray-700 leading-relaxed">
            Kode perjalanan bisa ditemukan di halaman <strong>Kelola Undangan</strong> milik trip yang ingin kamu ikuti.
            Minta temanmu untuk berbagi kodenya!
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    var codeInput = document.getElementById('invite_code');
    if (codeInput) {
        codeInput.addEventListener('input', function () {
            var pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        });
    }
</script>
@endpush
