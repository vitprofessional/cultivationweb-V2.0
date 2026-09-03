@php
    if (!isset($config)) {
        $config = \Illuminate\Support\Facades\Schema::hasTable((new App\Models\ServerConfig())->getTable())
            ? App\Models\ServerConfig::first()
            : null;
    }
    $logoFile = !empty($config?->logo) ? basename((string) $config->logo) : null;
    $logoUrl = $logoFile && file_exists(public_path('upload/image/cultivation/' . $logoFile))
        ? url('/public/upload/image/cultivation/' . rawurlencode($logoFile))
        : asset('public/logo.png');
    $officeEmail = !empty($config?->officeEmail) && strtolower(trim($config->officeEmail)) !== 'info@cultivation.local'
        ? $config->officeEmail
        : null;
@endphp

<style>
    /* Global header styles shared by homepage and all inner pages */
    body.home-style2 .topbar-area {
        background: #1f3b6f;
        border-bottom: 0;
    }

    body.home-style2 .topbar-area .topbar-contact li a,
    body.home-style2 .topbar-area .topbar-right li,
    body.home-style2 .topbar-area .topbar-right li a,
    body.home-style2 .topbar-area .topbar-right li span {
        color: #ffffff;
    }

    body.home-style2 .topbar-area .topbar-contact li i,
    body.home-style2 .topbar-area .topbar-right li i {
        color: #7ed9f4;
    }

    body.home-style2 .topbar-area .topbar-right .apply-btn {
        background: #21a7d0;
        color: #ffffff;
        border-radius: 0;
        font-weight: 700;
        min-width: 104px;
        text-align: center;
    }

    body.home-style2 .topbar-area .topbar-right .apply-btn:hover {
        background: #1692ba;
        color: #ffffff;
    }

    body.home-style2 .topbar-area .topbar-right .lang-switcher-item {
        padding-right: 8px;
        display: none !important;
    }

    body.home-style2 .site-lang-switcher-inline {
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(126, 217, 244, 0.65);
        border-radius: 999px;
        overflow: hidden;
        background: rgba(7, 28, 62, 0.3);
    }

    body.home-style2 .site-lang-switcher-inline .lang-btn {
        border: 0;
        background: transparent;
        color: #dff6ff;
        padding: 7px 12px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.4px;
        line-height: 1;
        cursor: pointer;
    }

    body.home-style2 .site-lang-switcher-inline .lang-btn.active {
        background: #21a7d0;
        color: #ffffff;
    }

    body.home-style2 .menu-area.menu-sticky {
        background: #ffffff;
        border-bottom: 1px solid #e7edf5;
    }

    body.home-style2 .menu-area.menu-sticky .row.y-middle {
        min-height: 90px;
    }

    body.home-style2 .menu-area .logo-cat-wrap {
        display: flex;
        align-items: center;
        gap: 0;
        flex-wrap: nowrap;
    }

    body.home-style2 .menu-area .logo-part.pr-90,
    body.home-style2 .menu-area .main-menu.pr-90 {
        padding-right: 0 !important;
    }

    body.home-style2 .menu-area .logo-part .light-logo {
        display: none !important;
    }

    body.home-style2 .menu-area .logo-part .dark-logo {
        display: inline-flex !important;
        vertical-align: middle;
    }

    body.home-style2 .menu-area .logo-part img {
        height: 52px;
        width: auto;
        max-width: 240px;
        object-fit: contain;
    }

    body.home-style2 .menu-area .rs-menu-area {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }

    body.home-style2 .menu-area .main-menu {
        width: 100%;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
        gap: 0;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a {
        color: #273c66;
        font-size: 15px;
        font-weight: 700;
        padding: 0 12px;
        line-height: 90px;
        letter-spacing: 0.2px;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link {
        appearance: none;
        background: transparent;
        border: 0;
        color: #273c66;
        cursor: pointer;
        font-size: 15px;
        font-weight: 700;
        line-height: 90px;
        padding: 0 12px;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link:focus-visible,
    body.home-style2 .menu-area .mobile-menu .rs-menu-toggle:focus-visible {
        outline: 2px solid #21a7d0;
        outline-offset: 3px;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu {
        text-align: left;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu > li > a {
        font-size: 14px;
        font-weight: 600;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li.current-menu-item > a,
    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a:hover {
        color: #21a7d0;
    }

    body.home-style2 .menu-area.menu-sticky.sticky {
        background: #273c66 !important;
        background-color: #273c66 !important;
        z-index: 1000;
    }

    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > a,
    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > .rs-menu-link {
        color: #ffffff !important;
    }

    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li.current-menu-item > a,
    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > a:hover,
    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > .rs-menu-link:hover,
    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > .rs-menu-link:focus-visible {
        color: #7ed9f4;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link::after {
        content: "\f107";
        font-family: FontAwesome;
        margin-left: 6px;
    }

    body.home-style2 .menu-area.menu-sticky.sticky .rs-menu ul.nav-menu > li > .rs-menu-link::after {
        color: currentColor;
    }

    body.home-style2 .full-width-header.header-style2 .rs-header .menu-area .main-menu .rs-menu ul.nav-menu > li:hover > ul.sub-menu,
    body.home-style2 .full-width-header.header-style2 .rs-header .menu-area .main-menu .rs-menu ul.nav-menu > li > .rs-menu-link[aria-expanded="true"] + ul.sub-menu,
    body.home-style2 .full-width-header.header-style2 .rs-header .menu-area .main-menu .rs-menu ul.nav-menu > li > ul.sub-menu.visible {
        display: block !important;
        opacity: 1 !important;
        transform: scaleY(1) !important;
        visibility: visible !important;
        z-index: 1100 !important;
    }

    body.home-style2 .menu-area .mobile-menu {
        top: 50%;
        transform: translateY(-50%);
        right: 0;
    }

    #loader {
        pointer-events: none;
    }

    @media (max-width: 1199px) {
        body.home-style2 .menu-area .logo-part img {
            max-width: 200px;
            height: 46px;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a {
            padding: 0 9px;
            line-height: 86px;
            font-size: 14px;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link {
            padding: 0 9px;
            line-height: 86px;
            font-size: 14px;
        }
    }

    @media (max-width: 1100px) {
        body.home-style2 .topbar-area {
            display: none;
        }

        body.home-style2 .menu-area.menu-sticky .row.y-middle {
            min-height: 76px;
        }

        body.home-style2 .menu-area .logo-part img {
            height: 42px;
            max-width: 150px;
        }

        body.home-style2 .menu-area .mobile-menu {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        body.home-style2 .menu-area .mobile-menu .rs-menu-toggle {
            align-items: center;
            display: inline-flex;
            height: 48px;
            justify-content: center;
            line-height: 1;
            padding: 0;
            text-align: center;
            width: 48px;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu {
            display: block;
        }

        body.home-style2 .menu-area .rs-menu {
            background: #273c66;
            height: 0;
            max-height: calc(100vh - 76px);
            overflow-y: auto;
            position: absolute;
            top: 76px;
            left: 0;
            width: 100%;
            z-index: 1001;
        }

        body.home-style2 .menu-area .rs-menu.rs-menu-close {
            height: 0 !important;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a {
            padding: 10px 0;
            line-height: 1.5;
            color: #ffffff;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link {
            width: calc(100% - 45px);
            padding: 10px 0;
            line-height: 1.5;
            color: #ffffff;
            text-align: left;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link::after {
            display: none;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-parent {
            color: #273c66;
        }
    }
</style>

<div id="loader" class="loader">
    <div class="loader-container">
        <div class='loader-icon'>
            <img src="{{ asset('public/logo.png') }}" alt="">
        </div>
    </div>
</div>

<div class="full-width-header header-style2">
    <header id="rs-header" class="rs-header">
        <div class="topbar-area">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-md-7">
                        <ul class="topbar-contact">
                            @if($officeEmail)<li>
                                <i class="flaticon-email"></i>
                                <a href="mailto:{{ $officeEmail }}">{{ $officeEmail }}</a>
                            </li>@endif
                            @if(!empty($config?->officeMobile))<li>
                                <i class="flaticon-call"></i>
                                <a href="tel:{{ preg_replace('/\s+/', '', $config->officeMobile) }}">{{ $config->officeMobile }}</a>
                            </li>@endif
                        </ul>
                    </div>
                    <div class="col-md-5 text-right">
                        <ul class="topbar-right">
                            <li class="btn-part">
                                <a class="apply-btn" href="{{ route('supportPage') }}">Admission Information</a>
                            </li>
                            <li class="lang-switcher-item">
                                <div class="site-lang-switcher-inline js-site-lang-switcher" role="group" aria-label="Language switcher">
                                    <button type="button" class="lang-btn" data-lang="en">EN</button>
                                    <button type="button" class="lang-btn" data-lang="bn">বাংলা</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-area menu-sticky">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-3">
                        <div class="logo-cat-wrap">
                            <div class="logo-part pr-90">
                                <a class="dark-logo" href="{{ route('homePage') }}">
                                    <img src="{{ $logoUrl }}" alt="{{ !empty($config?->instituteName) ? $config->instituteName : 'Institution logo' }}">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 text-center">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-90">
                                <div class="mobile-menu">
                                    <button type="button" class="rs-menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="primary-navigation" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                </div>
                                <nav id="primary-navigation" class="rs-menu rs-menu-close" aria-label="Primary navigation">
                                    <ul class="nav-menu">
                                        <li><a href="{{ route('homePage') }}">Home</a></li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-institute" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Institute</button>
                                            <ul id="nav-submenu-institute" class="sub-menu">
                                                <li><a href="{{ route('institutePage') }}">About Us</a></li>
                                                <li><a href="{{ route('headOfInstituteMessagePage') }}">Head of Institute Message</a></li>
                                                <li><a href="{{ route('student') }}">Student</a></li>
                                                <li><a href="{{ route('teacherPage') }}">Teacher Directory</a></li>
                                                <li><a href="{{ route('staffPage') }}">Staff Directory</a></li>
                                                <li><a href="{{ route('comitteePage') }}">Governing Body</a></li>
                                                <li><a href="{{ route('exprincipalPage') }}">Former Heads of Institution</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-academic" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Academic</button>
                                            <ul id="nav-submenu-academic" class="sub-menu">
                                                <li><a href="{{ route('newSyllabus') }}">Syllabus</a></li>
                                                <li><a href="{{ route('newClassSchedule') }}">Class Routine</a></li>
                                                <li><a href="{{ route('newExamSchedule') }}">Exam Routine</a></li>
                                                <li><a href="{{ route('newSemister') }}">Semester Plan</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-result" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Result</button>
                                            <ul id="nav-submenu-result" class="sub-menu">
                                                <li><a href="{{ route('internalResult') }}">Internal Result</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-placement" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Job Placement</button>
                                            <ul id="nav-submenu-placement" class="sub-menu">
                                                <li><a href="{{ route('placementCellView') }}">Placement Cell</a></li>
                                                <li><a href="{{ route('jobNeedyStudentView') }}">Needy Student</a></li>
                                                <li><a href="https://bdjobs.com/" target="_blank" rel="noopener">Job Circular</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-gallery" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Gallery</button>
                                            <ul id="nav-submenu-gallery" class="sub-menu">
                                                <li><a href="{{ route('imagePage') }}">Photo Gallery</a></li>
                                                <li><a href="{{ route('videoPage') }}">Video Gallery</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('supportPage') }}">Support</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>

<script>
    document.addEventListener('keydown', function (event) {
        if ((event.key === 'Enter' || event.key === ' ') && event.target.matches('.rs-menu-toggle, .rs-menu-link')) {
            event.preventDefault();
            event.stopImmediatePropagation();
            event.target.click();
        }
    }, true);

    function syncStickyNavigationState() {
        const menu = document.querySelector('.menu-area.menu-sticky');
        if (!menu) {
            return;
        }

        const isSticky = menu.classList.contains('sticky');
        menu.style.setProperty('background-color', isSticky ? '#273c66' : '', isSticky ? 'important' : '');
        document.querySelectorAll('.nav-menu > li > a, .nav-menu > li > .rs-menu-link').forEach(function (control) {
            control.style.setProperty('color', isSticky ? '#ffffff' : '', isSticky ? 'important' : '');
        });
    }

    window.addEventListener('scroll', function () {
        window.requestAnimationFrame(syncStickyNavigationState);
    }, { passive: true });

    window.addEventListener('load', syncStickyNavigationState);

    document.addEventListener('click', function (event) {
        const menuToggle = event.target.closest('.rs-menu-toggle');
        if (menuToggle) {
            event.preventDefault();
            event.stopImmediatePropagation();
            const navigation = document.getElementById(menuToggle.getAttribute('aria-controls'));
            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
            navigation.classList.toggle('rs-menu-close', isOpen);
            navigation.style.height = isOpen ? '0px' : navigation.querySelector('.nav-menu').scrollHeight + 'px';
            return;
        }

        const menuLink = event.target.closest('.rs-menu-link');
        if (!menuLink) {
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();
        const submenu = document.getElementById(menuLink.getAttribute('aria-controls'));
        if (!submenu) {
            return;
        }
        const isOpen = menuLink.getAttribute('aria-expanded') === 'true';
        const desktopMode = window.matchMedia('(min-width: 1101px)').matches;
        menuLink.parentElement.parentElement.querySelectorAll(':scope > li > .rs-menu-link').forEach(function (control) {
            if (control !== menuLink) {
                control.setAttribute('aria-expanded', 'false');
                const siblingMenu = document.getElementById(control.getAttribute('aria-controls'));
                if (siblingMenu) {
                    siblingMenu.classList.remove('visible');
                    siblingMenu.style.removeProperty('display');
                    siblingMenu.style.removeProperty('visibility');
                    siblingMenu.style.removeProperty('opacity');
                    siblingMenu.style.removeProperty('transform');
                    siblingMenu.style.removeProperty('z-index');
                }
            }
        });
        menuLink.setAttribute('aria-expanded', String(!isOpen));
        submenu.classList.toggle('visible', !isOpen);
        if (!isOpen) {
            submenu.style.setProperty('display', 'block', 'important');
            submenu.style.setProperty('visibility', 'visible', 'important');
            submenu.style.setProperty('opacity', '1', 'important');
            submenu.style.setProperty('transform', 'scaleY(1)', 'important');
            submenu.style.setProperty('z-index', desktopMode ? '1100' : '1001', 'important');
        } else {
            submenu.style.removeProperty('display');
            submenu.style.removeProperty('visibility');
            submenu.style.removeProperty('opacity');
            submenu.style.removeProperty('transform');
            submenu.style.removeProperty('z-index');
        }

    }, true);
</script>

