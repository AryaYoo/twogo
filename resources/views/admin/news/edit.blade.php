@extends('layouts.admin', [
    'title' => 'Edit Berita',
    'pageHeader' => 'Edit Artikel Berita',
    'headerBadge' => 'News Editor'
])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6 md:p-8 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b-2 border-slate-200">
            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Formulir Edit Berita</h2>
            <a href="{{ route('admin.news.index') }}" class="px-3.5 py-1.5 bg-slate-100 border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Judul Artikel Berita <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $news->title) }}" required class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Nama Penulis</label>
                    <input type="text" name="author" value="{{ old('author', $news->author) }}" class="w-full px-4 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-xs text-[#1A1A2E]">
                        <input type="checkbox" name="is_published" value="1" {{ $news->is_published ? 'checked' : '' }} class="w-4 h-4 rounded accent-[#1A1A2E]">
                        <span>Publikasikan Artikel (Published)</span>
                    </label>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-2 border-[#1A1A2E] rounded-2xl space-y-3">
                <label class="block font-bold text-xs text-[#1A1A2E]">Gambar Pendukung</label>
                @if($news->image_url)
                    <div class="w-40 aspect-[4/3] rounded-lg border border-slate-300 overflow-hidden bg-slate-100">
                        <img src="{{ asset($news->image_url) }}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full px-3 py-1.5 bg-white border border-[#1A1A2E] rounded-xl text-xs font-bold">
                <span class="text-[10px] font-bold text-slate-500 block">Pilih file baru jika ingin mengganti gambar sebelumnya (Maks 2MB).</span>
            </div>

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Ringkasan Singkat (Excerpt)</label>
                <textarea name="excerpt" rows="2" class="w-full px-4 py-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">{{ old('excerpt', $news->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Isi Lengkap Artikel Berita <span class="text-red-500">*</span></label>
                <textarea name="content" rows="8" required class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold">{{ old('content', $news->content) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E]">
                    💾 Perbarui Berita
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
