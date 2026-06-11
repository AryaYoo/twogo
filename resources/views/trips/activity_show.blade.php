@extends('layouts.app')
@section('title', $activity->title)

@section('header')
<div class="flex items-center gap-2 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-lg font-heading font-bold truncate">Detail Kegiatan</h1>
        <p class="text-xs font-medium opacity-70 truncate">
            {{ $trip->title }} · Hari {{ $activity->day->day_number }}
        </p>
    </div>
</div>
@endsection

@section('content')

{{-- Hero / Photo --}}
@if($activity->photo)
<div class="nb-card p-0 overflow-hidden mb-4">
    <img src="{{ Storage::url($activity->photo) }}" alt="{{ $activity->title }}"
         class="w-full object-cover" style="max-height:240px;">
    <div class="px-4 py-2 bg-[#E1FCEF] border-t-[3px] border-[#1A1A2E] flex items-center gap-2">
        <span class="text-lg">✅</span>
        <span class="text-sm font-bold text-[#00D4AA]">Kegiatan Selesai</span>
    </div>
</div>
@elseif($activity->is_completed)
<div class="nb-card bg-[#E1FCEF] border-[#00D4AA] p-4 flex items-center gap-3 mb-4">
    <span class="text-3xl">✅</span>
    <div>
        <p class="font-bold text-sm">Kegiatan Selesai</p>
        <p class="text-xs opacity-70">Tidak ada foto dokumentasi</p>
    </div>
</div>
@else
<div class="nb-card bg-[#FFF0F5] border-[#FF6B9D] p-4 flex items-center gap-3 mb-4">
    <span class="text-3xl">📋</span>
    <div>
        <p class="font-bold text-sm">Kegiatan Belum Dilakukan</p>
        <p class="text-xs opacity-70">Tandai selesai saat kamu sudah melakukannya</p>
    </div>
</div>
@endif

{{-- Main Info Card --}}
<div class="nb-card bg-white p-4 mb-4">

    {{-- Title + Category --}}
    <div class="flex items-start justify-between gap-2 mb-3">
        <h2 class="font-heading font-bold text-xl leading-tight {{ $activity->is_completed ? 'line-through opacity-60' : '' }}">
            {{ $activity->title }}
        </h2>
        <span class="shrink-0 text-xs font-bold bg-gray-200 px-2 py-1 rounded-full border border-gray-400 capitalize">
            {{ $activity->category }}
        </span>
    </div>

    {{-- Session + Time --}}
    <div class="flex items-center gap-3 mb-3 flex-wrap">
        <span class="text-sm font-bold bg-[#FFE156] px-3 py-1 rounded-full border-2 border-[#1A1A2E]">
            @if($activity->session === 'pagi') 🌅 Pagi
            @elseif($activity->session === 'siang') 🌞 Siang
            @else 🌙 Malam
            @endif
        </span>
        @if($activity->start_time || $activity->end_time)
        <span class="text-sm font-medium text-gray-700">
            🕐
            {{ $activity->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $activity->start_time)->format('H:i') : '' }}
            @if($activity->start_time && $activity->end_time) — @endif
            {{ $activity->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $activity->end_time)->format('H:i') : '' }}
        </span>
        @endif
    </div>

    {{-- Day info --}}
    <div class="text-xs font-medium text-gray-500 mb-3">
        📅 Hari {{ $activity->day->day_number }}
        @if($activity->day->date)
            · {{ \Carbon\Carbon::parse($activity->day->date)->translatedFormat('d F Y') }}
        @endif
    </div>

    {{-- Divider --}}
    <div class="border-t-2 border-dashed border-gray-200 my-3"></div>

    {{-- Budget --}}
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div class="bg-[#FFF3C4] border-2 border-[#1A1A2E] rounded-lg p-3">
            <p class="text-xs font-bold opacity-60 mb-1">Estimasi Biaya</p>
            <p class="font-heading font-bold text-base">
                @if($activity->estimated_cost > 0)
                    Rp {{ number_format($activity->estimated_cost, 0, ',', '.') }}
                @else
                    <span class="opacity-50">—</span>
                @endif
            </p>
        </div>
        @if($activity->is_completed && $activity->actual_cost !== null)
        <div class="bg-[#E1FCEF] border-2 border-[#00D4AA] rounded-lg p-3">
            <p class="text-xs font-bold opacity-60 mb-1">Biaya Real</p>
            <p class="font-heading font-bold text-base text-[#00875A]">
                Rp {{ number_format($activity->actual_cost, 0, ',', '.') }}
            </p>
        </div>
        @endif
    </div>

    {{-- Description --}}
    @if($activity->description)
    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-3 mb-3">
        <p class="text-xs font-bold opacity-50 mb-1 uppercase tracking-wide">Catatan</p>
        <p class="text-sm font-medium leading-relaxed">{{ $activity->description }}</p>
    </div>
    @endif

</div>

{{-- Location & Maps Card --}}
@php
    $isTripParticipant = Auth::check() && ($trip->user_id === Auth::id() || $trip->members()->where('user_id', Auth::id())->exists());
