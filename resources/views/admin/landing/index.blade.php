@extends('layouts.admin', [
    'title' => 'Landing Page CMS',
    'pageHeader' => 'Kelola Konten & Tampilan Landing Page',
    'headerBadge' => 'Real-time CMS'
])

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'settings', editFeature: null, showFeatureModal: false, editStat: null, showStatModal: false, editTesti: null, showTestiModal: false }">
    
    <!-- Sub-navigation Tabs -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-2 flex items-center gap-2 overflow-x-auto">
        <button 
            @click="activeTab = 'settings'" 
            :class="activeTab === 'settings' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>📝 Pengaturan Utama & Hero</span>
        </button>

        <button 
            @click="activeTab = 'features'" 
            :class="activeTab === 'features' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>🌴 Fitur Utama ({{ $features->count() }})</span>
        </button>

        <button 
            @click="activeTab = 'stats'" 
            :class="activeTab === 'stats' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>🚀 Angka Pencapaian ({{ $stats->count() }})</span>
        </button>

        <button 
            @click="activeTab = 'testimonials'" 
            :class="activeTab === 'testimonials' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>💬 Testimoni ({{ $testimonials->count() }})</span>
        </button>
    </div>

    <!-- TAB 1: PENGATURAN UTAMA (HERO, MARQUEE, CTA, FOOTER) -->
    <div x-show="activeTab === 'settings'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Pengaturan Teks Utama Landing Page</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1">Ubah teks headline, deskripsi, marquee bar, dan CTA secara real-time.</p>
                </div>
                <a href="{{ route('landing') }}" target="_blank" class="px-3.5 py-1.5 bg-[#00D4AA] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs">
                    👁️ Lihat Live Landing Page
                </a>
            </div>

            <form action="{{ route('admin.landing.settings') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section Hero -->
                <div class="p-5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl space-y-4">
                    <h3 class="font-heading font-bold text-base text-[#1A1A2E] border-b border-slate-300 pb-2">1. Section Hero</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Badge Top Hero</label>
                            <input type="text" name="settings[hero_badge]" value="{{ $settings['hero_badge'] ?? '✨ Aplikasi Itinerary #1 buat Berdua' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Headline Utama (Hero Title)</label>
                            <input type="text" name="settings[hero_title]" value="{{ $settings['hero_title'] ?? 'Rencana Seru, Bareng-Bareng! 🎒' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Sub-Deskripsi Hero</label>
                        <textarea name="settings[hero_subtitle]" rows="2" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">{{ $settings['hero_subtitle'] ?? 'Aplikasi perencanaan perjalanan yang bikin liburanmu makin asyik, rapi, dan terorganisir tanpa ribet adu argumen budget.' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Teks Tombol Utama (Primary CTA)</label>
                            <input type="text" name="settings[hero_btn_primary]" value="{{ $settings['hero_btn_primary'] ?? 'Mulai Sekarang 🔥' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Teks Tombol Sekunder (Secondary CTA)</label>
                            <input type="text" name="settings[hero_btn_secondary]" value="{{ $settings['hero_btn_secondary'] ?? 'Sudah Punya Akun' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                    </div>
                </div>

                <!-- Section Marquee -->
                <div class="p-5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl space-y-3">
                    <h3 class="font-heading font-bold text-base text-[#1A1A2E] border-b border-slate-300 pb-2">2. Marquee Ticker Bar (Daftar Destinasi)</h3>
                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Teks Destinasi Berjalan (Pisahkan dengan tanda •)</label>
                        <input type="text" name="settings[marquee_destinations]" value="{{ $settings['marquee_destinations'] ?? 'BALI • JOGJA • LOMBOK • RAJA AMPAT • BANDUNG • LABUAN BAJO • MALANG • SURABAYA • UBUD • FLORES' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-mono font-bold text-[#4361EE]">
                    </div>
                </div>

                <!-- Section CTA & Footer -->
                <div class="p-5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl space-y-4">
                    <h3 class="font-heading font-bold text-base text-[#1A1A2E] border-b border-slate-300 pb-2">3. CTA Akhir & Footer</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Badge CTA Akhir</label>
                            <input type="text" name="settings[cta_badge]" value="{{ $settings['cta_badge'] ?? 'Tunggu Apa Lagi? 🎒' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Judul CTA Akhir</label>
                            <input type="text" name="settings[cta_title]" value="{{ $settings['cta_title'] ?? 'Siap untuk Liburan Berikutnya?' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Teks Tombol CTA Akhir</label>
                            <input type="text" name="settings[cta_btn]" value="{{ $settings['cta_btn'] ?? 'Buat Trip Sekarang 🚀' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Email Kontak Footer</label>
                            <input type="email" name="settings[footer_email]" value="{{ $settings['footer_email'] ?? 'adventuretwogo@gmail.com' }}" class="w-full px-3 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-sm text-[#1A1A2E] cursor-pointer">
                        💾 Simpan Pengaturan Landing Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: FITUR UTAMA -->
    <div x-show="activeTab === 'features'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Manajemen Fitur Utama</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1">Kelola kartu fitur yang tampil di landing page.</p>
                </div>
                <button @click="editFeature = null; showFeatureModal = true" class="px-4 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">
                    + Tambah Fitur
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($features as $f)
                    <div class="p-5 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl flex flex-col justify-between" style="background-color: {{ $f->bg_color }}; color: {{ $f->text_color }};">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-3xl p-2 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E] inline-block">{{ $f->icon }}</span>
                                <span class="text-xs font-extrabold px-2.5 py-0.5 border border-[#1A1A2E] rounded-md bg-white text-[#1A1A2E]">Order #{{ $f->order }}</span>
                            </div>
                            <h3 class="font-heading font-extrabold text-xl">{{ $f->title }}</h3>
                            <p class="text-xs font-bold opacity-90 leading-relaxed">{{ $f->description }}</p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-[#1A1A2E]/20 flex items-center justify-end gap-2">
                            <button @click="editFeature = {{ json_encode($f) }}; showFeatureModal = true" class="px-3 py-1.5 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.landing.features.destroy', $f) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus fitur ini?')" class="px-3 py-1.5 bg-red-500 text-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TAB 3: ANGKA PENCAPAIAN (STATS) -->
    <div x-show="activeTab === 'stats'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Manajemen Angka Pencapaian</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1">Kelola metrik & statistik pencapaian aplikasi.</p>
                </div>
                <button @click="editStat = null; showStatModal = true" class="px-4 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">
                    + Tambah Stat
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($stats as $st)
                    <div class="p-5 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl text-center space-y-3" style="background-color: {{ $st->bg_color }}; color: {{ $st->text_color }};">
                        <div class="font-heading font-extrabold text-3xl">{{ $st->number }}</div>
                        <div class="font-extrabold text-xs uppercase tracking-wider">{{ $st->label }}</div>

                        <div class="pt-3 border-t border-[#1A1A2E]/20 flex items-center justify-center gap-2">
                            <button @click="editStat = {{ json_encode($st) }}; showStatModal = true" class="px-3 py-1 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.landing.stats.destroy', $st) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus stat ini?')" class="px-3 py-1 bg-red-500 text-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TAB 4: TESTIMONI PENGGUNA -->
    <div x-show="activeTab === 'testimonials'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Manajemen Testimoni Pengguna</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1">Kelola kutipan ulasan dari para pelancong.</p>
                </div>
                <button @click="editTesti = null; showTestiModal = true" class="px-4 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">
                    + Tambah Testimoni
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($testimonials as $t)
                    <div class="p-6 border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl space-y-4 flex flex-col justify-between text-[#1A1A2E]" style="background-color: {{ $t->bg_color }};">
                        <p class="font-bold text-sm italic leading-relaxed">"{{ $t->quote }}"</p>

                        <div class="pt-4 border-t-2 border-[#1A1A2E] flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl p-2 bg-white border-2 border-[#1A1A2E] rounded-xl">{{ $t->avatar_emoji }}</span>
                                <div>
                                    <div class="font-heading font-extrabold text-sm">{{ $t->user_name }}</div>
                                    <div class="text-xs font-bold text-slate-600">{{ $t->user_tier }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button @click="editTesti = {{ json_encode($t) }}; showTestiModal = true" class="px-2.5 py-1 bg-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs cursor-pointer">✏️</button>
                                <form action="{{ route('admin.landing.testimonials.destroy', $t) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus testimoni ini?')" class="px-2.5 py-1 bg-red-500 text-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs cursor-pointer">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Feature Create/Edit -->
    <div x-show="showFeatureModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-md p-6 relative" @click.outside="showFeatureModal = false">
            <button @click="showFeatureModal = false" class="absolute top-4 right-4 text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">✕</button>

            <h3 class="font-heading font-bold text-xl text-[#1A1A2E] mb-4" x-text="editFeature ? 'Edit Fitur Utama' : 'Tambah Fitur Utama Baru'"></h3>

            <form x-bind:action="editFeature ? '/ctrl-twogo-admin/landing/features/' + editFeature.id : '{{ route('admin.landing.features.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editFeature">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Judul Fitur</label>
                    <input type="text" name="title" x-model="editFeature ? editFeature.title : ''" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="editFeature ? editFeature.description : ''" required rows="3" class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Ikon Emoji</label>
                        <input type="text" name="icon" x-model="editFeature ? editFeature.icon : '✨'" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold text-center">
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Warna Background (Hex)</label>
                        <input type="text" name="bg_color" x-model="editFeature ? editFeature.bg_color : '#00D4AA'" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-mono font-bold">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showFeatureModal = false" class="px-4 py-2 bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Simpan Fitur</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Stat Create/Edit -->
    <div x-show="showStatModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-md p-6 relative" @click.outside="showStatModal = false">
            <button @click="showStatModal = false" class="absolute top-4 right-4 text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">✕</button>

            <h3 class="font-heading font-bold text-xl text-[#1A1A2E] mb-4" x-text="editStat ? 'Edit Angka Stat' : 'Tambah Stat Baru'"></h3>

            <form x-bind:action="editStat ? '/ctrl-twogo-admin/landing/stats/' + editStat.id : '{{ route('admin.landing.stats.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editStat">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Angka / Nilai (Misal: 15.000+)</label>
                    <input type="text" name="number" x-model="editStat ? editStat.number : ''" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Label Metrik (Misal: Itinerary Dibuat)</label>
                    <input type="text" name="label" x-model="editStat ? editStat.label : ''" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Warna Background (Hex)</label>
                    <input type="text" name="bg_color" x-model="editStat ? editStat.bg_color : '#FFE156'" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-mono font-bold">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showStatModal = false" class="px-4 py-2 bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Simpan Stat</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Testimonial Create/Edit -->
    <div x-show="showTestiModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-md p-6 relative" @click.outside="showTestiModal = false">
            <button @click="showTestiModal = false" class="absolute top-4 right-4 text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">✕</button>

            <h3 class="font-heading font-bold text-xl text-[#1A1A2E] mb-4" x-text="editTesti ? 'Edit Testimoni' : 'Tambah Testimoni Baru'"></h3>

            <form x-bind:action="editTesti ? '/ctrl-twogo-admin/landing/testimonials/' + editTesti.id : '{{ route('admin.landing.testimonials.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editTesti">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Nama Pengguna / Pasangan</label>
                    <input type="text" name="user_name" x-model="editTesti ? editTesti.user_name : ''" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Tier / Subtitle (Misal: Traveler Sejati 🌟)</label>
                    <input type="text" name="user_tier" x-model="editTesti ? editTesti.user_tier : 'Traveler Sejati 🌟'" class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Kutipan Ulasan (Quote)</label>
                    <textarea name="quote" x-model="editTesti ? editTesti.quote : ''" required rows="3" class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Emoji Avatar</label>
                        <input type="text" name="avatar_emoji" x-model="editTesti ? editTesti.avatar_emoji : '🌟'" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold text-center">
                    </div>
                    <div>
                        <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Warna Background (Hex)</label>
                        <input type="text" name="bg_color" x-model="editTesti ? editTesti.bg_color : '#FFF3C4'" required class="w-full px-3 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-mono font-bold">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showTestiModal = false" class="px-4 py-2 bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Simpan Testimoni</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
