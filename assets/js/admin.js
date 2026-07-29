/* ==========================================================================
   Student Attendance Tracking Portal - Senior UI/UX JavaScript Suite
   Vercel / Linear / Clerk / Framer Inspired Interaction & Animation Engine
   ========================================================================== */

// 1. Chart.js Engine Global Premium Dark Defaults (No Animations)
if (window.Chart) {
    Chart.defaults.animation = false;
    Chart.defaults.animations = false;
    Chart.defaults.transitions = {
        active: { animation: { duration: 0 } },
        resize: { animation: { duration: 0 } }
    };
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = '#0B1730';
    Chart.defaults.plugins.tooltip.borderColor = 'rgba(79, 124, 255, 0.3)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 10;
    Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
    Chart.defaults.plugins.tooltip.bodyColor = '#94a3b8';
}

// 2. DataTables Global Pagination Symbols
if (window.jQuery && $.fn.dataTable) {
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            paginate: {
                previous: '<i class="fa-solid fa-angle-left"></i>',
                next: '<i class="fa-solid fa-angle-right"></i>'
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // A. Page Loader Fade Out
    const appLoader = document.getElementById('appLoader');
    if (appLoader) {
        setTimeout(function() {
            appLoader.style.opacity = '0';
            appLoader.style.pointerEvents = 'none';
            setTimeout(function() {
                if (appLoader.parentNode) appLoader.parentNode.removeChild(appLoader);
            }, 400);
        }, 250);
    }

    // B. Global Modal Stacking & Backdrop Cleanup Guard (Prevents UI Freezing on Modal Show/Hide)
    document.addEventListener('show.bs.modal', function (e) {
        const modal = e.target;
        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        if (modal) {
            modal.style.zIndex = '1060';
            setTimeout(function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(b) {
                    b.style.zIndex = '1050';
                });
            }, 10);
        }
    });

    document.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(function(el) {
            el.remove();
        });
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });

    // C. Dynamic Time-based Greeting & Daily Quotes / Tips
    updateDynamicGreeting();
    initDailyQuotesAndTips();

    // D. Smooth Mouse Cursor Glow Follower
    initCursorGlow();

    // E. Animated Number Counting (CountUp Effect)
    setTimeout(animateCountUpNumbers, 300);

    // F. Initialize Theme-Matching Custom Calendar Picker
    if (window.flatpickr && document.getElementById('topbarDatePicker')) {
        flatpickr('#topbarDatePicker', {
            dateFormat: 'Y-m-d',
            defaultDate: 'today',
            disableMobile: 'true',
            animate: true
        });
    }

    // G. Sidebar Toggle & Collapse Handlers
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');

    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
        document.body.classList.toggle('sidebar-open');
        
        const topbarToggleIcon = document.getElementById('topbarToggleIcon');
        if (topbarToggleIcon) {
            if (document.body.classList.contains('sidebar-collapsed')) {
                topbarToggleIcon.className = 'fa-solid fa-chevron-right';
            } else {
                topbarToggleIcon.className = 'fa-solid fa-bars';
            }
        }
    }

    if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
    if (sidebarCollapseBtn) sidebarCollapseBtn.addEventListener('click', toggleSidebar);

    // H. Material Design Ripple Effect on Buttons
    initMaterialRipple();

    // I. Desktop Magnetic Buttons & Card 3D Tilt Effect
    if (window.innerWidth >= 992) {
        initMagneticButtons();
        initCardTilt();
    }

    // J. Intersection Observer Scroll Reveal
    initScrollReveal();

    // K. Enterprise GSAP Cinematic Intro Sequence
    initCinematicIntro();

    // L. Spotlight Cursor Gradient Follower
    initSpotlightCards();

    // M. Command Palette (CTRL + K) Engine
    initCommandPalette();

    // N. Real-Time Live Clock Engine
    initLiveClock();

    // O. Seamless Page Exit & Enter Transitions
    initPageTransitions();

    // P. Premium Chart Card Entrance & Observer Trigger
    initChartCardAnimations();
});

/**
 * Material Design Ripple Click Effect
 */
