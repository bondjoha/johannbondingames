document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const warningText = document.getElementById('password-warning'); // Password requirement warning element

    // Function to validate password strength
    function validatePassword(pwd) {
        // Minimum 8 characters, at least 1 uppercase, 1 lowercase, 1 number, 1 special character
        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return pattern.test(pwd);
    }

    // Real-time password validation
    if (password && warningText) {
        password.addEventListener('input', () => {
            const value = password.value;

            // Check each requirement
            const minLength = value.length >= 8;
            const upper = /[A-Z]/.test(value);
            const lower = /[a-z]/.test(value);
            const number = /\d/.test(value);
            const special = /[\W_]/.test(value);

            if (minLength && upper && lower && number && special) {
                warningText.classList.add('hidden'); // Hide warning if all met
            } else {
                warningText.classList.remove('hidden'); // Show warning if any fails
            }
        });
    }

    // Password visibility toggle
    if (togglePassword && password) {
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Optional: toggle icon if using <i> element inside togglePassword
            const icon = togglePassword.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye-fill');
                icon.classList.toggle('bi-eye-slash-fill');
            }
        });
    }
});
