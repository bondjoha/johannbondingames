document.addEventListener('DOMContentLoaded', () => 
    {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) { // ensure elements exist
        togglePassword.addEventListener('click', function () 
        {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            this.querySelector('i').classList.toggle('bi-eye-fill');
            this.querySelector('i').classList.toggle('bi-eye-slash-fill');
        });
    }
});
