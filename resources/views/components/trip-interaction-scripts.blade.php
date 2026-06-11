<script>
(function () {
    'use strict';

    // ── Cached globals (parsed once, shared across all cards) ──────────────
    const CSRF    = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    const rel     = url => { try { return new URL(url, location.origin).pathname; } catch { return url || ''; } };
    const dist2   = (ax, ay, bx, by) => (ax - bx) ** 2 + (ay - by) ** 2; // avoids Math.sqrt

    // ── Init all cards when DOM is ready ───────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.trip-interaction-card').forEach(initCard);
    });

    function initCard(card) {
        // Parse & cache data attributes once per card
        const doubleTap = card.dataset.doubleTap === 'true';
        const likeUrl   = rel(card.dataset.likeUrl);
        const cloneUrl  = rel(card.dataset.cloneUrl);
        const detailUrl = rel(card.dataset.url);

        // State
        let tx = 0, ty = 0;       // touch/mouse start coords
        let lastTap = 0;
        let clickTimer = null;
        let holdTimer  = null;
        let isHolding  = false;
        let bar = null, badge = null;

        // ── Heart icon (only when double-tap is enabled) ───────────────────
        let heart = null;
        if (doubleTap) {
            heart = document.createElement('div');
            heart.setAttribute('aria-hidden', 'true');
            heart.style.cssText = [
                'position:absolute;inset:0;display:flex;align-items:center',
                'justify-content:center;opacity:0;pointer-events:none;z-index:50',
                'transition:opacity .25s ease,transform .25s ease;transform:scale(.5)',
            ].join(';');
            heart.innerHTML = '<span style="font-size:4.5rem;filter:drop-shadow(0 4px 6px rgba(0,0,0,.3))">❤️</span>';
            card.appendChild(heart);
        }

        // ── triggerLike ───────────────────────────────────────────────────
        function triggerLike() {
            if (!heart || !likeUrl) return;

            // Instant visual feedback before network
            heart.style.transform = 'scale(1.15)';
            heart.style.opacity   = '1';
            setTimeout(() => {
                heart.style.transform = 'scale(1.5)';
                heart.style.opacity   = '0';
                setTimeout(() => { heart.style.transform = 'scale(.5)'; }, 220);
            }, 480);

            fetch(likeUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                const span = card.querySelector('.like-count-text');
                if (span) span.textContent = '❤️ ' + data.count;

                const ico   = document.getElementById('like-icon');
                const cnt   = document.getElementById('like-count');
                const btn   = document.getElementById('like-btn');
                if (ico) ico.textContent = data.liked ? '❤️' : '🤍';
                if (cnt) cnt.textContent = data.count;
                if (btn) {
                    btn.classList.toggle('bg-[#FF6B9D]',    data.liked);
                    btn.classList.toggle('text-white',       data.liked);
                    btn.classList.toggle('bg-white',         !data.liked);
                    btn.classList.toggle('text-[#1A1A2E]',   !data.liked);
                }
            })
            .catch(() => {});
        }

        // ── triggerClone (fetch-based, no form submit) ────────────────────
        function triggerClone() {
            if (!cloneUrl) return;
            fetch(cloneUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json',
                           'Content-Type': 'application/json' },
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => { location.href = (data && data.redirect) ? data.redirect : '/trips'; })
            .catch(() => { location.href = '/trips'; });
        }

        // ── Hold start ────────────────────────────────────────────────────
        function startHold(e) {
            if (e.target.closest('a,button,input')) return;
            if (!cloneUrl) return;

            isHolding = true;

            // Progress bar (inline style — no Tailwind class parsing overhead)
            bar = document.createElement('div');
            bar.style.cssText = [
                'position:absolute;bottom:0;left:0;height:8px;width:0%',
                'background:#4361EE;border-top:2px solid #1A1A2E;z-index:40',
                'transition:width 600ms linear,background-color .15s ease',
            ].join(';');
            card.appendChild(bar);

            // Badge
            badge = document.createElement('div');
            badge.textContent = '⏱️ Tahan untuk menyimpan...';
            badge.style.cssText = [
                'position:absolute;top:10px;left:50%;transform:translateX(-50%)',
                'background:#1A1A2E;color:#fff;font-size:10px;font-weight:700',
                'padding:4px 10px;border-radius:6px;border:2px solid #fff',
                'box-shadow:2px 2px 0 rgba(0,0,0,.2);z-index:40;white-space:nowrap',
                'pointer-events:none;user-select:none;transition:opacity .2s ease',
            ].join(';');
            card.appendChild(badge);

            // Card press-down effect
            card.style.transition = 'transform 600ms cubic-bezier(.25,.8,.25,1)';
            card.style.transform  = 'scale(0.97)';

            // Force reflow so CSS transition fires from 0% → 100%
            bar.getBoundingClientRect();
            bar.style.width = '100%';

            holdTimer = setTimeout(() => {
                if (!isHolding) return;
                // Success state
                bar.style.background    = '#00D4AA';
                badge.textContent       = '📋 Menyalin...';
                badge.style.background  = '#00D4AA';
                badge.style.color       = '#1A1A2E';
                badge.style.borderColor = '#1A1A2E';
                badge.style.boxShadow   = '2px 2px 0 #1A1A2E';
                navigator.vibrate?.(50);
                isHolding = false;
                triggerClone();
            }, 600);
        }

        // ── Hold cancel ───────────────────────────────────────────────────
        function cancelHold() {
            if (!isHolding) return;
            isHolding = false;
            clearTimeout(holdTimer); holdTimer = null;

            card.style.transition = 'transform .2s cubic-bezier(.175,.885,.32,1.275)';
            card.style.transform  = 'none';

            [bar, badge].forEach(el => {
                if (!el) return;
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 200);
            });
            bar = badge = null;
        }

        // ── Touch events ──────────────────────────────────────────────────
        card.addEventListener('touchstart', e => {
            tx = e.touches[0].clientX;
            ty = e.touches[0].clientY;
            startHold(e);
        }, { passive: true });

        card.addEventListener('touchmove', e => {
            // Cancel hold if finger moved > 15px (225 = 15²)
            if (isHolding && dist2(e.touches[0].clientX, e.touches[0].clientY, tx, ty) > 225) {
                cancelHold();
            }
        }, { passive: true });

        card.addEventListener('touchend', e => {
            const ex = e.changedTouches[0].clientX;
            const ey = e.changedTouches[0].clientY;
            cancelHold();

            if (doubleTap) {
                const now = Date.now();
                const gap = now - lastTap;
                // Double tap: < 400ms gap & finger didn't move > 20px
                if (gap > 0 && gap < 400 && dist2(ex, ey, tx, ty) < 400) {
                    e.preventDefault();
                    clearTimeout(clickTimer); clickTimer = null;
                    triggerLike();
                    lastTap = 0;
                } else {
                    lastTap = now;
                }
            }
        });

        card.addEventListener('touchcancel', cancelHold);

        // ── Mouse events ──────────────────────────────────────────────────
        card.addEventListener('mousedown', e => {
            if (e.button !== 0) return;
            if (doubleTap && e.detail > 1) { cancelHold(); return; }
            tx = e.clientX; ty = e.clientY;
            startHold(e);
        });

        card.addEventListener('mousemove', e => {
            if (isHolding && dist2(e.clientX, e.clientY, tx, ty) > 225) cancelHold();
        });

        card.addEventListener('mouseup',    cancelHold);
        card.addEventListener('mouseleave', cancelHold);

        // ── Click / navigate ──────────────────────────────────────────────
        card.addEventListener('click', e => {
            if (e.target.closest('a,button,input')) return;
            if (!detailUrl) return;

            if (doubleTap) {
                // Delay 300ms so dblclick can cancel it
                if (e.detail === 1) {
                    clickTimer = setTimeout(() => { location.href = detailUrl; }, 300);
                }
            } else {
                location.href = detailUrl; // instant (no double-tap delay)
            }
        });

        // Desktop double-click to like
        card.addEventListener('dblclick', e => {
            if (!doubleTap) return;
            if (e.target.closest('a,button,input')) return;
            e.preventDefault();
            clearTimeout(clickTimer); clickTimer = null;
            triggerLike();
        });
    }
})();
</script>
