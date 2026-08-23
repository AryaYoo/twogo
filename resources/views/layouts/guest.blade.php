<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#FFE156">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} — TwoGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFE156] bg-cover bg-center bg-no-repeat bg-fixed relative" style="background-image: url('{{ asset('assets/images/img1.webp') }}');">
    <style>
        /* ===== WATER SHIMMER ANIMATION ===== */
        @keyframes water-shimmer-a {
            0%, 100% { opacity: 0; }
            50%       { opacity: 0.55; }
        }
        @keyframes water-shimmer-b {
            0%, 100% { opacity: 0.45; }
            50%       { opacity: 0; }
        }
        @keyframes water-shimmer-c {
            0%, 100% { opacity: 0.1; }
            40%       { opacity: 0.5; }
            80%       { opacity: 0.05; }
        }

        /* Shimmer strip — base horizontal band of white over the water */
        .shimmer-layer {
            position: fixed;
            left: 0;
            width: 100%;
            pointer-events: none;
            z-index: 1;
            will-change: opacity;
            background: rgba(255,255,255,0.38);
        }

        /* Three strips stacked at different heights in water zone (~55%–75% from top) */
        .shimmer-layer-1 {
            top: 72%;
            height: 6px;
            animation: water-shimmer-a 3.5s ease-in-out infinite;
        }
        .shimmer-layer-2 {
            top: 66%;
            height: 4px;
            animation: water-shimmer-b 4s ease-in-out infinite;
            animation-delay: 0.8s;
        }
        .shimmer-layer-3 {
            top: 60%;
            height: 3px;
            animation: water-shimmer-c 4.5s ease-in-out infinite;
            animation-delay: 1.6s;
        }

        /* Disable on mobile — waves too small to matter */
        @media (max-width: 639px) {
            .shimmer-layer { display: none !important; }
        }
    </style>

    <!-- Water Shimmer Strips (Desktop only) -->
    <div class="shimmer-layer shimmer-layer-1" aria-hidden="true"></div>
    <div class="shimmer-layer shimmer-layer-2" aria-hidden="true"></div>
    <div class="shimmer-layer shimmer-layer-3" aria-hidden="true"></div>

    <div class="app-container relative z-10" style="background-color: transparent; box-shadow: none;">
        <main class="p-6 min-h-screen flex flex-col justify-center animate-fade-in-up">
            @yield('content')
            
        </main>
        
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-[90vw]"></div>

        @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));</script>
        @endif
    </div>
</body>
</html>
