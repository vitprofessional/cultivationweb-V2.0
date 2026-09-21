@php
    if (!isset($config)) {
        $config = \Illuminate\Support\Facades\Schema::hasTable((new App\Models\ServerConfig())->getTable())
            ? App\Models\ServerConfig::first()
            : null;
    }
    $publicUrl = static function ($value) {
        $value = trim((string) $value);
        if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'])) return null;
        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
        if (!str_contains($host, '.') || preg_match('/(^localhost$|\.localhost$|\.local$|\.test$|\.example$|\.invalid$)/', $host) || filter_var($host, FILTER_VALIDATE_IP)) return null;
        if (parse_url($value, PHP_URL_USER) || parse_url($value, PHP_URL_PASS)) return null;
        return $value;
    };
    $contactValue = static fn ($value) => in_array(strtolower(trim((string) $value)), ['', 'n/a', 'na', 'none', '-']) ? null : trim((string) $value);
    $officeEmail = !empty($config?->officeEmail) && strtolower(trim($config->officeEmail)) !== 'info@cultivation.local'
        ? $config->officeEmail
        : null;
    $demoContact = config('cultivation_demo.contact', []);
    $footerAddress = $contactValue($config?->address) ?: ($demoContact['address'] ?? null);
    $footerPhone = $contactValue($config?->officeMobile) ?: ($demoContact['phone'] ?? null);
    $footerEmail = filter_var($officeEmail, FILTER_VALIDATE_EMAIL) ? $officeEmail : ($demoContact['email'] ?? null);
    $footerWebsite = collect([$config?->website, $config?->webAddress, $config?->websiteUrl, $config?->siteUrl])->map($publicUrl)->filter()->first();
    $footerName = $contactValue($config?->instituteName) ?: config('cultivation_demo.branding.institution_name');
    $footerDescription = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) ($config?->instituteDescription ?? ($insData->insDetails ?? ''))))), 125) ?: $demoContact['description'];
    $footerMap = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($footerAddress);
    $logoFile = !empty($config?->logo) ? basename((string) $config->logo) : null;
    $footerLogo = $logoFile && file_exists(public_path('upload/image/cultivation/' . $logoFile))
        ? url('/public/upload/image/cultivation/' . rawurlencode($logoFile))
        : null;
    $socialLinks = [
        'Facebook' => ['url' => $config?->facebookPage, 'icon' => 'fa-facebook'],
        'Twitter / X' => ['url' => $config?->twitterLink, 'icon' => 'fa-twitter'],
        'Instagram' => ['url' => $config?->instagramLink ?? $config?->instagram, 'icon' => 'fa-instagram'],
        'YouTube' => ['url' => $config?->youtubeChanel, 'icon' => 'fa-youtube-play'],
    ];
    $socialLinks = collect($socialLinks)->filter(fn ($social) => $publicUrl($social['url']));
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
        font-size: 16px;
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

    .footer-about .footer-contact-list li.footer-contact-row {
        align-items: flex-start;
        color: #a0c2e8;
        display: flex;
        font-size: 13.5px;
        gap: 10px;
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .footer-contact-row .footer-contact-icon {
        align-items: center;
        background: rgba(33, 167, 208, 0.12);
        border: 1px solid rgba(33, 167, 208, 0.24);
        border-radius: 8px;
        color: #21a7d0;
        display: inline-flex;
        flex: 0 0 34px;
        font-size: 14px;
        height: 34px;
        justify-content: center;
        margin-top: 0;
        width: 34px;
    }

    .footer-contact-row .footer-contact-value {
        min-width: 0;
        padding-top: 6px;
        word-break: break-word;
    }

    .footer-contact-row a {
        color: #a0c2e8;
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
        color: #a0c2e8;
        font-size: 14.2px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        line-height: 1.6;
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

    /* Restored contact strip and institution panel. */
    #rs-footer .footer-info-strip { background:#142e56; padding:32px 0; }
    .footer-contact-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:36px; }
    #rs-footer .fi-icon { width:48px; height:48px; font-size:20px; }
    #rs-footer .fi-body { min-width:0; }
    #rs-footer .fi-body h6 { font-size:16px; margin-bottom:8px; }
    #rs-footer .fi-body p,#rs-footer .fi-body a { font-size:14px; overflow-wrap:anywhere; word-break:normal; line-height:1.7; }
    #rs-footer .fi-body .footer-map-link { color:#7ed9f4; font-weight:600; }
    #rs-footer .footer-top { padding:56px 0 !important; background:#0b1d38; }
    #rs-footer .footer-main-row { row-gap:40px; }
    #rs-footer .footer-widget { margin-bottom:0; }
    #rs-footer .footer-about { gap:14px; }
    #rs-footer .footer-about-shell { padding:22px 18px; }
    #rs-footer .footer-about-badge { font-size:9px; margin:0; }
    #rs-footer .footer-education-mark { width:54px; height:54px; display:flex; align-items:center; justify-content:center; background:rgba(33,167,208,.13); border-radius:12px; color:#7ed9f4; font-size:28px; }
    #rs-footer .footer-institution-name { color:#fff; font-size:20px; line-height:1.4; margin:0; overflow-wrap:anywhere; }
    #rs-footer .footer-brand-logo img { max-width:100%; max-height:64px; margin:0; }
    #rs-footer .footer-about-desc { font-size:14px; line-height:1.7; }
    #rs-footer .footer-social-inline { margin:4px 0 2px; }
    #rs-footer .footer-sub-section { margin-top:8px; padding-top:22px; }
    #rs-footer .footer-contact-list { list-style:none; padding:0; margin-bottom:0; }
    #rs-footer .footer-contact-row { display:flex; align-items:center; gap:10px; min-height:64px; padding:10px; margin-bottom:10px; border:1px solid rgba(255,255,255,.09); border-radius:10px; background:rgba(255,255,255,.03); }
    #rs-footer .footer-contact-icon { width:30px; height:30px; flex:0 0 30px; font-size:14px; margin:0; }
    #rs-footer .footer-contact-icon i { width:14px; font-size:14px; line-height:1; text-align:center; }
    #rs-footer .footer-contact-value { padding:0; font-size:13px; line-height:1.6; overflow-wrap:anywhere; word-break:normal; }
    #rs-footer .site-map li { padding:9px 0; }
    #rs-footer .site-map li::before { display:none; }
    #rs-footer .site-map a { overflow-wrap:anywhere; }
    #rs-footer .footer-link-group-label { color:#7ed9f4; margin-top:16px; }
    #rs-footer .footer-link-group-label:first-child { margin-top:0; }
    #rs-footer .footer-bottom { background:#07162c; padding:28px 0; }
    #rs-footer .footer-bottom-links { justify-content:flex-end; }
    #rs-footer a:focus-visible { outline:2px solid #7ed9f4; outline-offset:4px; }
    @media(min-width:992px) { #rs-footer .footer-main-row > :first-child { flex:0 0 31%; max-width:31%; } #rs-footer .footer-main-row > :not(:first-child) { flex:0 0 23%; max-width:23%; } }
    @media(max-width:991px) { .footer-contact-grid { grid-template-columns:1fr; gap:24px; } #rs-footer .footer-bottom-links { justify-content:flex-start; margin-top:16px; } }
    @media(max-width:767px) { #rs-footer .footer-top { padding:40px 0 !important; } #rs-footer .footer-widget { padding-right:15px; } #rs-footer .footer-bottom-links { justify-content:center; } #rs-footer .footer-bottom-legal { text-align:center; } }

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
    <div class="footer-info-strip">
        <div class="container footer-contact-grid">
            <div class="fi-card"><span class="fi-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span><div class="fi-body"><h6>Address</h6><p>{{ $footerAddress }}</p></div></div>
            <div class="fi-card"><span class="fi-icon"><i class="fa fa-phone" aria-hidden="true"></i></span><div class="fi-body"><h6>Phone &amp; Email</h6><p><a href="tel:{{ preg_replace('/[^+0-9]/', '', $footerPhone) }}">{{ $footerPhone }}</a></p><p><a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></p></div></div>
            <div class="fi-card"><span class="fi-icon"><i class="fa fa-globe" aria-hidden="true"></i></span><div class="fi-body"><h6>Find Us Online</h6><p>@if($footerWebsite)<a href="{{ $footerWebsite }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('#^https?://#', '', rtrim($footerWebsite, '/')) }}</a>@else<span>Website information coming soon</span>@endif</p><a class="footer-map-link" href="{{ $footerMap }}" target="_blank" rel="noopener noreferrer">Open in Google Maps &rarr;</a></div></div>
        </div>
    </div>

    <div class="footer-top">
        <div class="container">
            <div class="row footer-main-row">

                {{-- Institution identity remains supplied by ServerConfig. --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget">
                    <div class="footer-about footer-about-shell">
                        <span class="footer-about-badge">Official School Portal</span>
                        <a href="{{ route('homePage') }}" class="footer-brand-logo" aria-label="{{ $footerName }} home">
                            @if($footerLogo)<img src="{{ $footerLogo }}" alt="{{ $footerName }} logo" loading="lazy">
                            @else<span class="footer-education-mark"><i class="fa fa-university" aria-hidden="true"></i></span>@endif
                        </a>
                        <h4 class="footer-institution-name">{{ $footerName }}</h4>
                        <p class="footer-about-desc">{{ $footerDescription }}</p>
                        @if($socialLinks->isNotEmpty())
                            <ul class="footer-social-inline">
                                @foreach($socialLinks as $label => $social)
                                    <li><a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}"><i class="fa {{ $social['icon'] }}" aria-hidden="true"></i></a></li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="footer-sub-section">
                            <h5 class="footer-sub-title">Contact Us</h5>
                            <ul class="footer-contact-list">
                                <li class="footer-contact-row"><span class="footer-contact-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span><span class="footer-contact-value">{{ $footerAddress }}</span></li>
                                <li class="footer-contact-row"><span class="footer-contact-icon"><i class="fa fa-phone" aria-hidden="true"></i></span><a class="footer-contact-value" href="tel:{{ preg_replace('/[^+0-9]/', '', $footerPhone) }}">{{ $footerPhone }}</a></li>
                                <li class="footer-contact-row"><span class="footer-contact-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span><a class="footer-contact-value" href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></li>
                                <li class="footer-contact-row"><span class="footer-contact-icon"><i class="fa fa-globe" aria-hidden="true"></i></span>@if($footerWebsite)<a class="footer-contact-value" href="{{ $footerWebsite }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('#^https?://#', '', rtrim($footerWebsite, '/')) }}</a>@else<span class="footer-contact-value">Website information coming soon</span>@endif</li>
                            </ul>
                        </div>
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
                    </ul>
                </div>

                {{-- Col 4: Gallery & Academic --}}
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget">
                    <h4 class="widget-title">Gallery &amp; Academic</h4>
                    <ul class="site-map">
                        <li class="footer-link-group-label">Gallery</li>
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
                            <p>&copy; <span>{{ date('Y') }}</span> {{ $footerName }}. All Rights Reserved.</p>
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
