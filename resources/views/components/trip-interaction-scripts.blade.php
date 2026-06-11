<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.trip-interaction-card');
    
    cards.forEach(card => {
        let touchstartX = 0;
        let touchstartY = 0;
        let lastTapTime = 0;
        let clickTimer = null;
        
        let holdTimer = null;
        let isHolding = false;
        let holdProgressBar = null;
        let holdIndicator = null;
        
        // Add giant heart element if not exists
        let heartIcon = card.querySelector('.giant-heart');
        if (!heartIcon) {
            heartIcon = document.createElement('div');
            heartIcon.className = 'giant-heart absolute inset-0 flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 transform scale-50 z-50';
            heartIcon.innerHTML = '<span class="text-7xl drop-shadow-xl filter" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">❤️</span>';
            card.appendChild(heartIcon);
        }

        const triggerLike = () => {
            // Animation
            heartIcon.classList.remove('opacity-0', 'scale-50', 'scale-150');
            heartIcon.classList.add('opacity-100', 'scale-110');
            setTimeout(() => {
                heartIcon.classList.remove('opacity-100', 'scale-110');
                heartIcon.classList.add('opacity-0', 'scale-150');
                setTimeout(() => {
                    heartIcon.classList.remove('scale-150');
                    heartIcon.classList.add('scale-50');
                }, 300);
            }, 600);

            // Fetch request
            const likeUrl = card.dataset.likeUrl;
            if (likeUrl) {
                fetch(likeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    // Update UI counters if they exist in this card
                    const countSpan = card.querySelector('.like-count-text');
                    if (countSpan) countSpan.innerText = '❤️ ' + data.count;
                    
                    const likeBtnIcon = document.getElementById('like-icon');
                    const likeBtnCount = document.getElementById('like-count');
                    if (likeBtnIcon) likeBtnIcon.innerText = data.liked ? '❤️' : '🤍';
                    if (likeBtnCount) likeBtnCount.innerText = data.count;
                    
                    // Toggle button class if it exists (in public_show)
                    const likeBtn = document.getElementById('like-btn');
                    if (likeBtn) {
                        if (data.liked) {
                            likeBtn.classList.remove('bg-white', 'text-[#1A1A2E]');
                            likeBtn.classList.add('bg-[#FF6B9D]', 'text-white');
                        } else {
                            likeBtn.classList.add('bg-white', 'text-[#1A1A2E]');
                            likeBtn.classList.remove('bg-[#FF6B9D]', 'text-white');
                        }
                    }
                }).catch(err => console.error(err));
            }
        };

        const triggerClone = () => {
            const cloneUrl = card.dataset.cloneUrl;
            if (cloneUrl) {
                // visual feedback
                card.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.transform = 'scale(0.93) rotate(-1.5deg)';
                
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = cloneUrl;
                let token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(token);
                document.body.appendChild(form);
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        };

        const startHold = (e) => {
            // Ignore if clicked on a button, link or input
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;

            const cloneUrl = card.dataset.cloneUrl;
            if (!cloneUrl) return;

            isHolding = true;

            // Remove any existing indicators
            if (holdProgressBar) holdProgressBar.remove();
            if (holdIndicator) holdIndicator.remove();

            // Progress bar
            holdProgressBar = document.createElement('div');
            holdProgressBar.className = 'absolute bottom-0 left-0 h-2 bg-[#4361EE] border-t-2 border-[#1A1A2E] z-40';
            holdProgressBar.style.width = '0%';
            holdProgressBar.style.transition = 'width 800ms linear, background-color 0.2s';
            card.appendChild(holdProgressBar);

            // Floating indicator
            holdIndicator = document.createElement('div');
            holdIndicator.className = 'hold-indicator absolute top-3 left-1/2 -translate-x-1/2 bg-[#1A1A2E] text-white text-[10px] font-bold px-2.5 py-1 rounded-md border-2 border-white shadow-[2px_2px_0px_rgba(0,0,0,0.2)] z-40 whitespace-nowrap pointer-events-none select-none';
            holdIndicator.innerText = '⏱️ Tahan untuk menyimpan...';
            card.appendChild(holdIndicator);

            // Animate card shrinking
            card.style.transition = 'transform 800ms cubic-bezier(0.25, 0.8, 0.25, 1)';
            card.style.transform = 'scale(0.97)';

            // Trigger reflow for width transition
            holdProgressBar.offsetHeight;
            holdProgressBar.style.width = '100%';

            holdTimer = setTimeout(() => {
                if (isHolding) {
                    // Success feedback
                    holdProgressBar.style.backgroundColor = '#00D4AA';
                    holdIndicator.innerText = '📋 Menyalin...';
                    holdIndicator.className = 'hold-indicator absolute top-3 left-1/2 -translate-x-1/2 bg-[#00D4AA] text-[#1A1A2E] text-[10px] font-bold px-2.5 py-1 rounded-md border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] z-40 whitespace-nowrap pointer-events-none select-none';

                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                    triggerClone();
                }
                isHolding = false;
            }, 800);
        };

        const cancelHold = () => {
            if (!isHolding) return;
            isHolding = false;
            
            if (holdTimer) {
                clearTimeout(holdTimer);
                holdTimer = null;
            }

            // Reset scale
            card.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            card.style.transform = 'none';

            // Fade out progress bar
            if (holdProgressBar) {
                holdProgressBar.style.transition = 'width 0.2s ease, opacity 0.2s ease';
                holdProgressBar.style.width = '0%';
                holdProgressBar.style.opacity = '0';
                const el = holdProgressBar;
                setTimeout(() => el.remove(), 200);
                holdProgressBar = null;
            }

            // Fade out indicator
            if (holdIndicator) {
                holdIndicator.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                holdIndicator.style.opacity = '0';
                holdIndicator.style.transform = 'translate(-50%, -10px)';
                const el = holdIndicator;
                setTimeout(() => el.remove(), 200);
                holdIndicator = null;
            }
        };

        // TOUCH LISTENERS
        card.addEventListener('touchstart', e => {
            touchstartX = e.touches[0].clientX;
            touchstartY = e.touches[0].clientY;
            startHold(e);
        }, {passive: true});

        card.addEventListener('touchmove', e => {
            if (!isHolding) return;
            let currentX = e.touches[0].clientX;
            let currentY = e.touches[0].clientY;
            let dist = Math.sqrt(Math.pow(currentX - touchstartX, 2) + Math.pow(currentY - touchstartY, 2));
            if (dist > 15) {
                cancelHold();
            }
        }, {passive: true});

        card.addEventListener('touchend', e => {
            let touchendX = e.changedTouches[0].clientX;
            let touchendY = e.changedTouches[0].clientY;
            
            let deltaX = touchendX - touchstartX;
            let deltaY = Math.abs(touchendY - touchstartY);
            
            // Mobile Double tap
            let currentTime = new Date().getTime();
            let tapLength = currentTime - lastTapTime;
            
            if (tapLength < 500 && tapLength > 0 && Math.abs(deltaX) < 20 && deltaY < 20) {
                e.preventDefault();
                if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; }
                cancelHold();
                triggerLike();
                lastTapTime = 0;
            } else {
                cancelHold();
                lastTapTime = currentTime;
            }
        });

        card.addEventListener('touchcancel', () => {
            cancelHold();
        });

        // MOUSE LISTENERS
        card.addEventListener('mousedown', e => {
            if (e.button !== 0) return; // Left click only
            if (e.detail > 1) {
                cancelHold();
                return;
            }
            touchstartX = e.clientX;
            touchstartY = e.clientY;
            startHold(e);
        });

        card.addEventListener('mousemove', e => {
            if (!isHolding) return;
            let dist = Math.sqrt(Math.pow(e.clientX - touchstartX, 2) + Math.pow(e.clientY - touchstartY, 2));
            if (dist > 15) {
                cancelHold();
            }
        });

        card.addEventListener('mouseup', () => {
            cancelHold();
        });

        card.addEventListener('mouseleave', () => {
            cancelHold();
        });

        // Click handler with delay to allow double click/tap
        card.addEventListener('click', e => {
            // Ignore if clicked on a button or an explicit link inside
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;

            const detailUrl = card.dataset.url;
            if (!detailUrl) return; // public_show doesn't have data-url

            // Delay for double click check
            if (e.detail === 1) { // Single click
                clickTimer = setTimeout(() => {
                    window.location.href = detailUrl;
                }, 300);
            }
        });

        // Desktop Double Click
        card.addEventListener('dblclick', e => {
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;
            e.preventDefault();
            if (clickTimer) {
                clearTimeout(clickTimer);
                clickTimer = null;
            }
            triggerLike();
        });
    });
});
</script>
