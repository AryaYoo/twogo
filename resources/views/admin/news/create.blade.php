@extends('layouts.admin', [
    'title' => 'Tambah Berita Baru',
    'pageHeader' => 'Buat Artikel Berita Baru',
    'headerBadge' => 'News Editor'
])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6 md:p-8 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b-2 border-slate-200">
            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Formulir Berita Baru</h2>
            <a href="{{ route('admin.news.index') }}" class="px-3.5 py-1.5 bg-slate-100 border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-xl font-bold text-xs">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Judul Artikel Berita <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul artikel..." class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Nama Penulis</label>
                    <input type="text" name="author" value="{{ old('author', 'Tim TwoGo') }}" class="w-full px-4 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
                </div>

                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer font-extrabold text-xs text-[#1A1A2E]">
                        <input type="checkbox" name="is_published" value="1" checked class="w-4 h-4 rounded accent-[#1A1A2E]">
                        <span>Langsung Publikasikan (Publish)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Gambar Pendukung (Maks 2MB)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">
            </div>

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Ringkasan Singkat (Excerpt)</label>
                <textarea name="excerpt" rows="2" placeholder="Ringkasan singkat yang muncul di daftar berita..." class="w-full px-4 py-2.5 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-xl text-sm font-bold">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Isi Lengkap Artikel Berita <span class="text-red-500">*</span></label>
                <textarea name="content" rows="8" required placeholder="Tuliskan isi berita lengkap di sini..." class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold">{{ old('content') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E]">
                    💾 Simpan Berita
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
