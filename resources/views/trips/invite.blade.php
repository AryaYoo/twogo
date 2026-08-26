@extends('layouts.app')
@section('title', 'Undang Teman')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform cursor-pointer">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Undang Partner 🤝</h1>
    </div>
</div>
@endsection

@section('content')

@if($trip->members->count() >= 2)
    @php
        $host = $trip->members->firstWhere('id', $trip->user_id) ?? $trip->creator;
        $partner = $trip->members->firstWhere('id', '!=', $trip->user_id);
        $isHost = ($trip->user_id === Auth::id());
    @endphp

    {{-- Banner Status --}}
    <div class="mb-5 p-4 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-xl shadow-[3px_3px_0px_#1A1A2E] flex items-center gap-3">
        <span class="text-3xl">👥</span>
        <div>
            <h2 class="font-heading font-extrabold text-base text-[#1A1A2E]">Trip Penuh (2/2 Explorer)</h2>
            <p class="text-xs font-medium text-slate-700">Trip TwoGo difokuskan untuk 2 explorer agar petualangan tetap seru dan intim!</p>
        </div>
    </div>

    {{-- Daftar Explorer --}}
    <div class="space-y-3 mb-6">
        <h3 class="font-heading font-bold text-base text-[#1A1A2E] flex items-center gap-1.5">
            <span>Daftar Explorer</span>
            <span class="text-xs bg-white px-2 py-0.5 border-2 border-[#1A1A2E] rounded-full font-extrabold shadow-[1px_1px_0px_#1A1A2E]">2 Orang</span>
        </h3>

        {{-- Host Card --}}
        @if($host)
        <div class="p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <x-avatar :user="$host" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-heading font-extrabold text-base text-[#1A1A2E] truncate">{{ $host->name }}</span>
                        <span class="px-2 py-0.5 bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shrink-0 shadow-[1px_1px_0px_#1A1A2E]">
                            👑 Host
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-500 truncate">
                        {{ $host->bio ?: ($host->email ?: 'Pembuat Perjalanan') }}
                    </p>
                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">Pembuat Trip</p>
                </div>
            </div>
            @if($host->id === Auth::id())
                <span class="text-[10px] font-bold text-slate-400 px-2 py-1 bg-slate-100 rounded-md border border-slate-300 shrink-0">Kamu</span>
            @endif
        </div>
        @endif

        {{-- Partner Card --}}
        @if($partner)
        <div class="p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] flex flex-col gap-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$partner" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-extrabold text-base text-[#1A1A2E] truncate">{{ $partner->name }}</span>
                            <span class="px-2 py-0.5 bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shrink-0 shadow-[1px_1px_0px_#1A1A2E]">
                                🤝 Partner
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-500 truncate">
                            {{ $partner->bio ?: ($partner->email ?: 'Partner Perjalanan') }}
                        </p>
                        @if($partner->pivot && $partner->pivot->joined_at)
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">
                                Bergabung {{ \Carbon\Carbon::parse($partner->pivot->joined_at)->translatedFormat('d M Y') }}
                            </p>
                        @endif
                    </div>
                </div>
                @if($partner->id === Auth::id())
                    <span class="text-[10px] font-bold text-slate-400 px-2 py-1 bg-slate-100 rounded-md border border-slate-300 shrink-0">Kamu</span>
                @endif
            </div>

            {{-- Action for Host: Hapus Partner --}}
            @if($isHost)
            <div class="pt-3 border-t-2 border-slate-100 flex justify-end">
                <button
                    type="button"
                    onclick="openModal('removePartnerModal')"
                    class="px-3.5 py-2 bg-[#EF4444] hover:bg-red-600 active:translate-y-[1px] text-white border-2 border-[#1A1A2E] rounded-lg shadow-[2px_2px_0px_#1A1A2E] font-heading font-extrabold text-xs flex items-center gap-1.5 transition-all cursor-pointer"
                >
                    <span>🚫</span>
                    <span>Keluarkan Partner</span>
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Pop-up Modal Konfirmasi Hapus Partner --}}
    @if($isHost && $partner)
    <x-modal id="removePartnerModal" title="Keluarkan Partner?">
        <div class="space-y-4">
            <div class="flex items-center gap-3 p-3 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                <x-avatar :user="$partner" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                <div class="min-w-0">
                    <p class="font-heading font-bold text-sm text-[#1A1A2E] truncate">{{ $partner->name }}</p>
                    <p class="text-xs text-slate-700">Partner Trip</p>
                </div>
            </div>

            <div class="p-3.5 bg-red-50 border-2 border-[#EF4444] rounded-xl text-xs text-red-700 font-medium space-y-1">
                <p class="font-bold flex items-center gap-1">
                    <span>⚠️</span>
                    <span>Perhatian:</span>
                </p>
                <p>
                    Jika dikeluarkan, <strong>{{ $partner->name }}</strong> tidak akan lagi memiliki akses ke rencana perjalanan, wishlist, dokumen, dan ruang chat trip <strong>{{ $trip->title }}</strong>.
                </p>
                <p>Kamu dapat mengundang partner baru setelahnya.</p>
            </div>

            <form action="{{ route('trips.members.remove', [$trip, $partner]) }}" method="POST" class="flex gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    onclick="closeModal('removePartnerModal')"
                    class="flex-1 py-2.5 bg-white hover:bg-gray-100 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="flex-1 py-2.5 bg-[#EF4444] hover:bg-red-600 active:translate-y-[1px] text-white border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                >
                    Ya, Keluarkan
                </button>
            </form>
        </div>
    </x-modal>
    @endif
@else
    @php
        $selectedFriend = $availableFriends->firstWhere('id', (int) old('invited_user_id'));
    @endphp

    <!-- Invite via Code -->
    <x-card class="bg-[#FFE156] text-center p-8 mb-6 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 text-6xl opacity-20 transform rotate-12">🎫</div>

        <h3 class="font-heading font-bold text-lg mb-2 relative z-10">Bagikan Kode Invite</h3>
        <p class="text-sm font-medium mb-6 relative z-10 opacity-80">Kasih kode ini ke partnermu untuk join trip.</p>

        <div class="bg-white border-[3px] border-[#1A1A2E] rounded-xl py-4 px-6 inline-block shadow-[4px_4px_0px_#1A1A2E] relative z-10 cursor-pointer hover:bg-gray-50 transition-colors" onclick="copyCode()">
            <span id="invite-code" class="font-heading font-bold text-3xl tracking-widest text-[#4361EE]">{{ $trip->invite_code }}</span>
            <div class="text-[10px] uppercase font-bold text-gray-500 mt-1">Tap untuk Copy</div>
        </div>
    </x-card>


    <!-- Invite a friend directly -->
    <h3 class="font-heading font-bold text-lg mt-6 mb-3">Undang Teman</h3>
    <x-card>
        @if($availableFriends->isEmpty())
            <x-empty-state
                icon="🤝"
                title="Belum ada teman tersedia"
                description="Tambah teman dulu lewat menu Teman di profilmu, atau bagikan kode invite di atas."
            />
        @else
            <form action="{{ route('invitations.send', $trip) }}" method="POST" class="flex flex-col gap-3" id="inviteFriendForm">
                @csrf
                <input type="hidden" name="invited_user_id" id="invited_user_id" value="{{ old('invited_user_id') }}" required>

                <button
                    type="button"
                    onclick="openPickFriendModal()"
                    class="w-full min-w-0 flex items-center gap-3 border-[3px] border-[#1A1A2E] rounded-sm px-3 py-2.5 bg-white text-left shadow-[2px_2px_0px_#1A1A2E] hover:bg-gray-50 transition-colors"
                >
                    <span id="selectedFriendAvatar" class="shrink-0 {{ $selectedFriend ? '' : 'hidden' }}">
                        @if($selectedFriend)
                            <x-avatar :user="$selectedFriend" size="sm" />
                        @endif
                    </span>
                    <span id="selectedFriendText" class="flex-1 min-w-0 truncate font-bold text-sm">
                        {{ $selectedFriend ? $selectedFriend->name : 'Pilih teman untuk diundang' }}
                    </span>
                    <span class="shrink-0 text-xs font-bold opacity-60">UBAH</span>
                </button>

                <x-button type="submit" variant="mint" class="w-full">Kirim Undangan</x-button>
            </form>
        @endif
        @error('invited_user_id')
            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
        @enderror
    </x-card>

    <!-- Open Partner Section -->
    <div class="mt-8 pt-6 border-t-2 border-[#1A1A2E] border-dashed">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-heading font-bold text-lg text-[#1A1A2E] flex items-center gap-1.5">
                <span>Buka Lowongan Partner 🌍</span>
            </h3>
            <span class="px-2 py-0.5 bg-[#4361EE] text-white border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E]">
                Maks 2x/Bulan
            </span>
        </div>

        @if($trip->is_open_partner)
            {{-- Status: Sedang Open Partner --}}
            <div class="p-4 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-xs font-extrabold shadow-[1px_1px_0px_#1A1A2E] mb-1.5">
                            ✨ Sedang Aktif
                        </span>
                        <h4 class="font-heading font-extrabold text-base text-[#1A1A2E]">Trip Ini Sedang Open Partner!</h4>
                        <p class="text-xs font-medium text-slate-800 mt-1">
                            Perjalanan ini sudah tayang di halaman pencarian Open Partner. Explorer lain dapat mengirimkan permohonan gabung kepadamu.
                        </p>
                    </div>
                </div>

                @if($trip->open_partner_note)
                    <div class="p-2.5 bg-white border-2 border-[#1A1A2E] rounded-lg text-xs font-medium text-[#1A1A2E]">
                        <span class="font-bold text-slate-500 block mb-0.5">Catatan untuk calon partner:</span>
                        "{{ $trip->open_partner_note }}"
                    </div>
                @endif

                @php
                    $pendingRequestsCount = $trip->pendingPartnerRequestsCount();
                @endphp

                <div class="flex flex-col sm:flex-row items-center gap-2 pt-2 border-t-2 border-[#1A1A2E]/20">
                    <a href="{{ route('partner-requests.index', $trip) }}" class="w-full sm:flex-1 py-2.5 px-4 bg-[#FFE156] hover:bg-[#F2D449] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center gap-1.5 transition-all text-center">
                        <span>📋</span>
                        <span>Lihat Permintaan Masuk</span>
                        @if($pendingRequestsCount > 0)
                            <span class="bg-[#FF6B9D] text-white px-1.5 py-0.2 rounded-full border border-[#1A1A2E] text-[10px]">
                                {{ $pendingRequestsCount }} baru
                            </span>
                        @endif
                    </a>

                    <form action="{{ route('trips.open_partner.deactivate', $trip) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-3 bg-white hover:bg-red-50 text-red-600 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer">
                            Tutup Open Partner
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- Status: Belum Open Partner --}}
            <x-card class="bg-white p-4">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#4361EE] text-white rounded-xl border-2 border-[#1A1A2E] flex items-center justify-center text-xl shrink-0 shadow-[2px_2px_0px_#1A1A2E]">
                        🤝
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-heading font-bold text-sm text-[#1A1A2E]">Belum punya partner? Buka Open Partner!</h4>
                        <p class="text-xs font-medium text-slate-600 mt-0.5 leading-relaxed">
                            Publikasikan tripmu ke komunitas TwoGo agar explorer lain bisa menemukan rencana wisatamu dan melamar jadi partner.
                        </p>
                    </div>
                </div>

                <div class="p-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-lg flex items-center justify-between text-xs mb-3">
                    <span class="font-bold text-[#1A1A2E]">Sisa kuota bulan ini:</span>
                    <span class="font-extrabold px-2 py-0.5 {{ ($remainingQuota ?? 0) > 0 ? 'bg-[#00D4AA] text-[#1A1A2E]' : 'bg-red-200 text-red-800' }} border border-[#1A1A2E] rounded-md">
                        {{ $remainingQuota ?? 0 }} / 2 Trip
                    </span>
                </div>

                @if(($remainingQuota ?? 0) > 0)
                    <button
                        type="button"
                        onclick="openModal('openPartnerModal')"
                        class="w-full py-2.5 bg-[#FFE156] hover:bg-[#F2D449] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center gap-2 transition-all cursor-pointer"
                    >
                        <span>🔓</span>
                        <span>Jadikan Open Partner</span>
                    </button>
                @else
                    <button
                        type="button"
                        disabled
                        class="w-full py-2.5 bg-gray-200 text-gray-500 border-2 border-gray-400 rounded-xl font-heading font-bold text-sm cursor-not-allowed text-center"
                    >
                        Kuota Bulan Ini Habis (2/2)
                    </button>
                @endif
            </x-card>

            {{-- Pop-up Modal Ketentuan Open Partner --}}
            <x-modal id="openPartnerModal" title="Ketentuan Open Partner 🤝">
                <form action="{{ route('trips.open_partner.activate', $trip) }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Informasi Kuota --}}
                    <div class="p-3 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🎟️</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Kuota Open Partner Kamu</p>
                                <p class="text-[11px] font-medium text-slate-600">Direset setiap awal bulan</p>
                            </div>
                        </div>
                        <span class="font-heading font-extrabold text-sm bg-white px-2.5 py-1 border-2 border-[#1A1A2E] rounded-lg shadow-[1px_1px_0px_#1A1A2E]">
                            {{ $remainingQuota ?? 0 }} / 2 Sisa
                        </span>
                    </div>

                    {{-- Poin Ketentuan --}}
                    <div class="p-3.5 bg-white border-2 border-[#1A1A2E] rounded-xl text-xs space-y-2">
                        <p class="font-heading font-bold text-sm text-[#1A1A2E] flex items-center gap-1.5">
                            <span>📜</span>
                            <span>Ketentuan & Aturan:</span>
                        </p>
                        <ul class="space-y-1.5 list-disc pl-4 text-slate-700 font-medium leading-relaxed">
                            <li>Trip kamu akan ditampilkan di halaman publik <strong>Open Partner</strong>.</li>
                            <li>Explorer lain dapat melihat detail itinerary dan mengirim permohonan gabung dengan pesan.</li>
                            <li>Kamu sebagai Host memiliki kendali penuh untuk <strong>menerima atau menolak</strong> permohonan.</li>
                            <li>Setelah 1 partner kamu terima, status lowongan otomatis <strong>ditutup</strong> dan menjadi trip biasa berdua.</li>
                            <li>Setiap akun memiliki kuota membuka Open Partner maksimal <strong>2 kali per bulan</strong>.</li>
                        </ul>
                    </div>

                    {{-- Catatan Tambahan (Opsional) --}}
                    <div>
                        <label for="open_partner_note" class="block font-heading font-bold text-xs text-[#1A1A2E] mb-1">
                            Catatan untuk calon partner (Opsional):
                        </label>
                        <textarea
                            name="open_partner_note"
                            id="open_partner_note"
                            rows="2"
                            maxlength="500"
                            placeholder="Contoh: Cari partner yang suka kulineran & santai, budget fleksibel..."
                            class="nb-input w-full text-xs font-medium"
                        ></textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            onclick="closeModal('openPartnerModal')"
                            class="flex-1 py-2.5 bg-white hover:bg-gray-100 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="flex-1 py-2.5 bg-[#00D4AA] hover:bg-[#00B894] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                        >
                            Konfirmasi & Buka
                        </button>
                    </div>
                </form>
            </x-modal>
        @endif
    </div>

    <x-modal id="pickFriendModal" title="Pilih Teman">
        <input
            type="search"
            id="friendSearch"
            placeholder="Cari nama teman..."
            class="nb-input w-full mb-4"
            autocomplete="off"
        >

        <div id="friendList" class="flex flex-col gap-2">
            @foreach($availableFriends as $friend)
                <button
                    type="button"
                    class="friend-pick-card w-full flex items-center gap-3 p-3 bg-white border-[3px] border-[#1A1A2E] rounded-lg shadow-[2px_2px_0px_#1A1A2E] hover:bg-[#FFF9E6] transition-colors text-left"
                    data-id="{{ $friend->id }}"
                    data-name="{{ $friend->name }}"
                    data-avatar="{{ $friend->avatar ? (str_starts_with($friend->avatar, 'http') ? $friend->avatar : Storage::url($friend->avatar)) : '' }}"
                    data-initials="{{ strtoupper(substr($friend->name, 0, 1)) }}"
                    onclick="selectFriend(this)"
                >
                    <x-avatar :user="$friend" size="md" class="shrink-0 border-2 border-[#1A1A2E]" />
                    <div class="flex-1 min-w-0">
                        <div class="font-bold font-heading truncate">{{ $friend->name }}</div>
                        @if($friend->bio)
                            <div class="text-xs opacity-70 truncate">{{ $friend->bio }}</div>
                        @endif
                    </div>
                </button>
            @endforeach
        </div>

        <div id="friendSearchEmpty" class="hidden text-center py-8 text-sm font-medium opacity-70">
            Tidak ada teman yang cocok.
        </div>
    </x-modal>
@endif

@endsection

@push('scripts')
<script>
    function copyCode() {
        navigator.clipboard.writeText('{{ $trip->invite_code }}');
        showToast('Kode berhasil dicopy!', 'success');
    }

    function openPickFriendModal() {
        const search = document.getElementById('friendSearch');
        if (search) {
            search.value = '';
            filterFriends('');
        }
        openModal('pickFriendModal');
        setTimeout(() => search?.focus(), 150);
    }

    function filterFriends(query) {
        const q = query.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.friend-pick-card').forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const show = !q || name.includes(q);
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        const empty = document.getElementById('friendSearchEmpty');
        if (empty) empty.classList.toggle('hidden', visible > 0);
    }

    function selectFriend(card) {
        document.getElementById('invited_user_id').value = card.dataset.id;
        document.getElementById('selectedFriendText').textContent = card.dataset.name;

        const avatarWrap = document.getElementById('selectedFriendAvatar');
        if (avatarWrap) {
            avatarWrap.classList.remove('hidden');
            if (card.dataset.avatar) {
                avatarWrap.innerHTML = '<div class="nb-avatar nb-avatar-sm shrink-0 border-2 border-[#1A1A2E]"><img src="' + card.dataset.avatar + '" alt=""></div>';
            } else {
                avatarWrap.innerHTML = '<div class="nb-avatar nb-avatar-sm shrink-0 border-2 border-[#1A1A2E]"><span class="opacity-70">' + card.dataset.initials + '</span></div>';
            }
        }

        closeModal('pickFriendModal');
    }

    document.getElementById('friendSearch')?.addEventListener('input', function () {
        filterFriends(this.value);
    });
</script>
@endpush
