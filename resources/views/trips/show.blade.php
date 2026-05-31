@extends('layouts.app')
@section('title', $trip->title)

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.index') }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">{{ $trip->title }}</h1>
        <p class="text-xs font-medium opacity-80 truncate">{{ $trip->start_date->format('d M') }} - {{ $trip->end_date->format('d M Y') }}</p>
    </div>
    @if($trip->user_id === Auth::id())
    <a href="{{ route('trips.edit', $trip) }}" class="w-10 h-10 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        ✏️
    </a>
    @endif
</div>
@endsection

@section('content')
<!-- Horizontal Day Tabs -->
<div class="flex overflow-x-auto no-scrollbar gap-3 mb-6 pb-2 -mx-4 px-4 sticky top-[76px] bg-[#FFFBEB] z-20 py-2">
    @foreach($trip->days as $day)
        <a href="#hari-{{ $day->day_number }}" class="shrink-0">
            <div class="nb-card px-4 py-2 bg-white flex flex-col items-center justify-center min-w-[70px] transition-colors hover:bg-[#FFE156]">
                <span class="text-xs font-bold opacity-70">Hari</span>
                <span class="text-xl font-heading font-bold">{{ $day->day_number }}</span>
            </div>
        </a>
    @endforeach
</div>

<!-- Timeline -->
<div class="flex flex-col gap-8">
    @foreach($trip->days as $day)
        <div id="hari-{{ $day->day_number }}" class="scroll-mt-32">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-1 flex-1 bg-[#1A1A2E]"></div>
                <h2 class="font-heading font-bold text-lg bg-[#FFE156] px-4 py-1 border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E]">
                    Hari {{ $day->day_number }} • {{ Carbon\Carbon::parse($day->date)->translatedFormat('d M y') }}
                </h2>
                <div class="h-1 flex-1 bg-[#1A1A2E]"></div>
            </div>

            <div class="flex flex-col gap-6 pl-2 border-l-[3px] border-[#1A1A2E] ml-4 py-2">
                
                @foreach(['pagi' => '🌅 PAGI', 'siang' => '🌞 SIANG', 'malam' => '🌙 MALAM'] as $session => $label)
                    <div class="relative">
                        <!-- Session Marker -->
                        <div class="absolute -left-[20px] top-0 w-8 h-8 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center text-sm shadow-[2px_2px_0px_#1A1A2E] z-10">
                            {{ $session === 'pagi' ? '🌅' : ($session === 'siang' ? '🌞' : '🌙') }}
                        </div>
                        
                        <div class="ml-8 mb-3">
                            <h3 class="font-heading font-bold text-sm">{{ $label }}</h3>
                        </div>
                        
                        <div class="ml-8 flex flex-col gap-3 mb-6">
                            @php
                                $activities = $day->activities->where('session', $session);
                            @endphp
                            
                            @forelse($activities as $act)
                                <div class="nb-card {{ $act->is_completed ? 'bg-gray-100 opacity-70' : 'bg-white' }} p-3 relative group">
                                    <div class="flex gap-3">
                                        <!-- Checkbox form -->
                                        <form action="{{ route('activities.toggle', $act) }}" method="POST" class="shrink-0 mt-1">
                                            @csrf
                                            <button type="submit" class="w-6 h-6 border-[3px] border-[#1A1A2E] rounded-sm flex items-center justify-center {{ $act->is_completed ? 'bg-[#00D4AA]' : 'bg-white' }}">
                                                @if($act->is_completed) <span class="text-white text-xs font-bold">✓</span> @endif
                                            </button>
                                        </form>
                                        
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-bold font-heading text-lg {{ $act->is_completed ? 'line-through' : '' }}">{{ $act->title }}</h4>
                                                
                                                <form action="{{ route('activities.destroy', $act) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-500 font-bold ml-2 p-1">&times;</button>
                                                </form>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 mt-1 mb-2">
                                                <span class="text-xs font-bold bg-gray-200 px-2 py-0.5 rounded-full border border-gray-400">
                                                    {{ $act->category }}
                                                </span>
                                                @if($act->estimated_cost > 0)
                                                <span class="text-xs font-bold bg-[#FFE156] px-2 py-0.5 rounded-full border border-[#1A1A2E]">
                                                    Rp {{ number_format($act->estimated_cost, 0, ',', '.') }}
                                                </span>
                                                @endif
                                            </div>
                                            
                                            @if($act->location_name)
                                                <a href="{{ $act->location_url ?? '#' }}" target="_blank" class="text-sm text-[#4361EE] hover:underline font-medium inline-flex items-center gap-1">
                                                    📍 {{ $act->location_name }}
                                                </a>
                                            @endif
                                            
                                            @if($act->description)
                                                <p class="text-sm mt-2 opacity-80">{{ $act->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="border-[2px] border-dashed border-gray-400 rounded-lg p-3 text-center">
                                    <span class="text-xs font-bold opacity-50">Belum ada kegiatan</span>
                                </div>
                            @endforelse
                            
                            <!-- Add button for session -->
                            <button onclick="openAddActivityModal('{{ $day->id }}', '{{ $session }}')" class="nb-btn nb-btn-ghost nb-btn-sm border-[2px] border-dashed border-[#1A1A2E] hover:bg-white text-xs w-full mt-1 justify-center gap-2">
                                <span>+</span> Tambah Kegiatan {{ ucfirst($session) }}
                            </button>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Tambah Kegiatan -->
<x-modal id="addActivityModal" title="Tambah Kegiatan">
    <form id="addActivityForm" method="POST" action="">
        @csrf
        
        <input type="hidden" name="session" id="activity_session" value="pagi">
        
        <x-input 
            name="title" 
            label="Nama Kegiatan" 
            placeholder="Makan siang di..." 
            required="true"
        />
        
        <div class="nb-form-group">
            <label class="nb-label">Kategori <span class="text-red-500">*</span></label>
            <select name="category" class="nb-select" required>
                <option value="wisata">🏖️ Wisata</option>
                <option value="kuliner">🍜 Kuliner</option>
                <option value="transportasi">🚗 Transportasi</option>
                <option value="akomodasi">🏨 Akomodasi</option>
                <option value="belanja">🛍️ Belanja</option>
                <option value="lainnya">✨ Lainnya</option>
            </select>
        </div>
        
        <x-input 
            type="number"
            name="estimated_cost" 
            label="Estimasi Biaya (Rp)" 
            placeholder="50000" 
        />
        
        <x-input 
            name="location_name" 
            label="Nama Lokasi (Opsional)" 
            placeholder="Kuta Beach" 
        />
        
        <x-input 
            name="location_url" 
            label="Link Google Maps (Opsional)" 
            placeholder="https://maps.google.com/..." 
        />
        
        <x-input 
            type="textarea"
            name="description" 
            label="Catatan Khusus (Opsional)" 
            placeholder="Pesan tempat yang pinggir jendela..." 
        />
        
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full">Simpan Kegiatan</x-button>
        </div>
    </form>
</x-modal>

@endsection

@push('scripts')
<script>
    function openAddActivityModal(dayId, session) {
        document.getElementById('addActivityForm').action = `/trips/days/${dayId}/activities`;
        document.getElementById('activity_session').value = session;
        openModal('addActivityModal');
    }
</script>
@endpush
