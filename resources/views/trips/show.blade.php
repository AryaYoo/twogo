@extends('layouts.app')
@section('title', $trip->title)

@section('header')
<div class="flex items-center gap-2 md:gap-3 w-full">
    <a href="{{ route('trips.index') }}" class="w-9 h-9 md:w-10 md:h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform text-sm md:text-base">
        &larr;
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-lg md:text-xl font-heading font-bold truncate">{{ $trip->title }}</h1>
        <p class="text-xs font-medium opacity-80 truncate">
            @if($trip->start_date)
                {{ $trip->start_date->format('d M') }} - {{ $trip->end_date->format('d M Y') }}
            @else
                <span class="text-[#FF6B9D]">💖 Wishlist — tanggal belum diisi</span>
            @endif
        </p>
    </div>
    @if($trip->user_id === Auth::id())
    <div class="relative shrink-0">
        <button id="trip-actions-btn" type="button" aria-haspopup="true" aria-expanded="false" class="w-9 h-9 md:w-10 md:h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-2px] transition-transform text-sm md:text-base">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="#1A1A2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div id="trip-actions-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border-[3px] border-[#1A1A2E] rounded-lg shadow-[2px_2px_0px_#1A1A2E] z-50 overflow-hidden">
            <a href="{{ route('trips.edit', $trip) }}" class="block px-3 py-2 hover:bg-[#FFE156] text-sm font-medium">✏️ Edit Perjalanan</a>
            <a href="{{ route('invitations.show', $trip) }}" class="block px-3 py-2 hover:bg-[#FFE156] text-sm font-medium">🤝 Kelola Undangan</a>
            <a href="{{ route('trips.summary', $trip) }}" class="block px-3 py-2 hover:bg-[#FFE156] text-sm font-medium">📋 Ringkasan Perjalanan</a>
        </div>
    </div>
    @endif
</div>
@endsection

@section('content')

{{-- ===== WISHLIST MODE: tanggal belum diisi ===== --}}
@if(!$trip->start_date)
<div class="flex flex-col items-center text-center py-10 gap-5">
    <div class="text-6xl">💖</div>
    <div>
        <h2 class="font-heading font-bold text-xl mb-2">Trip ini masih di Wishlist!</h2>
        <p class="text-sm font-medium opacity-60 leading-relaxed">
            Isi tanggal berangkat dan pulang untuk mulai menyusun itinerary.
        </p>
    </div>
    @if($trip->user_id === Auth::id())
    <a href="{{ route('trips.edit', $trip) }}"
       class="nb-btn nb-btn-primary w-full max-w-xs justify-center">
        📅 Isi Tanggal Sekarang
    </a>
    @endif
</div>

@else
{{-- ===== TRIP MODE: ada tanggal, tampilkan timeline ===== --}}

@push('floating-bottom')
<div class="fixed left-1/2 bottom-[71px] z-50 w-full max-w-[480px] -translate-x-1/2 px-1 pointer-events-none">
    <div class="w-full pointer-events-auto relative">
        <button id="day-prev" class="absolute left-1 top-1/2 -translate-y-1/2 bg-white border-[3px] border-[#1A1A2E] rounded-full w-6 h-6 flex items-center justify-center z-60 hidden">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 18l-6-6 6-6" stroke="#1A1A2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button id="day-next" class="absolute right-1 top-1/2 -translate-y-1/2 bg-white border-[3px] border-[#1A1A2E] rounded-full w-6 h-6 flex items-center justify-center z-60 hidden">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 6l6 6-6 6" stroke="#1A1A2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="relative w-full">
            <div class="absolute inset-0 left-0 right-0 bottom-0 top-0 pointer-events-none">
                <div class="w-full h-full bg-[#FFFBEB]"></div>
            </div>
            <div id="dayTabsContainer" class="flex w-full overflow-x-auto no-scrollbar gap-3 pb-2 pt-2 justify-center">
                @foreach($trip->days as $day)
                    <a href="#hari-{{ $day->day_number }}" class="shrink-0 day-tab" data-day="{{ $day->day_number }}">
                        <div class="nb-card day-card-sm bg-white flex flex-col items-center justify-center gap-1 min-w-[56px] transition-colors hover:bg-[#FFE156]">
                            <span class="text-xs font-bold opacity-70">Hari</span>
                            <span class="text-xl font-heading font-bold">{{ $day->day_number }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endpush

{{-- Timeline --}}
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
                                        @if($act->is_completed)
                                            <button type="button" onclick="openUncheckActivityModal({{ $act->id }})" class="shrink-0 mt-1 w-6 h-6 border-[3px] border-[#1A1A2E] rounded-sm flex items-center justify-center bg-[#00D4AA] hover:bg-[#00BFA5] transition-colors">
                                                <span class="text-white text-xs font-bold">✓</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="openCompleteActivityModal({{ $act->id }})" class="shrink-0 mt-1 w-6 h-6 border-[3px] border-[#1A1A2E] rounded-sm flex items-center justify-center bg-white hover:bg-gray-100 transition-colors"></button>
                                        @endif
                                        
                                        <div class="flex-1 pr-14 relative">
                                            <div class="flex flex-col">
                                                <div class="flex items-start mb-1">
                                                    <h4 class="font-bold font-heading text-lg {{ $act->is_completed ? 'line-through' : '' }}">{{ $act->title }}</h4>
                                                </div>
                                                
                                                @if($act->start_time || $act->end_time)
                                                <div class="text-xs text-gray-600 mb-2">
                                                    {{ $act->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $act->start_time)->format('H:i') : '' }}
                                                    @if($act->start_time && $act->end_time) — @endif
                                                    {{ $act->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $act->end_time)->format('H:i') : '' }}
                                                </div>
                                                @endif
                                                
                                                <div class="flex items-center gap-2 mb-2 flex-wrap">
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
                                                    <a href="{{ $act->location_url ?? '#' }}" target="_blank" class="text-sm text-[#4361EE] hover:underline font-medium inline-flex items-center gap-1 mb-2">
                                                        📍 {{ $act->location_name }}
                                                    </a>
                                                @endif
                                                
                                                @if($act->description)
                                                    <p class="text-sm opacity-80">{{ $act->description }}</p>
                                                @endif
                                            </div>

                                            <div class="absolute right-0 top-0 flex flex-col items-end gap-2">
                                                <button type="button" onclick="openDeleteActivityModal({{ $act->id }})" class="w-7 h-7 flex items-center justify-center rounded-sm bg-red-500 text-white border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform text-sm">&times;</button>
                                                <button type="button" onclick='openEditActivityModal(@json($act))' class="w-10 h-10 flex items-center justify-center rounded-sm bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform text-lg">✏️</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="border-[2px] border-dashed border-gray-400 rounded-lg p-3 text-center">
                                    <span class="text-xs font-bold opacity-50">Belum ada kegiatan</span>
                                </div>
                            @endforelse
                            
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

