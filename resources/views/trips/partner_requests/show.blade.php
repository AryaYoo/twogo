@extends('layouts.app')
@section('title', 'Detail Permohonan - ' . $partnerRequest->requester->name)

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('partner-requests.index', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-lg md:text-xl font-heading font-bold truncate">Review Permohonan 🤝</h1>
        <p class="text-xs font-medium text-slate-500 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')

<div class="space-y-4">
    {{-- Status Banner jika bukan pending --}}
    @if($partnerRequest->status === 'accepted')
        <div class="p-3.5 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-xl shadow-[3px_3px_0px_#1A1A2E] flex items-center gap-3">
            <span class="text-2xl">🎉</span>
            <div>
                <h3 class="font-heading font-extrabold text-sm text-[#1A1A2E]">Permohonan Telah Diterima</h3>
                <p class="text-xs font-medium text-slate-800">{{ $partnerRequest->requester->name }} sudah resmi menjadi partnermu dalam trip ini.</p>
            </div>
        </div>
    @elseif($partnerRequest->status === 'rejected')
        <div class="p-3.5 bg-slate-100 border-[3px] border-slate-300 rounded-xl flex items-center gap-3">
            <span class="text-2xl">📪</span>
            <div>
                <h3 class="font-heading font-bold text-sm text-slate-700">Permohonan Telah Ditolak</h3>
                <p class="text-xs font-medium text-slate-500">Pemberitahuan ramah telah dikirimkan ke calon partner.</p>
            </div>
        </div>
    @endif

    {{-- Profil Pemohon Card --}}
    <div class="p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil Calon Partner</span>
            <span class="px-2 py-0.5 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] rounded text-[10px] font-extrabold">
                Pemohon
            </span>
        </div>

        <div class="flex items-center gap-3.5 pt-1">
            <x-avatar :user="$partnerRequest->requester" size="lg" class="border-2 border-[#1A1A2E] shrink-0" />
            <div class="min-w-0 flex-1">
                <h2 class="font-heading font-extrabold text-base text-[#1A1A2E] truncate">
                    {{ $partnerRequest->requester->name }}
                </h2>
                <p class="text-xs font-medium text-slate-600 truncate">
                    {{ $partnerRequest->requester->email }}
                </p>
                @if($partnerRequest->requester->phone)
                    <p class="text-xs font-medium text-slate-500 truncate">
                        📱 {{ $partnerRequest->requester->phone }}
                    </p>
                @endif
            </div>
        </div>

        @if($partnerRequest->requester->bio)
            <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-lg text-xs font-medium text-slate-700 leading-relaxed">
                <span class="font-bold text-[#1A1A2E] block mb-0.5">Bio:</span>
                "{{ $partnerRequest->requester->bio }}"
            </div>
        @endif

        <div class="pt-2">
            <a href="{{ route('profile.user', $partnerRequest->requester) }}" target="_blank" class="w-full py-2 px-3 bg-white hover:bg-slate-50 active:translate-y-[1px] border-2 border-[#1A1A2E] rounded-lg font-heading font-bold text-xs text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center gap-1.5 transition-all text-center">
                <span>👤</span>
                <span>Buka Profil Lengkap Explorer</span>
                <span>&nearr;</span>
            </a>
        </div>
    </div>

    {{-- Pesan Permohonan Card --}}
    <div class="p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesan Permohonan</span>
            <span class="text-[11px] font-bold text-slate-400">
                {{ $partnerRequest->created_at->translatedFormat('d M Y, H:i') }}
            </span>
        </div>

        <div class="p-3.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-medium text-[#1A1A2E] leading-relaxed whitespace-pre-wrap">
            {{ $partnerRequest->message ?: '(Tidak ada pesan tambahan)' }}
        </div>
    </div>

    {{-- Action Bar jika Status Pending --}}
    @if($partnerRequest->isPending())
        <div class="p-4 bg-white border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3">
            <h3 class="font-heading font-bold text-sm text-[#1A1A2E]">Keputusan Host:</h3>
            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="openModal('rejectModal')"
                    class="flex-1 py-2.5 bg-white hover:bg-red-50 active:translate-y-[1px] text-[#EF4444] border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer flex items-center justify-center gap-1"
                >
                    <span>✕</span>
                    <span>Tolak</span>
                </button>

                <button
                    type="button"
                    onclick="openModal('acceptModal')"
                    class="flex-1 py-2.5 bg-[#00D4AA] hover:bg-[#00B894] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer flex items-center justify-center gap-1.5"
                >
                    <span>🤝</span>
                    <span>Terima Jadi Partner</span>
                </button>
            </div>
        </div>

        {{-- Modal Konfirmasi Terima --}}
        <x-modal id="acceptModal" title="Terima Sebagai Partner? 🤝">
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                    <x-avatar :user="$partnerRequest->requester" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                    <div class="min-w-0">
                        <p class="font-heading font-bold text-sm text-[#1A1A2E] truncate">{{ $partnerRequest->requester->name }}</p>
                        <p class="text-xs text-slate-700">Calon Partner Perjalanan</p>
                    </div>
                </div>

                <div class="p-3.5 bg-green-50 border-2 border-[#00D4AA] rounded-xl text-xs text-slate-700 font-medium space-y-1.5">
                    <p class="font-bold text-[#1A1A2E] flex items-center gap-1">
                        <span>✨</span>
                        <span>Setelah diterima:</span>
                    </p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li><strong>{{ $partnerRequest->requester->name }}</strong> resmi masuk ke trip sebagai partnermu.</li>
                        <li>Status <strong>Open Partner</strong> trip ini otomatis ditutup karena kuota (2/2) sudah terpenuhi.</li>
                        <li>Kalian berdua bisa langsung berdiskusi di <strong>Ruang Chat Privat</strong> trip ini.</li>
                    </ul>
                </div>

                <form action="{{ route('partner-requests.accept', [$trip, $partnerRequest]) }}" method="POST" class="flex gap-3 pt-2">
                    @csrf
                    <button
                        type="button"
                        onclick="closeModal('acceptModal')"
                        class="flex-1 py-2.5 bg-white hover:bg-gray-100 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 py-2.5 bg-[#00D4AA] hover:bg-[#00B894] active:translate-y-[1px] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                    >
                        Ya, Terima Partner
                    </button>
                </form>
            </div>
        </x-modal>

        {{-- Modal Konfirmasi Tolak --}}
        <x-modal id="rejectModal" title="Tolak Permohonan?">
            <div class="space-y-4">
                <div class="p-3.5 bg-slate-50 border-2 border-[#1A1A2E] rounded-xl text-xs text-slate-700 font-medium space-y-1">
                    <p class="font-bold text-[#1A1A2E] flex items-center gap-1">
                        <span>ℹ️</span>
                        <span>Pemberitahuan:</span>
                    </p>
                    <p>
                        Sistem TwoGo akan mengirimkan notifikasi penolakan yang ramah dan penuh semangat agar <strong>{{ $partnerRequest->requester->name }}</strong> tetap termotivasi mencari partner trip lainnya.
                    </p>
                </div>

                <form action="{{ route('partner-requests.reject', [$trip, $partnerRequest]) }}" method="POST" class="flex gap-3 pt-2">
                    @csrf
                    <button
                        type="button"
                        onclick="closeModal('rejectModal')"
                        class="flex-1 py-2.5 bg-white hover:bg-gray-100 border-2 border-[#1A1A2E] rounded-xl font-heading font-bold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 py-2.5 bg-[#EF4444] hover:bg-red-600 active:translate-y-[1px] text-white border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] transition-all cursor-pointer"
                    >
                        Tolak Permohonan
                    </button>
                </form>
            </div>
        </x-modal>
    @endif
</div>

@endsection