@endphp

@if($activity->location_name || $isTripParticipant)
<div class="nb-card bg-white p-4 mb-4">
    <div class="flex items-center justify-between mb-3 border-b-2 border-dashed border-gray-200 pb-2">
        <h3 class="text-xs font-bold opacity-60 uppercase tracking-wide flex items-center gap-1.5">
            📍 Lokasi & Peta
        </h3>
        @if($activity->location_name)
            <span class="text-[10px] font-bold bg-[#E1FCEF] text-[#00875A] px-2 py-0.5 rounded border border-[#00D4AA]">
                Tersemat
            </span>
        @else
            <span class="text-[10px] font-bold bg-red-50 text-red-600 px-2 py-0.5 rounded border border-red-200">
                Belum Ditentukan
            </span>
        @endif
    </div>

    @if($activity->location_name)
        <div class="mb-4">
            <h4 class="font-heading font-bold text-base text-[#1A1A2E] leading-tight mb-1">
                {{ $activity->location_name }}
            </h4>
            <p class="text-xs text-gray-500 font-medium">
                Sesi {{ ucfirst($activity->session) }} · Estimasi: Rp {{ number_format($activity->estimated_cost, 0, ',', '.') }}
            </p>
        </div>

        @php
            $mapsUrl = $activity->location_url ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($activity->location_name . ' ' . $trip->destination);
        @endphp

        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
           class="w-full nb-btn nb-btn-blue flex items-center justify-center gap-2 py-3 shadow-[3px_3px_0px_#1A1A2E] hover:translate-y-[-2px] hover:shadow-[5px_5px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none transition-all rounded-xl font-bold text-sm"
           onclick="event.stopPropagation()">
            🗺️ Buka di Google Maps
        </a>
        
        <p class="text-center text-[10px] text-gray-400 mt-2.5 font-medium">
            *Membuka petunjuk arah, estimasi waktu tempuh, dan navigasi langsung.
        </p>
    @else
        <div class="py-3 text-center">
            <p class="text-xs font-medium text-gray-500 mb-3 leading-relaxed">
                Lokasi belum ditambahkan untuk kegiatan ini. Tambahkan sekarang agar lebih mudah dinavigasi saat perjalanan!
            </p>
            <button type="button" onclick='openEditActivityModal(@json($activity))'
                    class="nb-btn nb-btn-primary nb-btn-sm inline-flex items-center gap-1.5 shadow-[2px_2px_0px_#1A1A2E]">
                ✏️ Tambah Lokasi
            </button>
        </div>
    @endif
</div>
@endif

{{-- Action Buttons --}}
@if($trip->user_id === Auth::id() || $trip->members()->where('user_id', Auth::id())->exists())
<div class="flex gap-3 mt-2">
    @if(!$activity->is_completed)
    <button type="button"
        onclick="openCompleteActivityModal({{ $activity->id }})"
        class="flex-1 nb-btn bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] text-sm font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-lg py-3">
        ✅ Selesaikan
    </button>
    <button type="button"
        onclick='openEditActivityModal(@json($activity))'
        class="flex-1 nb-btn bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] text-sm font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-lg py-3">
        ✏️ Edit
    </button>
    @else
    <button type="button"
        onclick="openUncheckActivityModal({{ $activity->id }})"
        class="flex-1 nb-btn bg-[#FF6B9D] text-white border-2 border-[#1A1A2E] text-sm font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-lg py-3">
        ↩️ Batalkan Selesai
    </button>
    @endif
    <button type="button"
        onclick="openDeleteActivityModal({{ $activity->id }})"
        class="nb-btn bg-red-100 text-red-600 border-2 border-[#1A1A2E] text-sm font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform rounded-lg py-3 px-4">
        🗑️
    </button>
</div>
@endif

{{-- ===== MODALS (reused from trips.show) ===== --}}

<x-modal id="editActivityModal" title="Edit Kegiatan">
    <form id="editActivityForm" method="POST" action="">
        @csrf @method('PUT')
        <input type="hidden" name="session" id="edit_activity_session" value="{{ $activity->session }}">
        <x-input id="edit_title" name="title" label="Nama Kegiatan" required="true" />
        <div class="grid grid-cols-2 gap-3">
            <x-input id="edit_start_time" type="time" name="start_time" label="Waktu Mulai" />
            <x-input id="edit_end_time" type="time" name="end_time" label="Waktu Selesai" />
        </div>
        <div class="nb-form-group">
            <label class="nb-label">Kategori <span class="text-red-500">*</span></label>
            <select id="edit_category" name="category" class="nb-select" required>
                <option value="wisata">🏖️ Wisata</option>
                <option value="kuliner">🍜 Kuliner</option>
                <option value="transportasi">🚗 Transportasi</option>
                <option value="akomodasi">🏨 Akomodasi</option>
                <option value="belanja">🛍️ Belanja</option>
                <option value="lainnya">✨ Lainnya</option>
            </select>
        </div>
        <x-input id="edit_estimated_cost" type="number" name="estimated_cost" label="Estimasi Biaya (Rp)" placeholder="50000" />
        <x-input id="edit_location_name" name="location_name" label="Nama Lokasi (Opsional)" placeholder="Kuta Beach" />
        <x-input id="edit_location_url" name="location_url" label="Link Google Maps (Opsional)" placeholder="https://maps.google.com/..." />
        <x-input id="edit_description" type="textarea" name="description" label="Catatan Khusus (Opsional)" placeholder="Pesan tempat yang pinggir jendela..." />
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full">Simpan Perubahan</x-button>
        </div>
    </form>
