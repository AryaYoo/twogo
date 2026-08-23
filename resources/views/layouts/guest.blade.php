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
        @keyframes water-shimmer {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        .animate-water-shimmer {
            animation: water-shimmer 3.5s ease-in-out infinite;
            will-change: opacity;
        }
    </style>
    
    <!-- Subtle Water Shimmer Overlay for Desktop -->
    <div class="hidden sm:block fixed bottom-0 left-0 w-full h-[40%] bg-gradient-to-t from-white/20 via-white/5 to-transparent mix-blend-overlay animate-water-shimmer pointer-events-none z-0"></div>

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
