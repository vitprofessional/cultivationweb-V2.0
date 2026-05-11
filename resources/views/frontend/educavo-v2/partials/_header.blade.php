@php
    if (!isset($config)) {
        $config = App\Models\ServerConfig::first();
    }
@endphp

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
                                                <li><a href="{{ route('principalSpeechPage') }}">Principal Message</a></li>
                                                <li><a href="{{ route('student') }}">Student</a></li>
                                                <li><a href="{{ route('teacherPage') }}">Teacher</a></li>
                                                <li><a href="{{ route('staffPage') }}">Staff</a></li>
                                                <li><a href="{{ route('comitteePage') }}">Managing Committee</a></li>
                                                <li><a href="{{ route('exprincipalPage') }}">Ex-Principal</a></li>
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
