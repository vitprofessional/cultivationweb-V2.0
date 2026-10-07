/* One shared scroll controller for every public page, independent of jQuery. */
(function () {
    'use strict';
    if (window.publicScrollReady) return;
    window.publicScrollReady = true;
    const header = document.getElementById('rs-header');
    const menu = header?.querySelector('.menu-sticky');
    const topbar = header?.querySelector('.topbar-area');
    const button = document.getElementById('scrollUp');
    function measure() {
        if (menu) {
            menu.style.removeProperty('--public-menu-height');
            menu.style.setProperty('--public-menu-height', menu.getBoundingClientRect().height + 'px');
        }
        schedule();
    }
    let pending = false;
    function update() {
        pending = false;
        const offset = topbar?.getBoundingClientRect().height || 0;
        document.body.style.setProperty('--topbar-height', offset + 'px');
        menu?.classList.toggle('sticky', window.scrollY > offset);
        if (button) button.hidden = window.scrollY < 300;
    }
    function schedule() {
        if (!pending) { pending = true; requestAnimationFrame(update); }
    }
    button?.addEventListener('click', function () {
        window.scrollTo({top:0,behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches?'instant':'smooth'});
        header?.querySelector('a')?.focus({preventScroll:true});
    });
    window.addEventListener('scroll', schedule, {passive:true});
    window.addEventListener('resize', measure);
    window.addEventListener('load', measure);
    window.addEventListener('pageshow', schedule);
    if (topbar && window.ResizeObserver) new ResizeObserver(schedule).observe(topbar);
    measure();
    update();
})();
