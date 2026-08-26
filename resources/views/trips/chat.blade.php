@extends('layouts.app')
@section('title', 'Chat Partner · ' . $trip->title)
@section('hide_notification', true)

@section('header')
<div class="flex items-center gap-2 md:gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-9 h-9 md:w-10 md:h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] active:translate-y-[1px] transition-transform text-sm md:text-base">
        &larr;
    </a>
    
    @if($partner)
        <a href="{{ route('profile.user', $partner) }}" class="flex items-center gap-2 min-w-0 flex-1 group">
            <x-avatar :user="$partner" size="sm" class="border-2 border-[#1A1A2E] shrink-0" />
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <h1 class="text-sm md:text-base font-heading font-extrabold truncate text-[#1A1A2E] group-hover:underline">
                        {{ $partner->name }}
                    </h1>
                    <span class="text-[9px] font-extrabold px-1.5 py-0.2 bg-[#00D4AA] text-[#1A1A2E] border border-[#1A1A2E] rounded-full shrink-0">
                        Partner
                    </span>
                </div>
                <p class="text-[10px] md:text-xs font-bold text-slate-500 truncate">
                    ✈️ {{ $trip->title }}
                </p>
            </div>
        </a>
    @else
        <div class="min-w-0 flex-1">
            <h1 class="text-sm md:text-base font-heading font-extrabold truncate text-[#1A1A2E]">
                Chat Partner
            </h1>
            <p class="text-[10px] md:text-xs font-bold text-slate-500 truncate">
                {{ $trip->title }}
            </p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    body, html {
        overflow: hidden !important;
        height: 100% !important;
    }
    /* Hapus padding page-content agar tidak ada celah di atas banner chat */
    main.page-content, main.page-content-no-nav {
        padding: 0 !important;
    }
</style>
@endpush

