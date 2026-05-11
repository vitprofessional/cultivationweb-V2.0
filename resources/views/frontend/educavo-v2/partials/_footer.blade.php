@php
    if (!isset($config)) {
        $config = App\Models\ServerConfig::first();
    }
@endphp

<style>
    /* ============================================================
       FOOTER — Professional Redesign
    ============================================================ */

    #rs-footer.rs-footer {
        background: linear-gradient(180deg, #0c1e3e 0%, #07122a 100%);
        padding-top: 0;
        margin-top: 0;
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

    /* col 1 — about */
    .footer-brand-logo img {
        max-height: 56px;
        width: auto;
        margin-bottom: 14px;
    }

    .footer-about-desc {
        color: #90b4d8;
        font-size: 13.5px;
        line-height: 1.65;
        margin-bottom: 16px;
    }

    /* footer sub-section inside col 1 */
    .footer-sub-section {
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .footer-sub-title {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #21a7d0;
        margin: 0 0 10px;
    }

    /* social icons */
    .footer-social-inline {
        display: flex !important;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-social-inline li a {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        color: #90b4d8;
        font-size: 14px;
        transition: all 0.22s ease;
    }

    .footer-social-inline li a:hover {
        background: #21a7d0;
        border-color: #21a7d0;
        color: #ffffff;
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
        gap: 10px;
        margin-bottom: 10px;
        color: #90b4d8;
        font-size: 13px;
        line-height: 1.5;
    }

    .footer-sub-section .address-widget li i {
        color: #21a7d0;
        font-size: 14px;
        margin-top: 2px;
        flex-shrink: 0;
        width: 16px;
        text-align: center;
    }

    .footer-sub-section .address-widget li a {
        color: #90b4d8;
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

    .footer-bottom .footer-social {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .footer-bottom .footer-social li a {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        color: #6b90b8;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .footer-bottom .footer-social li a:hover {
        background: #21a7d0;
        color: #ffffff;
        border-color: #21a7d0;
    }

    /* sidebar-strip (kept for compat — hidden on footer redesign) */
    .footer-sidebar-strip {
        display: none;
    }

    @media (max-width: 991px) {
        .footer-main-row .footer-widget {
            padding-right: 8px;
        }
    }

    @media (max-width: 767px) {
        .footer-bottom .footer-social {
            justify-content: center;
            margin-top: 10px;
        }

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
                <div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-map-marker"></i></div>
                        <div class="fi-body">
                            <h6>Address</h6>
                            <p>{{ !empty($config?->address) ? $config->address : 'Dhaka, Bangladesh' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-phone"></i></div>
                        <div class="fi-body">
                            <h6>Phone &amp; Email</h6>
                            <a href="tel:{{ !empty($config?->officeMobile) ? preg_replace('/\s+/', '', $config->officeMobile) : '+8801700000000' }}">{{ !empty($config?->officeMobile) ? $config->officeMobile : '+8801700000000' }}</a><br>
                            <a href="mailto:{{ !empty($config?->officeEmail) ? $config->officeEmail : 'info@cultivation.local' }}">{{ !empty($config?->officeEmail) ? $config->officeEmail : 'info@cultivation.local' }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="fi-card">
                        <div class="fi-icon"><i class="fa fa-globe"></i></div>
                        <div class="fi-body">
                            <h6>Find Us Online</h6>
                            <a href="{{ url('/') }}">{{ url('/') }}</a><br>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(!empty($config?->address) ? $config->address : 'Dhaka, Bangladesh') }}" target="_blank" rel="noopener">Open in Google Maps &rarr;</a>
                        </div>
                    </div>
                </div>
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
                        <a href="{{ route('homePage') }}" class="footer-brand-logo">
                            <img src="{{ asset('public/educavo/assets/images/logo.png') }}" alt="{{ !empty($config?->instituteName) ? $config->instituteName : 'Institute' }}">
                        </a>
                        <p class="footer-about-desc">
                            {{ !empty($config?->instituteName) ? $config->instituteName : 'Our Institute' }} is committed to quality education, character building and academic excellence.
                        </p>
                        <ul class="footer-social footer-social-inline">
                            <li><a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a></li>
                        </ul>
                    </div>

                    {{-- Contact Us below About --}}
                    <div class="footer-sub-section">
                        <h5 class="footer-sub-title">Contact Us</h5>
                        <ul class="address-widget" style="margin-top:0">
                            <li>
                                <i class="flaticon-location"></i>
                                <div class="desc">{{ !empty($config?->address) ? $config->address : 'Dhaka, Bangladesh' }}</div>
                            </li>
                            <li>
                                <i class="flaticon-call"></i>
                                <div class="desc">
                                    <a href="tel:{{ !empty($config?->officeMobile) ? preg_replace('/\s+/', '', $config->officeMobile) : '+8801700000000' }}">{{ !empty($config?->officeMobile) ? $config->officeMobile : '+8801700000000' }}</a>
                                </div>
                            </li>
                            <li>
                                <i class="flaticon-email"></i>
                                <div class="desc">
                                    <a href="mailto:{{ !empty($config?->officeEmail) ? $config->officeEmail : 'info@cultivation.local' }}">{{ !empty($config?->officeEmail) ? $config->officeEmail : 'info@cultivation.local' }}</a>
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
                    <ul class="site-map">
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
                        <li><a href="{{ route('supportPage') }}"><i class="fa fa-angle-right"></i> Honors Admission</a></li>
                        <li><a href="{{ route('supportPage') }}"><i class="fa fa-angle-right"></i> XI Class Admission</a></li>
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
                        <li><a href="{{ route('newSemister') }}"><i class="fa fa-angle-right"></i> Semister Plan</a></li>
                        <li><a href="{{ route('internalResult') }}"><i class="fa fa-angle-right"></i> Internal Result</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row y-middle">
                <div class="col-lg-4 col-md-12 md-mb-10 text-md-center">
                    <div class="copyright">
                        <p>&copy; <span>{{ date('Y') }}</span> {{ !empty($config?->instituteName) ? $config->instituteName : 'Institute' }}. All Rights Reserved.<br>Developed &amp; Powered By <span>Cultivation</span></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 md-mb-10">
                    <ul class="footer-bottom-links">
                        <li><a href="{{ route('homePage') }}">Home</a></li>
                        <li><a href="{{ route('institutePage') }}">About</a></li>
                        <li><a href="{{ route('allNotices') }}">Notice</a></li>
                        <li><a href="{{ route('supportPage') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <ul class="footer-social" style="display:flex;gap:6px;justify-content:flex-end;list-style:none;margin:0;padding:0">
                        <li><a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a></li>
                        <li><a href="#" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="scrollUp">
    <i class="fa fa-angle-up"></i>
</div>
