@php
    if (!isset($config)) {
        $config = App\Models\ServerConfig::first();
    }
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
    }

    @media (max-width: 991px) {
        body.home-style2 .menu-area.menu-sticky .row.y-middle {
            min-height: 76px;
        }

        body.home-style2 .menu-area .logo-part img {
            height: 42px;
            max-width: 150px;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu {
            display: block;
        }

        body.home-style2 .menu-area .rs-menu ul.nav-menu > li > a {
            padding: 10px 0;
            line-height: 1.5;
            color: #ffffff;
        }
    }
</style>

<div id="loader" class="loader">
    <div class="loader-container">
        <div class='loader-icon'>
            <img src="{{ asset('public/educavo/assets/images/pre-logo.png') }}" alt="">
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
                            <li>
                                <i class="flaticon-email"></i>
                                <a href="mailto:{{ !empty($config?->officeEmail) ? $config->officeEmail : 'support@rstheme.com' }}">{{ !empty($config?->officeEmail) ? $config->officeEmail : 'support@rstheme.com' }}</a>
                            </li>
                            <li>
                                <i class="flaticon-call"></i>
                                <a href="tel:{{ !empty($config?->officeMobile) ? preg_replace('/\s+/', '', $config->officeMobile) : '+8801700000000' }}">{{ !empty($config?->officeMobile) ? $config->officeMobile : '(+880) 1700000000' }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-5 text-right">
                        <ul class="topbar-right">
                            <li class="login-register">
                                <i class="fa fa-sign-in"></i>
                                <a href="#">Login</a>/<a href="#">Register</a>
                            </li>
                            <li class="btn-part">
                                <a class="apply-btn" href="{{ route('supportPage') }}">Apply Now</a>
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
                                    @if(!empty($config?->logo))
                                        <img src="{{ url('/public/upload/image/cultivation/' . rawurlencode(basename($config->logo))) }}" alt="logo" onerror="this.onerror=null;this.src='{{ asset('public/educavo/assets/images/logo-dark.png') }}';">
                                    @else
                                        <img src="{{ asset('public/educavo/assets/images/logo-dark.png') }}" alt="logo">
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 text-center">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-90">
                                <div class="mobile-menu">
                                    <a class="rs-menu-toggle">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </div>
                                <nav class="rs-menu">
                                    <ul class="nav-menu">
                                        <li><a href="{{ route('homePage') }}">Home</a></li>
                                        <li class="menu-item-has-children">
                                            <a href="#">Institute</a>
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
                                            <a href="#">Academic</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('newSyllabus') }}">Syllabus</a></li>
                                                <li><a href="{{ route('newClassSchedule') }}">Class Routine</a></li>
                                                <li><a href="{{ route('newExamSchedule') }}">Exam Routine</a></li>
                                                <li><a href="{{ route('newSemister') }}">Semister Plan</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <a href="#">Result</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('internalResult') }}">Internal Result</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <a href="#">Job Placement</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('placementCellView') }}">Placement Cell</a></li>
                                                <li><a href="{{ route('jobNeedyStudentView') }}">Needy Student</a></li>
                                                <li><a href="https://bdjobs.com/" target="_blank" rel="noopener">Job Circular</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <a href="#">Gallery</a>
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
