@extends('layouts.admin', [
    'title' => 'Sistem Gamifikasi & XP',
    'pageHeader' => 'Aturan XP, Leaderboard & Log Gamifikasi',
    'headerBadge' => 'XP Rules Engine'
])

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'rules' }">
    <!-- Sub-navigation Tabs -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-2 flex items-center gap-2 overflow-x-auto">
        <button 
            @click="activeTab = 'rules'" 
            :class="activeTab === 'rules' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>⚙️ Aturan Perolehan XP</span>
            <span class="px-2 py-0.5 bg-[#1A1A2E] text-white text-[10px] rounded-md font-mono">Dinamis</span>
        </button>

        <button 
            @click="activeTab = 'leaderboard'" 
            :class="activeTab === 'leaderboard' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>🏆 Leaderboard Top XP</span>
        </button>

        <button 
            @click="activeTab = 'logs'" 
            :class="activeTab === 'logs' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>📜 Log Transaksi XP</span>
        </button>

        <button 
            @click="activeTab = 'tiers'" 
            :class="activeTab === 'tiers' ? 'bg-[#FFE156] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-transparent hover:bg-slate-100'"
            class="px-5 py-2.5 rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] border-2 border-[#1A1A2E] transition-all cursor-pointer flex items-center gap-2"
        >
            <span>🎖️ Tier & Badge Level</span>
        </button>
    </div>

    <!-- TAB 1: Kontrol Aturan XP (Dinamis tanpa ubah kode!) -->
    <div x-show="activeTab === 'rules'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Pengaturan Aturan XP Real-time</h2>
                    <p class="text-xs font-bold text-slate-500 mt-1">
                        Ubah besaran XP yang didapatkan pengguna untuk setiap aktivitas langsung dari dashboard ini tanpa perlu mengubah kode program!
                    </p>
                </div>
                <span class="px-3 py-1 bg-[#00D4AA] border-2 border-[#1A1A2E] text-xs font-bold rounded-lg shadow-[2px_2px_0px_#1A1A2E]">
                    ⚡ Real-time Dynamic
                </span>
            </div>

            <form action="{{ route('admin.gamification.rules') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($xpRules as $rule)
                        <div class="p-4 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl shadow-[3px_3px_0px_#1A1A2E] space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="font-heading font-bold text-base text-[#1A1A2E]">{{ $rule->name }}</div>
                                <span class="font-mono text-[11px] font-bold px-2 py-0.5 bg-slate-200 border border-[#1A1A2E] rounded text-slate-700">
                                    {{ $rule->key }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 font-bold leading-relaxed">
                                {{ $rule->description ?? 'Bonus perolehan XP untuk aktivitas ini.' }}
                            </p>

                            <div class="pt-2 border-t border-slate-200 flex items-center gap-3">
                                <div class="flex-1">
                                    <label class="block text-[11px] font-extrabold text-slate-500 uppercase mb-1">Perolehan XP</label>
                                    <div class="flex items-center gap-1">
                                        <input 
                                            type="number" 
                                            name="rules[{{ $rule->id }}][xp_amount]" 
                                            value="{{ $rule->xp_amount }}" 
                                            min="0" 
                                            max="5000"
                                            class="w-full px-3 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg font-heading font-extrabold text-sm text-[#7B2FF7]"
                                        >
                                        <span class="font-extrabold text-xs text-[#7B2FF7]">XP</span>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="rules[{{ $rule->id }}][is_active]" 
                                            value="1" 
                                            {{ $rule->is_active ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-2 border-[#1A1A2E] accent-[#00D4AA]"
                                        >
                                        <span class="text-xs font-bold text-slate-700">Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-sm text-[#1A1A2E] cursor-pointer flex items-center gap-2">
                        <span>💾 Simpan Aturan XP Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: Leaderboard Top XP -->
    <div x-show="activeTab === 'leaderboard'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl overflow-hidden">
            <div class="p-5 bg-[#FFE156] border-b-3 border-[#1A1A2E] flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Leaderboard Peringkat XP Pengguna</h2>
                    <p class="text-xs font-bold text-slate-800 mt-0.5">Top 50 pengguna dengan perolehan XP tertinggi</p>
                </div>
                <span class="text-2xl">🏆</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b-2 border-[#1A1A2E] text-xs font-heading font-extrabold uppercase">
                            <th class="py-3 px-5 text-center">Rank</th>
                            <th class="py-3 px-5">Pengguna</th>
                            <th class="py-3 px-5">Tier & Level</th>
                            <th class="py-3 px-5 text-right">Total XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-slate-100 text-sm font-bold">
                        @foreach($leaderboard as $user)
                            <tr class="hover:bg-[#FFFBEB] transition-colors">
                                <td class="py-3.5 px-5 text-center">
                                    @if($user->rank === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FFE156] border-2 border-[#1A1A2E] text-base font-extrabold">🥇</span>
                                    @elseif($user->rank === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 border-2 border-[#1A1A2E] text-base font-extrabold">🥈</span>
                                    @elseif($user->rank === 3)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-200 border-2 border-[#1A1A2E] text-base font-extrabold">🥉</span>
                                    @else
                                        <span class="font-mono text-slate-500 font-extrabold">#{{ $user->rank }}</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-[#4361EE] border-2 border-[#1A1A2E] text-white flex items-center justify-center font-extrabold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-[#1A1A2E]">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500 font-normal">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-5">
                                    <span class="px-3 py-1 rounded-lg border border-[#1A1A2E] text-xs font-extrabold" style="background-color: {{ $user->level_info['tier']['card_bg'] }}; color: {{ $user->level_info['tier']['card_text'] }};">
                                        {{ $user->level_info['tier']['emoji'] }} {{ $user->level_info['tier']['name'] }} (Lvl {{ $user->level_info['level'] }})
                                    </span>
                                </td>

                                <td class="py-3.5 px-5 text-right font-heading font-extrabold text-base text-[#7B2FF7]">
                                    {{ number_format($user->xp ?? 0) }} XP
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Log Transaksi XP -->
    <div x-show="activeTab === 'logs'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl overflow-hidden">
            <div class="p-5 bg-slate-100 border-b-3 border-[#1A1A2E] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Log Transaksi XP Sistem</h2>
                    <p class="text-xs font-bold text-slate-500 mt-0.5">Catatan riwayat perolehan XP oleh seluruh pengguna</p>
                </div>

                <form action="{{ route('admin.gamification.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..." class="px-3 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-xl text-xs font-bold">
                    <button type="submit" class="px-3 py-1.5 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-xl font-bold text-xs">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#FFFBEB] border-b-2 border-[#1A1A2E] text-xs font-heading font-extrabold uppercase">
                            <th class="py-3.5 px-5">Waktu</th>
                            <th class="py-3.5 px-5">Pengguna</th>
                            <th class="py-3.5 px-5">Aktivitas (Source)</th>
                            <th class="py-3.5 px-5">Keterangan</th>
                            <th class="py-3.5 px-5 text-right">Jumlah XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-slate-100 text-xs font-bold text-[#1A1A2E]">
                        @forelse($xpLogs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-5 text-slate-500 font-mono">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-5">
                                    <span class="font-extrabold text-[#1A1A2E]">{{ $log->user->name ?? 'User #'.$log->user_id }}</span>
                                    @if($log->partner)
                                        <span class="text-[10px] font-normal text-slate-500">(bersama {{ $log->partner->name }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5">
                                    <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 border border-purple-300 rounded font-mono text-[11px]">
                                        {{ $log->source_type }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-slate-700">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3 px-5 text-right font-heading font-extrabold text-sm text-[#00D4AA]">
                                    +{{ $log->amount }} XP
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400 font-bold">
                                    Belum ada log transaksi XP tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-[#FFFBEB] border-t-2 border-[#1A1A2E]">
                {{ $xpLogs->links() }}
            </div>
        </div>
    </div>

    <!-- TAB 4: Badges & Tiers Overview -->
    <div x-show="activeTab === 'tiers'" class="space-y-6" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E] mb-2">Struktur Tier & Level Badge</h2>
            <p class="text-xs font-bold text-slate-500 mb-6">Tingkatan status pengguna berdasarkan jumlah XP yang dikumpulkan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tiers as $tier)
                    <div class="p-5 rounded-2xl border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] flex flex-col justify-between" style="background-color: {{ $tier['card_bg'] }}; color: {{ $tier['card_text'] }};">
                        <div>
                            <div class="text-4xl mb-2">{{ $tier['emoji'] }}</div>
                            <h3 class="font-heading font-extrabold text-xl mb-1">{{ $tier['name'] }}</h3>
                            <p class="text-xs font-bold opacity-90">
                                Minimum XP: {{ number_format($tier['min']) }} XP
                                @if($tier['max'])
                                    — {{ number_format($tier['max']) }} XP
                                @else
                                    + (Maksimal)
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
