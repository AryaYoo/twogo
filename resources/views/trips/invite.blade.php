@extends('layouts.app')
@section('title', 'Undang Teman')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Undang Partner 🤝</h1>
    </div>
</div>
@endsection

@section('content')

@if($trip->members->count() >= 2)
    <x-card class="bg-[#EF4444] text-white text-center py-8">
        <div class="text-5xl mb-4">🚫</div>
        <h2 class="font-heading font-bold text-2xl mb-2">Trip Penuh!</h2>
        <p class="font-medium opacity-90 max-w-xs mx-auto">Sesuai namanya, TwoGo difokuskan untuk 2 orang saja. Kuota trip ini sudah maksimal.</p>
    </x-card>
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

    <!-- Join with Code Form (For others) -->
    <h3 class="font-heading font-bold text-lg mb-3">Atau Punya Kode Invite?</h3>
    <x-card>
        <form action="{{ route('invitations.join_code') }}" method="POST" class="flex gap-2 min-w-0">
            @csrf
            <input
                type="text"
                name="invite_code"
                placeholder="Masukkan 6 digit kode..."
                class="flex-1 min-w-0 border-[3px] border-[#1A1A2E] rounded-sm px-3 py-2 text-[#1A1A2E] font-bold uppercase"
                required minlength="6" maxlength="6"
            >
            <x-button type="submit" variant="mint" class="shrink-0">Gabung</x-button>
        </form>
        @error('invite_code')
            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
        @enderror
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
                    data-avatar="{{ $friend->avatar ? Storage::url($friend->avatar) : '' }}"
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
