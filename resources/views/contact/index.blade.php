<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak & Kritik Saran — TwoGo 🎒</title>
    
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
                <a href="{{ route('login') }}" class="px-4 py-2 bg-white border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs md:text-sm text-[#1A1A2E]">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Sub-Navbar -->
    <x-sub-navbar />

    <!-- Main Content -->
    <main class="py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4 md:px-8 space-y-10">
            
            <!-- Page Header -->
            <div class="bg-[#FFE156] border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-8 md:p-12 space-y-3">
                <div class="inline-block px-3.5 py-1 bg-white text-[#1A1A2E] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-xs">
                    📞 Hubungi Tim TwoGo
                </div>
                <h1 class="font-heading font-extrabold text-3xl md:text-5xl text-[#1A1A2E]">
                    Kontak, Kritik & Saran 💬
                </h1>
                <p class="font-bold text-sm md:text-base text-[#1A1A2E] opacity-90 max-w-xl">
                    Punya ide, pertanyaan, atau masukan untuk pengembangan TwoGo? Kirimkan pesanmu langsung kepada tim kami!
                </p>
            </div>

            <!-- Toast Messages -->
            @if(session('success'))
                <div class="p-4 bg-[#00D4AA] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl font-bold text-sm text-[#1A1A2E]">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-[#FF6B9D] text-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Grid Content -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Info Card -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-3xl p-6 md:p-8 space-y-6">
                        <h2 class="font-heading font-extrabold text-2xl text-[#1A1A2E]">Informasi Kontak</h2>
                        
                        <div class="space-y-4 font-bold text-sm text-slate-700">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#00D4AA] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center text-xl shrink-0">📧</div>
                                <div>
                                    <div class="font-heading font-extrabold text-xs text-slate-400 uppercase">Email Layanan</div>
                                    <a href="mailto:adventuretwogo@gmail.com" class="text-[#4361EE] hover:underline font-extrabold">adventuretwogo@gmail.com</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#FF6B9D] text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center text-xl shrink-0">📍</div>
                                <div>
                                    <div class="font-heading font-extrabold text-xs text-slate-400 uppercase">Kantor Pengembang</div>
                                    <div>Jakarta & Malang, Indonesia</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#7B2FF7] text-white border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] flex items-center justify-center text-xl shrink-0">⏰</div>
                                <div>
                                    <div class="font-heading font-extrabold text-xs text-slate-400 uppercase">Jam Operasional</div>
                                    <div>Senin - Jumat (09:00 - 18:00 WIB)</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-2xl text-xs font-bold space-y-1">
                            <div class="font-heading font-extrabold text-sm text-[#1A1A2E]">💡 Respon Cepat</div>
                            <div class="text-slate-600">Setiap kritik & saran yang dikirim akan langsung masuk ke Dashboard Moderasi Admin dan dibaca oleh pengembang.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Form Card -->
                <div class="lg:col-span-7">
                    <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 md:p-8 space-y-6">
                        <div>
                            <h2 class="font-heading font-extrabold text-2xl text-[#1A1A2E]">Formulir Kritik & Saran</h2>
                            <p class="text-xs font-bold text-slate-500 mt-1">Sampaikan pesan kamu dengan melengkapi isian di bawah ini.</p>
                        </div>

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Arya Yosua" class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#FFE156]">
                                    @error('name') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Alamat Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#FFE156]">
                                    @error('email') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Subjek / Topik</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Misal: Saran Fitur Tambahan" class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#FFE156]">
                            </div>

                            <div>
                                <label class="block font-bold text-xs text-[#1A1A2E] mb-1">Pesan / Kritik & Saran <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" required placeholder="Tuliskan masukan kamu di sini..." class="w-full px-4 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#FFE156]">{{ old('message') }}</textarea>
                                @error('message') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <!-- Manual Simple Human Verification (Captcha) -->
                            <div class="p-4 bg-[#B2F5E4] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-2xl space-y-2">
                                <div class="flex items-center gap-2 font-heading font-extrabold text-sm text-[#1A1A2E]">
                                    <span>🤖 Verifikasi Manusia (Manual Captcha)</span>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Untuk memastikan kamu bukan bot otomatis, jawab pertanyaan matematika berikut:</p>
                                
                                <div class="flex items-center gap-3 pt-1">
                                    <div class="px-4 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl font-heading font-extrabold text-sm text-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]">
                                        {{ $captchaQuestion }}
                                    </div>
                                    <input type="number" name="captcha" required placeholder="Jawaban..." class="w-32 px-4 py-2 bg-white border-2 border-[#1A1A2E] rounded-xl text-sm font-extrabold focus:outline-none focus:ring-2 focus:ring-[#FFE156]">
                                </div>
                                @error('captcha') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-xl font-heading font-extrabold text-base text-[#1A1A2E] transition-all cursor-pointer">
                                🚀 Kirim Pesan Sekarang
                            </button>
                        </form>
                    </div>
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
