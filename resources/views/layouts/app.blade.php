<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1A1A2E">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwoGo' }} — TwoGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @auth
        @php
            $pendingInvites = \App\Models\TripInvitation::where('invited_user_id', Auth::id())
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->count();
        @endphp
        @endauth

        @hasSection('header')
        <header class="page-header">
            <div class="page-header-inner">
                <div class="header-main">
                    @yield('header')
                </div>
                @auth
                <a href="{{ route('invitations.index') }}" class="top-notification-button" title="Undangan">
                    <span class="text-xl">🔔</span>
                    @if($pendingInvites > 0)
                        <span class="top-notification-badge">{{ $pendingInvites }}</span>
                    @endif
                </a>
                @endauth
            </div>
        </header>
        @endif

        <main class="page-content animate-fade-in-up">
            @yield('content')
        </main>

        @stack('floating-bottom')

        @auth
        <x-bottom-nav />
        @endauth

        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-[90vw]"></div>

        @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'));</script>
        @endif
        @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error', 5000));</script>
        @endif
        @if($errors->any())
        <script>document.addEventListener('DOMContentLoaded', () => showToast('{{ $errors->first() }}', 'error', 5000));</script>
        @endif

        @auth
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var pending = {{ $pendingInvites }};
                if (pending > 0) {
                    showToast('Kamu mempunyai ' + pending + ' undangan baru.', 'info', 5000);
                }
            });
        </script>
        @endauth

        @stack('modals')
        @stack('scripts')
    </div>
</body>
</html>
