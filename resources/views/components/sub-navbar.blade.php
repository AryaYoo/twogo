<div class="w-full bg-white border-b-[3px] border-[#1A1A2E] py-2.5">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex items-center justify-center gap-2 md:gap-3 overflow-x-auto text-xs md:text-sm font-heading font-extrabold scrollbar-none">
        
        <!-- Link 1: Utama -->
        <a href="{{ route('landing') }}" 
           class="px-4 py-1.5 rounded-xl border-2 border-[#1A1A2E] transition-all whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('landing') ? 'bg-[#FFE156] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-white hover:bg-[#FFFBEB] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' }}">
            <span>🏠</span>
            <span>Utama</span>
        </a>

        <!-- Link 2: Berita -->
        <a href="{{ route('news.index') }}" 
           class="px-4 py-1.5 rounded-xl border-2 border-[#1A1A2E] transition-all whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('news.*') ? 'bg-[#FFE156] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-white hover:bg-[#FFFBEB] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' }}">
            <span>📰</span>
            <span>Berita</span>
        </a>

        <!-- Link 3: Kontak -->
        <a href="{{ route('contact.index') }}" 
           class="px-4 py-1.5 rounded-xl border-2 border-[#1A1A2E] transition-all whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('contact.*') ? 'bg-[#FFE156] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-white hover:bg-[#FFFBEB] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' }}">
            <span>📞</span>
            <span>Kontak</span>
        </a>

        <!-- Link 4: Photobooth Digital -->
        <a href="{{ route('photobooth.index') }}" 
           class="px-4 py-1.5 rounded-xl border-2 border-[#1A1A2E] transition-all whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('photobooth.*') ? 'bg-[#FFE156] text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]' : 'bg-[#FF6B9D] hover:bg-[#ff528c] text-white shadow-[2px_2px_0px_#1A1A2E]' }}">
            <span>📸</span>
            <span>Photobooth Digital</span>
            <span class="px-1.5 py-0.5 bg-[#FFE156] text-[#1A1A2E] text-[10px] rounded-md border border-[#1A1A2E] animate-pulse">BARU</span>
        </a>

    </div>
</div>
