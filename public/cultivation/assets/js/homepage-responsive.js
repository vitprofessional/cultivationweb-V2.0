/* Accessible homepage navigation; content and routing remain server-rendered. */
(function () {
    'use strict';
    const header = document.querySelector('.v2-homepage #rs-header');
    if (!header) return;
    const menu = header.querySelector('.menu-area');
    const navigation = header.querySelector('#primary-navigation');
    const toggle = header.querySelector('.rs-menu-toggle');
    const controls = Array.from(navigation.querySelectorAll('.rs-menu-link'));
    const mobile = window.matchMedia('(max-width: 1199px)');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const headerShell = header.closest('.full-width-header');
    const branding = menu.querySelector('.row.y-middle > div:first-child');
    let menuReturnY = null;
    let previousScrollAnchor = null;
    [toggle, ...controls].forEach(button => button.removeAttribute('onkeydown'));

    function setSubmenu(control, open) {
        control.setAttribute('aria-expanded', String(open));
        document.getElementById(control.getAttribute('aria-controls')).classList.toggle('visible', open);
    }
    function closeMenu(returnFocus) {
        const restoreY = menuReturnY;
        menuReturnY = null;
        headerShell.classList.remove('mobile-navigation-open');
        toggle.setAttribute('aria-expanded', 'false');
        navigation.classList.add('rs-menu-close');
        controls.forEach(control => setSubmenu(control, false));
        if (returnFocus) toggle.focus({ preventScroll: true });
        if (restoreY !== null) window.scrollTo({ top: restoreY, behavior: 'instant' });
        measureHeader();
        if (restoreY !== null) requestAnimationFrame(function () {
            if (menuReturnY === null) {
                window.scrollTo({ top: restoreY, behavior: 'instant' });
                document.documentElement.style.overflowAnchor = previousScrollAnchor || '';
                previousScrollAnchor = null;
            }
        });
    }
    function measureHeader() {
        const topbar = header.querySelector('.topbar-area');
        document.body.style.setProperty('--topbar-height', topbar.getBoundingClientRect().height + 'px');
        const brand = branding.getBoundingClientRect();
        document.body.style.setProperty('--brand-center', brand.height / 2 + 'px');
        document.body.style.setProperty('--open-brand-center', Math.max(brand.height / 2, brand.top + brand.height / 2) + 'px');
    }
    toggle.addEventListener('click', function () {
        const opening = toggle.getAttribute('aria-expanded') !== 'true';
        const openingScrollY = window.scrollY;
        closeMenu(false);
        if (opening && mobile.matches) {
            menuReturnY = openingScrollY;
            // Avoid automatic browser anchoring fighting explicit menu scroll restoration.
            if (previousScrollAnchor === null) previousScrollAnchor = document.documentElement.style.overflowAnchor;
            document.documentElement.style.overflowAnchor = 'none';
            headerShell.classList.add('mobile-navigation-open');
        }
        toggle.setAttribute('aria-expanded', String(opening));
        navigation.classList.toggle('rs-menu-close', !opening);
        if (opening && mobile.matches && menuReturnY > 0) window.scrollTo({ top: headerShell.offsetTop + header.querySelector('.topbar-area').offsetHeight, behavior: 'instant' });
        measureHeader();
    });
    controls.forEach(control => {
        control.addEventListener('click', function (event) {
            const opening = (!mobile.matches && event.detail > 0) || control.getAttribute('aria-expanded') !== 'true';
            controls.forEach(sibling => setSubmenu(sibling, false));
            setSubmenu(control, opening);
            measureHeader();
        });
        control.parentElement.addEventListener('mouseenter', function () {
            if (!mobile.matches) {
                controls.forEach(sibling => setSubmenu(sibling, sibling === control));
            }
        });
        control.parentElement.addEventListener('mouseleave', function () {
            if (!mobile.matches && !control.parentElement.contains(document.activeElement)) setSubmenu(control, false);
        });
    });
    document.addEventListener('click', event => {
        if (!header.contains(event.target) || event.target.closest('#primary-navigation a')) closeMenu(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        const expanded = controls.find(control => control.getAttribute('aria-expanded') === 'true');
        if (expanded) { setSubmenu(expanded, false); expanded.focus(); }
        else if (toggle.getAttribute('aria-expanded') === 'true') closeMenu(true);
    });
    navigation.addEventListener('focusout', event => {
        if (!navigation.contains(event.relatedTarget) && event.relatedTarget !== toggle) closeMenu(false);
    });
    mobile.addEventListener('change', () => closeMenu(false));
    window.addEventListener('resize', measureHeader);
    window.addEventListener('scroll', measureHeader, { passive: true });
    new ResizeObserver(measureHeader).observe(menu);
    measureHeader();

    // Owl 2 generates div controls. Supply keyboard semantics without replacing the carousel.
    function labelCarousel() {
        const carousel = document.querySelector('.rs-slider .owl-carousel');
        const buttons = carousel.querySelectorAll('.owl-prev, .owl-next, .owl-dot');
        buttons.forEach((button, index) => {
            button.setAttribute('role', 'button');
            button.setAttribute('tabindex', '0');
            button.setAttribute('aria-label', button.classList.contains('owl-prev') ? 'Previous slide' : button.classList.contains('owl-next') ? 'Next slide' : 'Show slide ' + (Array.from(carousel.querySelectorAll('.owl-dot')).indexOf(button) + 1));
            if (button.classList.contains('owl-dot')) button.setAttribute('aria-pressed', String(button.classList.contains('active')));
            if (!button.dataset.keyboardReady) {
                button.dataset.keyboardReady = 'true';
                button.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); button.click(); }
                });
            }
        });
        carousel.querySelectorAll('.owl-item').forEach(item => {
            const active = item.classList.contains('active');
            item.setAttribute('aria-hidden', String(!active));
            item.querySelectorAll('a').forEach(link => link.setAttribute('tabindex', active ? '0' : '-1'));
        });
    }
    const carousel = window.jQuery('.rs-slider .owl-carousel');
    carousel.on('initialized.owl.carousel translated.owl.carousel refreshed.owl.carousel', labelCarousel);
    carousel.on('focusin', () => carousel.trigger('stop.owl.autoplay'));
    carousel.on('focusout', event => {
        if (!reducedMotion.matches && !carousel[0].contains(event.relatedTarget)) carousel.trigger('play.owl.autoplay');
    });
    if (reducedMotion.matches) carousel.trigger('stop.owl.autoplay');
    reducedMotion.addEventListener('change', event => carousel.trigger(event.matches ? 'stop.owl.autoplay' : 'play.owl.autoplay'));
    labelCarousel();
    const scrollTop = document.getElementById('scrollUp');
    scrollTop.setAttribute('role', 'button');
    scrollTop.setAttribute('tabindex', '0');
    scrollTop.setAttribute('aria-label', 'Scroll to top');
    scrollTop.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); scrollTop.click(); }
    });
})();
