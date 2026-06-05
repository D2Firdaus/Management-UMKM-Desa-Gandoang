// hamburger & sidebar toggle - robust and mobile-friendly
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const closeBtn = document.getElementById('sidebarCloseBtn') || document.querySelector('.sidebar-close-btn');

    // ensure there's an overlay element for mobile; create if missing
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('close');
        if (toggleBtn) toggleBtn.classList.add('active');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('close');
        if (toggleBtn) toggleBtn.classList.remove('active');
        overlay.classList.remove('show');
    }

    if (toggleBtn && sidebar) {
        // initialize closed state on mobile
        if (window.matchMedia('(max-width: 768px)').matches) {
            sidebar.classList.add('close');
        }

        toggleBtn.addEventListener('click', function () {
            if (sidebar.classList.contains('close')) openSidebar();
            else closeSidebar();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    overlay.addEventListener('click', closeSidebar);

    // auto-hide any success alert after 3s (if present)
    setTimeout(function () {
        const alertBox = document.querySelector('.alert_sukses_menambah');
        if (alertBox) alertBox.style.display = 'none';
    }, 3000);
    
    // Handle main navbar hamburger (nav-links) if present
    const hamburgerMain = document.getElementById('hamburger-main');
    const navLinksMain = document.getElementById('nav-links-main');
    function openNavMain() {
        if (!navLinksMain || !hamburgerMain) return;
        navLinksMain.classList.add('active');
        hamburgerMain.classList.add('active');
        document.body.style.overflow = 'hidden';
        overlay.classList.add('show');
    }
    function closeNavMain() {
        if (!navLinksMain || !hamburgerMain) return;
        navLinksMain.classList.remove('active');
        hamburgerMain.classList.remove('active');
        document.body.style.overflow = '';
        overlay.classList.remove('show');
    }
    if (hamburgerMain && navLinksMain) {
        hamburgerMain.addEventListener('click', function (e) {
            if (navLinksMain.classList.contains('active')) closeNavMain();
            else openNavMain();
        });

        // close nav when overlay clicked
        overlay.addEventListener('click', function () {
            if (navLinksMain.classList.contains('active')) closeNavMain();
            // also close sidebar handled above
            closeSidebar();
        });
    }
});