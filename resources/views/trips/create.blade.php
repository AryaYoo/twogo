@extends('layouts.app')
@section('title', 'Buat Trip Baru')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.index') }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform cursor-pointer">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">Buat Trip Baru ✨</h1>
    </div>
</div>
@endsection

@section('content')

{{-- Progress Tracker --}}
<div class="mb-6">
    <div class="flex items-center justify-between relative">
        <div class="absolute top-5 left-0 right-0 flex items-center px-5 z-0">
            <div class="h-[3px] flex-1 bg-[#1A1A2E] opacity-10 rounded-full"></div>
            <div class="h-[3px] flex-1 bg-[#1A1A2E] opacity-10 rounded-full"></div>
        </div>

        {{-- Step 1 --}}
        <div class="flex flex-col items-center z-10 gap-1">
            <div class="w-10 h-10 rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center font-heading font-extrabold text-sm transition-all duration-300 shadow-[2px_2px_0px_#1A1A2E] step-circle bg-[#FFE156]" id="circle-1">
                <span class="step-num">1</span>
                <span class="step-check hidden">✓</span>
            </div>
            <span class="text-[10px] font-bold text-center text-[#1A1A2E] leading-tight" id="label-1">Nama &<br>Destinasi</span>
        </div>

        {{-- Step 2 --}}
        <div class="flex flex-col items-center z-10 gap-1">
            <div class="w-10 h-10 rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center font-heading font-extrabold text-sm transition-all duration-300 shadow-[2px_2px_0px_#1A1A2E] step-circle bg-white opacity-40" id="circle-2">
                <span class="step-num">2</span>
                <span class="step-check hidden">✓</span>
            </div>
            <span class="text-[10px] font-bold text-center text-[#1A1A2E] leading-tight opacity-40" id="label-2">Tanggal<br>Perjalanan</span>
        </div>

        {{-- Step 3 --}}
        <div class="flex flex-col items-center z-10 gap-1">
            <div class="w-10 h-10 rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center font-heading font-extrabold text-sm transition-all duration-300 shadow-[2px_2px_0px_#1A1A2E] step-circle bg-white opacity-40" id="circle-3">
                <span class="step-num">3</span>
                <span class="step-check hidden">✓</span>
            </div>
            <span class="text-[10px] font-bold text-center text-[#1A1A2E] leading-tight opacity-40" id="label-3">Budget &<br>Catatan</span>
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="mt-3 h-[5px] bg-[#1A1A2E] bg-opacity-10 rounded-full border-[1.5px] border-[#1A1A2E] overflow-hidden">
        <div id="progress-bar" class="h-full rounded-full transition-all duration-500 ease-out bg-[#FFE156]" style="width: 33%"></div>
    </div>
</div>

