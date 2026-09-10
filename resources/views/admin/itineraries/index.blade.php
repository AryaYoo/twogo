@extends('layouts.admin', [
    'title' => 'Manajemen Itinerary',
    'pageHeader' => 'Manajemen Itinerary & Moderasi Konten',
    'headerBadge' => $trips->total() . ' Trips'
])

@section('content')
<div class="space-y-6" x-data="{
    selectedTrip: null,
    showModal: false,
    activeTab: 'detail',
    editForm: {},
    activityForm: {},
    editingActivityId: null,
    savingTrip: false,
    savingActivity: false,
    toastMsg: '',
    toastSuccess: true,

    showToast(msg, success = true) {
        this.toastMsg = msg;
        this.toastSuccess = success;
        setTimeout(() => { this.toastMsg = ''; }, 3500);
    },

    saveTripInfo() {
        this.savingTrip = true;
        const tripId = this.selectedTrip.trip.id;
        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
        fetch('/ctrl-twogo-admin/itineraries/' + tripId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(this.editForm),
        })
        .then(r => r.json())
        .then(data => {
            this.savingTrip = false;
            if (data.success) {
                this.selectedTrip.trip = data.trip;
                if (data.days) {
                    this.selectedTrip.days = data.days;
                    const badge = document.querySelector(`[data-trip-days="${tripId}"]`);
                    if (badge) {
                        badge.textContent = data.days.length + ' Hari';
                    }
                }
                this.showToast('✅ ' + data.message, true);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(', ') : 'Terjadi kesalahan.';
                this.showToast('❌ ' + errors, false);
            }
        })
        .catch(() => {
            this.savingTrip = false;
            this.showToast('❌ Gagal menyimpan. Coba lagi.', false);
        });
    },

    startEditActivity(act) {
        this.editingActivityId = act.id;
        this.activityForm = {
            title:          act.title          || '',
            description:    act.description    || '',
            session:        act.session        || '',
            start_time:     act.start_time     ? act.start_time.substring(0,5) : '',
            end_time:       act.end_time       ? act.end_time.substring(0,5)   : '',
            location_name:  act.location_name  || '',
            location_url:   act.location_url   || '',
            estimated_cost: act.estimated_cost || '',
            category:       act.category       || '',
        };
    },

    saveActivity(act) {
        this.savingActivity = true;
        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
        fetch('/ctrl-twogo-admin/itineraries/activities/' + act.id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(this.activityForm),
        })
        .then(r => r.json())
        .then(data => {
            this.savingActivity = false;
            if (data.success) {
                // Update the activity in-place in selectedTrip.days
                for (let day of this.selectedTrip.days) {
                    const idx = day.activities.findIndex(a => a.id === act.id);
                    if (idx !== -1) {
                        day.activities[idx] = data.activity;
                        break;
                    }
                }
                this.editingActivityId = null;
                this.showToast('✅ ' + data.message, true);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(', ') : 'Terjadi kesalahan.';
                this.showToast('❌ ' + errors, false);
            }
        })
        .catch(() => {
            this.savingActivity = false;
            this.showToast('❌ Gagal menyimpan aktivitas. Coba lagi.', false);
        });
    },
}">
    <!-- Filter Tabs & Search -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 flex flex-col lg:flex-row items-center justify-between gap-4">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto w-full lg:w-auto pb-1 lg:pb-0">
            <a href="{{ route('admin.itineraries.index') }}" class="px-4 py-2 rounded-xl font-bold text-xs border-2 border-[#1A1A2E] transition-all whitespace-nowrap {{ !request('filter') ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-slate-100 hover:bg-slate-200' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.itineraries.index', ['filter' => 'published']) }}" class="px-4 py-2 rounded-xl font-bold text-xs border-2 border-[#1A1A2E] transition-all whitespace-nowrap {{ request('filter') === 'published' ? 'bg-[#00D4AA] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-slate-100 hover:bg-slate-200' }}">
                Publik ({{ $stats['published'] }})
            </a>
            <a href="{{ route('admin.itineraries.index', ['filter' => 'draft']) }}" class="px-4 py-2 rounded-xl font-bold text-xs border-2 border-[#1A1A2E] transition-all whitespace-nowrap {{ request('filter') === 'draft' ? 'bg-[#FF6B9D] text-white shadow-[2px_2px_0px_#1A1A2E]' : 'bg-slate-100 hover:bg-slate-200' }}">
                Private/Draft ({{ $stats['draft'] }})
            </a>
            <a href="{{ route('admin.itineraries.index', ['filter' => 'flagged']) }}" class="px-4 py-2 rounded-xl font-bold text-xs border-2 border-[#1A1A2E] transition-all whitespace-nowrap {{ request('filter') === 'flagged' ? 'bg-red-500 text-white shadow-[2px_2px_0px_#1A1A2E]' : 'bg-slate-100 hover:bg-slate-200' }}">
                ⚠️ Flagged / Dilaporkan ({{ $stats['flagged'] }})
            </a>
        </div>

        <!-- Search -->
        <form action="{{ route('admin.itineraries.index') }}" method="GET" class="w-full lg:w-80 flex items-center gap-2">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari judul, destinasi, pembuat..." 
                class="w-full px-4 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl font-bold text-xs"
            >
            <button type="submit" class="px-4 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs">
                Cari
            </button>
        </form>
    </div>

    <!-- Itinerary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($trips as $trip)
            <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl overflow-hidden flex flex-col justify-between hover:translate-y-[-2px] transition-all relative">
                <!-- Status Badges -->
                <div class="p-4 bg-[#FFFBEB] border-b-2 border-[#1A1A2E] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($trip->is_public)
                            <span class="px-2.5 py-0.5 bg-[#00D4AA] border border-[#1A1A2E] rounded-md font-extrabold text-[11px]">🌐 Publik</span>
                        @else
                            <span class="px-2.5 py-0.5 bg-slate-200 border border-[#1A1A2E] rounded-md font-extrabold text-[11px] text-slate-700">🔒 Private</span>
                        @endif

                        @if($trip->is_flagged)
                            <span class="px-2.5 py-0.5 bg-red-500 text-white border border-[#1A1A2E] rounded-md font-extrabold text-[11px]">⚠️ FLAGGED</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-500">ID #{{ $trip->id }}</span>
                </div>

                <!-- Main Body -->
                <div class="p-5 space-y-3 flex-1">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-[#1A1A2E] leading-snug line-clamp-1">{{ $trip->title }}</h3>
                        <p class="text-xs font-bold text-[#4361EE] mt-0.5">📍 {{ $trip->destination }}</p>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="w-6 h-6 rounded-full bg-[#FFE156] border border-[#1A1A2E] flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($trip->creator->name ?? 'U', 0, 1)) }}
                        </span>
                        <span class="truncate">Pembuat: <b>{{ $trip->creator->name ?? 'Unknown' }}</b></span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-[11px] font-bold">
                        <div class="p-2 bg-[#FFFBEB] border border-[#1A1A2E] rounded-lg">
                            <div class="text-slate-400">HARI</div>
                            <div data-trip-days="{{ $trip->id }}" class="text-[#1A1A2E] font-extrabold text-sm">{{ $trip->days_count }} Hari</div>
                        </div>
                        <div class="p-2 bg-[#FFFBEB] border border-[#1A1A2E] rounded-lg">
                            <div class="text-slate-400">KEGIATAN</div>
                            <div class="text-[#1A1A2E] font-extrabold text-sm">{{ $trip->activities_count }}</div>
                        </div>
                        <div class="p-2 bg-[#FFFBEB] border border-[#1A1A2E] rounded-lg">
                            <div class="text-slate-400">BUDGET</div>
                            <div class="text-[#7B2FF7] font-extrabold text-sm">Rp {{ number_format($trip->total_budget / 1000) }}k</div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-4 bg-slate-50 border-t-2 border-[#1A1A2E] flex items-center justify-between gap-2">
                    <button 
                        @click="fetchTripDetail({{ $trip->id }})"
                        class="flex-1 py-2 px-3 bg-[#FFE156] hover:bg-[#ffd829] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-extrabold text-xs cursor-pointer text-center"
                    >
                        🔍 Inspect Detail
                    </button>

                    <!-- Toggle Flag -->
                    <form action="{{ route('admin.itineraries.flag', $trip) }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="py-2 px-3 {{ $trip->is_flagged ? 'bg-emerald-400 hover:bg-emerald-500 text-[#1A1A2E]' : 'bg-red-400 hover:bg-red-500 text-white' }} border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-extrabold text-xs cursor-pointer"
                            title="{{ $trip->is_flagged ? 'Unflag Konten' : 'Flag Konten Melanggar' }}"
                        >
                            {{ $trip->is_flagged ? 'Unflag' : '🚩 Flag' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <form action="{{ route('admin.itineraries.destroy', $trip) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            onclick="return confirm('Hapus itinerary ini secara permanen?')"
                            class="py-2 px-2.5 bg-slate-200 hover:bg-red-200 border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-extrabold text-xs cursor-pointer"
                            title="Hapus Itinerary"
                        >
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 font-bold bg-white border-[3px] border-[#1A1A2E] rounded-2xl shadow-[6px_6px_0px_#1A1A2E]">
                Tidak ada itinerary yang sesuai dengan filter.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-4 bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl">
        {{ $trips->links() }}
    </div>

    <!-- Itinerary Detail Viewer / Editor Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-3xl relative max-h-[92vh] flex flex-col" @click.outside="showModal = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#1A1A2E] shrink-0">
                <div class="flex gap-2">
                    <button
                        @click="activeTab = 'detail'"
                        :class="activeTab === 'detail' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E] border-[#1A1A2E]' : 'bg-slate-100 border-slate-300'"
                        class="px-4 py-1.5 rounded-xl font-extrabold text-xs border-2 transition-all cursor-pointer"
                    >🔍 Detail</button>
                    <button
                        @click="activeTab = 'edit'"
                        :class="activeTab === 'edit' ? 'bg-[#00D4AA] shadow-[2px_2px_0px_#1A1A2E] border-[#1A1A2E]' : 'bg-slate-100 border-slate-300'"
                        class="px-4 py-1.5 rounded-xl font-extrabold text-xs border-2 transition-all cursor-pointer"
                    >✏️ Edit</button>
                </div>
                <button @click="showModal = false" class="text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer shrink-0">✕</button>
            </div>

            <!-- Toast notification -->
            <div x-show="toastMsg" x-transition style="display:none"
                :class="toastSuccess ? 'bg-[#00D4AA] border-[#1A1A2E]' : 'bg-red-400 border-[#1A1A2E]'"
                class="mx-6 mt-4 p-3 border-2 rounded-xl font-bold text-sm text-[#1A1A2E] shrink-0"
                x-text="toastMsg">
            </div>

            <!-- Scrollable Content -->
            <div class="overflow-y-auto flex-1 px-6 py-5">
                <template x-if="selectedTrip">
                    <div class="space-y-6">

                        <!-- ===== TAB: DETAIL ===== -->
                        <div x-show="activeTab === 'detail'" class="space-y-5">
                            <!-- Header -->
                            <div class="pb-4 border-b-2 border-slate-200">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 bg-[#FFE156] border border-[#1A1A2E] text-xs font-bold rounded-md" x-text="selectedTrip.trip?.is_public ? '🌐 Public' : '🔒 Private'"></span>
                                    <span class="text-xs font-bold text-slate-400" x-text="'Kode Invite: ' + selectedTrip.trip?.invite_code"></span>
                                </div>
                                <h2 class="font-heading font-extrabold text-2xl text-[#1A1A2E]" x-text="selectedTrip.trip?.title"></h2>
                                <p class="text-sm font-bold text-[#4361EE] mt-1" x-text="'📍 ' + selectedTrip.trip?.destination"></p>
                            </div>

                            <!-- Creator & Dates -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs font-bold">
                                <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl">
                                    <div class="text-slate-400 uppercase text-[10px]">Pembuat</div>
                                    <div class="text-[#1A1A2E] font-extrabold text-sm" x-text="selectedTrip.trip?.creator?.name"></div>
                                </div>
                                <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl">
                                    <div class="text-slate-400 uppercase text-[10px]">Tanggal</div>
                                    <div class="text-[#1A1A2E] font-extrabold text-xs" x-text="(selectedTrip.trip?.start_date || '') + ' - ' + (selectedTrip.trip?.end_date || '')"></div>
                                </div>
                                <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl col-span-2 sm:col-span-1">
                                    <div class="text-slate-400 uppercase text-[10px]">Total Anggaran</div>
                                    <div class="text-[#7B2FF7] font-extrabold text-sm" x-text="'Rp ' + Number(selectedTrip.trip?.total_budget || 0).toLocaleString('id-ID')"></div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div x-show="selectedTrip.trip?.description" class="p-4 bg-slate-50 border-2 border-[#1A1A2E] rounded-xl text-xs font-bold text-slate-700">
                                <div class="text-[10px] text-slate-400 uppercase mb-1">Deskripsi</div>
                                <p x-text="selectedTrip.trip?.description"></p>
                            </div>

                            <!-- Days & Activities Inspection -->
                            <div>
                                <h4 class="font-heading font-bold text-base text-[#1A1A2E] mb-3">📋 Rincian Hari & Kegiatan</h4>
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                                    <template x-for="day in selectedTrip.days" :key="day.id">
                                        <div class="p-3.5 bg-white border-2 border-[#1A1A2E] rounded-xl space-y-2">
                                            <div class="font-extrabold text-xs text-[#1A1A2E] flex items-center justify-between border-b border-slate-200 pb-1.5">
                                                <span x-text="'Hari ke-' + day.day_number + ' (' + (day.title || 'Jadwal Hari') + ')'"></span>
                                                <span class="text-slate-400" x-text="day.date"></span>
                                            </div>
                                            <div class="space-y-1.5 pl-2">
                                                <template x-for="act in day.activities" :key="act.id">
                                                    <div class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                                        <span class="w-2 h-2 bg-[#4361EE] rounded-full"></span>
                                                        <span x-text="(act.start_time ? act.start_time.substring(0,5) + ' - ' : '') + act.title"></span>
                                                        <span x-show="act.estimated_cost" class="text-[10px] font-extrabold text-emerald-700 ml-auto" x-text="'Rp ' + Number(act.estimated_cost).toLocaleString('id-ID')"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!day.activities || day.activities.length === 0">
                                                    <span class="text-xs text-slate-400 italic">Belum ada kegiatan.</span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- ===== TAB: EDIT ===== -->
                        <div x-show="activeTab === 'edit'" class="space-y-6">

                            <!-- Edit Trip Info -->
                            <div class="bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl p-4">
                                <h4 class="font-heading font-bold text-sm text-[#1A1A2E] mb-4">📝 Edit Info Trip</h4>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Judul Trip *</label>
                                            <input type="text" x-model="editForm.title" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]" placeholder="Judul trip...">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Destinasi *</label>
                                            <input type="text" x-model="editForm.destination" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]" placeholder="Destinasi...">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Tanggal Mulai</label>
                                            <input type="date" x-model="editForm.start_date" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Tanggal Selesai</label>
                                            <input type="date" x-model="editForm.end_date" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Total Budget (Rp)</label>
                                        <input type="number" x-model="editForm.total_budget" min="0" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Deskripsi</label>
                                        <textarea x-model="editForm.description" rows="2" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-bold text-xs focus:outline-none focus:border-[#4361EE]" placeholder="Deskripsi..."></textarea>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <label class="text-[10px] font-extrabold text-slate-500 uppercase">Visibilitas:</label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" x-model="editForm.is_public" class="w-4 h-4 accent-[#00D4AA] cursor-pointer">
                                            <span class="text-xs font-bold text-[#1A1A2E]" x-text="editForm.is_public ? '🌐 Publik' : '🔒 Privat'"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button
                                        @click="saveTripInfo()"
                                        :disabled="savingTrip"
                                        class="px-5 py-2 bg-[#00D4AA] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-extrabold text-xs cursor-pointer hover:translate-y-[-1px] transition-transform disabled:opacity-50"
                                    >
                                        <span x-show="!savingTrip">💾 Simpan Perubahan</span>
                                        <span x-show="savingTrip">⏳ Menyimpan...</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Edit Activities -->
                            <div>
                                <h4 class="font-heading font-bold text-sm text-[#1A1A2E] mb-3">🗓️ Edit Aktivitas per Hari</h4>
                                <div class="space-y-3">
                                    <template x-for="day in selectedTrip.days" :key="day.id">
                                        <div class="border-2 border-[#1A1A2E] rounded-xl overflow-hidden">
                                            <!-- Day Header -->
                                            <div class="bg-[#1A1A2E] text-[#FFE156] px-4 py-2 flex items-center justify-between">
                                                <span class="font-extrabold text-xs" x-text="'Hari ke-' + day.day_number + (day.date ? ' — ' + day.date : '')"></span>
                                                <span class="text-[10px] text-white opacity-60" x-text="(day.activities?.length || 0) + ' kegiatan'"></span>
                                            </div>
                                            <!-- Activities -->
                                            <div class="divide-y divide-slate-100">
                                                <template x-for="act in day.activities" :key="act.id">
                                                    <div class="p-3">
                                                        <!-- Activity Row (collapsed) -->
                                                        <div x-show="editingActivityId !== act.id" class="flex items-center justify-between gap-2">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-bold text-[#1A1A2E] truncate" x-text="(act.start_time ? act.start_time.substring(0,5) + ' ' : '') + act.title"></p>
                                                                <p class="text-[10px] text-slate-400 font-medium" x-text="(act.session || '') + (act.category ? ' · ' + act.category : '') + (act.estimated_cost ? ' · Rp ' + Number(act.estimated_cost).toLocaleString('id-ID') : '')"></p>
                                                            </div>
                                                            <button
                                                                @click="startEditActivity(act)"
                                                                class="shrink-0 px-3 py-1.5 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg font-extrabold text-[10px] cursor-pointer hover:translate-y-[-1px] transition-transform"
                                                            >✏️ Edit</button>
                                                        </div>

                                                        <!-- Activity Inline Edit Form -->
                                                        <div x-show="editingActivityId === act.id" class="space-y-2 mt-1">
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <div class="col-span-2">
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Judul Aktivitas *</label>
                                                                    <input type="text" x-model="activityForm.title" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none focus:border-[#4361EE]">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Sesi</label>
                                                                    <select x-model="activityForm.session" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none">
                                                                        <option value="">—</option>
                                                                        <option value="pagi">Pagi 🌅</option>
                                                                        <option value="siang">Siang ☀️</option>
                                                                        <option value="sore">Sore 🌇</option>
                                                                        <option value="malam">Malam 🌙</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Kategori</label>
                                                                    <select x-model="activityForm.category" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none">
                                                                        <option value="">—</option>
                                                                        <option value="wisata">Wisata 🏖️</option>
                                                                        <option value="kuliner">Kuliner 🍜</option>
                                                                        <option value="transportasi">Transportasi 🚗</option>
                                                                        <option value="akomodasi">Akomodasi 🏨</option>
                                                                        <option value="belanja">Belanja 🛍️</option>
                                                                        <option value="lainnya">Lainnya 📌</option>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Mulai</label>
                                                                    <input type="time" x-model="activityForm.start_time" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Selesai</label>
                                                                    <input type="time" x-model="activityForm.end_time" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none">
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Estimasi Biaya (Rp)</label>
                                                                    <input type="number" x-model="activityForm.estimated_cost" min="0" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none">
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Lokasi</label>
                                                                    <input type="text" x-model="activityForm.location_name" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none" placeholder="Nama lokasi...">
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-0.5">Deskripsi</label>
                                                                    <textarea x-model="activityForm.description" rows="2" class="w-full px-2.5 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs focus:outline-none" placeholder="Deskripsi aktivitas..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="flex gap-2 justify-end pt-1">
                                                                <button @click="editingActivityId = null" class="px-3 py-1.5 bg-slate-200 border-2 border-[#1A1A2E] rounded-lg font-extrabold text-[10px] cursor-pointer">Batal</button>
                                                                <button
                                                                    @click="saveActivity(act)"
                                                                    :disabled="savingActivity"
                                                                    class="px-4 py-1.5 bg-[#00D4AA] border-2 border-[#1A1A2E] shadow-[1px_1px_0px_#1A1A2E] rounded-lg font-extrabold text-[10px] cursor-pointer disabled:opacity-50"
                                                                >
                                                                    <span x-show="!savingActivity">💾 Simpan</span>
                                                                    <span x-show="savingActivity">⏳</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="!day.activities || day.activities.length === 0">
                                                    <div class="p-3 text-xs text-slate-400 italic">Belum ada kegiatan di hari ini.</div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!selectedTrip.days || selectedTrip.days.length === 0">
                                        <div class="text-center py-6 text-slate-400 text-sm font-bold">Trip ini belum memiliki jadwal hari.</div>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fetchTripDetail(tripId) {
        fetch('/ctrl-twogo-admin/itineraries/' + tripId)
            .then(res => res.json())
            .then(data => {
                const el = document.querySelector('[x-data]');
                if (el && el._x_dataStack) {
                    const state = el._x_dataStack[0];
                    state.selectedTrip = data;
                    state.activeTab = 'detail';
                    state.editingActivityId = null;
                    state.toastMsg = '';
                    // Pre-fill edit form from trip data
                    const trip = data.trip;
                    state.editForm = {
                        title:        trip.title        || '',
                        destination:  trip.destination  || '',
                        description:  trip.description  || '',
                        total_budget: trip.total_budget  || 0,
                        start_date:   trip.start_date   ? trip.start_date.substring(0,10) : '',
                        end_date:     trip.end_date     ? trip.end_date.substring(0,10)   : '',
                        is_public:    trip.is_public,
                    };
                    state.showModal = true;
                }
            });
    }

    document.addEventListener('alpine:init', () => {
        // Extend the x-data to add edit-related state & methods
        Alpine.data !== undefined; // noop, just check alpine loaded
    });
</script>
@endpush
@endsection

