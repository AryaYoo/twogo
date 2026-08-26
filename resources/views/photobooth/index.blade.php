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
                            
                            <!-- Video Stream (Live 4:3 Viewfinder Fill Mode) -->
                            <video x-ref="videoElement" autoplay playsinline class="w-full h-full object-cover scale-x-[-1]" x-show="!hasCaptured"></video>
                            
                            <!-- Sequence Status Overlay -->
                            <div x-show="isCapturing" class="absolute top-4 left-4 z-40 bg-[#FFE156] text-[#1A1A2E] px-4 py-2 rounded-xl font-heading font-extrabold text-sm border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E]" style="display: none;">
                                Foto <span x-text="photos.length + 1"></span> dari <span x-text="maxPhotos"></span>
                            </div>

                            <!-- Countdown Overlay -->
                            <div x-show="countdown > 0" class="absolute inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-30" style="display: none;">
                                <span x-text="countdown" class="font-heading font-extrabold text-8xl text-[#FFE156] animate-bounce drop-shadow-[4px_4px_0px_#1A1A2E]"></span>
                            </div>

                            <!-- Live Frame Overlay Preview on Video -->
                            <div x-show="!hasCaptured && isCameraActive" class="absolute inset-0 pointer-events-none z-10 border-[16px] transition-colors" :class="selectedTemplate === 1 ? 'border-[#FFE156]' : 'border-[#00D4AA]'">
                            </div>

                            <!-- Final Rendered Canvas (When Captured) -->
                            <div x-show="hasCaptured" class="absolute inset-0 z-20 bg-slate-100 overflow-y-auto p-4 scrollbar-none flex justify-center" style="display: none;">
                                <canvas x-ref="canvasElement" class="w-[80%] max-w-[300px] h-auto object-contain shadow-[4px_4px_0px_#1A1A2E] border-2 border-[#1A1A2E]"></canvas>
                            </div>

                            <!-- Placeholder when camera disabled -->
                            <div x-show="!isCameraActive && !hasCaptured" class="absolute inset-0 flex flex-col items-center justify-center text-center text-white space-y-3 p-6 bg-[#1A1A2E] z-20">
                                <span class="text-5xl">📷</span>
                                <div class="font-heading font-extrabold text-lg">Kamera Belum Aktif</div>
                                <p class="text-xs text-slate-300">Izinkan akses kamera di browser kamu untuk memulai Photobooth.</p>
                                <button @click="initCamera()" class="mt-2 px-5 py-2.5 bg-[#FFE156] text-[#1A1A2E] border-2 border-white rounded-xl font-heading font-extrabold text-xs shadow-[3px_3px_0px_#000] cursor-pointer">
                                    ▶️ Izinkan & Aktifkan Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-4 pt-2">
                            <template x-if="!hasCaptured">
                                <button @click="startSequence()" :disabled="!isCameraActive || isCapturing" class="w-full py-3.5 bg-[#FFE156] hover:bg-[#ffd829] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] active:translate-y-[2px] active:shadow-none rounded-2xl font-heading font-extrabold text-sm text-[#1A1A2E] transition-all cursor-pointer flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span>📸</span>
                                    <span x-text="isCapturing ? 'Sedang Mengambil Foto...' : 'Mulai Foto (3x Jepret)'"></span>
                                </button>
                            </template>

                            <template x-if="hasCaptured">
                                <div class="w-full flex items-center gap-3">
                                    <button @click="resetPhoto()" class="w-1/2 py-3 bg-slate-200 hover:bg-slate-300 border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] cursor-pointer">
                                        🔄 Foto Ulang
                                    </button>
                                    <button @click="downloadPhoto()" class="w-1/2 py-3 bg-[#00D4AA] hover:bg-[#00be98] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] cursor-pointer flex items-center justify-center gap-1.5">
                                        <span>⬇️</span>
                                        <span>Download Strip (PNG)</span>
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
                photos: [],
                maxPhotos: 3,
                isCapturing: false,

                initCamera() {
                    const video = this.$refs.videoElement;
                    navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 960 }
                        }, 
                        audio: false 
                    })
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

                startSequence() {
                    if (!this.isCameraActive || this.isCapturing) return;
                    this.photos = [];
                    this.isCapturing = true;
                    this.hasCaptured = false;
                    this.takeNextPhoto();
                },

                takeNextPhoto() {
                    if (this.photos.length >= this.maxPhotos) {
                        this.isCapturing = false;
                        this.hasCaptured = true;
                        this.$nextTick(() => {
                            this.renderCanvas();
                        });
                        return;
                    }

                    this.countdown = 3;
                    const timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(timer);
                            this.captureFrame();
                            
                            // Visual flash effect on capture
                            const flash = document.createElement('div');
                            flash.className = 'absolute inset-0 bg-white z-50 transition-opacity duration-300';
                            this.$refs.videoElement.parentElement.appendChild(flash);
                            setTimeout(() => flash.style.opacity = '0', 50);
                            setTimeout(() => flash.remove(), 350);

                            // Wait 1.5 seconds before starting next countdown (or finishing)
                            setTimeout(() => {
                                this.takeNextPhoto();
                            }, 1500);
                        }
                    }, 1000);
                },

                captureFrame() {
                    const video = this.$refs.videoElement;
                    const tempCanvas = document.createElement('canvas');
                    
                    // Base canvas target size for photobooth frame: 4:3 Ratio (1000x750)
                    const targetWidth = 1000;
                    const targetHeight = 750;
                    tempCanvas.width = targetWidth;
                    tempCanvas.height = targetHeight;
                    const tempCtx = tempCanvas.getContext('2d');
                    
                    // Object-fit: cover (Mode Fill) logic to fill 4:3 without distortion/stretching
                    const videoRatio = video.videoWidth / video.videoHeight;
                    const targetRatio = targetWidth / targetHeight; // 4/3 = 1.3333
                    
                    let drawWidth = video.videoWidth;
                    let drawHeight = video.videoHeight;
                    let offsetX = 0;
                    let offsetY = 0;

                    if (videoRatio > targetRatio) {
                        // Video is wider than 4:3 (e.g. 16:9 widescreen) -> crop left & right sides
                        drawWidth = video.videoHeight * targetRatio;
                        offsetX = (video.videoWidth - drawWidth) / 2;
                    } else {
                        // Video is taller than 4:3 (e.g. 9:16 portrait or 1:1) -> crop top & bottom sides
                        drawHeight = video.videoWidth / targetRatio;
                        offsetY = (video.videoHeight - drawHeight) / 2;
                    }

                    // Mirror horizontal capture for natural selfie preview match
                    tempCtx.translate(targetWidth, 0);
                    tempCtx.scale(-1, 1);

                    // Draw image center-cropped to 4:3
                    tempCtx.drawImage(
                        video, 
                        offsetX, offsetY, drawWidth, drawHeight, 
                        0, 0, targetWidth, targetHeight
                    );
                    
                    this.photos.push(tempCanvas);
                },

                renderCanvas() {
                    const canvas = this.$refs.canvasElement;
                    const ctx = canvas.getContext('2d');

                    // Photo strip dimensions (Optimized for 3x 4:3 photos)
                    const width = 600;
                    const height = 1580;
                    canvas.width = width;
                    canvas.height = height;

                    // Common photo sizes (Exact 4:3 Ratio per Photo)
                    const photoWidth = 500;
                    const photoHeight = 375; // 500 * (3 / 4) = 375px (4:3)
                    const photoX = (width - photoWidth) / 2;
                    const photoSpacing = 24;
                    
                    // Render Template Frames according to selection
                    if (this.selectedTemplate === 1) {
                        // Template 1: Holiday Polaroid (Pink & Yellow Frame)
                        const borderWidth = 15;

                        // Outer Pink Background
                        ctx.fillStyle = '#FF6B9D';
                        ctx.fillRect(0, 0, width, height);

                        // Black borders
                        ctx.lineWidth = 10;
                        ctx.strokeStyle = '#1A1A2E';
                        ctx.strokeRect(0, 0, width, height);
                        ctx.strokeRect(borderWidth, borderWidth, width - borderWidth * 2, height - borderWidth * 2);

                        // Header Graphic
                        ctx.fillStyle = '#FFE156';
                        ctx.fillRect(photoX, 40, photoWidth, 85);
                        ctx.strokeRect(photoX, 40, photoWidth, 85);
                        ctx.fillStyle = '#1A1A2E';
                        ctx.font = 'bold 34px "Space Grotesk", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('✨ HOLIDAY VIBES', width / 2, 95);

                        // Draw the 3 photos (4:3)
                        let currentY = 155;
                        this.photos.forEach((photoCanvas) => {
                            // Photo background (white border)
                            ctx.fillStyle = '#FFFFFF';
                            ctx.fillRect(photoX - 10, currentY - 10, photoWidth + 20, photoHeight + 20);
                            ctx.strokeRect(photoX - 10, currentY - 10, photoWidth + 20, photoHeight + 20);
                            
                            // Photo itself
                            ctx.drawImage(photoCanvas, photoX, currentY, photoWidth, photoHeight);
                            ctx.strokeRect(photoX, currentY, photoWidth, photoHeight);
                            
                            currentY += photoHeight + photoSpacing + 20; // +20 for padding
                        });

                        // Footer Graphic
                        const footerY = currentY + 10;
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(photoX, footerY, photoWidth, 120);
                        ctx.strokeRect(photoX, footerY, photoWidth, 120);
                        
                        ctx.font = 'extrabold 65px "Space Grotesk", sans-serif';
                        const twogoWidth = ctx.measureText('TwoGo').width;
                        const dotWidth = ctx.measureText('.').width;
                        const totalWidth = twogoWidth + dotWidth;
                        const startX = (width - totalWidth) / 2;
                        
                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#1A1A2E';
                        ctx.fillText('TwoGo', startX, footerY + 65);
                        ctx.fillStyle = '#FF6B9D';
                        ctx.fillText('.', startX + twogoWidth, footerY + 65);
                        
                        ctx.textAlign = 'center';
                        ctx.fillStyle = '#64748B';
                        ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
                        ctx.fillText('twogo.yohanux.my.id', width / 2, footerY + 100);

                    } else if (this.selectedTemplate === 2) {
                        // Template 2: Passport Stamp (Blue & Mint Frame)
                        const borderWidth = 15;

                        // Outer Blue Background
                        ctx.fillStyle = '#4361EE';
                        ctx.fillRect(0, 0, width, height);

                        // Black borders
                        ctx.lineWidth = 10;
                        ctx.strokeStyle = '#1A1A2E';
                        ctx.strokeRect(0, 0, width, height);
                        ctx.strokeRect(borderWidth, borderWidth, width - borderWidth * 2, height - borderWidth * 2);

                        // Header Graphic
                        ctx.fillStyle = '#00D4AA';
                        ctx.fillRect(photoX, 40, photoWidth, 85);
                        ctx.strokeRect(photoX, 40, photoWidth, 85);
                        ctx.fillStyle = '#1A1A2E';
                        ctx.font = 'bold 30px "Space Grotesk", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('✈️ OFFICIAL PASSPORT', width / 2, 95);

                        // Draw the 3 photos (4:3)
                        let currentY = 155;
                        this.photos.forEach((photoCanvas, index) => {
                            // Photo wrapper
                            ctx.fillStyle = '#1A1A2E';
                            ctx.fillRect(photoX - 10, currentY - 10, photoWidth + 20, photoHeight + 20);
                            
                            // Photo itself
                            ctx.drawImage(photoCanvas, photoX, currentY, photoWidth, photoHeight);
                            ctx.strokeRect(photoX, currentY, photoWidth, photoHeight);

                            // Stamp on the first photo
                            if (index === 0) {
                                ctx.fillStyle = '#FFE156';
                                ctx.beginPath();
                                ctx.arc(photoX + photoWidth - 45, currentY + 45, 40, 0, 2 * Math.PI);
                                ctx.fill();
                                ctx.stroke();
                                ctx.fillStyle = '#1A1A2E';
                                ctx.font = 'bold 15px "Space Grotesk", sans-serif';
                                ctx.fillText('PASSED', photoX + photoWidth - 45, currentY + 50);
                            }
                            
                            currentY += photoHeight + photoSpacing + 20;
                        });

                        // Footer Graphic
                        const footerY = currentY + 10;
                        ctx.fillStyle = '#1A1A2E';
                        ctx.fillRect(photoX, footerY, photoWidth, 120);
                        ctx.strokeStyle = '#FFE156';
                        ctx.lineWidth = 6;
                        ctx.strokeRect(photoX, footerY, photoWidth, 120);
                        
                        ctx.font = 'extrabold 65px "Space Grotesk", sans-serif';
                        const twogoWidth = ctx.measureText('TwoGo').width;
                        const dotWidth = ctx.measureText('.').width;
                        const totalWidth = twogoWidth + dotWidth;
                        const startX = (width - totalWidth) / 2;
                        
                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillText('TwoGo', startX, footerY + 65);
                        ctx.fillStyle = '#FF6B9D';
                        ctx.fillText('.', startX + twogoWidth, footerY + 65);
                        
                        ctx.textAlign = 'center';
                        ctx.fillStyle = '#94A3B8';
                        ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
                        ctx.fillText('twogo.yohanux.my.id', width / 2, footerY + 100);
                    }
                },

                resetPhoto() {
                    this.hasCaptured = false;
                    this.photos = [];
                },

                downloadPhoto() {
                    const canvas = this.$refs.canvasElement;
                    const link = document.createElement('a');
                    link.download = 'twogo-photostrip-' + Date.now() + '.png';
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
