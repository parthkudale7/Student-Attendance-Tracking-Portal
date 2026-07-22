/**
 * Session Manager & Global Interactions
 * Handles inactivity timeout, logout confirmation, and premium modals.
 */

(function () {
    'use strict';

    const TIMEOUT_MINUTES = 30;
    const WARNING_SECONDS = 60;
    
    let sessionTimeoutTimer;
    let warningTimer;
    let warningModal;
    let logoutConfirmModal;
    
    function initSessionManager() {
        // 1. Create Session Warning Modal
        if (!document.getElementById('sessionWarningModal')) {
            const warningHtml = `
                <div class="modal fade lux-modal-wrapper" id="sessionWarningModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glass-card border-0 text-center p-4">
                            <div class="mb-3 text-warning">
                                <i class="bi bi-exclamation-circle fs-1"></i>
                            </div>
                            <h4 class="text-white mb-3">Session Timeout</h4>
                            <p class="text-secondary mb-4">
                                Your session will expire in <strong id="sessionCountdown" class="text-white">60</strong> seconds due to inactivity.
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="button" class="btn btn-outline-lux w-50" id="logoutNowBtn">Logout</button>
                                <button type="button" class="btn btn-primary-lux w-50" id="extendSessionBtn">Stay Logged In</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', warningHtml);
            
            document.getElementById('extendSessionBtn').addEventListener('click', extendSession);
            document.getElementById('logoutNowBtn').addEventListener('click', forceLogout);
        }
        
        warningModal = new bootstrap.Modal(document.getElementById('sessionWarningModal'));

        // 2. Create Logout Confirmation Modal
        if (!document.getElementById('logoutConfirmModal')) {
            const logoutHtml = `
                <div class="modal fade lux-modal-wrapper" id="logoutConfirmModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glass-card border-0 text-center p-4">
                            <div class="mb-3 text-danger">
                                <i class="bi bi-power fs-1"></i>
                            </div>
                            <h4 class="text-white mb-3">Confirm Logout</h4>
                            <p class="text-secondary mb-4">Are you sure you want to securely end your session?</p>
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="button" class="btn btn-outline-lux w-50" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary-lux w-50" id="confirmLogoutBtn">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', logoutHtml);
            
            document.getElementById('confirmLogoutBtn').addEventListener('click', () => {
                document.getElementById('confirmLogoutBtn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
                document.getElementById('confirmLogoutBtn').disabled = true;
                setTimeout(() => forceLogout(), 800); // 0.8s fake delay for premium feel
            });
        }
        logoutConfirmModal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));

        // Bind logout links
        document.querySelectorAll('a[href*="logout.php"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                logoutConfirmModal.show();
            });
        });
        
        resetTimers();
        
        // Reset timers on user interaction
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];
        events.forEach(event => {
            document.addEventListener(event, resetTimers, true);
        });
    }
    
    function resetTimers() {
        clearTimeout(sessionTimeoutTimer);
        clearTimeout(warningTimer);
        
        const totalMs = TIMEOUT_MINUTES * 60 * 1000;
        const warningMs = totalMs - (WARNING_SECONDS * 1000);
        
        warningTimer = setTimeout(showWarning, warningMs);
    }
    
    let countdownInterval;
    function showWarning() {
        warningModal.show();
        let secondsLeft = WARNING_SECONDS;
        document.getElementById('sessionCountdown').textContent = secondsLeft;
        
        countdownInterval = setInterval(() => {
            secondsLeft--;
            document.getElementById('sessionCountdown').textContent = secondsLeft;
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                forceLogout();
            }
        }, 1000);
    }
    
    function extendSession() {
        clearInterval(countdownInterval);
        warningModal.hide();
        resetTimers();
        if(typeof CyberToast !== 'undefined') {
            CyberToast.show('Session extended successfully.', 'success');
        }
    }
    
    function forceLogout() {
        window.location.href = '/xampp/student%20attendence%20tracking%20protocol/auth/logout.php';
    }
    
    // Only init if we are on a dashboard (not on landing page)
    if (document.querySelector('.main-content') || window.location.pathname.includes('dashboard')) {
        document.addEventListener('DOMContentLoaded', initSessionManager);
    }

})();
