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
        /* ===== PIXEL-ART WATER SPARKLE ===== */
        @keyframes sparkleGlint {
            0%, 100% { opacity: 0;   transform: scale(0.6); }
            50%       { opacity: 1;   transform: scale(1);   }
        }

        /* Container — fixed, no interaction */
        .water-sparkle-wrap {
            position: fixed;
            pointer-events: none;
            z-index: 1;
            will-change: opacity, transform;
        }

        /* Cross/plus shape via ::before (vertical) + ::after (horizontal) */
        .water-sparkle {
            position: relative;
            width: 12px;
            height: 12px;
            opacity: 0;
            animation: sparkleGlint 2.5s ease-in-out infinite;
        }
        .water-sparkle::before,
        .water-sparkle::after {
            content: '';
            position: absolute;
            background: #FFFFFF;
            image-rendering: pixelated;
        }
        /* Vertical bar */
        .water-sparkle::before {
            width: 4px;
            height: 12px;
            left: 4px;
            top: 0;
        }
        /* Horizontal bar */
        .water-sparkle::after {
            width: 12px;
            height: 4px;
            left: 0;
            top: 4px;
        }

        /* Size variant: large (16px cross) */
        .water-sparkle.lg {
            width: 16px;
            height: 16px;
        }
        .water-sparkle.lg::before { width: 4px; height: 16px; left: 6px; top: 0; }
        .water-sparkle.lg::after  { width: 16px; height: 4px; left: 0; top: 6px; }

        /* Size variant: small (8px cross) */
        .water-sparkle.sm {
            width: 8px;
            height: 8px;
        }
        .water-sparkle.sm::before { width: 2px; height: 8px; left: 3px; top: 0; }
        .water-sparkle.sm::after  { width: 8px; height: 2px; left: 0; top: 3px; }

        /* Disable entirely on mobile */
        @media (max-width: 639px) {
            .water-sparkle-wrap { display: none !important; }
        }
    </style>

    <!--
        Pixel-art sparkle crosses — scattered across the water zone (top: 52%–82%).
        Each has a unique animation-delay so they glint one by one, not all at once.
        Positions are spread left/right to avoid centre where the form card sits.
    -->
    <!-- Left cluster -->
    <div class="water-sparkle-wrap" style="top:58%;left:6%;">  <div class="water-sparkle lg"  style="animation-delay:0s"></div></div>
    <div class="water-sparkle-wrap" style="top:68%;left:12%;"> <div class="water-sparkle sm"  style="animation-delay:0.6s"></div></div>
    <div class="water-sparkle-wrap" style="top:74%;left:4%;">  <div class="water-sparkle"     style="animation-delay:1.3s"></div></div>
    <div class="water-sparkle-wrap" style="top:80%;left:18%;"> <div class="water-sparkle sm"  style="animation-delay:2.1s"></div></div>
    <div class="water-sparkle-wrap" style="top:63%;left:24%;"> <div class="water-sparkle"     style="animation-delay:0.9s"></div></div>

    <!-- Right cluster -->
    <div class="water-sparkle-wrap" style="top:60%;right:7%;"> <div class="water-sparkle lg"  style="animation-delay:1.7s"></div></div>
    <div class="water-sparkle-wrap" style="top:70%;right:15%;"><div class="water-sparkle"     style="animation-delay:0.3s"></div></div>
    <div class="water-sparkle-wrap" style="top:76%;right:5%;"> <div class="water-sparkle sm"  style="animation-delay:1.0s"></div></div>
    <div class="water-sparkle-wrap" style="top:82%;right:22%;"><div class="water-sparkle"     style="animation-delay:2.4s"></div></div>
    <div class="water-sparkle-wrap" style="top:55%;right:28%;"><div class="water-sparkle sm"  style="animation-delay:1.5s"></div></div>

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
