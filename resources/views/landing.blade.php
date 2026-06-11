<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#FFFBEB">
    <title>TwoGo — Rencana Seru, Bareng-Bareng! 🎒</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float 4s ease-in-out 2s infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-15px) rotate(calc(var(--rot, 0deg) + 5deg)); }
            100% { transform: translateY(0px) rotate(var(--rot, 0deg)); }
        }
        .hero-bg-overlay {
            background: linear-gradient(to bottom, rgba(255,251,235,0.2) 0%, rgba(255,251,235,0.9) 100%);
        }
    </style>
</head>
<body class="bg-[#FFFBEB] font-sans text-[#1A1A2E] overflow-x-hidden relative">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-[#FFFBEB] border-b-[3px] border-[#1A1A2E] z-50 transition-all duration-300" id="navbar">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="font-heading font-extrabold text-2xl tracking-tight flex items-center gap-1">
                TwoGo<span class="text-[#FF6B9D] text-3xl leading-none">.</span>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="/login" class="font-bold text-sm hover:text-[#4361EE] transition-colors hidden sm:block">Masuk</a>
                <a href="/login" class="nb-btn nb-btn-secondary nb-btn-sm sm:hidden border-[2px] shadow-[2px_2px_0px_#1A1A2E]">Masuk</a>
                <a href="/register" class="nb-btn nb-btn-primary nb-btn-sm border-[2px] shadow-[2px_2px_0px_#1A1A2E]">Daftar</a>
            </div>
        </div>
    </nav>

    <main class="pt-16">
        <!-- Hero Section -->
        <div class="w-full relative overflow-hidden bg-[#B2F5E4] border-b-[3px] border-[#1A1A2E] min-h-[90vh] flex items-center bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/images/img1.webp') }}');">
            <!-- Subtle gradient overlay to ensure text contrast at the bottom if needed -->
            <div class="absolute inset-0 hero-bg-overlay z-0"></div>

            <!-- Floating Emojis -->
            <div id="emoji-left" class="absolute hidden md:flex items-center justify-center animate-float" style="bottom: 15%; left: 8%; font-size: 5rem; z-index: 5; --rot: -20deg;">😆</div>
            <div id="emoji-right" class="absolute hidden md:flex items-center justify-center animate-float-delayed" style="top: 20%; right: 8%; font-size: 4rem; z-index: 5; --rot: 15deg;">😎</div>
            <div id="emoji-center" class="absolute hidden lg:flex items-center justify-center animate-float" style="top: 15%; left: 20%; font-size: 3.5rem; z-index: 5; --rot: -10deg;">😀</div>

            <section class="max-w-5xl mx-auto px-4 py-12 relative z-10 w-full flex flex-col items-center">
                <!-- Clean Hero Card -->
                <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] md:shadow-[12px_12px_0px_#1A1A2E] rounded-[2rem] p-8 md:p-14 text-center max-w-3xl w-full transform -rotate-1 hover:rotate-0 transition-transform duration-300 relative">
                    <!-- Decorative pins -->
                    <div class="absolute -top-4 -left-4 w-8 h-8 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E]"></div>
                    <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-[#FFE156] border-[3px] border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E]"></div>

                    <div class="inline-block nb-badge nb-badge-pink mb-6 rotate-[-2deg] px-4 py-2 text-xs md:text-sm border-[2px] border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] font-bold">
                        ✨ Aplikasi Itinerary #1 buat Berdua
                    </div>

                    <h1 class="text-4xl md:text-6xl font-heading font-extrabold leading-tight mb-6 tracking-tight text-[#1A1A2E]">
                        Rencana Seru, <br class="hidden sm:block">
                        <span class="bg-[#FFE156] px-4 py-1 mt-2 inline-block border-[4px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rotate-[1deg]">Bareng-Bareng! 🎒</span>
                    </h1>

                    <p class="text-base md:text-lg font-bold mb-10 max-w-xl mx-auto text-[#1A1A2E] opacity-90 leading-relaxed">
                        Aplikasi perencanaan perjalanan yang bikin liburanmu makin asyik, rapi, dan terorganisir tanpa ribet.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="/register" class="nb-btn nb-btn-primary nb-btn-xl text-lg w-full sm:w-auto border-[3px] shadow-[4px_4px_0px_#1A1A2E] hover:shadow-[0px_0px_0px_#1A1A2E] hover:translate-y-[4px] hover:translate-x-[4px] transition-all">
                            Mulai Sekarang 🔥
                        </a>
                        <a href="/login" class="nb-btn nb-btn-secondary nb-btn-xl text-lg w-full sm:w-auto border-[3px] shadow-[4px_4px_0px_#1A1A2E] hover:shadow-[0px_0px_0px_#1A1A2E] hover:translate-y-[4px] hover:translate-x-[4px] transition-all">
                            Sudah Punya Akun
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <!-- Features Section -->
        <section class="max-w-5xl mx-auto px-4 py-20 border-b-[3px] border-[#1A1A2E]">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-heading font-extrabold inline-block relative">
                    <span class="relative z-10">Fitur yang Bikin Liburan Makin Chill 🌴</span>
                    <div class="absolute bottom-1 left-0 w-full h-4 bg-[#00D4AA] z-0 -rotate-1 opacity-50"></div>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Feature 1 -->
                <div class="nb-card bg-white hover:-translate-y-2 border-[3px] shadow-[6px_6px_0px_#1A1A2E] group rounded-2xl">
                    <div class="w-14 h-14 bg-[#00D4AA] rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center text-3xl mb-6 shadow-[3px_3px_0px_#1A1A2E] group-hover:rotate-12 transition-transform">📅</div>
                    <h3 class="text-2xl font-extrabold font-heading mb-3">Timeline Fleksibel</h3>
                    <p class="text-base font-semibold text-[#475569] leading-relaxed">Atur jadwal per hari dengan santai. Pagi, Siang, Malam — tanpa dikejar waktu karena liburan itu butuh chill.</p>
                </div>

                <!-- Feature 2 -->
                <div class="nb-card bg-[#FFE156] hover:-translate-y-2 border-[3px] shadow-[6px_6px_0px_#1A1A2E] group rounded-2xl">
                    <div class="w-14 h-14 bg-white rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center text-3xl mb-6 shadow-[3px_3px_0px_#1A1A2E] group-hover:-rotate-12 transition-transform">💰</div>
                    <h3 class="text-2xl font-extrabold font-heading mb-3">Budget Tracker</h3>
                    <p class="text-base font-semibold text-[#1A1A2E] opacity-90 leading-relaxed">Catat pengeluaran bersama. Siapa bayar apa, langsung tercatat rapi dan auto ngitung utang-piutang.</p>
                </div>

                <!-- Feature 3 -->
                <div class="nb-card bg-[#FF6B9D] hover:-translate-y-2 border-[3px] shadow-[6px_6px_0px_#1A1A2E] group rounded-2xl text-white">
                    <div class="w-14 h-14 bg-white rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center text-3xl mb-6 shadow-[3px_3px_0px_#1A1A2E] group-hover:rotate-12 transition-transform">📍</div>
                    <h3 class="text-2xl font-extrabold font-heading mb-3 text-black">Wishlist Destinasi</h3>
                    <p class="text-base font-semibold text-black opacity-90 leading-relaxed">Kumpulkan ide tempat seru di bucket list sebelum finalisasi rencana. Vote mana yang wajib didatangi.</p>
                </div>

                <!-- Feature 4 -->
                <div class="nb-card bg-[#7B2FF7] hover:-translate-y-2 border-[3px] shadow-[6px_6px_0px_#1A1A2E] group rounded-2xl text-white">
                    <div class="w-14 h-14 bg-[#FF8C42] rounded-full border-[3px] border-[#1A1A2E] flex items-center justify-center text-3xl mb-6 shadow-[3px_3px_0px_#1A1A2E] group-hover:-rotate-12 transition-transform">📸</div>
                    <h3 class="text-2xl font-extrabold font-heading mb-3 text-black">Dokumentasi</h3>
                    <p class="text-base font-semibold text-black opacity-90 leading-relaxed">Abadikan momen perjalanan. Foto dan catatan tersimpan rapi per trip, jadi kenangan digital yang aesthetic.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-5xl mx-auto px-4 py-24">
            <div class="bg-[#4361EE] rounded-[2.5rem] border-[4px] border-[#1A1A2E] shadow-[12px_12px_0px_#1A1A2E] overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 items-center">
                    <div class="p-10 md:p-16 flex flex-col justify-center items-center md:items-start text-center md:text-left">
                        <div class="inline-block nb-badge bg-[#00D4AA] text-[#1A1A2E] mb-6 rotate-[-2deg] px-4 py-2 border-[2px] border-[#1A1A2E] font-bold">
                            Tunggu Apa Lagi?
                        </div>
                        <h2 class="text-3xl md:text-5xl font-heading font-extrabold mb-6 text-white leading-tight">
                            Siap untuk Liburan Berikutnya?
                        </h2>
                        <p class="mb-10 font-semibold text-lg max-w-md text-white opacity-90">
                            Yuk bikin itinerary pertamamu di TwoGo, gratis!
                        </p>
                        <a href="/register" class="nb-btn nb-btn-primary nb-btn-xl text-lg border-[3px] shadow-[4px_4px_0px_#1A1A2E] hover:shadow-[0px_0px_0px_#1A1A2E] hover:translate-y-[4px] hover:translate-x-[4px] transition-all">
                            Buat Trip Sekarang 🚀
                        </a>
                    </div>
                    <div class="w-full flex justify-center items-end bg-[#FFD1E3] h-full p-8 md:p-12 border-t-[4px] md:border-t-0 md:border-l-[4px] border-[#1A1A2E]">
                        <img src="{{ asset('assets/images/img2.webp') }}" alt="Ilustrasi TwoGo" class="w-full max-w-[320px] rounded-2xl border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] transform rotate-[3deg] hover:rotate-0 transition-transform duration-300">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t-[4px] border-[#1A1A2E] bg-white py-10">
        <div class="max-w-5xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="font-heading font-extrabold text-2xl tracking-tight">
                TwoGo<span class="text-[#FF6B9D]">.</span>
            </div>
            <div class="text-center md:text-left font-bold text-sm text-[#475569]">
                &copy; 2026 TwoGo. V1.0.2.
            </div>
            <div class="font-bold text-sm text-[#1A1A2E] border-b-2 border-[#1A1A2E]">
                <a href="mailto:adventuretwogo@gmail.com">adventuretwogo@gmail.com</a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar shadow on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-[0_4px_0px_#1A1A2E]');
            } else {
                navbar.classList.remove('shadow-[0_4px_0px_#1A1A2E]');
            }
        });

        // Parallax effect for emojis
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            const maxScroll = 500;
            const progress = Math.min(scrollY / maxScroll, 1);

            const opacity = Math.max(1 - (progress * 1.5), 0);

            const emojiLeft = document.getElementById('emoji-left');
            if (emojiLeft) {
                emojiLeft.style.transform = `translateY(${-progress * 100}px) translateX(${-progress * 50}px) rotate(${-20 + (-20 * progress)}deg)`;
                emojiLeft.style.opacity = opacity;
            }

            const emojiRight = document.getElementById('emoji-right');
            if (emojiRight) {
                emojiRight.style.transform = `translateY(${-progress * 150}px) translateX(${progress * 50}px) rotate(${15 + (20 * progress)}deg)`;
                emojiRight.style.opacity = opacity;
            }
        });
    </script>
</body>
</html>
