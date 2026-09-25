document.addEventListener('DOMContentLoaded', () => {
    // Donut et Menus déroulants réutilisables (Header: créations, notifications, avatar)
    const dropdownClosers = [];

    function closeAllDropdowns() {
        dropdownClosers.forEach((close) => close());
    }

    function setupDropdown(triggerId, panelId) {
        const trigger = document.getElementById(triggerId);
        const panel = document.getElementById(panelId);
        if (!trigger || !panel) return;

        function close() {
            panel.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
            const chev = trigger.querySelector('[data-chevron]');
            if (chev) chev.style.transform = '';
        }

        function open() {
            closeAllDropdowns();
            panel.classList.remove('hidden');
            trigger.setAttribute('aria-expanded', 'true');
            const chev = trigger.querySelector('[data-chevron]');
            if (chev) chev.style.transform = 'rotate(180deg)';
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';
            isOpen ? close() : open();
        });

        dropdownClosers.push(close);
    }

    setupDropdown('creations-trigger', 'creations-menu');
    setupDropdown('notif-trigger', 'notif-panel');
    setupDropdown('avatar-trigger', 'avatar-menu');

    document.addEventListener('click', closeAllDropdowns);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllDropdowns();
    });

    // Tiroir de navigation mobile
    const drawer = document.getElementById('mobile-drawer');
    const backdrop = document.getElementById('mobile-drawer-backdrop');
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('mobile-drawer-close');

    if (drawer && backdrop && menuBtn && closeBtn) {
        function openDrawer() {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
            menuBtn.setAttribute('aria-expanded', 'true');
            closeBtn.focus();
        }

        function closeDrawer() {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
            menuBtn.setAttribute('aria-expanded', 'false');
            menuBtn.focus();
        }

        menuBtn.addEventListener('click', openDrawer);
        closeBtn.addEventListener('click', closeDrawer);
        backdrop.addEventListener('click', closeDrawer);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !drawer.classList.contains('-translate-x-full')) {
                closeDrawer();
            }
        });
    }
});
