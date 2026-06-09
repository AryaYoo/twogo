<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#FFFBEB">
    <title>TwoGo — Rencana Seru, Bareng-Bareng! 🎒</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFBEB] font-sans text-[#1A1A2E] overflow-x-hidden relative">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-[#FFFBEB] border-b-[3px] border-[#1A1A2E] z-50">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="font-heading font-bold text-xl tracking-tight">TwoGo<span class="text-[#FF6B9D]">.</span></div>
            <div>
                <a href="/login" class="font-bold text-sm hover:underline mr-4">Masuk</a>
                <a href="/register" class="nb-btn nb-btn-primary nb-btn-sm">Daftar</a>
            </div>
        </div>
    </nav>

    <main class="pt-16 pb-20">
        <!-- Hero Section with Fullscreen background covering entire section height -->
        <div class="w-full pt-30 pb-36 border-b-[2px] border-[#1A1A2E]" style="position: relative; overflow: hidden;">
            <!-- Background Image -->
            <img src="{{ asset('assets/images/img1.webp') }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -10;">
            
            <section class="max-w-4xl mx-auto px-4 text-center animate-fade-in-up" style="position: relative; z-index: 10;">
                <div class="inline-block nb-badge nb-badge-pink mb-6 rotate-[-2deg]">
                    ✨ Aplikasi Itinerary #1 buat Berdua
                </div>
                
                <h1 class="text-5xl md:text-7xl font-heading font-bold leading-tight mb-6 tracking-tight relative">
                    Rencana Seru, <br>
                    <span class="bg-[#FFE156] px-2 py-1 inline-block border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rotate-[1deg]">Bareng-Bareng! 🎒</span>
                </h1>
                
                <p class="text-xs md:text-sm font-medium mb-16 mt-3 max-w-fit mx-auto bg-white px-3 py-1 ">
                    Aplikasi perencanaan perjalanan yang bikin liburanmu<br>makin asyik, rapi, dan terorganisir.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/register" class="nb-btn nb-btn-primary nb-btn-lg text-lg w-full sm:w-auto">Mulai Sekarang 🔥</a>
                    <a href="/login" class="nb-btn nb-btn-secondary nb-btn-lg text-lg w-full sm:w-auto">Sudah Punya Akun</a>
                </div>
            </section>
        </div>

        <!-- Features Section -->
        <section class="max-w-4xl mx-auto px-4 py-16 border-b-[3px] border-[#1A1A2E] reveal-on-scroll">
            <h2 class="text-3xl font-heading font-bold mb-10 pb-3 text-center reveal-on-scroll">Fitur yang Bikin Liburan Makin Chill 🌴</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Feature 1 -->
                <div class="nb-card bg-white reveal-on-scroll">
                    <div class="w-12 h-12 bg-[#00D4AA] rounded-full border-2 border-[#1A1A2E] flex items-center justify-center text-2xl mb-4 shadow-[2px_2px_0px_#1A1A2E]">📅</div>
                    <h3 class="text-xl font-bold font-heading mb-2">Timeline Fleksibel</h3>
                    <p class="text-sm font-medium opacity-80">Atur jadwal per hari dengan santai. Pagi, Siang, Malam — tanpa dikejar waktu karena liburan itu butuh chill.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="nb-card bg-[#FFE156] reveal-on-scroll animate-delay-100">
                    <div class="w-12 h-12 bg-white rounded-full border-2 border-[#1A1A2E] flex items-center justify-center text-2xl mb-4 shadow-[2px_2px_0px_#1A1A2E]">💰</div>
                    <h3 class="text-xl font-bold font-heading mb-2">Budget Tracker</h3>
                    <p class="text-sm font-medium opacity-80">Catat pengeluaran bersama. Siapa bayar apa, langsung tercatat rapi dan auto ngitung utang-piutang.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="nb-card bg-[#FF6B9D] reveal-on-scroll">
                    <div class="w-12 h-12 bg-white rounded-full border-2 border-[#1A1A2E] flex items-center justify-center text-2xl mb-4 shadow-[2px_2px_0px_#1A1A2E]">📍</div>
                    <h3 class="text-xl font-bold font-heading mb-2">Wishlist Destinasi</h3>
                    <p class="text-sm font-medium opacity-90">Kumpulkan ide tempat seru di bucket list sebelum finalisasi rencana. Vote mana yang wajib didatangi.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="nb-card bg-[#7B2FF7] reveal-on-scroll animate-delay-100">
                    <div class="w-12 h-12 bg-[#FF8C42] rounded-full border-2 border-[#1A1A2E] flex items-center justify-center text-2xl mb-4 shadow-[2px_2px_0px_#1A1A2E]">📸</div>
                    <h3 class="text-xl font-bold font-heading mb-2">Dokumentasi</h3>
                    <p class="text-sm font-medium opacity-90">Abadikan momen perjalanan. Foto dan catatan tersimpan rapi per trip, jadi kenangan digital yang aesthetic.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-4xl mx-auto px-4 py-16 reveal-on-scroll">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="nb-card bg-[#4361EE] text-center md:text-left p-10 h-full flex flex-col justify-center items-center md:items-start text-white">
                    <h2 class="text-3xl font-heading font-bold mb-4 text-black">Siap untuk Liburan Berikutnya?</h2>
                    <p class="mb-8 font-medium max-w-md mx-auto md:mx-0 text-black opacity-95">Yuk bikin itinerary pertamamu di TwoGo, gratis!</p>
                    <a href="/register" class="nb-btn nb-btn-primary nb-btn-lg text-lg">Buat Trip Sekarang 🚀</a>
                </div>
                <div class="w-full flex justify-center">
                    <img src="{{ asset('assets/images/img2.webp') }}" class="w-full max-w-[350px] md:max-w-full rounded-2xl border-[4px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] transform rotate-[1.5deg] hover:rotate-0 transition-transform duration-200">
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t-[3px] border-[#1A1A2E] bg-white py-8">
        <div class="max-w-4xl mx-auto px-4 text-center font-bold text-sm">
            &copy; 2026 TwoGo. V1.0.2.
        </div>
        <div class="pt-2 max-w-4xl mx-auto px-2 text-center font-light text-sm">
            contact us : adventuretwogo@gmail.com
        </div>
    </footer>
</body>
</html>
