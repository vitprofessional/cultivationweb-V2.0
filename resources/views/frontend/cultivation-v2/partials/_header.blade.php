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

    @media (max-width: 991px) {
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
                            @if(!empty($config?->officeEmail))<li>
                                <i class="flaticon-email"></i>
                                <a href="mailto:{{ $config->officeEmail }}">{{ $config->officeEmail }}</a>
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
                                <nav id="primary-navigation" class="rs-menu" aria-label="Primary navigation">
                                    <ul class="nav-menu">
                                        <li><a href="{{ route('homePage') }}">Home</a></li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Institute</button>
                                            <ul class="sub-menu">
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
                                            <button type="button" class="rs-menu-link" aria-expanded="false" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Academic</button>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('newSyllabus') }}">Syllabus</a></li>
                                                <li><a href="{{ route('newClassSchedule') }}">Class Routine</a></li>
                                                <li><a href="{{ route('newExamSchedule') }}">Exam Routine</a></li>
                                                <li><a href="{{ route('newSemister') }}">Semester Plan</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Result</button>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('internalResult') }}">Internal Result</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Job Placement</button>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('placementCellView') }}">Placement Cell</a></li>
                                                <li><a href="{{ route('jobNeedyStudentView') }}">Needy Student</a></li>
                                                <li><a href="https://bdjobs.com/" target="_blank" rel="noopener">Job Circular</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <button type="button" class="rs-menu-link" aria-expanded="false" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); event.stopPropagation(); this.click(); }">Gallery</button>
                                            <ul class="sub-menu">
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
        const submenu = menuLink.parentElement.querySelector(':scope > .sub-menu');
        if (!submenu) {
            return;
        }
        const isOpen = menuLink.getAttribute('aria-expanded') === 'true';
        menuLink.setAttribute('aria-expanded', String(!isOpen));
        submenu.classList.toggle('visible', !isOpen);
        if (window.matchMedia('(min-width: 992px)').matches) {
            submenu.style.display = isOpen ? '' : 'block';
        }
    }, true);
</script>

