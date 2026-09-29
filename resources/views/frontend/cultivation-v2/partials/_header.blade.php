@php
    if (!isset($config)) {
        $config = \Illuminate\Support\Facades\Schema::hasTable((new App\Models\ServerConfig())->getTable())
            ? App\Models\ServerConfig::query()->latest('id')->first()
            : null;
    }
    $logoUrl = app(\App\Services\PublicMediaUrl::class)->institutionLogo($config?->logo);
    $institutionName = trim((string) ($config?->instituteName ?? ''));
    $contactValue = static fn ($value) => in_array(strtolower(trim((string) $value)), ['', 'n/a', 'na', 'none', '-']) ? null : trim((string) $value);
    $officeEmail = $contactValue($config?->officeEmail);
    $officeEmail = filter_var($officeEmail, FILTER_VALIDATE_EMAIL) && !preg_match('/\.(local|test|example|invalid)$/i', substr(strrchr($officeEmail, '@') ?: '', 1)) ? $officeEmail : null;
    $officePhone = $contactValue($config?->officeMobile);
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
        height: 100%;
    }

    body.home-style2 .menu-area .logo-part.pr-90,
    body.home-style2 .menu-area .main-menu.pr-90 {
        padding-right: 0 !important;
    }

    body.home-style2 .menu-area .logo-part {
        align-items: center;
        display: flex;
        height: 100%;
        padding-left: 0;
    }

    body.home-style2 .menu-area .logo-part .light-logo {
        display: none !important;
    }

    body.home-style2 .menu-area .logo-part .dark-logo {
        align-items: center;
        display: inline-flex !important;
        height: 100%;
    }

    body.home-style2 .menu-area .logo-part img {
        display: block;
        height: 64px;
        width: auto;
        max-width: 240px;
        max-height: 64px !important;
        object-fit: contain;
    }

    body.home-style2 .menu-area .header-institute-fallback {
        color: #0f2b5c;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
        max-width: 240px;
        overflow-wrap: anywhere;
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
        justify-content: flex-end;
        align-items: center;
        flex-wrap: nowrap;
        gap: 0;
    }

    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a,
    body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link {
        color: #1a365d;
        font-size: 15px;
        font-weight: 700;
        padding: 0 12px;
        line-height: 90px;
        letter-spacing: 0;
        white-space: nowrap !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        transition: color 0.2s ease;
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
            max-width: 220px;
            height: 60px;
            max-height: 60px !important;
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
        body.home-style2 .full-width-header.header-style2 {
            position: relative;
            z-index: 1102;
        }

        body.home-style2 .topbar-area {
            display: none;
        }

        body.home-style2 .menu-area.menu-sticky .row.y-middle {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            height: 72px;
            min-height: 72px;
            margin-left: 0;
            margin-right: 0;
        }

        body.home-style2 .menu-area > .container {
            position: relative !important;
            width: 100%;
            max-width: 100%;
            padding-left: 16px;
            padding-right: 16px;
            box-sizing: border-box;
        }

        body.home-style2 .menu-area :is(.main-menu, .rs-menu-area) {
            position: static !important;
        }

        body.home-style2 .menu-area .row.y-middle > [class*="col-"] {
            float: none;
            padding-left: 0;
            padding-right: 0;
        }

        body.home-style2 .menu-area .row.y-middle > [class*="col-"]:first-child {
            display: flex;
            flex: 1 1 auto;
            align-items: center;
            width: auto;
            max-width: none;
            min-width: 0;
        }

        body.home-style2 .menu-area .row.y-middle > [class*="col-"]:last-child {
            position: static;
            flex: 0 0 0;
            width: 0;
            max-width: 0;
            min-width: 0;
            padding: 0;
        }

        body.home-style2 .menu-area .logo-part img {
            width: auto;
            height: 48px;
            max-width: min(155px, 100%);
            max-height: 48px !important;
            object-fit: contain;
        }

        body.home-style2 .menu-area .logo-cat-wrap,
        body.home-style2 .menu-area .logo-part {
            width: auto;
            height: auto;
            min-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            line-height: normal;
        }

        body.home-style2 .full-width-header.header-style2 .rs-header .menu-area .logo-cat-wrap {
            position: static;
            width: auto;
            height: auto;
            min-height: 0;
            line-height: normal;
        }

        body.home-style2 .menu-area .mobile-menu {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1003;
        }

        body.home-style2 .menu-area .mobile-menu .rs-menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            min-width: 44px;
            height: 44px;
            min-height: 44px;
            line-height: 1;
            padding: 0;
            text-align: center;
            color: #17334f !important;
            background: #ffffff !important;
            border: 1px solid #cbd7e3 !important;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(18, 42, 71, .08);
            cursor: pointer;
        }

        body.home-style2 .menu-area .mobile-menu .mobile-menu-icon {
            display: flex;
            width: 19px;
            flex-direction: column;
            gap: 4px;
        }

        body.home-style2 .menu-area .mobile-menu .mobile-menu-icon span {
            display: block;
            width: 100%;
            height: 2px;
            border-radius: 2px;
            background: currentColor;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu {
            display: block;
        }

        /* The theme inserts a second arrow button; our accessible menu buttons own toggling. */
        body.home-style2 .menu-area .rs-menu .rs-menu-parent {
            display: none !important;
        }

        body.home-style2 .menu-area .rs-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            width: auto;
            margin: 0;
            background: #ffffff;
            border: 1px solid rgba(26, 54, 93, .12);
            border-top: 3px solid #21a7d0;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 18px 38px rgba(14, 31, 57, .2);
            height: auto;
            max-height: calc(100vh - 76px);
            overflow-y: auto;
            z-index: 1001;
        }

        body.home-style2 .menu-area .rs-menu.rs-menu-close {
            display: none !important;
            height: 0 !important;
        }

        body.home-style2 .menu-area .rs-menu:not(.rs-menu-close) {
            display: block !important;
            height: auto !important;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a {
            display: block;
            padding: 13px 18px;
            line-height: 1.5;
            color: #172b4d;
            border-bottom: 1px solid #e8edf4;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link {
            width: 100%;
            padding: 13px 18px;
            line-height: 1.5;
            color: #172b4d;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8edf4 !important;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link::after {
            display: inline-block;
            content: "\f107";
            font-family: FontAwesome;
            transition: transform .18s ease;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > .rs-menu-link[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu,
        body.home-style2 .full-width-header.header-style2 .rs-header .menu-area .main-menu .rs-menu ul.nav-menu > li:hover > ul.sub-menu:not(.visible) {
            display: none !important;
            position: static !important;
            width: 100% !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            padding: 4px 0 8px 14px !important;
            background: #f3f6fa !important;
            box-shadow: none !important;
            border: 0 !important;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu.visible {
            display: block !important;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu > li > a {
            display: block;
            padding: 11px 18px;
            color: #263b5b !important;
            line-height: 1.4;
            border-bottom: 1px solid rgba(23, 43, 77, .07);
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu > li > a:hover,
        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu > li > a:focus-visible {
            color: #087fa4 !important;
            background: #e8f4f8;
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
                            @if($officePhone)<li>
                                <i class="flaticon-call"></i>
                                <a href="tel:{{ preg_replace('/\s+/', '', $officePhone) }}">{{ $officePhone }}</a>
                            </li>@endif
                        </ul>
                    </div>
                    <div class="col-md-5 text-right">
                        <ul class="topbar-right">
                            <li class="btn-part">
                                <a class="apply-btn" href="{{ route('supportPage') }}">Admission Information</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-area menu-sticky">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-4 col-xl-4">
                        <div class="logo-cat-wrap">
                            <div class="logo-part pr-90">
                                <a class="dark-logo" href="{{ route('homePage') }}" aria-label="{{ $institutionName ?: 'Institution home' }}" style="display: inline-flex; align-items: center; text-decoration: none;">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $institutionName ?: 'Institution logo' }}">
                                    @else
                                        <span class="header-institute-fallback">{{ $institutionName ?: 'Institution name unavailable' }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-8 text-center">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-90">
                                <div class="mobile-menu">
                                    <button type="button" class="rs-menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="primary-navigation">
                                        <span class="mobile-menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>
                                    </button>
                                </div>
                                <nav id="primary-navigation" class="rs-menu rs-menu-close" aria-label="Primary navigation">
                                    <ul class="nav-menu">
                                        <li><a href="{{ route('homePage') }}">Home</a></li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-institute">Institute</button>
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
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-academic">Academic</button>
                                            <ul id="nav-submenu-academic" class="sub-menu">
                                                <li><a href="{{ route('newSyllabus') }}">Syllabus</a></li>
                                                <li><a href="{{ route('newClassSchedule') }}">Class Routine</a></li>
                                                <li><a href="{{ route('newExamSchedule') }}">Exam Routine</a></li>
                                                <li><a href="{{ route('newSemister') }}">Semester Plan</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-result">Result</button>
                                            <ul id="nav-submenu-result" class="sub-menu">
                                                <li><a href="{{ route('internalResult') }}">Internal Result</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-gallery">Gallery</button>
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
    (function () {
        const navigation = document.getElementById('primary-navigation');
        const toggle = document.querySelector('.rs-menu-toggle[aria-controls="primary-navigation"]');
        if (!navigation || !toggle) return;

        const closeSubmenus = function (except) {
            navigation.querySelectorAll('.rs-menu-link').forEach(function (control) {
                if (control === except) return;
                control.setAttribute('aria-expanded', 'false');
                const submenu = document.getElementById(control.getAttribute('aria-controls'));
                if (submenu) submenu.classList.remove('visible');
            });
        };

        const closeMenu = function () {
            toggle.setAttribute('aria-expanded', 'false');
            navigation.classList.add('rs-menu-close');
            navigation.style.removeProperty('height');
            closeSubmenus(null);
        };

        const syncLayout = function () {
            navigation.style.removeProperty('height');
            if (window.matchMedia('(min-width: 1101px)').matches) {
                navigation.classList.remove('rs-menu-close');
                toggle.setAttribute('aria-expanded', 'false');
                closeSubmenus(null);
            } else if (toggle.getAttribute('aria-expanded') !== 'true') {
                navigation.classList.add('rs-menu-close');
            }
        };

        const syncStickyNavigationState = function () {
            const menu = document.querySelector('.menu-area.menu-sticky');
            if (!menu) return;
            const sticky = menu.classList.contains('sticky');
            menu.style.setProperty('background-color', sticky ? '#273c66' : '', sticky ? 'important' : '');
            document.querySelectorAll('.nav-menu > li > a, .nav-menu > li > .rs-menu-link').forEach(function (control) {
                control.style.setProperty('color', sticky ? '#ffffff' : '', sticky ? 'important' : '');
            });
        };

        document.addEventListener('click', function (event) {
            const menuToggle = event.target.closest('.rs-menu-toggle[aria-controls="primary-navigation"]');
            if (menuToggle) {
                event.preventDefault();
                event.stopImmediatePropagation();
                const open = menuToggle.getAttribute('aria-expanded') !== 'true';
                menuToggle.setAttribute('aria-expanded', String(open));
                navigation.classList.toggle('rs-menu-close', !open);
                navigation.style.removeProperty('height');
                if (!open) closeSubmenus(null);
                return;
            }

            const menuLink = event.target.closest('.rs-menu-link');
            if (menuLink && navigation.contains(menuLink)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                const submenu = document.getElementById(menuLink.getAttribute('aria-controls'));
                if (!submenu) return;
                const open = menuLink.getAttribute('aria-expanded') !== 'true';
                closeSubmenus(menuLink);
                menuLink.setAttribute('aria-expanded', String(open));
                submenu.classList.toggle('visible', open);
                return;
            }

            if (window.matchMedia('(max-width: 1100px)').matches && event.target.closest('#primary-navigation .nav-menu a')) {
                closeMenu();
                return;
            }

            if (window.matchMedia('(max-width: 1100px)').matches && !event.target.closest('.main-menu')) closeMenu();
        }, true);

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') return;
            const expanded = navigation.querySelector('.rs-menu-link[aria-expanded="true"]');
            if (expanded) {
                const submenu = document.getElementById(expanded.getAttribute('aria-controls'));
                expanded.setAttribute('aria-expanded', 'false');
                if (submenu) submenu.classList.remove('visible');
            } else if (toggle.getAttribute('aria-expanded') === 'true') {
                closeMenu();
                toggle.focus();
            }
        });

        window.addEventListener('resize', syncLayout);
        window.addEventListener('scroll', function () { window.requestAnimationFrame(syncStickyNavigationState); }, { passive: true });
        window.addEventListener('load', syncStickyNavigationState);
        syncLayout();
    })();
</script>

