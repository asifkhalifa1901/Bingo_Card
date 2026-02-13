import './bootstrap';

// Lightweight decorative JS for nav + smooth scrolling + live preview sync
document.addEventListener('DOMContentLoaded', () => {
    // Mobile nav toggle
    const toggles = document.querySelectorAll('[data-nav-toggle]');
    const menus = document.querySelectorAll('[data-nav-menu]');

    if (toggles.length && menus.length) {
        toggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                menus.forEach((menu) => {
                    const isOpen = menu.getAttribute('data-open') === 'true';
                    menu.setAttribute('data-open', String(!isOpen));
                    menu.classList.toggle('hidden', isOpen);
                });
            });
        });
    }

    // Soft fade-in for main content
    const main = document.querySelector('main');
    if (main) {
        main.style.opacity = '0';
        main.style.transform = 'translateY(6px)';
        requestAnimationFrame(() => {
            main.style.transition = 'opacity 260ms ease-out, transform 260ms ease-out';
            main.style.opacity = '1';
            main.style.transform = 'translateY(0)';
        });
    }

    // Live sync between edit inputs and preview cells on /cards/{uuid}/edit
    const textInputs = document.querySelectorAll('[data-cell-text-index]');
    const imageInputs = document.querySelectorAll('[data-cell-image-index]');

    textInputs.forEach((input) => {
        const idx = input.getAttribute('data-cell-text-index');
        const target = document.querySelector(`[data-preview-cell-index="${idx}"] [data-preview-text]`);
        if (!target) return;
        const sync = () => {
            target.textContent = input.value || '';
        };
        input.addEventListener('input', sync);
    });

    imageInputs.forEach((input) => {
        const idx = input.getAttribute('data-cell-image-index');
        const target = document.querySelector(`[data-preview-cell-index="${idx}"] [data-preview-image]`);
        if (!target) return;
        const sync = () => {
            target.textContent = input.value || '';
        };
        input.addEventListener('input', sync);
    });

    // Optional: smooth scroll for internal anchor links (works with CSS scroll-behavior)
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const targetId = anchor.getAttribute('href')?.slice(1);
            if (!targetId) return;
            const target = document.getElementById(targetId);
            if (!target) return;

            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
