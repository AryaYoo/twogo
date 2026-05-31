@extends('layouts.app')
@section('title', 'Dokumentasi Trip')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Kenangan 📸</h1>
        <p class="text-xs font-medium opacity-80 truncate">{{ $trip->title }}</p>
    </div>
    <button onclick="openModal('addPhotoModal')" class="nb-btn nb-btn-primary nb-btn-sm whitespace-nowrap">
        + Tambah
    </button>
</div>
@endsection

@section('content')
<div class="columns-2 gap-3 space-y-3 pb-8">
    @forelse($documents as $doc)
        <div class="break-inside-avoid">
            <x-card class="bg-white p-2 relative group overflow-hidden">
                <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Hapus kenangan ini?');" class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold border-2 border-[#1A1A2E]">&times;</button>
                </form>
                
                @if($doc->type === 'photo')
                    <div class="w-full h-auto bg-gray-100 border-2 border-[#1A1A2E] rounded-md mb-2 overflow-hidden">
                        <img src="{{ Storage::url($doc->file_path) }}" alt="Photo" class="w-full object-cover">
                    </div>
                @else
                    <div class="w-full bg-[#FFE156] border-2 border-[#1A1A2E] rounded-md mb-2 p-3 pb-6 relative">
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-6 h-2 bg-gray-300 border-2 border-[#1A1A2E] rounded-full"></div>
                        <p class="text-sm font-medium font-sans leading-relaxed">{{ $doc->content }}</p>
                    </div>
                @endif
                
                @if($doc->caption)
                    <p class="text-xs font-bold mb-1">{{ $doc->caption }}</p>
                @endif
                
                <div class="flex justify-between items-center text-[10px] opacity-70 font-bold">
                    <span>{{ $doc->user->name }}</span>
                    <span>{{ $doc->created_at->format('d M y') }}</span>
                </div>
            </x-card>
        </div>
    @empty
        <div class="col-span-2 break-inside-avoid">
            <x-empty-state 
                icon="📸" 
                title="Belum ada foto/catatan" 
                description="Abadikan setiap momen indah liburanmu di sini."
            >
                <div class="flex gap-2 mt-4 justify-center w-full">
                    <button onclick="openModal('addPhotoModal')" class="nb-btn nb-btn-primary nb-btn-sm flex-1 justify-center">📷 Upload Foto</button>
                    <button onclick="openModal('addNoteModal')" class="nb-btn nb-btn-pink nb-btn-sm flex-1 justify-center">📝 Buat Catatan</button>
                </div>
            </x-empty-state>
        </div>
    @endforelse
</div>

<!-- Add Photo Modal -->
<x-modal id="addPhotoModal" title="Upload Foto">
    <form action="{{ route('documents.store', $trip) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="photo">
        
        <div class="nb-form-group">
            <label class="nb-label">Pilih Foto <span class="text-red-500">*</span></label>
            <input type="file" name="photo" class="nb-input" accept="image/*" required>
        </div>
        
        <x-input name="caption" label="Caption Singkat (Opsional)" placeholder="Sunset di pantai..." />
        
        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full">Upload Foto</x-button>
        </div>
    </form>
</x-modal>

<!-- Add Note Modal -->
<x-modal id="addNoteModal" title="Buat Catatan Jurnal">
    <form action="{{ route('documents.store', $trip) }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="note">
        
        <x-input type="textarea" name="content" label="Isi Catatan" placeholder="Hari ini seru banget karena..." required="true" />
        <x-input name="caption" label="Judul Singkat (Opsional)" placeholder="Hari Pertama" />
        
        <div class="mt-6">
            <x-button type="submit" variant="pink" class="w-full">Simpan Catatan</x-button>
        </div>
    </form>
</x-modal>
@endsection
