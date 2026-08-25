@extends('layouts.app')
@section('title', 'For You')

@section('header')
<div class="flex items-center justify-between w-full">
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold">For You ✨</h1>
        <p class="text-xs font-medium opacity-80">Update trip, wishlist & aktivitas publik</p>
    </div>
</div>
@endsection

@section('content')

@if($feed->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="text-6xl mb-4">🌴</div>
        <h2 class="font-heading font-bold text-xl mb-2">Belum Ada Update</h2>
        <p class="text-sm font-medium opacity-70 max-w-xs leading-relaxed mb-6">
            Buat trip atau wishlist dan atur sebagai publik — atau tambah teman yang juga membagikan perjalanan mereka!
        </p>
        <a href="{{ route('trips.create') }}" class="nb-btn nb-btn-primary">Buat Trip Baru</a>
    </div>
@else
    <div 
        x-data="fypStackedCarousel({{ count($feed) }})"
        x-on:touchstart="onTouchStart($event)"
        x-on:touchmove="onTouchMove($event)"
        x-on:touchend="onTouchEnd($event)"
        x-on:mousedown="onMouseDown($event)"
        x-on:mousemove="onMouseMove($event)"
        x-on:mouseup="onMouseUp($event)"
        x-on:wheel.prevent="onWheel($event)"
        class="relative w-full flex-1 flex flex-col justify-between overflow-hidden select-none py-1 min-h-[calc(100vh-190px)]"
        style="touch-action: pan-y;"
    >
        <!-- Stacked Cards Container -->
        <div class="relative w-full flex-1 min-h-[440px] sm:min-h-[500px] flex items-center justify-center mt-1">
            @foreach($feed as $index => $item)
                @php
                    $trip      = $item['trip'];
                    $user      = $item['user'];
                    $isWishlist = $item['type'] === 'wishlist';
                    $isActivity = $item['type'] === 'activity';
                    $activity  = $item['activity'];
                    $imgUrl    = $item['image_url'];
                @endphp

                @if($isActivity)
                {{-- ===== CARD: AKTIVITAS / DOKUMENTASI ===== --}}
                <div
                    class="absolute inset-x-0 mx-auto w-full max-w-sm transition-all duration-300 ease-out origin-bottom fyp-card-{{ $index }}"
                    data-title="{{ e($trip->title) }}"
                    data-clone-url=""
                    data-like-url=""
                    :style="getCardStyle({{ $index }})"
                    x-show="isCardVisible({{ $index }})"
                >
                    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-3.5 space-y-2.5 flex flex-col justify-between h-[430px] sm:h-[500px]">

                        {{-- Header: user info & badge aktivitas --}}
                        <div class="flex items-center justify-between gap-2 pb-1.5 border-b-2 border-[#1A1A2E] border-dashed">
                            <a href="{{ $item['is_own'] ? route('profile.show') : route('profile.user', $user) }}" class="flex items-center gap-2 min-w-0">
                                <x-avatar :user="$user" size="sm" class="border-2 border-[#1A1A2E] shrink-0" />
                                <div class="min-w-0">
                                    <p class="font-heading font-extrabold text-xs text-[#1A1A2E] truncate">
                                        {{ $item['is_own'] ? 'Kamu' : $user->name }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-500">
                                        {{ $item['created_at']->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                            <span class="px-2 py-0.5 bg-[#FF6B9D] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-[10px] text-white shrink-0">
                                📸 Aktivitas
                            </span>
                        </div>

                        {{-- Foto aktivitas --}}
                        <div class="relative w-full h-44 sm:h-60 rounded-xl border-[3px] border-[#1A1A2E] overflow-hidden bg-[#FFFBEB] shrink-0">
                            <img src="{{ $imgUrl }}" alt="{{ $activity->title }}" class="w-full h-full object-cover" />

                            {{-- Badge kategori --}}
                            <div class="absolute top-2 right-2 px-2 py-0.5 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-[10px] text-[#1A1A2E]">
                                {{ $activity->category_icon }} {{ ucfirst($activity->category) }}
                            </div>

                            {{-- Badge lokasi --}}
                            @if($activity->location_name)
                            <div class="absolute bottom-2 left-2 px-2 py-0.5 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-bold text-[11px] text-[#1A1A2E] flex items-center gap-1 max-w-[85%] truncate">
                                <span>📍 {{ $activity->location_name }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Judul aktivitas & info trip --}}
                        <div class="space-y-0.5 flex-1 flex flex-col justify-center">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-[#1A1A2E] line-clamp-1 leading-tight">
                                {{ $activity->title }}
                            </h3>
                            <p class="text-[11px] sm:text-xs font-bold text-slate-500 flex items-center gap-1">
                                {{ $activity->session_icon }} {{ ucfirst($activity->session) }}
                                <span class="opacity-40 mx-0.5">·</span>
                                🗓️ {{ $activity->day->date->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-[11px] font-bold text-[#4361EE] truncate mt-0.5">
                                📍 {{ $trip->title }}
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div class="pt-1.5 border-t-2 border-[#1A1A2E] border-dashed flex items-center justify-between gap-1.5">
                            <span class="text-[10px] font-medium text-slate-400">
                                @if($activity->actual_cost > 0)
                                    💸 Rp{{ number_format($activity->actual_cost, 0, ',', '.') }}
                                @else
                                    ✅ Selesai
                                @endif
                            </span>
                            <a
                                href="{{ route('trips.show', $trip) }}"
                                class="px-3 py-1 bg-[#FF6B9D] hover:bg-[#ff5089] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] active:translate-y-[1px] active:shadow-none rounded-xl font-heading font-extrabold text-xs text-white transition-all flex items-center gap-1"
                            >
                                <span>Lihat Trip</span>
                                <span>→</span>
                            </a>
                        </div>

                    </div>
                </div>

                @else
                {{-- ===== CARD: TRIP / WISHLIST ===== --}}
                <div
                    class="absolute inset-x-0 mx-auto w-full max-w-sm transition-all duration-300 ease-out origin-bottom fyp-card-{{ $index }}"
                    data-title="{{ e($trip->title) }}"
                    data-clone-url="{{ route('trips.clone', $trip) }}"
                    data-like-url="{{ route('trips.like', $trip) }}"
                    :style="getCardStyle({{ $index }})"
                    x-show="isCardVisible({{ $index }})"
                >
                    <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-3.5 space-y-2.5 flex flex-col justify-between h-[430px] sm:h-[500px]">

                        {{-- Header: User info & trip type --}}
                        <div class="flex items-center justify-between gap-2 pb-1.5 border-b-2 border-[#1A1A2E] border-dashed">
                            <a href="{{ $item['is_own'] ? route('profile.show') : route('profile.user', $user) }}" class="flex items-center gap-2 min-w-0">
                                <x-avatar :user="$user" size="sm" class="border-2 border-[#1A1A2E] shrink-0" />
                                <div class="min-w-0">
                                    <p class="font-heading font-extrabold text-xs text-[#1A1A2E] truncate">
                                        {{ $item['is_own'] ? 'Kamu' : $user->name }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-500">
                                        {{ $item['created_at']->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                            <span class="px-2 py-0.5 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-[10px] text-[#1A1A2E] shrink-0">
                                {{ $isWishlist ? '💭 Wishlist' : '🌴 Trip' }}
                            </span>
                        </div>

                        {{-- Foto destinasi --}}
                        <div class="relative w-full h-44 sm:h-60 rounded-xl border-[3px] border-[#1A1A2E] overflow-hidden bg-[#FFFBEB] shrink-0">
                            <img src="{{ $imgUrl }}" alt="{{ $trip->title }}" class="w-full h-full object-cover" />

                            <div class="absolute top-2 right-2 px-2 py-0.5 bg-[#00D4AA] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-extrabold text-[10px] text-[#1A1A2E]">
                                🌍 Publik
                            </div>

                            <div class="absolute bottom-2 left-2 px-2 py-0.5 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-heading font-bold text-[11px] text-[#1A1A2E] flex items-center gap-1 max-w-[85%] truncate">
                                <span>📍 {{ $trip->destination }}</span>
                            </div>
                        </div>

                        {{-- Judul trip & detail --}}
                        <div class="space-y-0.5 flex-1 flex flex-col justify-center">
                            <h3 class="font-heading font-extrabold text-sm sm:text-base text-[#1A1A2E] line-clamp-1 leading-tight">
                                {{ $trip->title }}
                            </h3>
                            @if(!$isWishlist && $trip->start_date)
                                <p class="text-[11px] sm:text-xs font-bold text-slate-600 flex items-center gap-1">
                                    <span>📅 {{ $trip->start_date->format('d M Y') }} – {{ $trip->end_date->format('d M Y') }}</span>
                                </p>
                            @else
                                <p class="text-[11px] sm:text-xs font-bold text-slate-500 italic line-clamp-2">
                                    {{ $trip->description ? Str::limit($trip->description, 60) : 'Rencana destinasi liburan impian.' }}
                                </p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="pt-1.5 border-t-2 border-[#1A1A2E] border-dashed flex items-center justify-between gap-1.5">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-medium text-slate-400">
                                    {{ $trip->likes->count() }} suka
                                </span>
                                <span class="text-[10px] font-medium text-slate-400">
                                    {{ $trip->clones()->count() }} salin
                                </span>
                            </div>

                            <a
                                href="{{ route('trips.public_show', $trip) }}"
                                class="px-3 py-1 bg-[#FFE156] hover:bg-[#ffd829] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] active:translate-y-[1px] active:shadow-none rounded-xl font-heading font-extrabold text-xs text-[#1A1A2E] transition-all flex items-center gap-1"
                            >
                                <span>Detail</span>
                                <span>→</span>
                            </a>
                        </div>

                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Bottom Controls & Navigation Buttons -->
        <div class="w-full flex items-center justify-between px-4 pt-4 pb-2 z-40 mt-auto">
            <!-- Progress Bar Indicator -->
            <div class="flex-1 mr-3 bg-slate-200 border-2 border-[#1A1A2E] rounded-full h-2.5 sm:h-3 overflow-hidden shadow-[2px_2px_0px_#1A1A2E]">
                <div 
                    class="bg-[#00D4AA] h-full transition-all duration-300"
                    :style="'width: ' + (((currentIndex + 1) / total) * 100) + '%'"
                ></div>
            </div>

            <!-- Up / Down Navigation Buttons -->
            <div class="flex items-center gap-1.5">
                <button 
                    @click="prev()"
                    :disabled="currentIndex === 0"
                    :class="currentIndex === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:-translate-y-0.5 cursor-pointer'"
                    class="w-9 h-9 sm:w-10 sm:h-10 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] active:translate-y-[1px] rounded-xl font-heading font-extrabold text-sm sm:text-base flex items-center justify-center text-[#1A1A2E] transition-all"
                    aria-label="Card Sebelumnya"
                    title="Card Sebelumnya"
                >
                    ↑
                </button>
                <button 
                    @click="next()"
                    :disabled="currentIndex === total - 1"
                    :class="currentIndex === total - 1 ? 'opacity-40 cursor-not-allowed' : 'hover:-translate-y-0.5 cursor-pointer'"
                    class="w-9 h-9 sm:w-10 sm:h-10 bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] active:translate-y-[1px] rounded-xl font-heading font-extrabold text-sm sm:text-base flex items-center justify-center text-[#1A1A2E] transition-all"
                    aria-label="Card Berikutnya"
                    title="Card Berikutnya"
                >
                    ↓
                </button>
            </div>
        </div>

        <!-- Clone Confirmation Modal -->
        <div 
            x-show="showCloneModal" 
            x-transition.opacity
            class="fixed inset-0 z-50 bg-[#1A1A2E]/60 backdrop-blur-sm flex items-center justify-center p-4"
            x-cloak
        >
            <div 
                @click.away="showCloneModal = false"
                class="bg-white border-[3px] border-[#1A1A2E] shadow-[8px_8px_0px_#1A1A2E] rounded-3xl p-6 w-full max-w-sm space-y-4 text-center animate-fade-in-up"
            >
                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#FFE156] border-[3px] border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] flex items-center justify-center text-3xl font-extrabold">
                    📋
                </div>

                <div class="space-y-2">
                    <h3 class="font-heading font-extrabold text-xl text-[#1A1A2E]">
                        Salin Itinerary Ini?
                    </h3>
                    <p class="text-xs font-bold text-slate-600 leading-relaxed">
                        Apakah kamu ingin menyalin itinerary <span class="text-[#4361EE] font-extrabold" x-text="'&ldquo;' + selectedTripTitle + '&rdquo;'"></span> ke daftar trip kamu?
                    </p>
                </div>

                <div class="pt-2 flex items-center justify-center gap-3">
                    <button 
                        type="button"
                        @click="showCloneModal = false"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 border-2 border-[#1A1A2E] rounded-xl font-bold text-xs text-[#1A1A2E] cursor-pointer transition-all"
                    >
                        Batal
                    </button>
                    <button 
                        type="button"
                        @click="confirmClone()"
                        :disabled="isCloning"
                        class="px-5 py-2.5 bg-[#00D4AA] hover:bg-[#00c29a] text-[#1A1A2E] border-2 border-[#1A1A2E] shadow-[3px_3px_0px_#1A1A2E] active:translate-y-[1px] active:shadow-none rounded-xl font-heading font-extrabold text-xs cursor-pointer transition-all flex items-center gap-1.5"
                    >
                        <span x-show="!isCloning">Ya, Salin Sekarang ✨</span>
                        <span x-show="isCloning" class="animate-pulse">Menyalin...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
function fypStackedCarousel(totalItems) {
    return {
        currentIndex: 0,
        total: totalItems,
        startX: 0,
        startY: 0,
        isDragging: false,
        dragOffsetX: 0,
        dragOffsetY: 0,
        touchThreshold: 35,
        showCloneModal: false,
        selectedTripTitle: '',
        selectedCloneUrl: '',
        isCloning: false,
        
        next() {
            if (this.currentIndex < this.total - 1) {
                this.currentIndex++;
            }
        },
        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },

        isCardVisible(index) {
            return index >= this.currentIndex - 1 && index <= this.currentIndex + 3;
        },

        openCloneModal(title, url) {
            this.selectedTripTitle = title;
            this.selectedCloneUrl = url;
            this.showCloneModal = true;
        },

        triggerCloneActive() {
            const activeEl = document.querySelector(`.fyp-card-${this.currentIndex}`);
            if (activeEl) {
                const title = activeEl.dataset.title || 'Trip ini';
                const url = activeEl.dataset.cloneUrl || '';
                if (url) {
                    this.openCloneModal(title, url);
                }
            }
        },

        handleLike(event, url) {
            const btn = event.currentTarget;
            if (!btn || !url) return;
            const csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data || data.count === undefined) return;
                const countEl = btn.querySelector('.like-count');
                const iconEl  = btn.querySelector('.like-icon');
                if (countEl) countEl.textContent = data.count;
                if (iconEl)  iconEl.textContent  = data.liked ? '❤️' : '🤍';
                if (data.liked) {
                    btn.classList.remove('bg-white', 'text-[#1A1A2E]');
                    btn.classList.add('bg-[#FF6B9D]', 'text-white');
                } else {
                    btn.classList.remove('bg-[#FF6B9D]', 'text-white');
                    btn.classList.add('bg-white', 'text-[#1A1A2E]');
                }
            })
            .catch(err => console.error('Like error:', err));
        },

        confirmClone() {
            if (!this.selectedCloneUrl || this.isCloning) return;
            this.isCloning = true;

            const csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
            fetch(this.selectedCloneUrl, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrf, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json' 
                }
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                this.isCloning = false;
                this.showCloneModal = false;
                if (typeof showToast === 'function') {
                    showToast('Itinerary berhasil disalin ke akun kamu! 🎉', 'success');
                }
                setTimeout(() => {
                    location.href = (data && data.redirect) ? data.redirect : '/trips';
                }, 800);
            })
            .catch(() => {
                this.isCloning = false;
                this.showCloneModal = false;
                location.href = '/trips';
            });
        },

        onTouchStart(e) {
            if (e.target.closest('a, button, input')) return;
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
            this.isDragging = true;
            this.dragOffsetX = 0;
            this.dragOffsetY = 0;
        },
        onTouchMove(e) {
            if (!this.isDragging) return;
            this.dragOffsetX = e.touches[0].clientX - this.startX;
            this.dragOffsetY = e.touches[0].clientY - this.startY;
        },
        onTouchEnd(e) {
            if (!this.isDragging) return;
            this.isDragging = false;

            // Skip carousel logic if touch ended on an interactive element
            if (e.target.closest('a, button, input')) return;
            
            const absX = Math.abs(this.dragOffsetX);
            const absY = Math.abs(this.dragOffsetY);

            if (absX > absY && this.dragOffsetX < -45) {
                // Swipe left -> open clone modal
                this.triggerCloneActive();
            } else if (absY > absX) {
                if (this.dragOffsetY < -this.touchThreshold) {
                    this.next();
                } else if (this.dragOffsetY > this.touchThreshold) {
                    this.prev();
                }
            }

            this.dragOffsetX = 0;
            this.dragOffsetY = 0;
        },

        onMouseDown(e) {
            if (e.button !== 0 || e.target.closest('a, button, input')) return;
            this.startX = e.clientX;
            this.startY = e.clientY;
            this.isDragging = true;
            this.dragOffsetX = 0;
            this.dragOffsetY = 0;
        },
        onMouseMove(e) {
            if (!this.isDragging) return;
            this.dragOffsetX = e.clientX - this.startX;
            this.dragOffsetY = e.clientY - this.startY;
        },
        onMouseUp(e) {
            if (!this.isDragging) return;
            this.isDragging = false;

            const absX = Math.abs(this.dragOffsetX);
            const absY = Math.abs(this.dragOffsetY);

            if (absX > absY && this.dragOffsetX < -45) {
                this.triggerCloneActive();
            } else if (absY > absX) {
                if (this.dragOffsetY < -this.touchThreshold) {
                    this.next();
                } else if (this.dragOffsetY > this.touchThreshold) {
                    this.prev();
                }
            }

            this.dragOffsetX = 0;
            this.dragOffsetY = 0;
        },

        onWheel(e) {
            if (Math.abs(e.deltaY) > 25) {
                if (e.deltaY > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }
        },

        getCardStyle(index) {
            const diff = index - this.currentIndex;
            if (diff < 0) {
                return 'transform: translateY(-130%) scale(0.9); opacity: 0; z-index: 0; pointer-events: none;';
            } else if (diff === 0) {
                let shiftY = this.isDragging ? Math.max(-50, Math.min(50, this.dragOffsetY * 0.35)) : 0;
                let shiftX = (this.isDragging && Math.abs(this.dragOffsetX) > Math.abs(this.dragOffsetY)) ? Math.max(-60, Math.min(0, this.dragOffsetX * 0.5)) : 0;
                return `transform: translate(${shiftX}px, ${shiftY}px) scale(1); opacity: 1; z-index: 30; pointer-events: auto;`;
            } else if (diff === 1) {
                return 'transform: translateY(22px) scale(0.93); opacity: 0.8; z-index: 20; pointer-events: none;';
            } else if (diff === 2) {
                return 'transform: translateY(42px) scale(0.86); opacity: 0.45; z-index: 10; pointer-events: none;';
            } else {
                return 'transform: translateY(60px) scale(0.8); opacity: 0; z-index: 0; pointer-events: none;';
            }
        }
    };
}

function toggleLike(btnEl, url) {
    const csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.ok ? r.json() : null)
    .then(data => {
        if (data && data.count !== undefined) {
            const countEl = btnEl.querySelector('.like-count');
            const iconEl = btnEl.querySelector('.like-icon');
            if (countEl) countEl.textContent = data.count;
            if (iconEl) iconEl.textContent = data.liked ? '❤️' : '🤍';
            if (data.liked) {
                btnEl.classList.remove('bg-white', 'text-[#1A1A2E]');
                btnEl.classList.add('bg-[#FF6B9D]', 'text-white');
            } else {
                btnEl.classList.remove('bg-[#FF6B9D]', 'text-white');
                btnEl.classList.add('bg-white', 'text-[#1A1A2E]');
            }
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush

