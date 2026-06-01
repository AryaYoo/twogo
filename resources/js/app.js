import './bootstrap';

// Toast Notification System
window.showToast = function(message, type = 'success', duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `nb-toast nb-toast-${type}`;
    
    // Icon based on type
    let icon = '';
    if (type === 'success') icon = '✅';
    else if (type === 'error') icon = '🚨';
    else if (type === 'warning') icon = '⚠️';
    else icon = 'ℹ️';
    
    toast.innerHTML = `
        <div class="text-xl">${icon}</div>
        <div class="flex-1">
            <p class="font-bold text-sm">${message}</p>
        </div>
        <button class="text-xl opacity-70 hover:opacity-100">&times;</button>
    `;
    
    container.appendChild(toast);
    
    const dismiss = () => {
        toast.classList.add('nb-toast-fadeout');
        setTimeout(() => toast.remove(), 300);
    };
    
    toast.querySelector('button').addEventListener('click', dismiss);
    setTimeout(dismiss, duration);
};

// Modal Bottom Sheet System
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        modal.querySelectorAll('.nb-modal-overlay, .nb-modal, .nb-bottom-sheet').forEach(el => {
            el.classList.add('active');
            el.classList.add('nb-modal-active');
        });
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        modal.querySelectorAll('.nb-modal-overlay, .nb-modal, .nb-bottom-sheet').forEach(el => {
            el.classList.remove('active');
            el.classList.remove('nb-modal-active');
        });
        document.body.style.overflow = '';
    }
};

// Close modal when clicking overlay
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('nb-modal-overlay')) {
        const modal = e.target.closest('div[id]');
        if (modal) closeModal(modal.id);
    }
});

// Scroll Reveal Animation (Intersection Observer)
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
});

// Confirm Delete Dialog
window.confirmDelete = function(formId, message = 'Yakin mau menghapus data ini?') {
    if (confirm(message)) {
        document.getElementById(formId).submit();
    }
};

// Format Rupiah Helper
window.formatRupiah = function(number) {
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    }).format(number);
};

window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
