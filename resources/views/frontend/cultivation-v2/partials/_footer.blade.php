@php
    if (!isset($config)) {
        $config = \Illuminate\Support\Facades\Schema::hasTable((new App\Models\ServerConfig())->getTable())
            ? App\Models\ServerConfig::first()
            : null;
    }
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap');

    /* ============================================================
       FOOTER â€” Professional Redesign
    ============================================================ */

    #rs-footer.rs-footer {
        background: linear-gradient(180deg, #0c1e3e 0%, #07122a 100%);
        padding-top: 0;
        margin-top: 0;
    }

    #rs-footer,
    #rs-footer p,
    #rs-footer li,
    #rs-footer a {
        color: #90b4d8;
    }

    #rs-footer a {
        text-decoration: none;
    }

    #rs-footer img {
        max-width: 80%;
        height: auto;
    }

    /* ---- info strip (Contact / Visitor / Map) ---- */
    .footer-info-strip {
        background: linear-gradient(90deg, #112958 0%, #0e2248 50%, #112958 100%);
        border-bottom: 1px solid rgba(33,167,208,0.25);
        padding: 20px 0;
        margin-bottom: 0;
    }

    .footer-info-strip .fi-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .footer-info-strip .fi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(33,167,208,0.18);
        border: 1px solid rgba(33,167,208,0.35);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #21a7d0;
        font-size: 16px;
    }

    .footer-info-strip .fi-body h6 {
        margin: 0 0 4px;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.2;
    }

    .footer-info-strip .fi-body p,
    .footer-info-strip .fi-body a {
        margin: 0;
        color: #90b4d8;
        font-size: 13px;
        line-height: 1.5;
        word-break: break-all;
    }

    .footer-info-strip .fi-body a:hover {
        color: #21a7d0;
    }

    .footer-info-strip .fi-divider {
        width: 1px;
        background: rgba(255,255,255,0.1);
        align-self: stretch;
        margin: 0 8px;
    }

    /* ---- footer-top main columns ---- */
    .footer-top,
    #rs-footer .footer-top,
    .rs-footer .footer-top {
        padding: 48px 0 32px !important;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .footer-main-row .footer-widget {
        padding-right: 16px;
    }

    .footer-main-row .widget-title {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #ffffff;
        margin-bottom: 18px;
        padding-bottom: 12px;
        position: relative;
    }

    .footer-main-row .widget-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 32px;
        height: 2px;
        background: #21a7d0;
        border-radius: 2px;
    }

    /* col 1 â€” about */
    .footer-about {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 4px 0 0;
    }

    .footer-about-shell {
        padding: 18px 18px 16px;
        border: 1px solid rgba(33, 167, 208, 0.14);
        border-radius: 16px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
        box-shadow: 0 16px 32px rgba(7, 18, 42, 0.18);
    }

    .footer-about-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        margin-bottom: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(33, 167, 208, 0.14);
        border: 1px solid rgba(33, 167, 208, 0.25);
        color: #a8dff2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.9px;
        line-height: 1;
        text-transform: uppercase;
    }

    .footer-brand-logo img {
        max-height: 52px;
        width: auto;
        margin-bottom: 10px;
        object-fit: contain;
        filter: none !important;
    }

    .footer-about-desc {
        color: #a9c8e8;
        font-size: 13.8px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .footer-about-note {
        margin: 0;
        color: #6e94bf;
        font-size: 12px;
        line-height: 1.5;
    }

    /* footer sub-section inside col 1 */
    .footer-sub-section {
        margin-top: 16px;
        padding-top: 18px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .footer-sub-title {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #7ed9f4 !important;
        margin: 0 0 10px;
    }

    /* social icons */
    .footer-social-inline {
        display: flex !important;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 14px 0 0;
    }

    .footer-social-inline li a {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        color: #a9c8e8;
        font-size: 14px;
        transition: all 0.22s ease;
    }

    .footer-social-inline li a:hover {
        background: #21a7d0;
        border-color: #21a7d0;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .footer-contact-list {
        margin-top: 10px !important;
    }

    /* address widget (inside sub-section) */
    .footer-sub-section .address-widget {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-sub-section .address-widget li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
        color: #a9c8e8;
        font-size: 13px;
        line-height: 1.55;
        padding: 10px 12px;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
    }

    .footer-sub-section .address-widget li i {
        color: #21a7d0;
        font-size: 14px;
        margin-top: 3px;
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        text-align: center;
    }

    .footer-sub-section .address-widget li a {
        color: #c1dcf6;
        word-break: break-all;
    }

    .footer-sub-section .address-widget li a:hover {
        color: #21a7d0;
    }

    .address-widget.mt-20 {
        margin-top: 16px;
    }

    /* site-map links */
    .footer-main-row .site-map {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-main-row .site-map li {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 5px 0;
    }

    .footer-main-row .site-map li:last-child {
        border-bottom: none;
    }

    .footer-main-row .site-map li a {
        color: #90b4d8;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .footer-main-row .site-map li a:hover {
        color: #21a7d0;
        padding-left: 4px;
    }

    .footer-main-row .site-map li a i.fa-angle-right {
        font-size: 12px;
        color: #21a7d0;
        flex-shrink: 0;
    }

    .footer-main-row .site-map.bangla-links li a {
        font-family: 'Hind Siliguri', 'Noto Sans Bengali', 'SolaimanLipi', 'Nikosh', sans-serif;
        letter-spacing: 0;
        line-height: 1.7;
    }

    .footer-link-group-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #5c7fa8;
        padding: 8px 0 4px !important;
        border-bottom: none !important;
    }

    /* footer-bottom bar */
    .footer-bottom {
        background: rgba(0,0,0,0.3);
        border-top: 1px solid rgba(255,255,255,0.06);
        padding: 18px 0;
    }

    .footer-bottom .copyright p {
        color: #6b90b8;
        font-size: 13px;
        margin: 0;
        line-height: 1.5;
    }

    .footer-bottom .copyright p span {
        color: #21a7d0;
        font-weight: 600;
    }

    .footer-bottom-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px 0;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-bottom-links li a {
        color: #6b90b8;
        font-size: 13px;
        padding: 0 10px;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .footer-bottom-links li a:hover {
        color: #21a7d0;
    }

    .footer-bottom-links li + li::before {
        content: '|';
        color: rgba(255,255,255,0.12);
    }

    .footer-bottom .footer-bottom-legal {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .footer-bottom .footer-bottom-legal .eyebrow {
        color: #7ed9f4;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
    }

    .footer-bottom .footer-bottom-legal .subcopy {
        color: #6b90b8;
        font-size: 12px;
        margin: 0;
        line-height: 1.5;
    }

    /* sidebar-strip (kept for compat â€” hidden on footer redesign) */
    .footer-sidebar-strip {
        display: none;
    }

    @media (max-width: 991px) {
        .footer-main-row .footer-widget {
            padding-right: 8px;
        }
    }

    @media (max-width: 767px) {
        .footer-bottom-links {
            justify-content: center;
            margin-bottom: 8px;
        }

        .footer-bottom .copyright p {
            text-align: center;
        }

        .footer-info-strip .fi-card {
            margin-bottom: 12px;
        }
    }
</style>

<footer id="rs-footer" class="rs-footer">

    {{-- Info strip: Address / Phone+Email / Find Us Online --}}
    <div class="footer-info-strip">
        <div class="container">
            <div class="row y-middle">
                @if(!empty($config?->address))<div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-map-marker"></i></div>
                        <div class="fi-body">
                            <h6>Address</h6>
                            <p>{{ $config->address }}</p>
                        </div>
                    </div>
                </div>@endif
                @if(!empty($config?->officeMobile) || !empty($config?->officeEmail))<div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-phone"></i></div>
                        <div class="fi-body">
                            <h6>Phone &amp; Email</h6>
                            @if(!empty($config?->officeMobile))<a href="tel:{{ preg_replace('/\s+/', '', $config->officeMobile) }}">{{ $config->officeMobile }}</a>@endif
                            @if(!empty($config?->officeMobile) && !empty($config?->officeEmail))<br>@endif
                            @if(!empty($config?->officeEmail))<a href="mailto:{{ $config->officeEmail }}">{{ $config->officeEmail }}</a>@endif
                        </div>
                    </div>
                </div>@endif
                @if(!empty($config?->address))<div class="col-lg-4 col-md-6">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-globe"></i></div>
                        <div class="fi-body">
                            <h6>Find Us Online</h6>
                            <a href="{{ url('/') }}">{{ url('/') }}</a><br>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($config->address) }}" target="_blank" rel="noopener noreferrer">Open in Google Maps &rarr;</a>
                        </div>
                    </div>
                </div>@endif
            </div>
        </div>
    </div>

    <div class="footer-top">
        <div class="container">
            <div class="footer-sidebar-strip"></div>
            <div class="row footer-main-row">

                {{-- Col 1: About + Contact --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget md-mb-50">
                    <div class="footer-about">
                        <div class="footer-about-shell">
                            <div class="footer-about-badge">Official School Portal</div>
                            <a href="{{ route('homePage') }}" class="footer-brand-logo" aria-label="Home">
                                <img src="{{ asset('public/logoWhite.png') }}" alt="{{ !empty($config?->instituteName) ? $config->instituteName : 'Institute' }}">
                            </a>
                            <p class="footer-about-desc">
                                {{ !empty($config?->instituteName) ? $config->instituteName : 'Our Institute' }} is committed to quality education, character building and academic excellence.
                            </p>
                            <p class="footer-about-note">Stay connected for notices, updates, and official announcements.</p>
                        </div>
                    </div>

                    {{-- Contact Us below About --}}
                    <div class="footer-sub-section">
                        <h5 class="footer-sub-title">Contact Us</h5>
                        <ul class="address-widget footer-contact-list" style="margin-top:0">
                            <li>
                                <i class="flaticon-location"></i>
                                <div class="desc">{{ $config?->address }}</div>
                            </li>
                            <li>
                                <i class="flaticon-call"></i>
                                <div class="desc">
                                    @if(!empty($config?->officeMobile))<a href="tel:{{ preg_replace('/\s+/', '', $config->officeMobile) }}">{{ $config->officeMobile }}</a>@endif
                                </div>
                            </li>
                            <li>
                                <i class="flaticon-email"></i>
                                <div class="desc">
                                    @if(!empty($config?->officeEmail))<a href="mailto:{{ $config->officeEmail }}">{{ $config->officeEmail }}</a>@endif
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-external-link" style="font-size:14px;color:#6b90c8"></i>
                                <div class="desc">
                                    <a href="{{ url('/') }}">{{ url('/') }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Col 2: Important Links --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget md-mb-50">
                    <h4 class="widget-title">Important Links</h4>
                    <ul class="site-map bangla-links">
                        <li><a href="https://www.bangladesh.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</a></li>
                        <li><a href="https://moedu.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> শিক্ষা মন্ত্রণালয়</a></li>
                        <li><a href="https://www.dshe.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> মাধ্যমিক ও উচ্চশিক্ষা অধিদপ্তর</a></li>
                        <li><a href="https://www.dhakaeducationboard.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড</a></li>
                        <li><a href="https://shed.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> মাধ্যমিক ও উচ্চ শিক্ষা বিভাগ</a></li>
                        <li><a href="https://ebook.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> ই-বুক</a></li>
                        <li><a href="https://www.i-book.com.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> আই-বুক</a></li>
                        <li><a href="https://www.dshe.gov.bd/" target="_blank" rel="noopener"><i class="fa fa-angle-right"></i> মাউশি</a></li>
                    </ul>
                </div>

                {{-- Col 3: Admission & Student --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget md-mb-50">
                    <h4 class="widget-title">Admission &amp; Student</h4>
                    <ul class="site-map">
                        <li class="footer-link-group-label">Admission</li>
                        <li><a href="{{ route('supportPage') }}"><i class="fa fa-angle-right"></i> Admission Information</a></li>
                        <li class="footer-link-group-label">Student Corner</li>
                        <li><a href="{{ route('student') }}"><i class="fa fa-angle-right"></i> Student Database</a></li>
                        <li><a href="{{ route('placementCellView') }}"><i class="fa fa-angle-right"></i> Placement Cell</a></li>
                        <li><a href="{{ route('jobNeedyStudentView') }}"><i class="fa fa-angle-right"></i> Job Seekers</a></li>
                        <li><a href="{{ route('internalResult') }}"><i class="fa fa-angle-right"></i> Internal Result</a></li>
                    </ul>
                </div>

                {{-- Col 4: Gallery & Academic --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget">
                    <h4 class="widget-title">Gallery &amp; Academic</h4>
                    <ul class="site-map">
                        <li><a href="{{ route('imagePage') }}"><i class="fa fa-angle-right"></i> Photo Gallery</a></li>
                        <li><a href="{{ route('videoPage') }}"><i class="fa fa-angle-right"></i> Video Gallery</a></li>
                    </ul>
                    <ul class="site-map" style="margin-top:8px">
                        <li class="footer-link-group-label">Academic</li>
                        <li><a href="{{ route('newSyllabus') }}"><i class="fa fa-angle-right"></i> Syllabus</a></li>
                        <li><a href="{{ route('newClassSchedule') }}"><i class="fa fa-angle-right"></i> Class Routine</a></li>
                        <li><a href="{{ route('newExamSchedule') }}"><i class="fa fa-angle-right"></i> Exam Routine</a></li>
                        <li><a href="{{ route('newSemister') }}"><i class="fa fa-angle-right"></i> Semester Plan</a></li>
                        <li><a href="{{ route('internalResult') }}"><i class="fa fa-angle-right"></i> Internal Result</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row y-middle">
                <div class="col-lg-7 col-md-12 md-mb-10 text-lg-start">
                    <div class="footer-bottom-legal">
                        <div class="copyright">
                            <p>&copy; <span>{{ date('Y') }}</span> {{ !empty($config?->instituteName) ? $config->instituteName : 'Institute' }}. All Rights Reserved.</p>
                        </div>
                        <p class="subcopy">Developed &amp; Powered By <strong>Cultivation</strong></p>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <ul class="footer-bottom-links">
                        <li><a href="{{ route('homePage') }}">Home</a></li>
                        <li><a href="{{ route('institutePage') }}">About</a></li>
                        <li><a href="{{ route('allNotices') }}">Notice</a></li>
                        <li><a href="{{ route('supportPage') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="scrollUp">
    <i class="fa fa-angle-up"></i>
</div>

