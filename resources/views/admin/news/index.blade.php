@extends('layouts.admin', [
    'title' => 'Manajemen Berita',
    'pageHeader' => 'Manajemen Artikel Berita & Pengumuman',
    'headerBadge' => 'News CMS'
])

@section('content')
<div class="space-y-6">
    
    <!-- Action Header -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Daftar Artikel Berita</h2>
            <p class="text-xs font-bold text-slate-500 mt-0.5">Kelola artikel berita dan kabar pembaruan yang tampil di halaman publik.</p>
        </div>

        <a href="{{ route('admin.news.create') }}" class="px-5 py-2.5 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] cursor-pointer flex items-center gap-1.5">
            <span>+</span>
            <span>Tambah Berita Baru</span>
        </a>
    </div>

    <!-- Table List -->
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-bold">
                <thead class="bg-[#FFE156] border-b-[3px] border-[#1A1A2E] font-heading font-extrabold text-sm text-[#1A1A2E]">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">Judul Berita</th>
                        <th class="p-4">Penulis</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal Terbit</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-slate-100">
                    @forelse($newsList as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-extrabold">{{ $newsList->firstItem() + $index }}</td>
                            <td class="p-4 max-w-xs">
                                <div class="font-heading font-extrabold text-sm text-[#1A1A2E] line-clamp-1">{{ $item->title }}</div>
                                <div class="text-[11px] font-bold text-slate-500 line-clamp-1">{{ $item->excerpt }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-[#FFFBEB] border border-[#1A1A2E] rounded-md font-extrabold">{{ $item->author }}</span>
                            </td>
                            <td class="p-4">
                                @if($item->is_published)
                                    <span class="px-2.5 py-1 bg-[#00D4AA] text-[#1A1A2E] border border-[#1A1A2E] rounded-md font-extrabold">Terbit</span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-200 text-slate-600 border border-slate-400 rounded-md font-extrabold">Draft</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $item->published_at ? $item->published_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('news.show', $item->slug) }}" target="_blank" class="px-2.5 py-1 bg-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-bold text-xs" title="Lihat Artikel">
                                        👁️
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="px-2.5 py-1 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-bold text-xs" title="Edit Artikel">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus artikel berita ini?')" class="px-2.5 py-1 bg-red-500 text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-bold text-xs cursor-pointer" title="Hapus Artikel">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-bold">
                                Belum ada artikel berita. Klik tombol di atas untuk membuat berita baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t-2 border-slate-100">
            {{ $newsList->links('vendor.pagination.neo-brutalism') }}
        </div>
    </div>

</div>
@endsection
