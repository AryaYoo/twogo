@extends('layouts.app')
@section('title', 'Permohonan Partner - ' . $trip->title)

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-lg md:text-xl font-heading font-bold truncate">Permohonan Partner 🤝</h1>
        <p class="text-xs font-medium text-slate-500 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')

{{-- Info Banner --}}
<div class="mb-5 p-4 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-xl shadow-[3px_3px_0px_#1A1A2E] flex items-center justify-between gap-3">
    <div>
        <h2 class="font-heading font-extrabold text-sm text-[#1A1A2E]">Daftar Explorer yang Tertarik</h2>
        <p class="text-xs font-medium text-slate-700 mt-0.5">
            Pilih 1 partner terbaik untuk menjelajahi trip ini bersamamu.
        </p>
    </div>
    <span class="px-2.5 py-1 bg-white border-2 border-[#1A1A2E] rounded-lg text-xs font-extrabold shadow-[1px_1px_0px_#1A1A2E] shrink-0">
        {{ $requests->count() }} Permohonan
    </span>
</div>

{{-- Requests List --}}
<div class="space-y-3">
    @forelse($requests as $req)
        <a href="{{ route('partner-requests.show', [$trip, $req]) }}" class="block">
            <div class="p-4 bg-white hover:bg-[#FFFBEB] transition-colors border-[3px] border-[#1A1A2E] rounded-xl shadow-[4px_4px_0px_#1A1A2E] space-y-3">
                {{-- Header Pemohon --}}
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-avatar :user="$req->requester" size="md" class="border-2 border-[#1A1A2E] shrink-0" />
                        <div class="min-w-0">
                            <h3 class="font-heading font-extrabold text-sm text-[#1A1A2E] truncate">
                                {{ $req->requester->name }}
                            </h3>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $req->requester->bio ?: ($req->requester->email ?: 'Explorer TwoGo') }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    @if($req->status === 'pending')
                        <span class="px-2.5 py-1 bg-[#FFE156] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E] shrink-0">
                            ⏳ Menunggu Respon
                        </span>
                    @elseif($req->status === 'accepted')
                        <span class="px-2.5 py-1 bg-[#00D4AA] text-[#1A1A2E] border-2 border-[#1A1A2E] rounded-full text-[10px] font-extrabold shadow-[1px_1px_0px_#1A1A2E] shrink-0">
                            ✅ Diterima
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-300 rounded-full text-[10px] font-bold shrink-0">
                            Ditolak
                        </span>
                    @endif
                </div>

                {{-- Snippet Pesan --}}
                @if($req->message)
                    <div class="p-2.5 bg-slate-50 border-2 border-slate-200 rounded-lg text-xs text-slate-700 font-medium line-clamp-2">
                        💬 "{{ $req->message }}"
                    </div>
                @endif

                {{-- Footer Info --}}
                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 pt-1 border-t border-slate-100">
                    <span>Dikirim {{ $req->created_at->diffForHumans() }}</span>
                    <span class="text-[#4361EE] flex items-center gap-0.5">
                        <span>Review Permohonan</span>
                        <span>&rarr;</span>
                    </span>
                </div>
            </div>
        </a>
    @empty
        <x-empty-state 
            icon="📭"
            title="Belum ada permohonan"
            description="Belum ada explorer yang mengirimkan permohonan gabung ke trip ini. Pastikan tripmu menarik dan aktifkan open partner!"
        />
    @endforelse
</div>

@endsection
