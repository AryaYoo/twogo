@extends('layouts.app')
@section('title', 'Ringkasan Perjalanan - ' . $trip->title)

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Ringkasan Trip 📋</h1>
        <p class="text-sm font-medium opacity-80 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col h-full">
    <div class="flex gap-2 mb-5 bg-white border-[3px] border-[#1A1A2E] rounded-xl p-1 shadow-[2px_2px_0px_#1A1A2E]">
        <button id="tab-summary-btn" onclick="switchSummaryTab('summary')" 
            class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 summary-tab-btn active-tab" data-tab="summary">
            📋 Ringkasan
        </button>
        <button id="tab-docs-btn" onclick="switchSummaryTab('docs')" 
            class="flex-1 py-2 px-3 rounded-lg font-heading font-bold text-sm transition-all duration-200 summary-tab-btn" data-tab="docs">
            📸 Dokumentasi
        </button>
    </div>

    <div id="tab-summary" class="summary-tab-content flex flex-col gap-6">

        <div class="nb-card bg-white p-4">
            <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-dashed border-gray-200 pb-2">Status Itinerary</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium opacity-80">Total Kegiatan</p>
                    <p class="text-2xl font-bold font-heading">{{ $totalActivities }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-80">Selesai</p>
                    <p class="text-2xl font-bold font-heading text-[#00D4AA]">{{ $completedActivities }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-80">Progress</p>
                    <p class="text-2xl font-bold font-heading text-[#FF6B9D]">
                        {{ $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0 }}%
                    </p>
                </div>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-3 mt-4 border-2 border-[#1A1A2E] overflow-hidden">
                <div class="bg-[#00D4AA] h-full" style="width: {{ $totalActivities > 0 ? ($completedActivities / $totalActivities) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="nb-card bg-[#FFE156] p-4">
            <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-[#1A1A2E] pb-2">Laporan Budget</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-white p-3 rounded-lg border-2 border-[#1A1A2E]">
                    <span class="font-bold">Total Budget</span>
                    <span class="font-bold font-heading">Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center bg-white p-3 rounded-lg border-2 border-[#1A1A2E]">
                    <span class="font-bold">Total Pengeluaran Real</span>
                    <span class="font-bold font-heading text-[#FF6B9D]">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center bg-[#1A1A2E] text-white p-3 rounded-lg">
                    <span class="font-bold">Sisa Budget</span>
                    <span class="font-bold font-heading {{ $remainingBudget == 0 && $totalSpent > $totalBudget ? 'text-[#FF6B9D]' : 'text-[#00D4AA]' }}">
                        Rp {{ number_format($totalBudget - $totalSpent, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        @if($trip->status !== 'completed')
        <div class="nb-card bg-white p-4">
            <h3 class="font-heading font-bold text-lg mb-3 border-b-2 border-dashed border-gray-200 pb-2">Aksi Selanjutnya</h3>
            <div class="flex flex-col gap-3">
                <form action="{{ route('trips.complete', $trip) }}" method="POST" onsubmit="return confirm('Tandai perjalanan ini sebagai selesai?');">
                    @csrf
                    <button type="submit" class="w-full nb-btn nb-btn-primary bg-[#00D4AA] text-white border-2 border-[#1A1A2E] justify-between">
                        <span>✅ Tandai Trip Selesai</span>
                        <span>&rarr;</span>
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>

    <div id="tab-docs" class="summary-tab-content hidden flex flex-col gap-4">
        <div class="grid grid-cols-2 gap-3">
        @forelse($activitiesWithPhotos as $act)
            @php
                // Get filename to point to thumbnail path if needed
                $filename = basename($act->photo);
                $dirname = dirname($act->photo);
                $thumbPath = $dirname . '/thumb_' . $filename;
            @endphp
            <div class="nb-card bg-white p-2 cursor-pointer hover:bg-gray-50 transition-colors" onclick="openPhotoModal('{{ asset('storage/' . $act->photo) }}', '{{ $act->title }}')">
                <div class="w-full h-32 bg-gray-200 border-[3px] border-[#1A1A2E] rounded-md mb-2 overflow-hidden">
                    {{-- Load compressed thumbnail --}}
                    <img src="{{ asset('storage/' . $thumbPath) }}" onerror="this.src='{{ asset('storage/' . $act->photo) }}'" class="w-full h-full object-cover" alt="Foto {{ $act->title }}" loading="lazy">
                </div>
                <h4 class="font-bold font-heading text-sm truncate">{{ $act->title }}</h4>
            </div>
        @empty
            <div class="col-span-2 nb-card bg-white p-8 text-center border-dashed">
                <div class="text-4xl mb-2">📸</div>
                <h4 class="font-bold font-heading text-lg mb-1">Belum Ada Dokumentasi</h4>
                <p class="text-sm opacity-80 font-medium">Selesaikan kegiatan itinerary dan unggah foto dokumentasinya.</p>
            </div>
        @endforelse
        </div>
    </div>

</div>

<x-modal id="photoModal" title="Preview Foto">
    <div class="flex flex-col items-center p-2">
        <div class="w-full relative mb-4">
            <img id="modalPhotoImage" src="" class="w-full rounded-md border-[3px] border-[#1A1A2E]" alt="Dokumentasi">
            <div class="absolute bottom-2 left-2 right-2 bg-[#1A1A2E]/80 text-white p-2 rounded text-sm font-bold truncate backdrop-blur-sm shadow-md" id="modalPhotoTitle"></div>
        </div>
        <a id="modalPhotoDownload" href="" download class="w-full nb-btn bg-[#00D4AA] text-white border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:bg-[#00BFA5] py-2 flex items-center justify-center transition-transform hover:translate-y-[-1px]">
            📥 Download Foto High-Res
        </a>
    </div>
</x-modal>

@endsection

@push('scripts')
<style>
    .summary-tab-btn { color: #1A1A2E; background: transparent; }
    .active-tab { background: #1A1A2E; color: #FFE156; }
</style>
<script>
    function switchSummaryTab(tab) {
        document.querySelectorAll('.summary-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.summary-tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active-tab');
    }

    function openPhotoModal(imgUrl, title) {
        document.getElementById('modalPhotoImage').src = imgUrl;
        document.getElementById('modalPhotoDownload').href = imgUrl;
        document.getElementById('modalPhotoTitle').textContent = title;
        openModal('photoModal');
    }
</script>
@endpush