{{-- Form --}}
<form action="{{ route('trips.store') }}" method="POST" id="trip-form">
    @csrf

    {{-- ===== STEP 1: Nama & Destinasi ===== --}}
    <div id="step-1" class="step-panel animate-fade-in-up">
        <div class="relative group mb-6">
            <div class="absolute inset-0 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-2xl rotate-1 transition-transform duration-300 group-hover:rotate-2"></div>
            <div class="relative bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-2xl p-5 shadow-[5px_5px_0px_#1A1A2E]">
                <div class="mb-5">
                    <div class="w-12 h-12 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-xl flex items-center justify-center text-2xl shadow-[2px_2px_0px_#1A1A2E] mb-3">✈️</div>
                    <h2 class="font-heading font-extrabold text-[#1A1A2E] text-xl">Mau kemana nih?</h2>
                    <p class="text-sm font-medium text-[#1A1A2E] opacity-60 mt-1">Kasih nama dan tujuan tripmu dulu ya!</p>
                </div>

                <div class="mb-4">
                    <label for="title" class="nb-label">Nama Trip <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        placeholder="Ke Jepang Bareng Bestie 🌸" class="nb-input">
                    @error('title')
                        <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="destination" class="nb-label">Destinasi Utama <span class="text-red-500">*</span></label>
                    <input type="text" id="destination" name="destination" value="{{ old('destination') }}"
                        placeholder="Bali, Indonesia 🏝️" class="nb-input">
                    @error('destination')
                        <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <button type="button" onclick="goToStep(2)"
            class="nb-btn w-full py-3.5 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-xl font-heading font-extrabold text-[#1A1A2E] text-base shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-center gap-2">
            Lanjut — Pilih Tanggal 📅 <span>→</span>
        </button>
    </div>

    {{-- ===== STEP 2: Tanggal ===== --}}
    <div id="step-2" class="step-panel hidden">
        <div class="relative group mb-6">
            <div class="absolute inset-0 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-2xl rotate-1 transition-transform duration-300 group-hover:rotate-2"></div>
            <div class="relative bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-2xl p-5 shadow-[5px_5px_0px_#1A1A2E]">
                <div class="mb-5">
                    <div class="w-12 h-12 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-xl flex items-center justify-center text-2xl shadow-[2px_2px_0px_#1A1A2E] mb-3">📅</div>
                    <h2 class="font-heading font-extrabold text-[#1A1A2E] text-xl">Kapan berangkat?</h2>
                    <p class="text-sm font-medium text-[#1A1A2E] opacity-60 mt-1">Pilih tanggal pergi dan pulang. Boleh dikosongkan dulu!</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="start_date" class="nb-label">Berangkat</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="nb-input">
                    </div>
                    <div>
                        <label for="end_date" class="nb-label">Pulang</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="nb-input">
                    </div>
                </div>
                @error('start_date')
                    <p class="text-red-500 text-xs font-bold mb-2">{{ $message }}</p>
                @enderror
                @error('end_date')
                    <p class="text-red-500 text-xs font-bold mb-2">{{ $message }}</p>
                @enderror

                <div class="bg-[#FF6B9D] bg-opacity-10 border-[2px] border-[#FF6B9D] rounded-xl p-3 flex items-start gap-2">
                    <span class="text-base shrink-0">💖</span>
                    <p class="text-xs font-bold text-[#1A1A2E] leading-relaxed">
                        Kosongkan tanggal untuk simpan ke <strong>Wishlist</strong> — isi nanti kalau udah fix!
                    </p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="goToStep(1)"
                class="nb-btn py-3.5 px-5 bg-white border-[3px] border-[#1A1A2E] rounded-xl font-heading font-extrabold text-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E]">
                ← Balik
            </button>
            <button type="button" onclick="goToStep(3)"
                class="nb-btn flex-1 py-3.5 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-xl font-heading font-extrabold text-[#1A1A2E] text-base shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-center gap-2">
                Lanjut — Budget &amp; Catatan 💰 <span>→</span>
            </button>
        </div>
    </div>

    {{-- ===== STEP 3: Budget & Deskripsi ===== --}}
    <div id="step-3" class="step-panel hidden">
        <div class="relative group mb-6">
            <div class="absolute inset-0 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-2xl rotate-1 transition-transform duration-300 group-hover:rotate-2"></div>
            <div class="relative bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-2xl p-5 shadow-[5px_5px_0px_#1A1A2E]">
                <div class="mb-5">
                    <div class="w-12 h-12 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-xl flex items-center justify-center text-2xl shadow-[2px_2px_0px_#1A1A2E] mb-3">💰</div>
                    <h2 class="font-heading font-extrabold text-[#1A1A2E] text-xl">Berapa budgetnya?</h2>
                    <p class="text-sm font-medium text-[#1A1A2E] opacity-60 mt-1">Estimasi budget dan catatan. Semua opsional kok!</p>
                </div>

                <div class="mb-4">
                    <label for="total_budget" class="nb-label">Total Budget <span class="text-xs font-medium opacity-50">(Opsional)</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-heading font-extrabold text-[#1A1A2E] text-sm opacity-60">Rp</span>
                        <input type="number" id="total_budget" name="total_budget" value="{{ old('total_budget') }}"
                            placeholder="5000000" class="nb-input pl-10" min="0">
                    </div>
                    @error('total_budget')
                        <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="nb-label">Deskripsi / Catatan <span class="text-xs font-medium opacity-50">(Opsional)</span></label>
                    <textarea id="description" name="description"
                        placeholder="Trip santai aja, ga usah ngoyo... 😌"
                        class="nb-textarea" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div id="summary-box" class="bg-[#1A1A2E] border-[3px] border-[#1A1A2E] rounded-2xl p-4 mb-5 hidden">
            <p class="text-[#FFE156] font-heading font-extrabold text-sm mb-2">📋 Ringkasan Trip</p>
            <div class="space-y-1">
                <p class="text-white text-sm font-bold">🏷️ <span id="sum-title" class="font-medium opacity-80"></span></p>
                <p class="text-white text-sm font-bold">📍 <span id="sum-dest" class="font-medium opacity-80"></span></p>
                <p class="text-white text-sm font-bold" id="sum-date-row">📅 <span id="sum-date" class="font-medium opacity-80"></span></p>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="goToStep(2)"
                class="nb-btn py-3.5 px-5 bg-white border-[3px] border-[#1A1A2E] rounded-xl font-heading font-extrabold text-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E]">
                ← Balik
            </button>
            <button type="submit" id="btn-submit"
                class="nb-btn flex-1 py-3.5 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-xl font-heading font-extrabold text-white text-base shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-center gap-2">
                Mulai Rencanakan! 🚀
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

const progressWidths  = { 1: '33%', 2: '66%', 3: '100%' };
const stepBgColors    = { 1: '#FFE156', 2: '#00D4AA', 3: '#FF6B9D' };
const circleActiveBgs = { 1: 'bg-[#FFE156]', 2: 'bg-[#00D4AA]', 3: 'bg-[#FF6B9D]' };

function goToStep(target) {
    if (target > currentStep && !validateStep(currentStep)) return;

    document.getElementById('step-' + currentStep).classList.add('hidden');

    if (target > currentStep) {
        markStepDone(currentStep);
    }

    currentStep = target;

    const panel = document.getElementById('step-' + currentStep);
    panel.classList.remove('hidden');

    // Update progress bar warna & lebar
    const bar = document.getElementById('progress-bar');
    bar.style.width           = progressWidths[currentStep];
    bar.style.backgroundColor = stepBgColors[currentStep];

    updateTracker();
    window.scrollTo({ top: 0, behavior: 'smooth' });

    if (currentStep === 3) updateSummary();
}

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('title').value.trim();
        const dest  = document.getElementById('destination').value.trim();
        if (!title) { shakeInput('title', 'Nama trip wajib diisi dulu ya! 🙏'); return false; }
        if (!dest)  { shakeInput('destination', 'Destinasi wajib diisi dulu ya! 📍'); return false; }
    }
    if (step === 2) {
        const start = document.getElementById('start_date').value;
        const end   = document.getElementById('end_date').value;
        if (start && end && start > end) {
            shakeInput('end_date', 'Tanggal pulang harus setelah berangkat! 📅');
            return false;
        }
    }
    return true;
}

