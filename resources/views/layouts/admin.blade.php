<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} — TwoGo Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #FFFBEB;
            color: #1A1A2E;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 280px;
            background-color: #FFFFFF;
            border-right: 3px solid #1A1A2E;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
        }

        .admin-sidebar-header {
            padding: 1.5rem;
            border-bottom: 3px solid #1A1A2E;
            background-color: #FFE156;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-sidebar-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: #1A1A2E;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .admin-nav {
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
            overflow-y: auto;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            border: 3px solid transparent;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1A1A2E;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .admin-nav-item:hover {
            background-color: #FFFBEB;
            border-color: #1A1A2E;
            transform: translateX(3px);
        }

        .admin-nav-item.active {
            background-color: #FFE156;
            border-color: #1A1A2E;
            box-shadow: 4px 4px 0px #1A1A2E;
        }

        .admin-sidebar-footer {
            padding: 1.25rem 1rem;
            border-top: 3px solid #1A1A2E;
            background-color: #FFFFFF;
        }

        /* Content Wrapper */
        .admin-main {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Topbar */
        .admin-topbar {
            height: 72px;
            background-color: #FFFFFF;
            border-bottom: 3px solid #1A1A2E;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .admin-content {
            padding: 2rem;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Desktop -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.overview') }}" class="admin-sidebar-brand">
                    <span class="p-1.5 bg-[#1A1A2E] text-[#FFE156] rounded-lg text-lg">⚡</span>
                    TwoGo Admin
                </a>
                <span class="px-2.5 py-1 text-xs font-bold bg-[#1A1A2E] text-white rounded-md border border-[#1A1A2E]">v1.0</span>
            </div>

            <nav class="admin-nav">
                <div class="text-xs font-extrabold uppercase tracking-wider text-[#94A3B8] px-3 mb-1">Menu Utama</div>

                <a href="{{ route('admin.overview') }}" class="admin-nav-item {{ request()->routeIs('admin.overview') ? 'active' : '' }}">
                    <span class="text-xl">📊</span>
                    <span>Ringkasan / Overview</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="text-xl">👥</span>
                    <span>Manajemen User</span>
                </a>

                <a href="{{ route('admin.itineraries.index') }}" class="admin-nav-item {{ request()->routeIs('admin.itineraries.*') ? 'active' : '' }}">
                    <span class="text-xl">🗺️</span>
                    <span>Manajemen Itinerary</span>
                </a>

                <a href="{{ route('admin.gamification.index') }}" class="admin-nav-item {{ request()->routeIs('admin.gamification.*') ? 'active' : '' }}">
                    <span class="text-xl">🏆</span>
                    <span>Gamifikasi & XP</span>
                </a>

                <a href="{{ route('admin.landing.index') }}" class="admin-nav-item {{ request()->routeIs('admin.landing.*') ? 'active' : '' }}">
                    <span class="text-xl">🖼️</span>
                    <span>Landing Page CMS</span>
                </a>

                <a href="{{ route('admin.news.index') }}" class="admin-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <span class="text-xl">📰</span>
                    <span>Manajemen Berita</span>
                </a>

                <a href="{{ route('admin.feedback.index') }}" class="admin-nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <span class="text-xl">📬</span>
                    <span>Kritik & Saran</span>
                </a>

                <div class="my-3 border-t-2 border-dashed border-[#E2E8F0]"></div>

                <div class="text-xs font-extrabold uppercase tracking-wider text-[#94A3B8] px-3 mb-1">Aplikasi</div>

                <a href="{{ route('landing') }}" target="_blank" class="admin-nav-item">
                    <span class="text-xl">🌐</span>
                    <span>Lihat Web App</span>
                    <span class="ml-auto text-xs opacity-60">↗</span>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <div class="p-3 bg-[#FFFBEB] rounded-xl border-[3px] border-[#1A1A2E] flex items-center justify-between">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <div class="w-9 h-9 rounded-lg bg-[#FF6B9D] border-2 border-[#1A1A2E] flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <div class="font-extrabold text-sm text-[#1A1A2E] truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="text-xs text-slate-500 truncate">Administrator</div>
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg border-2 border-transparent hover:border-[#1A1A2E] transition-all" title="Keluar">
                            🚪
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="flex items-center gap-3">
                    <h1 class="font-heading font-bold text-xl text-[#1A1A2E]">{{ $pageHeader ?? 'Dashboard Admin' }}</h1>
                    @if(isset($headerBadge))
                        <span class="px-3 py-1 bg-[#FFE156] border-2 border-[#1A1A2E] font-bold text-xs rounded-full shadow-[2px_2px_0px_#1A1A2E]">
                            {{ $headerBadge }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-slate-500 font-bold">Status Koneksi</div>
                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-emerald-600">
                            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            Sistem Online
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="admin-content animate-fade-in-up">
                <!-- Toast notifications -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-[#00D4AA] text-[#1A1A2E] font-extrabold rounded-xl border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">✅</span>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:opacity-75">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-[#FF6B9D] text-white font-extrabold rounded-xl border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">⚠️</span>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:opacity-75">✕</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