@if($trip->status !== 'completed')
<div class="mt-6">
    <form action="{{ route('trips.complete', $trip) }}" method="POST" onsubmit="return confirm('Tandai perjalanan ini sebagai selesai?');">
        @csrf
        <button type="submit" class="w-full nb-btn nb-btn-primary bg-[#FFE156] text-[#1A1A2E] border-[3px] border-[#1A1A2E] rounded-xl py-3 font-heading font-bold hover:translate-y-[-2px] transition-transform">
            ✅ Selesaikan Perjalanan
        </button>
    </form>
</div>
@else
<div class="mt-6 p-4 bg-[#E1FCEF] border-[3px] border-[#00D4AA] rounded-xl text-[#1A1A2E] font-bold">
    Perjalanan ini sudah ditandai sebagai selesai. Kamu dapat melihat budget-nya di halaman Budget Tracker.
</div>
@endif

<div class="h-32"></div>

@endif {{-- end trip mode --}}

{{-- ===== MODALS ===== --}}
<x-modal id="addActivityModal" title="Tambah Kegiatan">
    <form id="addActivityForm" method="POST" action="">
        @csrf
        <input type="hidden" name="session" id="activity_session" value="pagi">
        <x-input name="title" label="Nama Kegiatan" placeholder="Makan siang di..." required="true" />
        <div class="grid grid-cols-2 gap-3">
            <x-input type="time" name="start_time" label="Waktu Mulai" />
            <x-input type="time" name="end_time" label="Waktu Selesai" />
        </div>
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
        <x-input type="number" name="estimated_cost" label="Estimasi Biaya (Rp)" placeholder="50000" />
        <x-input name="location_name" label="Nama Lokasi (Opsional)" placeholder="Kuta Beach" />
        <x-input name="location_url" label="Link Google Maps (Opsional)" placeholder="https://maps.google.com/..." />
        <x-input type="textarea" name="description" label="Catatan Khusus (Opsional)" placeholder="Pesan tempat yang pinggir jendela..." />
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full">Simpan Kegiatan</x-button>
        </div>
    </form>
</x-modal>

<x-modal id="editActivityModal" title="Edit Kegiatan">
    <form id="editActivityForm" method="POST" action="">
        @csrf @method('PUT')
        <input type="hidden" name="session" id="edit_activity_session" value="pagi">
        <x-input id="edit_title" name="title" label="Nama Kegiatan" placeholder="Makan siang di..." required="true" />
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
                <input type="checkbox" name="split_bill" value="1" class="w-6 h-6 border-[2px] border-[#1A1A2E] rounded-sm checked:bg-[#00D4AA] accent-[#00D4AA]">
                <div>
                    <span class="font-bold text-sm block">Bagi Otomatis (Split Bill)</span>
                    <span class="text-xs opacity-80">Budget real akan dibagi rata dengan {{ $trip->members->count() - 1 }} partner kamu.</span>
                </div>
            </label>
        </div>
        @else
        <div class="mt-4 p-3 bg-gray-100 border-2 border-gray-300 rounded-lg">
            <span class="text-xs font-medium text-gray-600">Trip ini kamu lakukan sendiri, budget akan langsung dicatat sepenuhnya ke pengeluaranmu.</span>
        </div>
        @endif
        
        <div class="mt-6">
            <x-button type="submit" variant="mint" class="w-full text-lg">✅ Selesai & Catat Pengeluaran</x-button>
        </div>
    </form>