function shakeInput(fieldId, msg) {
    const el = document.getElementById(fieldId);
    el.classList.add('!border-red-500');
    el.style.animation = 'nb-shake 0.35s ease';
    el.focus();

    let errEl = el.parentElement.querySelector('.temp-error');
    if (!errEl) {
        errEl = document.createElement('p');
        errEl.className = 'temp-error text-red-500 text-xs font-bold mt-1';
        el.parentElement.appendChild(errEl);
    }
    errEl.textContent = msg;

    setTimeout(() => {
        el.classList.remove('!border-red-500');
        el.style.animation = '';
        errEl && errEl.remove();
    }, 2500);
}

function markStepDone(step) {
    const c = document.getElementById('circle-' + step);
    c.className = c.className.replace(/bg-\[[^\]]+\]/g, '').trim();
    c.classList.add('bg-[#1A1A2E]');
    c.classList.remove('opacity-40');
    c.querySelector('.step-num').classList.add('hidden');
    const check = c.querySelector('.step-check');
    check.classList.remove('hidden');
    check.classList.add('text-white');

    const lbl = document.getElementById('label-' + step);
    if (lbl) lbl.classList.remove('opacity-40');
}

function updateTracker() {
    for (let s = 1; s <= totalSteps; s++) {
        const c   = document.getElementById('circle-' + s);
        const lbl = document.getElementById('label-' + s);

        if (s === currentStep) {
            // Aktif — beri warna sesuai step
            if (!c.classList.contains('bg-[#1A1A2E]')) {
                c.className = c.className.replace(/bg-\[[^\]]+\]/g, '').trim();
                c.classList.add(circleActiveBgs[s]);
            }
            c.classList.remove('opacity-40');
            if (lbl) lbl.classList.remove('opacity-40');
        } else if (s > currentStep) {
            // Belum dikunjungi & belum done
            if (!c.classList.contains('bg-[#1A1A2E]')) {
                c.classList.add('opacity-40');
                if (lbl) lbl.classList.add('opacity-40');
            }
        }
    }
}

function updateSummary() {
    const title = document.getElementById('title').value.trim();
    const dest  = document.getElementById('destination').value.trim();
    const start = document.getElementById('start_date').value;
    const end   = document.getElementById('end_date').value;

    document.getElementById('sum-title').textContent = title;
    document.getElementById('sum-dest').textContent  = dest;
    document.getElementById('summary-box').classList.remove('hidden');

    const opts     = { day: 'numeric', month: 'short', year: 'numeric' };
    const dateText = (start && end)
        ? new Date(start).toLocaleDateString('id-ID', opts) + ' – ' + new Date(end).toLocaleDateString('id-ID', opts)
        : 'Belum ditentukan (Wishlist)';
    document.getElementById('sum-date').textContent = dateText;
}

// Tekan Enter = lanjut step berikutnya (kecuali step terakhir)
document.getElementById('trip-form').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && currentStep < 3) {
        e.preventDefault();
        goToStep(currentStep + 1);
    }
});

// Inject keyframe shake
const style = document.createElement('style');
style.textContent = `
    @keyframes nb-shake {
        0%,100%{ transform:translateX(0) }
        20%{ transform:translateX(-6px) }
        40%{ transform:translateX(6px) }
        60%{ transform:translateX(-4px) }
        80%{ transform:translateX(4px) }
    }
`;
document.head.appendChild(style);

// Auto-jump ke step bermasalah saat ada server error
@if($errors->any())
    @if($errors->has('title') || $errors->has('destination'))
        {{-- tetap step 1, tidak perlu navigasi --}}
    @elseif($errors->has('start_date') || $errors->has('end_date'))
        goToStep(2);
    @else
        goToStep(3);
    @endif
@endif
</script>
@endpush