function initMaterialRipple() {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('button, .btn, .btn-primary-action, .btn-action-edit, .btn-action-delete, .btn-action-view');
        if (!btn) return;

        btn.classList.add('ripple-container');
        const rect = btn.getBoundingClientRect();
        const circle = document.createElement('span');
        const diameter = Math.max(rect.width, rect.height);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${e.clientX - rect.left - radius}px`;
        circle.style.top = `${e.clientY - rect.top - radius}px`;
        circle.classList.add('ripple-circle');

        const existingRipple = btn.querySelector('.ripple-circle');
        if (existingRipple) existingRipple.remove();

        btn.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
    });
}

/**
 * Desktop Magnetic Button Effect (Max 4px movement)
 */
function initMagneticButtons() {
    const magneticBtns = document.querySelectorAll('.btn-primary-action, .btn-magnetic');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', function(e) {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate3d(${x * 0.15}px, ${y * 0.15}px, 0) scale(1.02)`;
        });
        btn.addEventListener('mouseleave', function() {
            btn.style.transform = 'translate3d(0, 0, 0) scale(1)';
        });
    });
}

/**
 * Desktop 3D Card Tilt Effect (Max 3° Tilt)
 */
function initCardTilt() {
    const cards = document.querySelectorAll('.stat-card, .data-card');
    cards.forEach(card => {
        card.classList.add('tilt-card');
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -2.5;
            const rotateY = ((x - centerX) / centerX) * 2.5;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translate3d(0, -3px, 0)`;
        });
        card.addEventListener('mouseleave', function() {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0)';
        });
    });
}

/**
 * Intersection Observer for Scroll Reveal
 */
function initScrollReveal() {
    const elements = document.querySelectorAll('.data-card, .stat-card, .page-header-container, .reveal-on-scroll');
    if (!('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(el => {
        el.classList.add('reveal-on-scroll');
        observer.observe(el);
    });
}

/**
 * Dynamic Greeting depending on time
 */
function updateDynamicGreeting() {
    const greetingEl = document.getElementById('dynamicGreeting');
    if (!greetingEl) return;

    const hour = new Date().getHours();
    let text = 'Good Morning 👋';

    if (hour >= 12 && hour < 17) {
        text = 'Good Afternoon 👋';
    } else if (hour >= 17 || hour < 5) {
        text = 'Good Evening 👋';
    }

    greetingEl.innerText = text;
}

/**
 * Daily Motivational Quotes & Tip of the Day Engine
 */
function initDailyQuotesAndTips() {
    const quotes = [
        { text: "Education is the key to unlocking the world, a passport to freedom.", author: "Oprah Winfrey" },
        { text: "Quality is not an act, it is a habit.", author: "Aristotle" },
        { text: "Excellence is never an accident; it is the result of high intention and sincere effort.", author: "Aristotle" },
        { text: "Focus on being productive instead of busy.", author: "Tim Ferriss" },
        { text: "Small daily improvements over time lead to stunning achievements.", author: "Robin Sharma" }
    ];

    const tips = [
        "Allocate subjects to faculty members before scheduling attendance classes.",
        "Verify department registration codes to ensure smooth course enrollment.",
        "Use the active academic session toggle to switch university terms quickly.",
        "Export attendance and course records directly to CSV or Excel from data tables.",
        "Ensure all semester division capacities are configured before registering students."
    ];

    const dayOfYear = Math.floor((new Date() - new Date(new Date().getFullYear(), 0, 0)) / 1000 / 60 / 60 / 24);
    
    const quoteEl = document.getElementById('dailyQuoteText');
    const authorEl = document.getElementById('dailyQuoteAuthor');
    if (quoteEl && authorEl) {
        const q = quotes[dayOfYear % quotes.length];
        quoteEl.innerText = `"${q.text}"`;
        authorEl.innerText = `— ${q.author}`;
    }

    const tipEl = document.getElementById('dailyTipText');
    if (tipEl) {
        tipEl.innerText = tips[dayOfYear % tips.length];
    }
}

/**
 * Smooth Cursor Glow Follower
 */
function initCursorGlow() {
    const glow = document.getElementById('cursorGlow');
    if (!glow) return;

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let currentX = mouseX;
    let currentY = mouseY;

    window.addEventListener('mousemove', function (e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateGlow() {
        currentX += (mouseX - currentX) * 0.1;
        currentY += (mouseY - currentY) * 0.1;
        glow.style.transform = `translate3d(${currentX - 150}px, ${currentY - 150}px, 0)`;
        requestAnimationFrame(animateGlow);
    }
    animateGlow();
}

/**
 * Animated Number Counting (CountUp Animation with Easing)
 */
function animateCountUpNumbers() {
    const elements = document.querySelectorAll('.stat-value, [id^="dash"], [id^="stat"]');
    elements.forEach(function (el) {
        const text = el.innerText.trim();
        const num = parseInt(text, 10);
        if (!isNaN(num) && num > 0 && !el.dataset.animated) {
            el.dataset.animated = 'true';
            const duration = 1000;
            const startTime = performance.now();

            function updateCount(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeProgress = 1 - Math.pow(1 - progress, 3);
                const currentVal = Math.floor(easeProgress * num);
                el.innerText = currentVal;
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    el.innerText = num;
                }
            }
            requestAnimationFrame(updateCount);
        }
    });
}

/**
 * CSRF Token Helper
 */
function getCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken && metaToken.getAttribute('content')) {
        return metaToken.getAttribute('content');
    }
    const inputToken = document.querySelector('input[name="csrf_token"]');
    return inputToken ? inputToken.value : '';
}

/**
 * SweetAlert2 Animated Toast Notification Helper
 */
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        showClass: { popup: 'animate__animated animate__fadeInRight animate__faster' },
        hideClass: { popup: 'animate__animated animate__fadeOutRight animate__faster' },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

/**
 * SweetAlert2 Alert Dialog Helper
 */
function showAlert(title, message, type = 'info') {
    return Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonColor: '#4F7CFF',
        customClass: {
            confirmButton: 'btn btn-primary-action px-4'
        }
    });
}

/**
 * SweetAlert2 Confirmation Dialog Helper
 */
function showConfirm(title, text, confirmText = 'Yes, proceed', type = 'warning') {
    return Swal.fire({
        title: title,
        text: text,
        icon: type,
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
}

/**
 * Toggle Button Loading State
 */
function setButtonLoading(button, isLoading, loadingText = 'Processing...') {
    if (!button) return;
    if (isLoading) {
        button.dataset.originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
    } else {
        button.disabled = false;
        if (button.dataset.originalHtml) {
            button.innerHTML = button.dataset.originalHtml;
        }
    }
}

/**
 * 1. Enterprise GSAP Cinematic Dashboard Intro Sequence
 */
function initCinematicIntro() {
    if (!window.gsap) return;

    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

    // Step 1: Ambient background overlays
    tl.fromTo('.ambient-orbs-container, .bg-grid-overlay, .bg-mesh-overlay', 
        { opacity: 0 }, 
        { opacity: 1, duration: 0.5 }
    );

    // Step 2: Sidebar slide from left
    if (document.getElementById('sidebar')) {
        tl.fromTo('#sidebar', 
            { x: -75, opacity: 0 }, 
            { x: 0, opacity: 1, duration: 0.75, ease: "expo.out" }, 
            "-=0.3"
        );
    }

    // Step 3: Topbar slide from top
    if (document.querySelector('.topbar')) {
        tl.fromTo('.topbar', 
            { y: -45, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.65, ease: "power4.out" }, 
            "-=0.5"
        );
    }

    // Step 4: Greeting & Headers
    if (document.querySelectorAll('.topbar-welcome, .dashboard-hero-title, .page-header-container').length) {
        tl.fromTo('.topbar-welcome, .dashboard-hero-title, .page-header-container', 
            { y: 20, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.6, ease: "expo.out" }, 
            "-=0.4"
        );
    }

    // Step 5: Stat cards stagger
    if (document.querySelectorAll('.stat-card').length) {
        tl.fromTo('.stat-card', 
            { y: 30, opacity: 0, scale: 0.96 }, 
            { y: 0, opacity: 1, scale: 1, duration: 0.6, stagger: 0.07, ease: "back.out(1.2)" }, 
            "-=0.3"
        );
    }

    // Step 6: Hero cards & Data cards stagger (Excludes graph cards from popping)
    const animCards = Array.from(document.querySelectorAll('.data-card, .dashboard-hero-card')).filter(card => !card.querySelector('canvas') && !card.closest('.data-card:has(canvas)'));
    if (animCards.length) {
        tl.fromTo(animCards, 
            { y: 20, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.5, stagger: 0.06, ease: "expo.out" }, 
            "-=0.3"
        );
    }


    // Step 7: Tables & Quick Actions
    if (document.querySelectorAll('.dataTables_wrapper, .btn-primary-action, .cmd-palette-btn').length) {
        tl.fromTo('.dataTables_wrapper, .btn-primary-action, .cmd-palette-btn', 
            { y: 15, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.5, stagger: 0.05, ease: "power4.out" }, 
            "-=0.3"
        );
    }
}

/**
 * 2. Premium Spotlight Hover Gradient Follower
 */
function initSpotlightCards() {
    const cards = document.querySelectorAll('.stat-card, .data-card, .card');
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`);
            card.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`);
        });
    });
}

/**
 * 5. Command Palette (CTRL + K) JS Search Engine & Keyboard Navigation
 */
function initCommandPalette() {
    const triggerBtn = document.getElementById('cmdPaletteTrigger');
    const modalEl = document.getElementById('commandPaletteModal');
    if (!modalEl) return;

    const cmdModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const inputEl = document.getElementById('cmdPaletteInput');
    const resultsContainer = document.getElementById('cmdResultsList');

    if (triggerBtn) {
        triggerBtn.addEventListener('click', function() {
            cmdModal.show();
        });
    }

    // Global Shortcut Key Listener (CTRL + K or CMD + K)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            if (modalEl.classList.contains('show')) {
                cmdModal.hide();
            } else {
                cmdModal.show();
            }
        }
    });

    modalEl.addEventListener('shown.bs.modal', function() {
        if (inputEl) {
            inputEl.value = '';
            inputEl.focus();
            filterCmdItems('');
        }
    });

    if (inputEl) {
        inputEl.addEventListener('input', function() {
            filterCmdItems(this.value.toLowerCase().trim());
        });

        // Keyboard Arrow & Enter Navigation
        inputEl.addEventListener('keydown', function(e) {
            const items = Array.from(resultsContainer.querySelectorAll('.cmd-item:not([style*="display: none"])'));
            if (!items.length) return;

            let activeIndex = items.findIndex(item => item.classList.contains('active'));

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (activeIndex >= 0) items[activeIndex].classList.remove('active');
                activeIndex = (activeIndex + 1) % items.length;
                items[activeIndex].classList.add('active');
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (activeIndex >= 0) items[activeIndex].classList.remove('active');
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                items[activeIndex].classList.add('active');
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeIndex >= 0 && items[activeIndex]) {
                    items[activeIndex].click();
                } else if (items[0]) {
                    items[0].click();
                }
            }
        });
    }

    function filterCmdItems(query) {
        const items = resultsContainer.querySelectorAll('.cmd-item');
        items.forEach((item, index) => {
            const text = item.innerText.toLowerCase();
            if (!query || text.includes(query)) {
                item.style.display = 'flex';
                if (index === 0 && query) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            } else {
                item.style.display = 'none';
                item.classList.remove('active');
            }
        });
    }
}

/**
 * 7. Real-Time Live Clock Engine
 */
function initLiveClock() {
    const clockEl = document.getElementById('liveClock');
    if (!clockEl) return;

    function updateClock() {
        const now = new Date();
        clockEl.innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
    }
    updateClock();
    setInterval(updateClock, 1000);
}

/**
 * 9. Seamless Page Exit & Enter Transitions (GSAP)
 */
function initPageTransitions() {
    document.querySelectorAll('.sidebar-menu-link, .submenu-link, a.nav-link-transition').forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:') || link.getAttribute('target') === '_blank' || link.dataset.bsToggle) return;

        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.gsap) {
                gsap.to('.main-content, #sidebar', {
                    opacity: 0,
                    scale: 0.98,
                    y: 10,
                    duration: 0.35,
                    ease: "power4.in",
                    onComplete: function() {
                        window.location.href = href;
                    }
                });
            } else {
                window.location.href = href;
            }
        });
    });
}

/**
 * Premium Chart Card Entrance (GSAP & Intersection Observer)
 */
function initChartCardAnimations() {
    const chartContainers = document.querySelectorAll('.data-card:has(canvas), .stat-card:has(canvas), canvas');
    if (!chartContainers.length) return;

    chartContainers.forEach(el => {
        const card = el.closest('.data-card, .stat-card, .card') || el.parentElement;
        if (!card) return;
        card.style.opacity = '1';
        card.style.transform = 'none';
        card.style.filter = 'none';
        card.classList.add('animated-in');
    });
}