</x-modal>

<x-modal id="completeActivityModal" title="Selesaikan Kegiatan">
    <form id="completeActivityForm" method="POST" action="" enctype="multipart/form-data">
        @csrf
        <x-input type="file" name="photo" label="Foto Dokumentasi (Opsional)" accept="image/*" />
        <x-input type="number" name="actual_cost" label="Budget Real (Rp)" placeholder="Berapa yang dihabiskan?" required="true" />
        @if($trip->members->count() > 1)
        <div class="nb-form-group mt-4 p-3 bg-[#E1FCEF] border-2 border-[#00D4AA] rounded-lg">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="split_bill" value="1" class="w-6 h-6 border-[2px] border-[#1A1A2E] rounded-sm accent-[#00D4AA]">
                <div>
                    <span class="font-bold text-sm block">Bagi Otomatis (Split Bill)</span>
                    <span class="text-xs opacity-80">Dibagi rata dengan {{ $trip->members->count() - 1 }} partner kamu.</span>
                </div>
            </label>
        </div>
        @endif
        <div class="mt-6">
            <x-button type="submit" variant="mint" class="w-full">✅ Selesai &amp; Catat Pengeluaran</x-button>
        </div>
    </form>
</x-modal>

<x-modal id="uncheckActivityModal" title="Batalkan Kegiatan?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">⚠️</div>
        <h3 class="font-heading font-bold text-xl mb-2">Yakin Ingin Membatalkan?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">
            Foto dokumentasi dan catatan pengeluaran real akan dihapus secara permanen.
        </p>
        <form id="uncheckActivityForm" method="POST" action="">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('uncheckActivityModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold rounded-md py-2">Kembali</button>
                <button type="submit" class="flex-1 nb-btn bg-[#FF6B9D] text-white border-2 border-[#1A1A2E] font-bold rounded-md py-2">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal id="deleteActivityModal" title="Hapus Kegiatan?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">🗑️</div>
        <h3 class="font-heading font-bold text-xl mb-2">Hapus Kegiatan Ini?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">Kegiatan yang dihapus tidak dapat dikembalikan lagi.</p>
        <form id="deleteActivityForm" method="POST" action="">
            @csrf @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteActivityModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold rounded-md py-2">Batal</button>
                <button type="submit" class="flex-1 nb-btn bg-red-500 text-white border-2 border-[#1A1A2E] font-bold rounded-md py-2">Ya, Hapus</button>
            </div>
        </form>
    </div>
</x-modal>

@endsection

@push('scripts')
<script>
    function formatTimeForInput(time) {
        if (!time) return '';
        const match = String(time).match(/^(\d{1,2}):(\d{2})/);
        if (!match) return '';
        return match[1].padStart(2, '0') + ':' + match[2];
    }

    function openEditActivityModal(activity) {
        document.getElementById('editActivityForm').action = `/activities/${activity.id}`;
        document.getElementById('edit_title').value = activity.title || '';
        document.getElementById('edit_activity_session').value = activity.session || '';
        document.getElementById('edit_start_time').value = formatTimeForInput(activity.start_time);
        document.getElementById('edit_end_time').value = formatTimeForInput(activity.end_time);
        document.getElementById('edit_category').value = activity.category || '';
        document.getElementById('edit_estimated_cost').value = activity.estimated_cost ?? '';
        document.getElementById('edit_location_name').value = activity.location_name || '';
        document.getElementById('edit_location_url').value = activity.location_url || '';
        document.getElementById('edit_description').value = activity.description || '';
        openModal('editActivityModal');
    }

    function openCompleteActivityModal(activityId) {
        document.getElementById('completeActivityForm').action = `/activities/${activityId}/complete`;
        openModal('completeActivityModal');
    }

    function openUncheckActivityModal(activityId) {
        document.getElementById('uncheckActivityForm').action = `/activities/${activityId}/toggle`;
        openModal('uncheckActivityModal');
    }

    function openDeleteActivityModal(activityId) {
        document.getElementById('deleteActivityForm').action = `/activities/${activityId}`;
        // After delete, go back to trip
        document.getElementById('deleteActivityForm').addEventListener('submit', function() {
            // The controller redirects back, which would be this page, so override
            this.action = `/activities/${activityId}`;
        }, { once: true });
        openModal('deleteActivityModal');
    }
</script>
@endpush
