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
<body class="bg-[#FFE156]">
    <div class="app-container" style="background-color: transparent; box-shadow: none;">
        <main class="p-6 min-h-screen flex flex-col justify-center animate-fade-in-up">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-heading font-bold text-[#1A1A2E] tracking-tight">TwoGo<span class="text-[#FF6B9D]">.</span></h1>
                <p class="text-sm font-medium mt-2">Rencana Seru, Bareng-Bareng! 🎒</p>
            </div>
            
            @yield('content')
            
        </main>
        
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-[90vw]"></div>

        @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));</script>
        @endif
    </div>
</body>
</html>
