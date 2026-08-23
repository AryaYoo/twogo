<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} — TwoGo News 🎒</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                <a href="{{ route('login') }}" class="px-4 py-2 bg-white border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Sub-Navbar -->
    <x-sub-navbar />

    <!-- Article Content -->
    <main class="py-12 md:py-16">
        <div class="max-w-4xl mx-auto px-4 md:px-8 space-y-8">
            
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] hover:bg-slate-100 transition-all">
                <span>←</span>
                <span>Kembali ke Daftar Berita</span>
            </a>

            <article class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 md:p-12 space-y-6">
                <div class="space-y-3 border-b-2 border-slate-200 pb-6">
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                        <span class="px-3 py-1 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] rounded-md font-extrabold">✍️ {{ $news->author }}</span>
                        <span>📅 {{ $news->published_at ? $news->published_at->format('d F Y') : $news->created_at->format('d F Y') }}</span>
                    </div>

                    <h1 class="font-heading font-extrabold text-3xl md:text-5xl text-[#1A1A2E] leading-tight">
                        {{ $news->title }}
                    </h1>
                </div>

                <div class="prose prose-lg max-w-none font-bold text-slate-700 leading-relaxed space-y-4">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </article>

            @if($recentNews->count() > 0)
                <div class="space-y-4 pt-6">
                    <h2 class="font-heading font-extrabold text-2xl text-[#1A1A2E]">Berita Lainnya 📣</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($recentNews as $item)
                            <a href="{{ route('news.show', $item->slug) }}" class="p-4 bg-white border-2 border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-2xl hover:translate-y-[-2px] transition-all space-y-2 block">
                                <div class="text-[10px] font-bold text-slate-500">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="font-heading font-extrabold text-sm text-[#1A1A2E] line-clamp-2">{{ $item->title }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

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
