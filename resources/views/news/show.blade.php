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
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-8">
            
            <!-- Back Button -->
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] hover:bg-slate-100 transition-all">
                <span>←</span>
                <span>Kembali ke Daftar Berita</span>
            </a>

            <!-- Two-Column Grid layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: News Body (approx. 70%) -->
                <div class="lg:col-span-8 space-y-6">
                    <article class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 md:p-10 space-y-6">
                        
                        <!-- Metadata Info -->
                        <div class="space-y-3 border-b-2 border-slate-200 pb-5">
                            <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                                <span class="px-3 py-1 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] rounded-md font-extrabold">✍️ {{ $news->author }}</span>
                                <span>📅 {{ $news->published_at ? $news->published_at->format('d F Y') : $news->created_at->format('d F Y') }}</span>
                            </div>

                            <h1 class="font-heading font-extrabold text-3xl md:text-4xl lg:text-5xl text-[#1A1A2E] leading-tight">
                                {{ $news->title }}
                            </h1>
                        </div>

                        <!-- Supporting Image under title -->
                        @if($news->image_url)
                            <div class="w-full aspect-[21/9] border-[3px] border-[#1A1A2E] rounded-2xl overflow-hidden mb-6 bg-slate-100 shadow-[4px_4px_0px_#1A1A2E]">
                                <img src="{{ asset($news->image_url) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <!-- Main news body text -->
                        <div class="prose prose-lg max-w-none font-bold text-slate-700 leading-relaxed space-y-4">
                            {!! nl2br(e($news->content)) !!}
                        </div>
                    </article>
                </div>

                <!-- Right Column: Sidebar Widgets (approx. 30%) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Widget 1: Dynamic HTML/CSS Ad Injection -->
                    @if(isset($settings['news_sidebar_ad_html']) && !empty($settings['news_sidebar_ad_html']))
                        {!! $settings['news_sidebar_ad_html'] !!}
                    @endif

                    <!-- Widget 2: Recommended Articles -->
                    @if($recentNews->count() > 0)
                        <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-3xl p-6 space-y-4">
                            <h3 class="font-heading font-extrabold text-lg text-[#1A1A2E] border-b-2 border-slate-100 pb-2">
                                Rekomendasi Berita 📰
                            </h3>
                            <div class="space-y-4">
                                @foreach($recentNews as $item)
                                    <a href="{{ route('news.show', $item->slug) }}" class="flex gap-3 items-start group">
                                        @if($item->image_url)
                                            <div class="w-16 h-16 rounded-xl border-2 border-[#1A1A2E] overflow-hidden bg-slate-100 shrink-0 shadow-[2px_2px_0px_#1A1A2E]">
                                                <img src="{{ asset($item->image_url) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-xl border-2 border-[#1A1A2E] bg-[#FFE156] flex items-center justify-center shrink-0 shadow-[2px_2px_0px_#1A1A2E] font-extrabold text-lg">
                                                📰
                                            </div>
                                        @endif
                                        <div class="space-y-1">
                                            <div class="text-[10px] font-bold text-slate-500">
                                                {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                                            </div>
                                            <div class="font-heading font-bold text-xs text-[#1A1A2E] line-clamp-2 group-hover:text-[#4361EE] transition-colors leading-tight">
                                                {{ $item->title }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

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
