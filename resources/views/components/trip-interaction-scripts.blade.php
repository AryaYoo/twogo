<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.trip-interaction-card');
    
    cards.forEach(card => {
        let touchstartX = 0;
        let touchstartY = 0;
        let lastTapTime = 0;
        let clickTimer = null;
        
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
                card.style.transition = 'transform 0.3s ease-out';
                card.style.transform = 'translateX(50px)';
                
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

        card.addEventListener('touchstart', e => {
            touchstartX = e.changedTouches[0].screenX;
            touchstartY = e.changedTouches[0].screenY;
        }, {passive: true});

        card.addEventListener('touchend', e => {
            let touchendX = e.changedTouches[0].screenX;
            let touchendY = e.changedTouches[0].screenY;
            
            let deltaX = touchendX - touchstartX;
            let deltaY = Math.abs(touchendY - touchstartY);
            
            // Swipe right
            if (deltaX > 80 && deltaY < 50) {
                e.preventDefault();
                triggerClone();
                return;
            }

            // Mobile Double tap
            let currentTime = new Date().getTime();
            let tapLength = currentTime - lastTapTime;
            if (tapLength < 500 && tapLength > 0 && Math.abs(deltaX) < 20 && deltaY < 20) {
                e.preventDefault();
                if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; }
                triggerLike();
                lastTapTime = 0;
            } else {
                lastTapTime = currentTime;
            }
        });

        // Click handler with delay to allow double click/tap
        card.addEventListener('click', e => {
            // Ignore if clicked on a button or an explicit link inside
            if (e.target.closest('a') || e.target.closest('button')) return;

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
            if (e.target.closest('a') || e.target.closest('button')) return;
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
