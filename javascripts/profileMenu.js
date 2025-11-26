document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('user-profile-btn');
    const menu = document.getElementById('user-profile-menu');

    if (!btn || !menu) return;

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', () => {
        if (!menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    });
});
