@extends('layouts.app')
@section('title', 'Ubah Tanggal Perjalanan')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.edit', $trip) }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform cursor-pointer">
        &larr;
    </a>
    <div>
        <h1 class="text-xl font-heading font-bold">Ubah Tanggal 📅</h1>
        <p class="text-xs font-medium opacity-70 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')

{{-- Warning banner --}}
<div class="nb-card bg-[#FFF4E5] border-[#FFB830] p-4 flex gap-3 items-start mb-5">
    <div class="text-2xl shrink-0">⚠️</div>
    <div>
        <h2 class="font-heading font-bold text-base text-[#1A1A2E] mb-1">Perhatian!</h2>
        <p class="text-sm font-medium text-gray-700 leading-relaxed">
            Mengubah tanggal akan <strong>menggeser jadwal semua hari</strong> sesuai tanggal baru.
            Aktivitas tetap tersimpan, namun hari yang melebihi durasi baru akan <strong class="text-red-600">dihapus permanen</strong>.
        </p>
    </div>
</div>

{{-- Tanggal saat ini --}}
<div class="nb-card bg-white p-4 mb-4">
    <p class="text-xs font-bold opacity-50 uppercase tracking-wide mb-2">Tanggal Saat Ini</p>
    <div class="flex items-center gap-3">
        <span class="font-heading font-bold text-[#4361EE]">
            {{ $trip->start_date->translatedFormat('d M Y') }}
        </span>
        <span class="text-gray-400 font-bold">→</span>
        <span class="font-heading font-bold text-[#4361EE]">
            {{ $trip->end_date->translatedFormat('d M Y') }}
        </span>
        <span class="ml-auto text-xs font-bold bg-[#FFE156] px-2 py-0.5 border border-[#1A1A2E] rounded-full">
            {{ $trip->start_date->diffInDays($trip->end_date) + 1 }} hari
        </span>
    </div>
</div>

{{-- Form ubah tanggal --}}
<x-card>
    <form action="{{ route('trips.update', $trip) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Field tersembunyi agar controller tahu ini update info biasa sekaligus tanggal --}}
        <input type="hidden" name="title"        value="{{ $trip->title }}">
        <input type="hidden" name="destination"  value="{{ $trip->destination }}">
        <input type="hidden" name="total_budget" value="{{ $trip->total_budget }}">
        <input type="hidden" name="description"  value="{{ $trip->description }}">

        <p class="text-xs font-bold opacity-50 uppercase tracking-wide mb-3">Tanggal Baru</p>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="nb-form-group">
                <label class="nb-label" for="start_date">✈️ Berangkat</label>
                <input
                    id="start_date"
                    type="date"
                    name="start_date"
                    value="{{ $trip->start_date->format('Y-m-d') }}"
                    required
                    class="nb-input"
                />
                @error('start_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="nb-form-group">
                <label class="nb-label" for="end_date">🏠 Pulang</label>
                <input
                    id="end_date"
                    type="date"
                    name="end_date"
                    value="{{ $trip->end_date->format('Y-m-d') }}"
                    required
                    class="nb-input"
                />
                @error('end_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Durasi preview --}}
        <div id="duration-preview" class="mb-5 p-3 bg-[#E1FCEF] border-[2px] border-[#00D4AA] rounded-lg hidden">
            <p class="text-sm font-bold text-[#1A1A2E]">
                🗓️ Durasi baru: <span id="duration-text" class="text-[#00B89C]"></span>
            </p>
        </div>

        <x-button type="submit" variant="primary" class="w-full text-base">
            💾 Simpan Tanggal Baru
        </x-button>
    </form>
</x-card>

@endsection

@push('scripts')
<script>
    (function () {
        var startEl   = document.getElementById('start_date');
        var endEl     = document.getElementById('end_date');
        var preview   = document.getElementById('duration-preview');
        var durationT = document.getElementById('duration-text');

        function updatePreview() {
            var s = startEl.value, e = endEl.value;
            if (!s || !e) { preview.classList.add('hidden'); return; }
            var start = new Date(s), end = new Date(e);
            if (end < start) { preview.classList.add('hidden'); return; }
            var days = Math.round((end - start) / 86400000) + 1;
            durationT.textContent = days + ' hari';
            preview.classList.remove('hidden');
        }

        startEl.addEventListener('change', updatePreview);
        endEl.addEventListener('change', updatePreview);
        updatePreview();
    })();
</script>
@endpush