</x-modal>

<x-modal id="uncheckActivityModal" title="Batalkan Kegiatan?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">⚠️</div>
        <h3 class="font-heading font-bold text-xl mb-2 text-[#1A1A2E]">Yakin Ingin Membatalkan?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">
            Membatalkan kegiatan ini akan menghapus <strong class="text-[#FF6B9D]">foto dokumentasi</strong> dan <strong class="text-[#FF6B9D]">catatan pengeluaran real</strong> yang telah tersimpan secara permanen.
        </p>
        
        <form id="uncheckActivityForm" method="POST" action="">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('uncheckActivityModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] hover:bg-gray-100 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                    Kembali
                </button>
                <button type="submit" class="flex-1 nb-btn bg-[#FF6B9D] text-white border-2 border-[#1A1A2E] hover:bg-[#E85D8B] font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                    Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal id="deleteActivityModal" title="Hapus Kegiatan?">
    <div class="text-center p-2">
        <div class="text-5xl mb-4">🗑️</div>
        <h3 class="font-heading font-bold text-xl mb-2 text-[#1A1A2E]">Hapus Kegiatan Ini?</h3>
        <p class="text-sm font-medium text-gray-600 mb-6 leading-relaxed">
            Kegiatan yang dihapus tidak dapat dikembalikan lagi.
        </p>
        
        <form id="deleteActivityForm" method="POST" action="">
            @csrf @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteActivityModal')" class="flex-1 nb-btn bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] hover:bg-gray-100 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                    Batal
                </button>
                <button type="submit" class="flex-1 nb-btn bg-red-500 text-white border-2 border-[#1A1A2E] hover:bg-red-600 font-bold transition-transform hover:translate-y-[-1px] shadow-[2px_2px_0px_#1A1A2E] rounded-md py-2">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</x-modal>

@endsection

@push('scripts')
<script>
    function openAddActivityModal(dayId, session) {
        document.getElementById('addActivityForm').action = `/trips/days/${dayId}/activities`;
        document.getElementById('activity_session').value = session;
        openModal('addActivityModal');
    }

    function openEditActivityModal(activity) {
        document.getElementById('editActivityForm').action = `/activities/${activity.id}`;
        document.getElementById('edit_title').value = activity.title || '';
        document.getElementById('edit_activity_session').value = activity.session || '';
        document.getElementById('edit_start_time').value = activity.start_time || '';
        document.getElementById('edit_end_time').value = activity.end_time || '';
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
        openModal('deleteActivityModal');
    }

    // Trip actions dropdown toggle
    document.addEventListener('click', function(e) {
        var btn = document.getElementById('trip-actions-btn');
        var dropdown = document.getElementById('trip-actions-dropdown');
        if (!btn || !dropdown) return;
        if (btn.contains(e.target)) {
            dropdown.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', !dropdown.classList.contains('hidden'));
            return;
        }
        if (!dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    });

    // Day tabs pagination
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('dayTabsContainer');
        var prevBtn = document.getElementById('day-prev');
        var nextBtn = document.getElementById('day-next');
        if (!container) return;

        function updateControls() {
            var isOverflow = container.scrollWidth > container.clientWidth + 1;
            if (isOverflow) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                container.classList.remove('justify-center');
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                container.classList.add('justify-center');
            }
            return isOverflow;
        }

        function centerElement(el, smooth = true) {
            if (!el) return;
            var left = el.offsetLeft + el.offsetWidth / 2 - container.clientWidth / 2;
            container.scrollTo({ left: left, behavior: smooth ? 'smooth' : 'auto' });
        }

        var target = null;
        if (location.hash) {
            var hash = location.hash.replace('#hari-', '');
            target = container.querySelector('[data-day="' + hash + '"]');
        }
        if (!target) target = container.querySelector('.day-tab');
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                var overflowing = updateControls();
                if (overflowing) centerElement(target, false);
            });
        });

        container.querySelectorAll('.day-tab').forEach(function(a) {
            a.addEventListener('click', function() {
                setTimeout(function() { centerElement(a, true); }, 0);
            });
        });

        prevBtn.addEventListener('click', function() {
            container.scrollBy({ left: -Math.round(container.clientWidth * 0.6), behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', function() {
            container.scrollBy({ left: Math.round(container.clientWidth * 0.6), behavior: 'smooth' });
        });

        window.addEventListener('resize', function() { updateControls(); });
        container.addEventListener('scroll', function() { updateControls(); });
        updateControls();
    });
</script>
@endpush
