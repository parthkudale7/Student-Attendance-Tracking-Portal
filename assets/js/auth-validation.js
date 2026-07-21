/**
 * Client-Side Authentication Form Validation
 * 
 * Implements standard Bootstrap 5 validation styles and checks.
 */

(function () {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission on invalid inputs
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            // Password confirmation check for change_password form
            if (form.id === 'changePasswordForm') {
                const newPassword = document.getElementById('new_password');
                const confirmPassword = document.getElementById('confirm_password');

                if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else if (confirmPassword) {
                    confirmPassword.setCustomValidity('');
                }
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
})();
