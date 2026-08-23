@extends('layouts.admin', [
    'title' => 'Kritik & Saran',
    'pageHeader' => 'Daftar Pesan Kritik & Saran',
    'headerBadge' => 'Inbox Feedback'
])

@section('content')
<div class="space-y-6">
    
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b-2 border-slate-200">
            <div>
                <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Pesan Kritik & Saran Masuk</h2>
                <p class="text-xs font-bold text-slate-500 mt-1">Daftar masukan yang dikirim oleh pengguna melalui formulir kontak.</p>
            </div>
            <span class="px-3 py-1 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-extrabold text-xs">
                Total {{ $feedbacks->total() }} Pesan
            </span>
        </div>

        <div class="space-y-4">
            @forelse($feedbacks as $fb)
                <div class="p-5 border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl space-y-3 transition-all {{ $fb->is_read ? 'bg-white opacity-80' : 'bg-[#FFFBEB]' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#00D4AA] border-2 border-[#1A1A2E] flex items-center justify-center font-extrabold text-sm text-[#1A1A2E]">
                                👤
                            </div>
                            <div>
                                <div class="font-heading font-extrabold text-sm text-[#1A1A2E]">{{ $fb->name }}</div>
                                <div class="text-xs font-bold text-[#4361EE]">{{ $fb->email }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-bold">
                            <span class="text-slate-500">📅 {{ $fb->created_at->format('d M Y, H:i') }}</span>
                            <form action="{{ route('admin.feedback.read', $fb) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-md border border-[#1A1A2E] font-extrabold text-[11px] cursor-pointer {{ $fb->is_read ? 'bg-slate-200 text-slate-600' : 'bg-[#FFE156] text-[#1A1A2E]' }}">
                                    {{ $fb->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca ✓' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.feedback.destroy', $fb) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus pesan kritik ini?')" class="px-2.5 py-1 bg-red-500 text-white rounded-md border border-[#1A1A2E] font-extrabold text-[11px] cursor-pointer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="font-heading font-extrabold text-sm text-[#1A1A2E]">📌 Subjek: {{ $fb->subject ?? 'Kritik & Saran' }}</div>
                        <p class="font-bold text-xs text-slate-700 leading-relaxed bg-white/70 p-3 rounded-xl border border-slate-200">
                            {{ $fb->message }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500 font-bold">
                    Belum ada pesan kritik & saran yang masuk.
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $feedbacks->links('vendor.pagination.neo-brutalism') }}
        </div>
    </div>

</div>
@endsection
