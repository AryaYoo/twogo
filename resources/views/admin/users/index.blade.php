@extends('layouts.admin', [
    'title' => 'Manajemen User',
    'pageHeader' => 'Manajemen Pengguna Aplikasi',
    'headerBadge' => $users->total() . ' Users'
])

@section('content')
<div class="space-y-6" x-data="{ selectedUser: null, showModal: false, showResetModal: false, resetUser: null }">
    <!-- Filter & Search Bar -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Form -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-80">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, email, telepon..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl font-medium text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#4361EE]"
                >
                <span class="absolute left-3 top-3 text-slate-400 text-sm">🔍</span>
            </div>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl font-bold text-sm text-[#1A1A2E] cursor-pointer">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>

            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-[#FFE156] hover:bg-[#ffd829] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-sm cursor-pointer">
                Filter
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-red-600 underline">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FFE156] border-b-3 border-[#1A1A2E] text-[#1A1A2E] font-heading font-extrabold text-xs uppercase tracking-wider">
                        <th class="py-4 px-5">Pengguna</th>
                        <th class="py-4 px-4">Status Akun</th>
                        <th class="py-4 px-4">Tier</th>
                        <th class="py-4 px-4">Level & XP</th>
                        <th class="py-4 px-4">Itinerary</th>
                        <th class="py-4 px-4">Terdaftar</th>
                        <th class="py-4 px-4">Terakhir Login</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-slate-100 text-sm font-medium text-[#1A1A2E]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#FFFBEB] transition-colors">
                            <!-- User Info -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#4361EE] border-2 border-[#1A1A2E] flex items-center justify-center text-white font-extrabold text-sm shadow-[2px_2px_0px_#1A1A2E] overflow-hidden shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-base flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->isAdmin())
                                                <span class="px-2 py-0.5 bg-[#FFE156] text-[10px] font-extrabold border border-[#1A1A2E] rounded">ADMIN</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 font-bold">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($user->status === 'banned')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 font-extrabold text-xs border border-red-400 rounded-lg">
                                        🚫 Banned
                                    </span>
                                @elseif($user->status === 'suspended')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-extrabold text-xs border border-amber-400 rounded-lg">
                                        ⚠️ Suspended
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-extrabold text-xs border border-emerald-400 rounded-lg">
                                        ✅ Aktif
                                    </span>
                                @endif
                            </td>

                            <!-- Account Tier -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.users.tier', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="account_tier" onchange="this.form.submit()" class="px-2 py-1 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-lg font-bold text-xs text-[#1A1A2E] cursor-pointer shadow-[1px_1px_0px_#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#4361EE] appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231A1A2E%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 8px top 50%; background-size: 8px auto; padding-right: 24px;">
                                        <option value="standard" {{ $user->account_tier === 'standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="early_access" {{ $user->account_tier === 'early_access' ? 'selected' : '' }}>Early Access</option>
                                        <option value="premium" {{ $user->account_tier === 'premium' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Level & XP -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">{{ $user->level_info['tier']['emoji'] }}</span>
                                    <div>
                                        <div class="font-extrabold text-xs text-[#1A1A2E] whitespace-nowrap">Lvl {{ $user->level_info['level'] }} — {{ $user->level_info['tier']['name'] }}</div>
                                        <div class="text-[11px] font-extrabold text-[#7B2FF7] whitespace-nowrap">{{ number_format($user->xp ?? 0) }} XP</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Total Trips -->
                            <td class="py-3.5 px-4">
                                <span class="font-extrabold bg-slate-100 border border-slate-300 px-2.5 py-1 rounded-lg text-xs whitespace-nowrap inline-flex items-center gap-1">
                                    <span>🗺️</span> <span>{{ $user->owned_trips_count }} Trip</span>
                                </span>
                            </td>

                            <!-- Registered Date -->
                            <td class="py-3.5 px-4 text-xs font-bold text-slate-600">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Last Login -->
                            <td class="py-3.5 px-4 text-xs font-bold text-slate-500">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <!-- Detail Button -->
                                    <button 
                                        @click="fetchUserDetail({{ $user->id }})" 
                                        class="px-2.5 py-1.5 bg-[#00D4AA] hover:bg-[#00b894] border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer"
                                        title="Lihat Detail User"
                                    >
                                        👁️ Detail
                                    </button>

                                    <!-- Status Toggle Action -->
                                    <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @if($user->status === 'suspended' || $user->status === 'banned')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" onclick="return confirm('Aktifkan kembali akun ini?')" class="px-2.5 py-1.5 bg-[#FFE156] hover:bg-[#ffd829] border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                                ✔️ Aktifkan
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" onclick="return confirm('Tangguhkan (suspend) akun pengguna ini?')" class="px-2.5 py-1.5 bg-amber-200 hover:bg-amber-300 border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                                ⚠️ Suspend
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Reset Password Button -->
                                    <button 
                                        @click="openResetPasswordModal({{ json_encode($user) }})" 
                                        class="px-2.5 py-1.5 bg-[#4361EE] hover:bg-blue-600 text-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer"
                                        title="Reset Password"
                                    >
                                        🔑 Reset
                                    </button>

                                    <!-- Delete Account -->
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS PERMANEN AKUN USER INI? Data trip dan pengeluaran terkait akan dihapus!')" class="px-2 py-1.5 bg-red-500 hover:bg-red-600 text-white border-2 border-[#1A1A2E] rounded-lg font-bold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 font-bold text-base">
                                Tidak ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-4 bg-[#FFFBEB] border-t-3 border-[#1A1A2E]">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Detail Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto" @click.outside="showModal = false">
            <button @click="showModal = false" class="absolute top-4 right-4 text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">✕</button>

            <template x-if="selectedUser">
                <div class="space-y-5">
                    <div class="flex items-center gap-4 pb-4 border-b-2 border-slate-200">
                        <div class="w-16 h-16 rounded-2xl bg-[#FFE156] border-[3px] border-[#1A1A2E] flex items-center justify-center text-3xl font-heading font-extrabold shadow-[3px_3px_0px_#1A1A2E]">
                            <span x-text="selectedUser.level_info?.tier?.emoji || '👤'"></span>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-2xl text-[#1A1A2E]" x-text="selectedUser.user?.name"></h3>
                            <p class="text-xs font-bold text-slate-500" x-text="selectedUser.user?.email"></p>
                            <span class="inline-block mt-1 px-2.5 py-0.5 bg-[#7B2FF7] text-white text-xs font-extrabold rounded-md border border-[#1A1A2E]" x-text="'Lvl ' + selectedUser.level_info?.level + ' — ' + selectedUser.level_info?.tier?.name"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                            <div class="text-[10px] font-bold text-slate-500 uppercase">Total XP</div>
                            <div class="font-heading font-extrabold text-lg text-[#7B2FF7]" x-text="selectedUser.user?.xp + ' XP'"></div>
                        </div>
                        <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                            <div class="text-[10px] font-bold text-slate-500 uppercase">Itinerary</div>
                            <div class="font-heading font-extrabold text-lg text-[#1A1A2E]" x-text="selectedUser.trips_count + ' Trip'"></div>
                        </div>
                        <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                            <div class="text-[10px] font-bold text-slate-500 uppercase">Status</div>
                            <div class="font-heading font-extrabold text-sm capitalize" x-text="selectedUser.user?.status || 'Active'"></div>
                        </div>
                        <div class="p-3 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl shadow-[2px_2px_0px_#1A1A2E]">
                            <div class="text-[10px] font-bold text-slate-500 uppercase">Tier</div>
                            <div class="font-heading font-bold text-xs truncate capitalize" x-text="(selectedUser.user?.account_tier || 'standard').replace('_', ' ')"></div>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs font-bold text-slate-700 bg-slate-50 p-4 rounded-xl border-2 border-[#1A1A2E]">
                        <div>🗓️ <b>Tanggal Registrasi:</b> <span x-text="selectedUser.registered_at"></span></div>
                        <div>🕒 <b>Login Terakhir:</b> <span x-text="selectedUser.last_login"></span></div>
                        <div>📝 <b>Bio Profile:</b> <span x-text="selectedUser.user?.bio || 'Belum diisi'"></span></div>
                        <div class="pt-2 mt-2 border-t border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-600">🤖 Kuota AI:</span>
                                    <span class="ml-1.5 px-2 py-0.5 rounded text-white text-[11px] font-black"
                                          :class="(selectedUser.ai_quota_left ?? 0) > 0 ? 'bg-emerald-600' : 'bg-red-500'"
                                          x-text="(selectedUser.ai_quota_left ?? 0) + ' / ' + (selectedUser.ai_limit ?? 2) + ' sisa'"></span>
                                    <template x-if="selectedUser.ai_reset_relative">
                                        <span class="text-slate-400 text-[10px] ml-1 font-semibold" x-text="'(Reset ' + selectedUser.ai_reset_relative + ')'"></span>
                                    </template>
                                </div>
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase" x-text="'Limit: ' + (selectedUser.ai_limit ?? 2) + 'x'"></span>
                            </div>

                            <!-- Panel Kelola Kuota AI -->
                            <div class="bg-[#FFF8E7] border-2 border-[#1A1A2E] rounded-xl p-3 space-y-2.5">
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">⚙️ Kelola Kuota AI</div>

                                <!-- Reset Instan -->
                                <form :action="'/ctrl-twogo-admin/users/' + selectedUser.user?.id + '/ai-quota'" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="reset">
                                    <button type="submit"
                                        onclick="return confirm('Reset kuota AI user ini? Kuota terpakai akan kembali ke 0.')"
                                        class="flex-1 px-3 py-2 bg-[#00D4AA] hover:bg-[#00b896] text-white border-2 border-[#1A1A2E] rounded-xl font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer transition-all active:shadow-none active:translate-x-[2px] active:translate-y-[2px]">
                                        🔄 Reset Kuota Instan
                                    </button>
                                </form>

                                <!-- Set Kuota Terpakai Manual -->
                                <form :action="'/ctrl-twogo-admin/users/' + selectedUser.user?.id + '/ai-quota'" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="set">
                                    <label class="text-[11px] font-bold text-slate-600 whitespace-nowrap">Kuota terpakai:</label>
                                    <input type="number" name="quota_count" min="0" max="100"
                                           :value="selectedUser.ai_quota_used ?? 0"
                                           class="flex-1 w-16 px-2 py-1.5 bg-white border-2 border-[#1A1A2E] rounded-lg text-xs font-bold text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#4361EE] text-center">
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-[#4361EE] hover:bg-[#3451d1] text-white border-2 border-[#1A1A2E] rounded-xl font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer transition-all active:shadow-none active:translate-x-[2px] active:translate-y-[2px]">
                                        Set
                                    </button>
                                </form>

                                <!-- Ubah Batas Limit -->
                                <form :action="'/ctrl-twogo-admin/users/' + selectedUser.user?.id + '/ai-quota'" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="set_limit">
                                    <label class="text-[11px] font-bold text-[#7B2FF7] whitespace-nowrap">✦ Batas limit:</label>
                                    <input type="number" name="quota_limit" min="1" max="100"
                                           :value="selectedUser.ai_limit ?? 2"
                                           class="flex-1 w-16 px-2 py-1.5 bg-white border-2 border-[#7B2FF7] rounded-lg text-xs font-bold text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#7B2FF7] text-center">
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-[#7B2FF7] hover:bg-[#6a28d4] text-white border-2 border-[#1A1A2E] rounded-xl font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E] cursor-pointer transition-all active:shadow-none active:translate-x-[2px] active:translate-y-[2px]">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div x-show="showResetModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-2xl w-full max-w-md p-6 relative" @click.outside="showResetModal = false">
            <button @click="showResetModal = false" class="absolute top-4 right-4 text-xl font-bold bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">✕</button>

            <h3 class="font-heading font-bold text-xl text-[#1A1A2E] mb-2">🔑 Reset Password User</h3>
            <p class="text-xs text-slate-600 font-bold mb-4" x-text="'Ubah password untuk akun: ' + (resetUser?.name || '')"></p>

            <form x-bind:action="'/ctrl-twogo-admin/users/' + (resetUser?.id || '') + '/reset-password'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Password Baru</label>
                    <input type="password" name="new_password" placeholder="Minimal 6 karakter" required class="w-full px-3.5 py-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#4361EE]">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="showResetModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#FFE156] hover:bg-[#ffd829] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs cursor-pointer">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fetchUserDetail(userId) {
        fetch('/ctrl-twogo-admin/users/' + userId)
            .then(res => res.json())
            .then(data => {
                const el = document.querySelector('[x-data]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].showResetModal = false;
                    el._x_dataStack[0].selectedUser = data;
                    el._x_dataStack[0].showModal = true;
                }
            });
    }

    function openResetPasswordModal(user) {
        const el = document.querySelector('[x-data]');
        if (el && el._x_dataStack) {
            el._x_dataStack[0].showModal = false;
            el._x_dataStack[0].resetUser = user;
            el._x_dataStack[0].showResetModal = true;
        }
    }
</script>
@endpush
@endsection
