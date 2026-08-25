@extends('layouts.app')
@section('title', $trip->start_date ? 'Edit Trip' : 'Tetapkan Tanggal')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">{{ $trip->start_date ? 'Edit Trip ✏️' : 'Tetapkan Tanggal 📅' }}</h1>
    </div>
</div>
@endsection

@section('content')

@if(!$trip->start_date)
{{-- ===== WISHLIST MODE: hanya isi tanggal ===== --}}
<div class="flex flex-col gap-4">

    {{-- Info banner --}}
    <div class="nb-card bg-[#FFF0F5] border-[#FF6B9D] p-4 flex gap-3 items-start">
        <div class="text-2xl">💖</div>
        <div>
            <h2 class="font-heading font-bold text-base text-[#1A1A2E] mb-1">Tetapkan tanggal trip</h2>
            <p class="text-sm font-medium text-gray-600 leading-relaxed">
                Trip <strong>"{{ $trip->title }}"</strong> masih berstatus Wishlist. Isi tanggal berangkat dan pulang untuk mulai menyusun itinerary!
            </p>
        </div>
    </div>

    {{-- Date form --}}
    <x-card>
        <form action="{{ route('trips.update', $trip) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <p class="text-xs font-bold opacity-50 uppercase tracking-wide mb-3">Tanggal Perjalanan</p>
                <div class="grid grid-cols-2 gap-4">
                    <x-input
                        type="date"
                        name="start_date"
                        label="✈️ Berangkat"
                        required="true"
                    />
                    <x-input
                        type="date"
                        name="end_date"
                        label="🏠 Pulang"
                        required="true"
                    />
                </div>
            </div>

            <x-button type="submit" variant="primary" class="w-full text-base">
                🚀 Aktifkan Trip Sekarang
            </x-button>
        </form>
    </x-card>

    {{-- Danger zone --}}
    <div class="nb-card bg-white p-4">
        <h3 class="font-heading font-bold text-sm mb-1 text-red-500">Danger Zone</h3>
        <p class="text-xs font-medium mb-3 opacity-70">Menghapus trip akan menghapus semua data terkait secara permanen.</p>
        <form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="button" variant="danger" class="w-full" onclick="openModal('deleteTripModal')">
                Hapus Wishlist
            </x-button>
        </form>
    </div>

</div>

@else
{{-- ===== NORMAL TRIP MODE: edit lengkap ===== --}}
<x-card>
    <form action="{{ route('trips.update', $trip) }}" method="POST">
        @csrf
        @method('PUT')
        
        <x-input 
            name="title" 
            label="Nama Trip" 
            value="{{ $trip->title }}"
            required="true"
        />
        
        <x-input 
            name="destination" 
            label="Destinasi Utama" 
            value="{{ $trip->destination }}"
            required="true"
        />
        
        <x-input 
            type="number"
            name="total_budget" 
            label="Total Budget" 
            value="{{ $trip->total_budget }}" 
        />
        
        <x-input 
            type="textarea"
            name="description" 
            label="Deskripsi / Catatan" 
            value="{{ $trip->description }}" 
        />
        
        <div class="mt-6 flex gap-4">
            <x-button type="submit" variant="mint" class="flex-1">Simpan Perubahan</x-button>
        </div>
    </form>

    {{-- Tombol Ubah Tanggal — hanya untuk trip planning --}}
    @if($trip->status === 'planning')
    <div class="mt-6 border-t-[2px] border-dashed border-[#1A1A2E] pt-5">
        <div class="flex items-center justify-between mb-2">
            <div>
                <p class="font-heading font-bold text-sm text-[#1A1A2E]">📅 Tanggal Perjalanan</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $trip->start_date->translatedFormat('d M Y') }} — {{ $trip->end_date->translatedFormat('d M Y') }}
                    <span class="ml-1 font-bold text-[#4361EE]">({{ $trip->start_date->diffInDays($trip->end_date) + 1 }} hari)</span>
                </p>
            </div>
            <button
                type="button"
                id="btn-ubah-tanggal"
                onclick="openModal('konfirmasiTanggalModal')"
                class="nb-btn bg-[#FFE156] text-[#1A1A2E] border-[2px] border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform text-sm font-bold px-3 py-2 rounded-lg"
            >
                ✏️ Ubah Tanggal
            </button>
        </div>
    </div>
    @endif
    
    <div class="mt-8 border-t-[3px] border-[#1A1A2E] pt-6">
        <h3 class="font-heading font-bold text-lg mb-2 text-red-500">Danger Zone</h3>
        <p class="text-sm font-medium mb-4 opacity-80">Menghapus trip akan menghapus semua aktivitas, budget, dan dokumen terkait.</p>
        
        <form id="delete-trip-form" action="{{ route('trips.destroy', $trip) }}" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="button" variant="danger" class="w-full" onclick="openModal('deleteTripModal')">
                Hapus Trip Permanen
            </x-button>
        </form>
    </div>
</x-card>
@endif

