<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photobooth Digital — TwoGo 📸</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
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

    <!-- Main Photobooth Application -->
    <main class="py-10 md:py-14" x-data="photoboothApp()">
        <div class="max-w-5xl mx-auto px-4 md:px-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Camera Viewport & Capture -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 space-y-4 relative">
                        
                        <!-- Header status -->
                        <div class="flex items-center justify-between">
                            <div class="font-heading font-extrabold text-sm text-[#1A1A2E] flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" :class="isCameraActive ? 'bg-emerald-500 animate-ping' : 'bg-red-500'"></span>
                                <span x-text="isCameraActive ? 'Kamera Aktif' : 'Kamera Mati'"></span>
                            </div>
                            <span class="text-xs font-bold text-slate-500">HTML5 WebRTC</span>
                        </div>

                        <!-- Video / Captured Preview Area -->
                        <div class="relative w-full aspect-[4/3] bg-[#1A1A2E] border-[3px] border-[#1A1A2E] rounded-2xl overflow-hidden flex items-center justify-center">
                            
                            <!-- Video Stream -->
                            <video x-ref="videoElement" autoplay playsinline class="w-full h-full object-cover" x-show="!hasCaptured"></video>
                            
                            <!-- Countdown Overlay -->
                            <div x-show="countdown > 0" class="absolute inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-30" style="display: none;">
                                <span x-text="countdown" class="font-heading font-extrabold text-8xl text-[#FFE156] animate-bounce drop-shadow-[4px_4px_0px_#1A1A2E]"></span>
                            </div>

                            <!-- Live Frame Overlay Preview on Video -->
                            <div x-show="!hasCaptured" class="absolute inset-0 pointer-events-none z-10">
                                <!-- Template 1 Overlay -->
                                <template x-if="selectedTemplate === 1">
                                    <div class="w-full h-full border-[14px] border-[#FFE156] flex flex-col justify-between p-3 relative">
                                        <div class="flex justify-between items-start">
                                            <span class="px-2.5 py-1 bg-[#FF6B9D] text-white border-2 border-[#1A1A2E] font-heading font-extrabold text-[10px] rounded-md shadow-[2px_2px_0px_#1A1A2E]">✨ TwoGo Holiday Vibes</span>
                                            <span class="w-6 h-6 bg-[#00D4AA] border-2 border-[#1A1A2E] rounded-full shadow-[2px_2px_0px_#1A1A2E]"></span>
                                        </div>
                                        <div class="bg-white/90 border-2 border-[#1A1A2E] p-2 rounded-xl text-center shadow-[2px_2px_0px_#1A1A2E]">
                                            <div class="font-heading font-extrabold text-xs text-[#1A1A2E]">Rencana Seru, Bareng-Bareng! 🎒</div>
                                            <div class="text-[9px] font-bold text-slate-500">📍 TwoGo Digital Moment • 2026</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Template 2 Overlay -->
                                <template x-if="selectedTemplate === 2">
                                    <div class="w-full h-full border-[14px] border-[#00D4AA] flex flex-col justify-between p-3 relative">
                                        <div class="flex justify-between items-start">
                                            <span class="px-2.5 py-1 bg-[#4361EE] text-white border-2 border-[#1A1A2E] font-heading font-extrabold text-[10px] rounded-md shadow-[2px_2px_0px_#1A1A2E]">APPROVED ✈️ OFFICIAL PASSPORT</span>
                                            <span class="px-2 py-0.5 bg-[#FFE156] text-[#1A1A2E] border border-[#1A1A2E] font-extrabold text-[9px] rounded">TwoGo Stamp</span>
                                        </div>
                                        <div class="bg-[#1A1A2E] text-[#FFE156] border-2 border-[#1A1A2E] p-2 rounded-xl text-center shadow-[2px_2px_0px_#FFE156]">
                                            <div class="font-heading font-extrabold text-xs">BALI • JOGJA • LOMBOK • RAJA AMPAT</div>
                                            <div class="text-[9px] font-bold text-slate-300">EXPLORER BADGE 🌟</div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Final Rendered Canvas (When Captured) -->
                            <canvas x-ref="canvasElement" class="w-full h-full object-contain" x-show="hasCaptured"></canvas>

                            <!-- Placeholder when camera disabled -->
                            <div x-show="!isCameraActive && !hasCaptured" class="text-center text-white space-y-3 p-6">
                                <span class="text-5xl">📷</span>
                                <div class="font-heading font-extrabold text-lg">Kamera Belum Aktif</div>
                                <p class="text-xs text-slate-300">Izinkan akses kamera di browser kamu untuk memulai Photobooth.</p>
                                <button @click="initCamera()" class="px-5 py-2.5 bg-[#FFE156] text-[#1A1A2E] border-2 border-white rounded-xl font-heading font-extrabold text-xs shadow-[3px_3px_0px_#000] cursor-pointer">
                                    ▶️ Izinkan & Aktifkan Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-4 pt-2">
                            <template x-if="!hasCaptured">
                                <button @click="startCountdown()" :disabled="!isCameraActive || countdown > 0" class="w-full py-3.5 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-2xl font-heading font-extrabold text-sm text-[#1A1A2E] transition-all cursor-pointer flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span>📸</span>
                                    <span>Ambil Foto (Timer 3 Detik)</span>
                                </button>
                            </template>

                            <template x-if="hasCaptured">
                                <div class="w-full flex items-center gap-3">
                                    <button @click="resetPhoto()" class="w-1/2 py-3 bg-slate-200 hover:bg-slate-300 border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] cursor-pointer">
                                        🔄 Foto Ulang
                                    </button>
                                    <button @click="downloadPhoto()" class="w-1/2 py-3 bg-[#00D4AA] hover:bg-[#00be98] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] cursor-pointer flex items-center justify-center gap-1.5">
                                        <span>⬇️</span>
                                        <span>Download Foto PNG</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Template Selector & Instructions -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-white border-[4px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 space-y-6">
                        <div>
                            <h2 class="font-heading font-extrabold text-xl text-[#1A1A2E]">Pilih Bingkai Frame TwoGo</h2>
                            <p class="text-xs font-bold text-slate-500 mt-1">Pilih desain template Neo-Brutalism favorit kamu:</p>
                        </div>

                        <!-- Template 1 Button Option -->
                        <div @click="setTemplate(1)" :class="selectedTemplate === 1 ? 'bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E]' : 'bg-[#FFFBEB] border-2 border-slate-300 hover:border-[#1A1A2E]'" class="p-4 rounded-2xl cursor-pointer transition-all space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-extrabold text-sm text-[#1A1A2E]">Template #1 — Holiday Polaroid 🎒</span>
                                <span x-show="selectedTemplate === 1" class="px-2 py-0.5 bg-[#1A1A2E] text-white text-[10px] rounded font-bold">Terpilih ✓</span>
                            </div>
                            <p class="text-xs font-bold text-slate-600">Bingkai Krem Kuning & Pink cerah lengkap dengan stiker TwoGo Holiday Vibes & pin dekoratif.</p>
                        </div>

                        <!-- Template 2 Button Option -->
                        <div @click="setTemplate(2)" :class="selectedTemplate === 2 ? 'bg-[#00D4AA] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E]' : 'bg-[#FFFBEB] border-2 border-slate-300 hover:border-[#1A1A2E]'" class="p-4 rounded-2xl cursor-pointer transition-all space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-extrabold text-sm text-[#1A1A2E]">Template #2 — Passport Stamp ✈️</span>
                                <span x-show="selectedTemplate === 2" class="px-2 py-0.5 bg-[#1A1A2E] text-white text-[10px] rounded font-bold">Terpilih ✓</span>
                            </div>
                            <p class="text-xs font-bold text-slate-600">Bingkai Mint & Biru bertema paspor perjalanan lengkap dengan cap stempel stempel destinasi.</p>
                        </div>

                        <div class="p-4 bg-[#FFFBEB] border-2 border-[#1A1A2E] rounded-2xl text-xs font-bold space-y-2">
                            <div class="font-heading font-extrabold text-sm text-[#1A1A2E]">🔒 Privasi Foto Terjamin</div>
                            <div class="text-slate-600 leading-relaxed">Proses foto dan render bingkai dilakukan 100% secara lokal di browser komputer/HP kamu. Foto **tidak diunggah** ke server maupun disimpan di database TwoGo.</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Photobooth Script Logic -->
    <script>
        function photoboothApp() {
            return {
                isCameraActive: false,
                hasCaptured: false,
                selectedTemplate: 1,
                countdown: 0,

                initCamera() {
                    const video = this.$refs.videoElement;
                    navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 960 }, audio: false })
                        .then((stream) => {
                            video.srcObject = stream;
                            this.isCameraActive = true;
                        })
                        .catch((err) => {
                            alert('Gagal mengakses kamera: ' + err.message);
                            this.isCameraActive = false;
                        });
                },

                setTemplate(id) {
                    this.selectedTemplate = id;
                    if (this.hasCaptured) {
                        this.renderCanvas();
                    }
                },

                startCountdown() {
                    if (this.countdown > 0) return;
                    this.countdown = 3;
                    const timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(timer);
                            this.capturePhoto();
                        }
                    }, 1000);
                },

                capturePhoto() {
                    this.hasCaptured = true;
                    this.$nextTick(() => {
                        this.renderCanvas();
                    });
                },

                renderCanvas() {
                    const video = this.$refs.videoElement;
                    const canvas = this.$refs.canvasElement;
                    const ctx = canvas.getContext('2d');

                    const width = 1280;
                    const height = 960;
                    canvas.width = width;
                    canvas.height = height;

                    // Draw camera video frame onto canvas
                    ctx.drawImage(video, 0, 0, width, height);

                    // Render Template Frames according to selection
                    if (this.selectedTemplate === 1) {
                        // Template 1: Holiday Polaroid (Yellow & Pink Frame)
                        const borderWidth = 30;

                        // Outer Yellow Border
                        ctx.fillStyle = '#FFE156';
                        ctx.fillRect(0, 0, width, borderWidth);
                        ctx.fillRect(0, height - borderWidth * 3, width, borderWidth * 3);
                        ctx.fillRect(0, 0, borderWidth, height);
                        ctx.fillRect(width - borderWidth, 0, borderWidth, height);

                        // Black strokes
                        ctx.lineWidth = 8;
                        ctx.strokeStyle = '#1A1A2E';
                        ctx.strokeRect(0, 0, width, height);

                        // Inner photo border
                        ctx.strokeRect(borderWidth, borderWidth, width - borderWidth * 2, height - borderWidth * 4);

                        // Header Badge
                        ctx.fillStyle = '#FF6B9D';
                        ctx.fillRect(50, 40, 360, 60);
                        ctx.strokeRect(50, 40, 360, 60);
                        ctx.fillStyle = '#FFFFFF';
                        ctx.font = 'bold 24px "Space Grotesk", sans-serif';
                        ctx.fillText('✨ TwoGo Holiday Vibes', 75, 78);

                        // Bottom Text Card
                        const cardWidth = 600;
                        const cardHeight = 110;
                        const cardX = (width - cardWidth) / 2;
                        const cardY = height - 140;

                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(cardX, cardY, cardWidth, cardHeight);
                        ctx.strokeRect(cardX, cardY, cardWidth, cardHeight);

                        ctx.fillStyle = '#1A1A2E';
                        ctx.font = 'extrabold 32px "Space Grotesk", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('Rencana Seru, Bareng-Bareng! 🎒', width / 2, cardY + 50);

                        ctx.fillStyle = '#64748B';
                        ctx.font = 'bold 20px "Plus Jakarta Sans", sans-serif';
                        ctx.fillText('📍 TwoGo Digital Moment • ' + new Date().toLocaleDateString('id-ID'), width / 2, cardY + 85);
                        ctx.textAlign = 'left';

                    } else if (this.selectedTemplate === 2) {
                        // Template 2: Passport Stamp (Mint & Blue Frame)
                        const borderWidth = 35;

                        // Outer Mint Border
                        ctx.fillStyle = '#00D4AA';
                        ctx.fillRect(0, 0, width, borderWidth);
                        ctx.fillRect(0, height - borderWidth * 3, width, borderWidth * 3);
                        ctx.fillRect(0, 0, borderWidth, height);
                        ctx.fillRect(width - borderWidth, 0, borderWidth, height);

                        // Black strokes
                        ctx.lineWidth = 8;
                        ctx.strokeStyle = '#1A1A2E';
                        ctx.strokeRect(0, 0, width, height);

                        // Header Passport Badge
                        ctx.fillStyle = '#4361EE';
                        ctx.fillRect(50, 45, 480, 65);
                        ctx.strokeRect(50, 45, 480, 65);
                        ctx.fillStyle = '#FFFFFF';
                        ctx.font = 'bold 24px "Space Grotesk", sans-serif';
                        ctx.fillText('APPROVED ✈️ OFFICIAL PASSPORT', 75, 85);

                        // Stamp Circle graphic
                        ctx.fillStyle = '#FFE156';
                        ctx.beginPath();
                        ctx.arc(width - 120, 100, 55, 0, 2 * Math.PI);
                        ctx.fill();
                        ctx.stroke();

                        ctx.fillStyle = '#1A1A2E';
                        ctx.font = 'bold 16px "Space Grotesk", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('PASSPORT', width - 120, 95);
                        ctx.fillText('PASSED ✓', width - 120, 115);

                        // Bottom Text Card
                        const cardWidth = 700;
                        const cardHeight = 110;
                        const cardX = (width - cardWidth) / 2;
                        const cardY = height - 145;

                        ctx.fillStyle = '#1A1A2E';
                        ctx.fillRect(cardX, cardY, cardWidth, cardHeight);
                        ctx.strokeStyle = '#FFE156';
                        ctx.lineWidth = 4;
                        ctx.strokeRect(cardX, cardY, cardWidth, cardHeight);

                        ctx.fillStyle = '#FFE156';
                        ctx.font = 'extrabold 30px "Space Grotesk", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('BALI • JOGJA • LOMBOK • RAJA AMPAT', width / 2, cardY + 50);

                        ctx.fillStyle = '#FFFFFF';
                        ctx.font = 'bold 20px "Plus Jakarta Sans", sans-serif';
                        ctx.fillText('EXPLORER BADGE 🌟 • TwoGo Official', width / 2, cardY + 85);
                        ctx.textAlign = 'left';
                    }
                },

                resetPhoto() {
                    this.hasCaptured = false;
                },

                downloadPhoto() {
                    const canvas = this.$refs.canvasElement;
                    const link = document.createElement('a');
                    link.download = 'twogo-photobooth-' + Date.now() + '.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                }
            }
        }
    </script>

    <!-- Footer -->
    <footer class="border-t-[4px] border-[#1A1A2E] bg-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-600">
            <div>TwoGo &copy; 2026. All rights reserved.</div>
            <div>📧 <a href="mailto:adventuretwogo@gmail.com" class="underline">adventuretwogo@gmail.com</a></div>
        </div>
    </footer>

</body>
</html>