@section('content')
<div id="chat-wrapper" class="fixed top-0 bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] flex flex-col bg-[#FFFBEB] z-30">


    {{-- Chat Messages Stream Area (Satu-satunya area yang bisa di-scroll) --}}
    <div id="chat-messages-container" class="flex-1 overflow-y-auto min-h-0 p-4 space-y-3 bg-[#FFFBEB]">
        {{-- Empty State --}}
        <div id="chat-empty-state" class="{{ $messages->count() > 0 ? 'hidden' : '' }} flex flex-col items-center justify-center h-full text-center p-6 space-y-3 my-auto">
            <div class="w-16 h-16 bg-[#FF6B9D] border-[3px] border-[#1A1A2E] rounded-2xl shadow-[4px_4px_0px_#1A1A2E] flex items-center justify-center text-3xl">
                💬
            </div>
            <div class="space-y-1 max-w-xs">
                <h3 class="font-heading font-extrabold text-base text-[#1A1A2E]">Belum ada pesan nih!</h3>
                <p class="text-xs font-medium text-slate-600 leading-relaxed">
                    Mulai obrolan dengan partnermu untuk merencanakan dan mendiskusikan agenda seru di trip <strong>{{ $trip->title }}</strong>! ✨
                </p>
            </div>
        </div>

        {{-- Existing Messages --}}
        @foreach($messages as $msg)
            @php
                $isMine = ($msg->user_id === Auth::id());
            @endphp

            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} message-item" data-id="{{ $msg->id }}">
                <div class="flex items-end gap-2 max-w-[85%] {{ $isMine ? 'flex-row-reverse' : 'flex-row' }}">
                    @if(!$isMine)
                        <x-avatar :user="$msg->user" size="xs" class="border-2 border-[#1A1A2E] shrink-0 mb-1" />
                    @endif

                    <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} max-w-full">
                        @if(!$isMine)
                            <p class="text-[10px] font-bold text-slate-500 mb-0.5 ml-1">
                                {{ $msg->user->name }}
                            </p>
                        @endif

                        <div class="inline-block w-fit px-3.5 py-2 rounded-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] break-words whitespace-pre-wrap text-sm font-medium leading-relaxed {{ $isMine ? 'bg-[#00D4AA] text-[#1A1A2E] rounded-br-none' : 'bg-white text-[#1A1A2E] rounded-bl-none' }}">{{ $msg->message }}</div>

                        <div class="flex items-center gap-1 mt-0.5 px-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <span class="text-[9px] font-bold text-slate-500">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
                            @if($isMine)
                                <span class="text-[10px] {{ $msg->read_at ? 'text-[#00D4AA]' : 'text-slate-400' }}" title="{{ $msg->read_at ? 'Dibaca' : 'Terkirim' }}">
                                    {{ $msg->read_at ? '✓✓' : '✓' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Chat Input Bar (Pinned Bottom) --}}
    <div class="p-3 bg-white border-t-[3px] border-[#1A1A2E] shadow-[0px_-4px_0px_rgba(26,26,46,0.05)] shrink-0">
        <form id="chat-form" method="POST" action="{{ route('trips.chat.store', $trip) }}" class="flex items-end gap-2.5" autocomplete="off">
            @csrf
            <div class="flex-1 relative">
                <textarea
                    id="chat-input"
                    name="message"
                    rows="1"
                    placeholder="Ketik pesan..."
                    maxlength="1000"
                    required
                    class="w-full px-3.5 py-2.5 bg-[#FFFBEB] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] rounded-xl font-medium text-sm text-[#1A1A2E] placeholder:text-slate-400 focus:outline-none focus:bg-white transition-all resize-none max-h-32 min-h-[44px] leading-snug block box-border"
                ></textarea>
            </div>

            <button
                type="submit"
                id="chat-send-btn"
                class="px-4 py-2.5 bg-[#FFE156] hover:bg-[#ffd829] active:translate-y-[1px] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] active:shadow-none rounded-xl font-heading font-extrabold text-sm text-[#1A1A2E] transition-all flex items-center justify-center gap-1.5 shrink-0 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed h-[44px] box-border"
            >
                <span>Kirim</span>
                <span class="text-base leading-none">🚀</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('chat-messages-container');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send-btn');
    const emptyState = document.getElementById('chat-empty-state');
    const currentUserId = {{ Auth::id() }};
    const storeUrl = "{{ route('trips.chat.store', $trip, false) }}";
    const fetchUrl = "{{ route('trips.chat.messages', $trip, false) }}";

    // Ukur tinggi header dan bottom-nav secara dinamis agar tidak ada celah
    const fitChatWrapper = () => {
        const wrapper = document.getElementById('chat-wrapper');
        const header = document.querySelector('.page-header');
        const bottomNav = document.querySelector('.bottom-nav');
        if (!wrapper) return;
        // offsetHeight lebih stabil daripada getBoundingClientRect saat font belum load
        const topOffset = header ? header.offsetTop + header.offsetHeight : 0;
        const bottomOffset = bottomNav ? bottomNav.offsetHeight : 0;
        wrapper.style.top = topOffset + 'px';
        wrapper.style.bottom = bottomOffset + 'px';
    };

    // Jalankan segera, setelah frame berikutnya, dan setelah semua asset (font) selesai load
    fitChatWrapper();
    requestAnimationFrame(fitChatWrapper);
    window.addEventListener('load', fitChatWrapper);
    window.addEventListener('resize', fitChatWrapper);


    // Auto-resize textarea as user types
    const autoResizeTextarea = () => {
        input.style.height = 'auto';
        input.style.height = Math.max(44, Math.min(input.scrollHeight, 120)) + 'px';
    };

    input.addEventListener('input', autoResizeTextarea);
    autoResizeTextarea();

    // Enter to submit (Shift+Enter for newline)
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    });

    const getCsrfToken = () => {
        return document.querySelector('meta[name="csrf-token"]')?.content ||
               document.querySelector('input[name="_token"]')?.value ||
               window.csrfToken || '';
    };

    // Track highest message ID
    let lastMessageId = 0;
    document.querySelectorAll('.message-item').forEach(el => {
        const id = parseInt(el.getAttribute('data-id'), 10);
        if (id > lastMessageId) lastMessageId = id;
    });

    // Auto-scroll to bottom
    const scrollToBottom = (smooth = false) => {
        if (!container) return;
        if (smooth) {
            container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
        } else {
            container.scrollTop = container.scrollHeight;
        }
    };

    // Initial scroll
    scrollToBottom(false);

    // Helper: Append Message Bubble to DOM
    const appendMessage = (data) => {
        if (emptyState) emptyState.classList.add('hidden');

        // Prevent duplicate append if ID exists
        if (document.querySelector(`.message-item[data-id="${data.id}"]`)) {
            return;
        }

        const isMine = (data.user_id === currentUserId || data.is_mine === true);
        const itemDiv = document.createElement('div');
        itemDiv.className = `flex flex-col ${isMine ? 'items-end' : 'items-start'} message-item animate-fade-in-up`;
        itemDiv.setAttribute('data-id', data.id);

        const avatarHtml = !isMine ? `
            <div class="w-6 h-6 rounded-full bg-[#FFE156] border-2 border-[#1A1A2E] flex items-center justify-center font-bold text-[10px] shrink-0 mb-1 overflow-hidden">
                ${data.user_avatar ? `<img src="${data.user_avatar}" class="w-full h-full object-cover">` : (data.user_name ? data.user_name.charAt(0).toUpperCase() : '?')}
            </div>
        ` : '';

        const nameHtml = !isMine ? `
            <p class="text-[10px] font-bold text-slate-500 mb-0.5 ml-1">${data.user_name || 'Partner'}</p>
        ` : '';

        const statusHtml = isMine ? `
            <span class="text-[10px] text-slate-400">✓</span>
        ` : '';

        // Safe text encoding preserving newlines
        const msgDiv = document.createElement('div');
        msgDiv.className = `inline-block w-fit px-3.5 py-2 rounded-2xl border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] break-words whitespace-pre-wrap text-sm font-medium leading-relaxed ${isMine ? 'bg-[#00D4AA] text-[#1A1A2E] rounded-br-none' : 'bg-white text-[#1A1A2E] rounded-bl-none'}`;
        msgDiv.textContent = data.message;

        itemDiv.innerHTML = `
            <div class="flex items-end gap-2 max-w-[85%] ${isMine ? 'flex-row-reverse' : 'flex-row'}">
                ${avatarHtml}
                <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'} max-w-full">
                    ${nameHtml}
                    ${msgDiv.outerHTML}
                    <div class="flex items-center gap-1 mt-0.5 px-1 ${isMine ? 'justify-end' : 'justify-start'}">
                        <span class="text-[9px] font-bold text-slate-500">${data.created_at}</span>
                        ${statusHtml}
                    </div>
                </div>
            </div>
        `;

        container.appendChild(itemDiv);
        if (data.id > lastMessageId) {
            lastMessageId = data.id;
        }
        scrollToBottom(true);
    };

    // Form Submission (Send)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        // Disable button during submit
        sendBtn.disabled = true;

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message: text })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success' && result.data) {
                    input.value = '';
                    autoResizeTextarea();
                    appendMessage(result.data);
                } else {
                    showToast('Pesan tidak dapat terkirim.', 'error');
                }
            } else {
                const errData = await response.json().catch(() => ({}));
                const errMsg = errData.message || (errData.errors?.message ? errData.errors.message[0] : 'Gagal mengirim pesan.');
                showToast(errMsg, 'error');
            }
        } catch (err) {
            console.error('Send error:', err);
            showToast('Koneksi terganggu. Silakan periksa jaringan.', 'error');
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    });

    // Incremental Polling
    let pollingActive = true;
    const fetchNewMessages = async () => {
        if (!pollingActive || document.hidden) return;

        try {
            const response = await fetch(`${fetchUrl}?after_id=${lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success' && Array.isArray(result.data)) {
                    result.data.forEach(msg => appendMessage(msg));
                }
            }
        } catch (err) {
            // Silently ignore polling errors
        }
    };

    // Start Polling Interval (every 2.5s)
    const pollInterval = setInterval(fetchNewMessages, 2500);

    // Pause polling when tab is hidden to save battery & CPU
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pollingActive = false;
        } else {
            pollingActive = true;
            fetchNewMessages();
        }
    });

    // Clean up interval on navigation away
    window.addEventListener('beforeunload', () => {
        clearInterval(pollInterval);
    });
});
</script>
@endpush
