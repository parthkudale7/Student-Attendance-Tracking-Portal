/**
 * Client-Side Authentication Form Validation & Utilities
 * Cyberpunk UI Enhancements
 */

const CyberToast = {
    show: (message, type = 'success') => {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toastEl = document.createElement('div');
        // Match Vercel/Stripe SaaS style
        toastEl.className = `toast saas-toast mb-3 border-0`;
        if (type === 'success') {
            toastEl.style.borderLeft = '4px solid var(--emerald)';
        } else {
            toastEl.style.borderLeft = '4px solid #ef4444'; // Red for error
        }
        
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        toastEl.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-center">
                <div class="toast-body fw-medium" style="font-size: 0.95rem;">
                    ${type === 'success' ? '<i class="bi bi-check-circle-fill text-accent-emerald me-2"></i>' : '<i class="bi bi-exclamation-circle-fill text-danger me-2"></i>'}
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close" style="font-size: 0.7rem;"></button>
            </div>
        `;
        
        container.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }
};

(function () {
    'use strict';

    // Show/Hide Password Toggle
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
                this.classList.add('text-neon-blue');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
                this.classList.remove('text-neon-blue');
            }
        });
    });

    // Password Strength Meter
    const newPasswordInput = document.getElementById('new_password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    if (newPasswordInput && strengthBar) {
        newPasswordInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength += 25;
            if (/[A-Z]/.test(val)) strength += 25;
            if (/[a-z]/.test(val)) strength += 25;
            if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) strength += 25;

            strengthBar.style.width = strength + '%';
            if (strength < 50) {
                strengthBar.className = 'progress-bar bg-danger';
            } else if (strength < 100) {
                strengthBar.className = 'progress-bar bg-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
            }
        });
    }

    // Form Validation & AJAX Login
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            
            // Handle Password Change Form
            if (form.id === 'changePasswordForm') {
                const newPassword = document.getElementById('new_password');
                const confirmPassword = document.getElementById('confirm_password');

                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (newPassword && !regex.test(newPassword.value)) {
                    newPassword.setCustomValidity('Password does not meet requirements');
                    CyberToast.show('Password does not meet complexity requirements!', 'error');
                } else if (newPassword) {
                    newPassword.setCustomValidity('');
                }

                if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                    CyberToast.show('Passwords do not match!', 'error');
                } else if (confirmPassword) {
                    confirmPassword.setCustomValidity('');
                }
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                // Add shake animation to form
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 500);
            } else {
                // If it's the AJAX login form
                if (form.id === 'ajaxLoginForm') {
                    event.preventDefault();
                    
                    const submitBtn = document.getElementById('modalSubmitBtn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Authenticating...';
                    submitBtn.disabled = true;

                    // Mock Database Authentication
                    const mockUsers = {
                        'admin@portal.com': { password: 'Admin@123', id: 1, name: 'Super Admin', status: 'active', role: 'admin' },
                        'faculty@portal.com': { password: 'Faculty@123', id: 2, name: 'John Doe', status: 'active', role: 'faculty' },
                        'student@portal.com': { password: 'Student@123', id: 3, name: 'Jane Smith', status: 'active', role: 'student' }
                    };

                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData.entries());

                    setTimeout(() => {
                        const email = data.email;
                        const password = data.password;
                        const role = data.role;
                        
                        let result = { success: false, message: '' };

                        if (!mockUsers[email]) {
                            result.message = 'Account not found.';
                        } else {
                            const user = mockUsers[email];
                            if (user.password !== password) {
                                result.message = 'Invalid credentials.';
                            } else if (user.role !== role) {
                                result.message = 'Unauthorized role access.';
                            } else if (user.status !== 'active') {
                                result.message = 'Account is inactive.';
                            } else {
                                result = { success: true, redirect: `../${user.role}/dashboard.html` };
                                
                                // Save session using sessionStorage
                                sessionStorage.setItem('user_session', JSON.stringify({
                                    user_id: user.id,
                                    role: user.role,
                                    name: user.name,
                                    login_time: Date.now()
                                }));
                            }
                        }

                        if (result.success) {
                            // Show full screen overlay
                            const overlay = document.getElementById('loadingOverlay');
                            if(overlay) overlay.classList.add('active');
                            
                            setTimeout(() => {
                                window.location.href = result.redirect;
                            }, 1500); // 1.5s artificial delay for smooth transition
                        } else {
                            CyberToast.show(result.message || 'Authentication failed', 'error');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    }, 800); // 800ms fake network delay
                } else {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && form.id !== 'logoutForm') {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                        submitBtn.disabled = true;
                    }
                }
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
