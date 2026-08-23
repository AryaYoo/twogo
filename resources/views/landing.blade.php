<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FFFBEB">
    <title>TwoGo — Rencana Seru, Bareng-Bareng! 🎒</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background-color: #FFFBEB;
            color: #1A1A2E;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Marquee Ticker Animation */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }

        .animate-marquee {
            display: inline-flex;
            white-space: nowrap;
            animation: marquee 25s linear infinite;
        }

        .animate-marquee:hover {
            animation-play-state: paused;
        }

        /* Floating animation for hero elements */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .animate-float-slow {
            animation: float-slow 4s ease-in-out infinite;
        }

        /* Neo-Brutalism Scroll Animations */
        .nb-reveal {
            opacity: 0;
            transform: translateY(60px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Slight bounce back effect typical of neo-brutalism */
        }
        .nb-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .nb-reveal-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .nb-reveal-left.is-visible {
            opacity: 1;
            transform: translateX(0);
        }

        .nb-reveal-right {
            opacity: 0;
            transform: translateX(60px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .nb-reveal-right.is-visible {
            opacity: 1;
            transform: translateX(0);
        }

        .nb-reveal-zoom {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .nb-reveal-zoom.is-visible {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
    </style>
</head>
<body class="bg-[#FFFBEB] text-[#1A1A2E] antialiased selection:bg-[#FFE156] selection:text-[#1A1A2E]">

    <!-- Navbar -->
    <nav class="sticky top-0 w-full bg-[#FFFBEB] border-b-[3px] border-[#1A1A2E] z-50">
        <div class="max-w-6xl mx-auto px-4 md:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="font-heading font-extrabold text-2xl md:text-3xl tracking-tight flex items-center gap-1">
                TwoGo<span class="text-[#FF6B9D] text-4xl leading-none">.</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 bg-white hover:bg-slate-100 border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E] transition-all">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E] transition-all">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Sub-Navbar Section -->
    <x-sub-navbar />

    <!-- SECTION 1: HERO SECTION -->
    <section class="py-12 md:py-20 border-b-[3px] border-[#1A1A2E] bg-[#FFFBEB] overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left nb-reveal-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#FF6B9D] text-white border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-full font-bold text-xs md:text-sm">
                        <span>✨</span>
                        <span>{{ $settings['hero_badge'] ?? '✨ Aplikasi Itinerary #1 buat Berdua' }}</span>
                    </div>

                    <h1 class="font-heading font-extrabold text-4xl sm:text-5xl lg:text-6xl text-[#1A1A2E] leading-[1.1] tracking-tight">
                        {{ $settings['hero_title'] ?? 'Rencana Seru, Bareng-Bareng! 🎒' }}
                    </h1>

                    <p class="font-bold text-base md:text-lg text-slate-700 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        {{ $settings['hero_subtitle'] ?? 'Aplikasi perencanaan perjalanan yang bikin liburanmu makin asyik, rapi, dan terorganisir tanpa ribet adu argumen budget.' }}
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[5px_5px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-base md:text-lg text-[#1A1A2E] transition-all text-center">
                            {{ $settings['hero_btn_primary'] ?? 'Mulai Sekarang 🔥' }}
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-100 border-[3px] border-[#1A1A2E] shadow-[5px_5px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-base md:text-lg text-[#1A1A2E] transition-all text-center">
                            {{ $settings['hero_btn_secondary'] ?? 'Sudah Punya Akun' }}
                        </a>
                    </div>
                </div>

                <!-- Right Visual Decorative Card -->
                <div class="lg:col-span-5 relative nb-reveal-zoom delay-100">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Decorative Pins -->
                        <div class="absolute -top-4 -left-4 w-8 h-8 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E] z-20"></div>
                        <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-[#00D4AA] border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E] z-20"></div>

                        <!-- Main Decorative Container -->
                        <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[10px_10px_0px_#1A1A2E] rounded-3xl p-5 md:p-6 space-y-4 animate-float-slow">
                            <!-- Hero Image -->
                            <div class="rounded-2xl border-[3px] border-[#1A1A2E] overflow-hidden relative aspect-[4/3] bg-[#B2F5E4]">
                                <img src="{{ asset('assets/images/img1.webp') }}" alt="Liburan TwoGo" class="w-full h-full object-cover">
                                <div class="absolute top-3 right-3 px-3 py-1 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg font-heading font-extrabold text-xs shadow-[2px_2px_0px_#1A1A2E]">
                                    ⭐ 4.9 Rating
                                </div>
                            </div>

                            <!-- Quick Stats Card Inside Hero -->
                            <div class="p-3.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#7B2FF7] text-white border-2 border-[#1A1A2E] flex items-center justify-center font-extrabold text-lg">
                                        ✈️
                                    </div>
                                    <div>
                                        <div class="font-heading font-extrabold text-sm text-[#1A1A2E]">Liburan Bali 4H3N</div>
                                        <div class="text-xs font-bold text-slate-500">Arya & Yohanes • Active</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-[#00D4AA] border border-[#1A1A2E] rounded-md font-extrabold text-[11px]">Rapi</span>
                            </div>
                        </div>

                        <!-- Floating Badges -->
                        <div class="absolute -bottom-6 -left-6 px-4 py-2 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs hidden sm:block">
                            📍 50+ Destinasi Impian
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: MARQUEE / TICKER BAR -->
    <div class="border-b-[3px] border-[#1A1A2E] bg-[#FFE156] py-3.5 overflow-hidden">
        <div class="animate-marquee font-heading font-extrabold text-sm sm:text-base text-[#1A1A2E] tracking-widest uppercase">
            @php
                $marqueeText = $settings['marquee_destinations'] ?? 'BALI • JOGJA • LOMBOK • RAJA AMPAT • BANDUNG • LABUAN BAJO • MALANG • SURABAYA • UBUD • FLORES';
            @endphp
            <span class="mx-6">{{ $marqueeText }} •</span>
            <span class="mx-6">{{ $marqueeText }} •</span>
            <span class="mx-6">{{ $marqueeText }} •</span>
        </div>
    </div>

    <!-- SECTION 3: FITUR UTAMA -->
    <section class="py-16 md:py-24 border-b-[3px] border-[#1A1A2E] bg-[#FFFBEB]">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3 nb-reveal">
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-[#1A1A2E]">
                    Fitur Utama yang Bikin Liburan Chill 🌴
                </h2>
                <p class="font-bold text-slate-600 text-sm md:text-base">
                    Semua kebutuhan perencanaan trip berdua dirancang simpel, transparan, dan estetik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($features as $f)
                    <div class="p-6 border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl space-y-4 hover:translate-y-[-3px] transition-all nb-reveal {{ 'delay-' . (min($loop->iteration, 4) * 100) }}" style="background-color: {{ $f->bg_color }}; color: {{ $f->text_color }};">
                        <div class="w-12 h-12 bg-white text-[#1A1A2E] rounded-xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] flex items-center justify-center text-2xl font-extrabold">
                            {{ $f->icon }}
                        </div>
                        <h3 class="font-heading font-extrabold text-xl">{{ $f->title }}</h3>
                        <p class="font-bold text-xs md:text-sm leading-relaxed opacity-95">
                            {{ $f->description }}
                        </p>
                    </div>
                @empty
                    <!-- Fallback default features if database empty -->
                    <div class="p-6 bg-[#00D4AA] border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl space-y-4 nb-reveal delay-100">
                        <div class="w-12 h-12 bg-white rounded-xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] flex items-center justify-center text-2xl font-extrabold">📅</div>
                        <h3 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Timeline Fleksibel</h3>
                        <p class="font-bold text-xs md:text-sm text-[#1A1A2E] opacity-90 leading-relaxed">Atur jadwal per hari dengan santai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 4: SHOWCASE APLIKASI -->
    <section class="py-16 md:py-24 border-b-[3px] border-[#1A1A2E] bg-white">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-20">
            
            @forelse($showcases as $index => $sc)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    @if($index % 2 === 0)
                        <!-- Text Left, Card Right -->
                        <div class="lg:col-span-6 space-y-4">
                            @if($sc->section_badge)
                                <div class="inline-block px-3 py-1 text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-xs uppercase" style="background-color: {{ $sc->badge_color }};">
                                    {{ $sc->section_badge }}
                                </div>
                            @endif
                            <h3 class="font-heading font-extrabold text-3xl md:text-4xl text-[#1A1A2E] leading-tight">
                                {{ $sc->title }}
                            </h3>
                            <p class="font-bold text-slate-600 text-sm md:text-base leading-relaxed">
                                {{ $sc->description }}
                            </p>
                            
                            @if(is_array($sc->bullet_points))
                                <ul class="space-y-2.5 text-xs md:text-sm font-bold text-slate-700 pt-2">
                                    @foreach($sc->bullet_points as $point)
                                        <li class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-lg bg-[#00D4AA] border-2 border-[#1A1A2E] flex items-center justify-center font-extrabold text-xs">✓</span>
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="lg:col-span-6">
                            <div class="bg-[#FFFBEB] border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-5 md:p-6 space-y-4">
                                <div class="flex items-center justify-between border-b-2 border-[#1A1A2E] pb-3">
                                    <div class="font-heading font-extrabold text-base text-[#1A1A2E]">📅 Hari 1 — Eksplor Seminyak</div>
                                    <span class="px-2.5 py-1 bg-[#FFE156] border border-[#1A1A2E] rounded-md font-extrabold text-xs">3 Kegiatan</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="p-3.5 bg-white border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md bg-[#00D4AA] border-2 border-[#1A1A2E] flex items-center justify-center text-xs">✓</span>
                                            <div>
                                                <div class="font-bold text-xs md:text-sm text-[#1A1A2E] line-through opacity-70">Sarapan Nasi Campur Bali</div>
                                                <div class="text-[10px] font-bold text-slate-400">08:30 - 09:30</div>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-xs text-emerald-600">+10 XP</span>
                                    </div>
                                    <div class="p-3.5 bg-white border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md bg-slate-100 border-2 border-[#1A1A2E] flex items-center justify-center text-xs"></span>
                                            <div>
                                                <div class="font-bold text-xs md:text-sm text-[#1A1A2E]">Surfing di Pantai Batu Bolong</div>
                                                <div class="text-[10px] font-bold text-slate-500">14:00 - 17:00</div>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-xs text-[#7B2FF7]">Rp 150.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Card Left, Text Right -->
                        <div class="lg:col-span-6 order-2 lg:order-1">
                            <div class="bg-[#FFFBEB] border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-5 md:p-6 space-y-4">
                                <div class="flex items-center justify-between border-b-2 border-[#1A1A2E] pb-3">
                                    <div>
                                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Total Pengeluaran</div>
                                        <div class="font-heading font-extrabold text-2xl text-[#7B2FF7]">Rp 2.450.000</div>
                                    </div>
                                    <span class="px-3 py-1 bg-[#00D4AA] border-2 border-[#1A1A2E] rounded-lg font-extrabold text-xs">Auto Split</span>
                                </div>

                                <div class="p-4 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl space-y-1">
                                    <div class="text-xs font-extrabold text-[#1A1A2E]">🤝 Ringkasan Utang-Piutang</div>
                                    <div class="text-xs font-bold text-[#1A1A2E]">
                                        <b>Yohanes</b> harus bayar ke <b>Arya</b> sebesar <span class="underline font-extrabold">Rp 350.000</span>
                                    </div>
                                </div>

                                <div class="space-y-2 text-xs font-bold">
                                    <div class="p-3 bg-white border-2 border-[#1A1A2E] rounded-xl flex justify-between">
                                        <span>🏨 Hotel Seminyak (Dibayar Arya)</span>
                                        <span class="font-extrabold text-[#1A1A2E]">Rp 1.200.000</span>
                                    </div>
                                    <div class="p-3 bg-white border-2 border-[#1A1A2E] rounded-xl flex justify-between">
                                        <span>🍽️ Makan Malam Seafood (Dibayar Yohanes)</span>
                                        <span class="font-extrabold text-[#1A1A2E]">Rp 500.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-6 space-y-4 order-1 lg:order-2">
                            @if($sc->section_badge)
                                <div class="inline-block px-3 py-1 text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-xs uppercase" style="background-color: {{ $sc->badge_color }};">
                                    {{ $sc->section_badge }}
                                </div>
                            @endif
                            <h3 class="font-heading font-extrabold text-3xl md:text-4xl text-[#1A1A2E] leading-tight">
                                {{ $sc->title }}
                            </h3>
                            <p class="font-bold text-slate-600 text-sm md:text-base leading-relaxed">
                                {{ $sc->description }}
                            </p>
                            
                            @if(is_array($sc->bullet_points))
                                <ul class="space-y-2.5 text-xs md:text-sm font-bold text-slate-700 pt-2">
                                    @foreach($sc->bullet_points as $point)
                                        <li class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-lg bg-[#FFE156] border-2 border-[#1A1A2E] flex items-center justify-center font-extrabold text-xs">✓</span>
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
            @endforelse

        </div>
    </section>

    <!-- SECTION 5: ANGKA PENCAPAIAN (STATS) -->
    <section class="py-16 md:py-20 border-b-[3px] border-[#1A1A2E] bg-[#FFFBEB]">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-10">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-[#1A1A2E]">
                    Pencapaian TwoGo Dalam Angka 🚀
                </h2>
                <p class="font-bold text-slate-600 text-sm">
                    Angka nyata dari komunitas pengguna pelancong TwoGo.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($stats as $st)
                    <div class="p-6 border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl text-center space-y-2" style="background-color: {{ $st->bg_color }}; color: {{ $st->text_color }};">
                        <div class="font-heading font-extrabold text-4xl md:text-5xl">{{ $st->number }}</div>
                        <div class="font-extrabold text-xs md:text-sm uppercase tracking-wider">{{ $st->label }}</div>
                    </div>
                @empty
                    <div class="p-6 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl text-center space-y-2">
                        <div class="font-heading font-extrabold text-4xl md:text-5xl text-[#1A1A2E]">15.000+</div>
                        <div class="font-extrabold text-xs md:text-sm text-[#1A1A2E] uppercase tracking-wider">Itinerary Dibuat</div>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- SECTION 6: TESTIMONIAL SECTION -->
    <section class="py-16 md:py-24 border-b-[3px] border-[#1A1A2E] bg-white">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-[#1A1A2E]">
                    Apa Kata Pengguna TwoGo? 💬
                </h2>
                <p class="font-bold text-slate-600 text-sm md:text-base">
                    Pengalaman nyata pelancong yang udah nyobain liburan rapi bareng TwoGo.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($testimonials as $t)
                    <div class="p-6 md:p-8 border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl space-y-6 flex flex-col justify-between text-[#1A1A2E]" style="background-color: {{ $t->bg_color }};">
                        <p class="font-bold text-base md:text-lg leading-relaxed italic">
                            "{{ $t->quote }}"
                        </p>
                        <div class="flex items-center gap-3 pt-4 border-t-2 border-[#1A1A2E]">
                            <div class="w-12 h-12 rounded-2xl bg-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center font-extrabold text-lg">
                                {{ $t->avatar_emoji }}
                            </div>
                            <div>
                                <div class="font-heading font-extrabold text-base">{{ $t->user_name }}</div>
                                <div class="text-xs font-bold text-slate-600">{{ $t->user_tier }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>

        </div>
    </section>

    <!-- SECTION 7: CTA AKHIR + FOOTER -->
    <section class="py-16 md:py-20 bg-[#FFFBEB]">
        <div class="max-w-5xl mx-auto px-4 md:px-8">
            <div class="bg-[#4361EE] border-[4px] border-[#1A1A2E] shadow-[12px_12px_0px_#1A1A2E] rounded-3xl p-8 md:p-14 text-center space-y-6 text-white nb-reveal-zoom">
                <div class="inline-block px-4 py-1.5 bg-[#00D4AA] text-[#1A1A2E] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-full font-heading font-extrabold text-xs md:text-sm">
                    {{ $settings['cta_badge'] ?? 'Tunggu Apa Lagi? 🎒' }}
                </div>

                <h2 class="font-heading font-extrabold text-3xl md:text-5xl text-white leading-tight">
                    {{ $settings['cta_title'] ?? 'Siap untuk Liburan Berikutnya?' }}
                </h2>

                <p class="font-bold text-base md:text-lg text-white opacity-95 max-w-lg mx-auto">
                    {{ $settings['cta_subtitle'] ?? 'Yuk bikin itinerary pertamamu di TwoGo secara gratis!' }}
                </p>

                <div class="pt-2">
                    <a href="{{ route('register') }}" style="color: #1A1A2E !important;" class="inline-block px-8 py-4 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[5px_5px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-base md:text-lg transition-all">
                        {{ $settings['cta_btn'] ?? 'Buat Trip Sekarang 🚀' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t-[4px] border-[#1A1A2E] bg-white py-12">
        <div class="max-w-6xl mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Col 1: Brand -->
            <div class="md:col-span-5 space-y-3">
                <div class="font-heading font-extrabold text-2xl md:text-3xl tracking-tight flex items-center gap-1">
                    TwoGo<span class="text-[#FF6B9D] text-4xl leading-none">.</span>
                </div>
                <p class="font-bold text-xs md:text-sm text-slate-600 max-w-sm">
                    {{ $settings['footer_tagline'] ?? 'Rencana Seru, Bareng-Bareng! Aplikasi itinerary & budget tracker perjalanan #1 di Indonesia.' }}
                </p>
            </div>

            <!-- Col 2: Navigation Links -->
            <div class="md:col-span-4 space-y-2 text-xs md:text-sm font-bold text-slate-700">
                <div class="font-heading font-extrabold text-sm text-[#1A1A2E] uppercase tracking-wider mb-2">Navigasi</div>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <a href="{{ route('landing') }}" class="hover:text-[#4361EE] transition-colors">Beranda</a>
                    <a href="{{ route('login') }}" class="hover:text-[#4361EE] transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="hover:text-[#4361EE] transition-colors">Daftar</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-[#4361EE] transition-colors text-purple-700 font-extrabold">Admin Panel</a>
                </div>
            </div>

            <!-- Col 3: Contact & Copyright -->
            <div class="md:col-span-3 space-y-2 text-xs font-bold text-slate-600 md:text-right">
                <div class="font-heading font-extrabold text-sm text-[#1A1A2E] uppercase tracking-wider mb-2">Hubungi Kami</div>
                <div>📧 <a href="mailto:{{ $settings['footer_email'] ?? 'adventuretwogo@gmail.com' }}" class="underline hover:text-[#4361EE]">{{ $settings['footer_email'] ?? 'adventuretwogo@gmail.com' }}</a></div>
                <div class="pt-2 text-slate-500">&copy; 2026 TwoGo. All rights reserved.</div>
            </div>

        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, observerOptions);

            const animElements = document.querySelectorAll('.nb-reveal, .nb-reveal-left, .nb-reveal-right, .nb-reveal-zoom');
            animElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