{{-- ===== MODAL: Konfirmasi Ubah Tanggal ===== --}}
@if($trip->start_date && $trip->status === 'planning')
<x-modal id="konfirmasiTanggalModal" title="Konfirmasi Ubah Tanggal">
    <div class="p-1">
        <div class="text-center mb-5">
            <div class="text-5xl mb-3">📅</div>
            <h3 class="font-heading font-bold text-lg text-[#1A1A2E] mb-1">Ubah Tanggal Perjalanan?</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                Mengubah tanggal akan <strong>menggeser semua jadwal hari</strong>.
                Hari yang melebihi durasi baru akan <strong class="text-red-500">dihapus permanen</strong>.
            </p>
        </div>

        <div class="nb-form-group mb-2">
            <label class="nb-label text-center block" for="konfirmasi_modal_input">
                Ketik <span class="font-mono font-bold bg-[#1A1A2E] text-[#FFE156] px-1.5 py-0.5 rounded text-sm">KONFIRMASI</span> untuk lanjut
            </label>
            <input
                id="konfirmasi_modal_input"
                type="text"
                autocomplete="off"
                placeholder="Ketik KONFIRMASI..."
                class="nb-input text-center tracking-widest font-bold mt-2 transition-all duration-200"
            />
            <p id="konfirmasi_modal_hint" class="text-xs text-center mt-1.5 font-medium text-gray-400 min-h-[1rem]"></p>
        </div>

        <div class="flex gap-3 mt-5">
            <button
                type="button"
                onclick="closeModal('konfirmasiTanggalModal')"
                class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] hover:bg-gray-100 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2"
            >
                Batal
            </button>
            <a
                id="btn-lanjut-tanggal"
                href="{{ route('trips.edit-dates', $trip) }}"
                class="flex-1 nb-btn bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold rounded-md py-2 text-center shadow-[2px_2px_0px_#1A1A2E] opacity-40 pointer-events-none transition-all duration-200"
                aria-disabled="true"
            >
                Lanjut →
            </a>
        </div>
    </div>
</x-modal>
@endif

{{-- ===== MODAL: Hapus Trip ===== --}}
<x-modal id="deleteTripModal" title="Hapus Trip?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">🚨</div>
        <h3 class="font-heading font-bold text-xl mb-2 text-[#1A1A2E]">Hapus Permanen?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">
            Tindakan ini tidak dapat dibatalkan. Semua <strong class="text-red-500">aktivitas</strong>, <strong class="text-red-500">budget</strong>, dan <strong class="text-red-500">dokumen</strong> yang terkait akan ikut terhapus.
        </p>
        
        <div class="flex gap-3">
            <button type="button" onclick="closeModal('deleteTripModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] hover:bg-gray-100 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                Kembali
            </button>
            <button type="button" onclick="document.getElementById('delete-trip-form').submit();" class="flex-1 nb-btn bg-red-500 text-white border-2 border-[#1A1A2E] hover:bg-red-600 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                Ya, Hapus
            </button>
        </div>
    </div>
</x-modal>

@endsection

@push('scripts')
<script>
    (function () {
        var input   = document.getElementById('konfirmasi_modal_input');
        var btnNext = document.getElementById('btn-lanjut-tanggal');
        var hint    = document.getElementById('konfirmasi_modal_hint');

        if (!input) return;

        // Reset saat modal dibuka ulang
        var modalEl = document.getElementById('konfirmasiTanggalModal');
        if (modalEl) {
            var observer = new MutationObserver(function () {
                if (!modalEl.classList.contains('hidden')) return;
                // Modal ditutup: reset
                input.value = '';
                setUnlocked(false);
            });
            observer.observe(modalEl, { attributes: true, attributeFilter: ['class'] });
        }

        function setUnlocked(ok) {
            if (ok) {
                btnNext.classList.remove('opacity-40', 'pointer-events-none');
                btnNext.removeAttribute('aria-disabled');
                hint.textContent = '✅ Terkonfirmasi! Klik Lanjut untuk mengubah tanggal.';
                hint.className   = 'text-xs text-center mt-1.5 font-medium text-green-600 min-h-[1rem]';
                input.style.borderColor = '#00D4AA';
            } else {
                btnNext.classList.add('opacity-40', 'pointer-events-none');
                btnNext.setAttribute('aria-disabled', 'true');
                var val = input.value.trim();
                if (val.length > 0) {
                    hint.textContent = '❌ Harus persis: KONFIRMASI (huruf kapital semua)';
                    hint.className   = 'text-xs text-center mt-1.5 font-medium text-red-500 min-h-[1rem]';
                    input.style.borderColor = '#EF4444';
                } else {
                    hint.textContent = '';
                    hint.className   = 'text-xs text-center mt-1.5 font-medium text-gray-400 min-h-[1rem]';
                    input.style.borderColor = '';
                }
            }
        }

        input.addEventListener('input', function () {
            setUnlocked(input.value.trim() === 'KONFIRMASI');
        });

        setUnlocked(false);
    })();
</script>
@endpush
