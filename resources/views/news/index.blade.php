<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Pengumuman — TwoGo 🎒</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFBEB] text-[#1A1A2E] antialiased selection:bg-[#FFE156] selection:text-[#1A1A2E] font-sans">

    <!-- Header Navbar -->
    <nav class="sticky top-0 w-full bg-[#FFFBEB] border-b-[3px] border-[#1A1A2E] z-50">
        <div class="max-w-6xl mx-auto px-4 md:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="font-heading font-extrabold text-2xl md:text-3xl tracking-tight flex items-center gap-1">
                TwoGo<span class="text-[#FF6B9D] text-4xl leading-none">.</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 bg-white hover:bg-slate-100 border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Sub-Navbar -->
    <x-sub-navbar />

    <!-- Main Content -->
    <main class="py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-10">
            
            <!-- Page Header -->
            <div class="bg-[#00D4AA] border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-8 md:p-12 space-y-3">
                <div class="inline-block px-3.5 py-1 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-xs">
                    📰 Kabar Terbaru TwoGo
                </div>
                <h1 class="font-heading font-extrabold text-3xl md:text-5xl text-[#1A1A2E]">
                    Berita & Pengumuman 📣
                </h1>
                <p class="font-bold text-sm md:text-base text-[#1A1A2E] opacity-90 max-w-xl">
                    Dapatkan kabar terbaru seputar pembaruan fitur TwoGo, tips liburan hemat, dan pengumuman komunitas pelancong.
                </p>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($newsList as $news)
                    <article class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] hover:translate-y-[-4px] hover:shadow-[8px_8px_0px_#1A1A2E] transition-all rounded-2xl p-6 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                                <span>📅 {{ $news->published_at ? $news->published_at->format('d M Y') : $news->created_at->format('d M Y') }}</span>
                                <span class="px-2.5 py-0.5 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] rounded-md font-extrabold">{{ $news->author }}</span>
                            </div>
                            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E] leading-snug hover:text-[#4361EE] transition-colors">
                                <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                            </h2>
                            <p class="font-bold text-xs md:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $news->excerpt }}
                            </p>
                        </div>

                        <div class="pt-4 border-t-2 border-slate-100 flex items-center justify-between">
                            <a href="{{ route('news.show', $news->slug) }}" class="inline-flex items-center gap-1 font-heading font-extrabold text-xs text-[#4361EE] hover:underline">
                                <span>Baca Selengkapnya</span>
                                <span>→</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full p-12 bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl text-center space-y-3">
                        <span class="text-4xl">📰</span>
                        <h3 class="font-heading font-extrabold text-xl">Belum ada berita dipublikasikan</h3>
                        <p class="text-xs font-bold text-slate-500">Silakan kembali lagi nanti untuk melihat berita terbaru.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $newsList->links('vendor.pagination.neo-brutalism') }}
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t-[4px] border-[#1A1A2E] bg-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-600">
            <div>TwoGo &copy; 2026. All rights reserved.</div>
            <div>📧 <a href="mailto:adventuretwogo@gmail.com" class="underline">adventuretwogo@gmail.com</a></div>
        </div>
    </footer>

</body>
</html>
