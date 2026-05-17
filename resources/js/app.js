import './bootstrap';

// Toast auto-dismiss
document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => toast.remove(), 5000);
    });

    // Bid countdown timers
    document.querySelectorAll('[data-countdown]').forEach(el => {
        const endTime = new Date(el.dataset.countdown).getTime();
        const update = () => {
            const now = Date.now();
            const diff = endTime - now;
            if (diff <= 0) {
                el.innerHTML = '<span class="text-error font-bold">Ended</span>';
                return;
            }
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const mins = Math.floor((diff % 3600000) / 60000);
            const secs = Math.floor((diff % 60000) / 1000);

            el.innerHTML = `
                <div class="flex items-center gap-2">
                    ${days > 0 ? `<div class="flex flex-col items-center justify-center bg-surface-container-high/50 border border-white/10 rounded-lg w-[50px] h-[50px]"><div class="font-mono text-lg font-bold text-white leading-none">${days}</div><div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mt-1">Days</div></div>` : ''}
                    <div class="flex flex-col items-center justify-center bg-surface-container-high/50 border border-white/10 rounded-lg w-[50px] h-[50px]"><div class="font-mono text-lg font-bold text-white leading-none">${String(hours).padStart(2, '0')}</div><div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mt-1">Hrs</div></div>
                    <div class="flex flex-col items-center justify-center bg-surface-container-high/50 border border-white/10 rounded-lg w-[50px] h-[50px]"><div class="font-mono text-lg font-bold text-white leading-none">${String(mins).padStart(2, '0')}</div><div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mt-1">Min</div></div>
                    <div class="flex flex-col items-center justify-center bg-surface-container-high/50 border border-secondary-fixed/30 rounded-lg w-[50px] h-[50px] shadow-[0_0_10px_rgba(195,244,0,0.1)]"><div class="font-mono text-lg font-bold text-secondary-fixed leading-none">${String(secs).padStart(2, '0')}</div><div class="font-label-caps text-[8px] text-secondary-fixed tracking-widest uppercase mt-1">Sec</div></div>
                </div>
            `;
        };
        update();
        setInterval(update, 1000);
    });

    // Compact Bid countdown timers for tables
    document.querySelectorAll('[data-compact-countdown]').forEach(el => {
        const endTime = new Date(el.dataset.compactCountdown).getTime();
        const update = () => {
            const now = Date.now();
            const diff = endTime - now;
            if (diff <= 0) {
                el.innerHTML = '<span class="text-error font-body-md text-sm">Ended</span>';
                return;
            }
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const mins = Math.floor((diff % 3600000) / 60000);
            const secs = Math.floor((diff % 60000) / 1000);

            let text = '';
            if (days > 0) text += `<span class="font-bold text-white">${days}d</span> `;
            text += `<span class="font-bold text-white">${String(hours).padStart(2, '0')}h</span> `;
            text += `<span class="font-bold text-white">${String(mins).padStart(2, '0')}m</span> `;
            text += `<span class="font-bold text-secondary-fixed">${String(secs).padStart(2, '0')}s</span>`;

            el.innerHTML = `<div class="font-mono text-base tracking-wider inline-flex items-center gap-1.5 bg-surface-container-high border border-white/20 px-4 py-2 rounded-full shadow-[0_0_15px_rgba(0,0,0,0.3)]">${text}</div>`;
        };
        update();
        setInterval(update, 1000);
    });

    // Text-only Bid countdown timers (no outer wrapper)
    document.querySelectorAll('[data-text-countdown]').forEach(el => {
        const endTime = new Date(el.dataset.textCountdown).getTime();
        const update = () => {
            const now = Date.now();
            const diff = endTime - now;
            if (diff <= 0) {
                el.innerHTML = 'Ended';
                return;
            }
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const mins = Math.floor((diff % 3600000) / 60000);
            const secs = Math.floor((diff % 60000) / 1000);

            let text = '';
            if (days > 0) text += `${days}d `;
            text += `${String(hours).padStart(2, '0')}h `;
            text += `${String(mins).padStart(2, '0')}m `;
            text += `${String(secs).padStart(2, '0')}s`;

            el.innerHTML = text;
        };
        update();
        setInterval(update, 1000);
    });

    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Auto-refresh bid status (polling every 10s)
    const bidStatusElements = document.querySelectorAll('[data-bid-session]');
    if (bidStatusElements.length > 0) {
        setInterval(() => {
            bidStatusElements.forEach(async (el) => {
                const sessionId = el.dataset.bidSession;
                try {
                    const res = await fetch(`/api/bids/${sessionId}`);
                    const data = await res.json();
                    if (data.success) {
                        const priceEl = el.querySelector('.current-price');
                        const countEl = el.querySelector('.bid-count');
                        if (priceEl) priceEl.textContent = '₹' + parseFloat(data.data.current_price).toLocaleString('en-IN');
                        if (countEl) countEl.textContent = data.data.total_bids + ' bids';
                    }
                } catch (e) {}
            });
        }, 10000);
    }
});

// Image preview for file uploads
window.previewImages = function(input, previewContainer) {
    const container = document.getElementById(previewContainer);
    if (!container) return;
    container.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-20 h-20 object-cover rounded-lg border border-white/10';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
};
