<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — TwoGo Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFBEB] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl mb-3 text-3xl">
                ⚡
            </div>
            <h1 class="font-heading font-extrabold text-3xl text-[#1A1A2E] tracking-tight">TwoGo Admin</h1>
            <p class="font-medium text-slate-600 mt-1 text-sm">Masuk ke Panel Kontrol Administrator</p>
        </div>

        <!-- Card Login -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6 md:p-8">
            @if(session('error'))
                <div class="mb-5 p-3.5 bg-[#FF6B9D] text-white text-sm font-bold rounded-xl border-[2px] border-[#1A1A2E]">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 bg-[#00D4AA] text-[#1A1A2E] text-sm font-bold rounded-xl border-[2px] border-[#1A1A2E]">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="login" class="block font-bold text-sm text-[#1A1A2E] mb-2">Username atau Email</label>
                    <input 
                        type="text" 
                        name="login" 
                        id="login" 
                        value="{{ old('login') }}" 
                        placeholder="Masukkan username atau email admin"
                        class="w-full px-4 py-3 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-[#4361EE] focus:bg-white transition-all text-[#1A1A2E]" 
                        required 
                        autofocus
                    >
                    @error('login')
                        <p class="mt-1 text-xs font-bold text-red-600">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-bold text-sm text-[#1A1A2E] mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] rounded-xl font-medium focus:outline-none focus:ring-2 focus:ring-[#4361EE] focus:bg-white transition-all text-[#1A1A2E]" 
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-xs font-bold text-red-600">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-2 border-[#1A1A2E] accent-[#4361EE]">
                        <span class="font-bold text-slate-700">Ingat Saya</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-6 bg-[#FFE156] hover:bg-[#ffd829] active:translate-y-[2px] active:shadow-none text-[#1A1A2E] font-heading font-extrabold text-base border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-xl transition-all cursor-pointer flex items-center justify-center gap-2"
                >
                    <span>Masuk ke Dashboard</span>
                    <span class="text-xl">➔</span>
                </button>
            </form>

            <!-- Credential helper notice -->
            <div class="mt-6 pt-5 border-t-2 border-dashed border-slate-200 text-xs text-slate-500 text-center">
                <div class="font-extrabold text-slate-700 mb-1">🔐 Akun Admin Default:</div>
                <div class="bg-slate-100 p-2.5 rounded-lg border border-slate-300 font-mono text-[11px] text-slate-800 space-y-1">
                    <div>User: <b class="text-[#4361EE]">YohanesMA</b> | Pass: <b class="text-[#4361EE]">AryaSangCEO</b></div>
                    <div>User: <b class="text-slate-600">admin@twogo.com</b> | Pass: <b class="text-slate-600">password123</b></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
