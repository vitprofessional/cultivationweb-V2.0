@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Support
@endsection
@section('frontcontent')
@php
    $config = \App\Models\ServerConfig::query()->latest('id')->first();
    $contactValue = static fn ($value) => in_array(strtolower(trim((string) $value)), ['', 'n/a', 'na', 'none', '-']) ? null : trim((string) $value);
    $officePhone = $contactValue($config?->officeMobile);
    $phoneForDial = $officePhone ? preg_replace('/\s+/', '', $officePhone) : '';
    $officeEmail = $contactValue($config?->officeEmail);
    $officeEmail = filter_var($officeEmail, FILTER_VALIDATE_EMAIL) && !preg_match('/\.(local|test|example|invalid)$/i', substr(strrchr($officeEmail, '@') ?: '', 1)) ? $officeEmail : null;
    $officeAddress = $contactValue($config?->address);
    $officeMap = $contactValue($config?->mapEmbed);
    $officeMap = filter_var($officeMap, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($officeMap, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true) && !parse_url($officeMap, PHP_URL_USER) && !parse_url($officeMap, PHP_URL_PASS) ? $officeMap : null;
    $storedMap = $contactValue($config?->mapEmbed);
    if (!$officeMap && $storedMap && preg_match('/\A![A-Za-z0-9%._~!$&\x27()*+,;=:@\/?-]+\z/D', $storedMap)) $officeMap = 'https://www.google.com/maps/embed?pb='.rawurlencode($storedMap);
    $publicSocialUrl = static function ($value) {
        $value = trim((string) $value);
        if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)) return null;
        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
        if ($host === '' || !str_contains($host, '.') || preg_match('/(^localhost$|\.(local|test|example|invalid)$)/', $host) || parse_url($value, PHP_URL_USER) || parse_url($value, PHP_URL_PASS)) return null;
        return $value;
    };
    $contactSocialLinks = collect([
        'Facebook' => ['url' => $config?->facebookPage, 'icon' => 'fa-facebook'],
        'Twitter / X' => ['url' => $config?->twitterLink, 'icon' => 'fa-twitter'],
        'YouTube' => ['url' => $config?->youtubeChanel, 'icon' => 'fa-youtube-play'],
        'LinkedIn' => ['url' => $config?->linkedIn, 'icon' => 'fa-linkedin'],
    ])->map(fn ($social) => array_merge($social, ['url' => $publicSocialUrl($social['url'])]))->filter(fn ($social) => $social['url']);
@endphp

<style>
    .support-pro-page {
        --support-ink: #17334f;
        --support-soft: #5f7288;
        --support-line: rgba(23, 51, 79, 0.12);
        --support-hero: linear-gradient(135deg, #ecf6ff 0%, #f4f8ef 42%, #fff4e8 100%);
        --support-accent: #1b7d66;
        --support-accent-2: #c56d2d;
        --support-shadow: 0 30px 72px rgba(16, 44, 83, 0.14);
        position: relative;
        color: var(--support-ink);
        padding: 34px 0 44px;
    }

    .support-pro-page::before,
    .support-pro-page::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
        filter: blur(4px);
    }

    .support-pro-page::before {
        width: 280px;
        height: 280px;
        left: -90px;
        top: 60px;
        background: radial-gradient(circle, rgba(27, 125, 102, 0.18), rgba(27, 125, 102, 0));
    }

    .support-pro-page::after {
        width: 320px;
        height: 320px;
        right: -120px;
        top: 260px;
        background: radial-gradient(circle, rgba(197, 109, 45, 0.16), rgba(197, 109, 45, 0));
    }

    .support-shell {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 24px;
    }

    .support-hero {
        border-radius: 28px;
        background: var(--support-hero);
        border: 1px solid rgba(255, 255, 255, 0.75);
        box-shadow: var(--support-shadow);
        overflow: hidden;
    }

    .support-hero-grid {
        padding: 28px;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
        gap: 24px;
        align-items: stretch;
    }

    .support-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 14px;
        background: rgba(255, 255, 255, 0.78);
        border: 1px solid rgba(23, 51, 79, 0.1);
        color: #166f5c;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .support-hero-copy h1 {
        margin: 16px 0 12px;
        font-size: clamp(2rem, 4.1vw, 3.8rem);
        line-height: 1;
        letter-spacing: -0.04em;
        font-weight: 800;
        max-width: 11ch;
    }

    .support-hero-copy p {
        margin: 0 0 20px;
        color: var(--support-soft);
        max-width: 58ch;
        line-height: 1.8;
        font-size: 1rem;
    }

    .support-stat-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .support-stat {
        border-radius: 16px;
        border: 1px solid rgba(23, 51, 79, 0.08);
        background: rgba(255, 255, 255, 0.72);
        padding: 14px 16px;
    }

    .support-stat strong {
        display: block;
        margin-bottom: 4px;
        font-size: 1.38rem;
        line-height: 1;
        font-weight: 800;
        color: #17395e;
    }

    .support-stat span {
        display: block;
        color: var(--support-soft);
        font-size: 0.82rem;
        line-height: 1.5;
    }

    .support-priority {
        border-radius: 24px;
        border: 1px solid rgba(23, 51, 79, 0.1);
        background: rgba(255, 255, 255, 0.76);
        box-shadow: 0 18px 40px rgba(14, 36, 58, 0.12);
        padding: 22px;
        display: grid;
        gap: 14px;
    }

    .support-priority h3 {
        margin: 0;
        font-size: 1.28rem;
        font-weight: 800;
        color: #17395e;
    }

    .support-priority p {
        margin: 0;
        color: var(--support-soft);
        line-height: 1.75;
        font-size: 0.95rem;
    }

    .support-priority ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 10px;
    }

    .support-priority li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #2b4f72;
        line-height: 1.6;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .support-priority li i {
        margin-top: 2px;
        color: var(--support-accent);
    }

    .support-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.6fr);
        gap: 22px;
        align-items: start;
    }

    .support-channels,
    .support-form-wrap {
        border-radius: 26px;
        border: 1px solid rgba(23, 51, 79, 0.09);
        background: #fff;
        box-shadow: 0 20px 50px rgba(15, 39, 63, 0.09);
        overflow: hidden;
    }

    .support-panel-head {
        padding: 20px 22px;
        border-bottom: 1px solid var(--support-line);
        background: linear-gradient(135deg, #f8fbff, #f9fcf8);
    }

    .support-panel-head span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 6px 11px;
        background: rgba(23, 125, 102, 0.09);
        color: #14715d;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .support-panel-head h2 {
        margin: 12px 0 8px;
        font-size: 1.6rem;
        line-height: 1.15;
        font-weight: 800;
        color: #17395e;
    }

    .support-panel-head p {
        margin: 0;
        color: var(--support-soft);
        line-height: 1.72;
        font-size: 0.95rem;
    }

    .support-channel-list {
        display: grid;
        gap: 12px;
        padding: 16px 16px 18px;
    }

    .support-channel-card {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 14px;
        border: 1px solid rgba(23, 51, 79, 0.08);
        background: linear-gradient(135deg, #fcfeff, #fbfdfb);
    }

    .support-channel-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: linear-gradient(135deg, #1b7d66, #0f5a8a);
        flex-shrink: 0;
    }

    .support-channel-card h4 {
        margin: 0 0 4px;
        font-size: 1rem;
        font-weight: 800;
        color: #17395e;
    }

    .support-channel-card p {
        margin: 0;
        color: var(--support-soft);
        line-height: 1.6;
        font-size: 0.88rem;
    }

    .support-channel-card a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        color: #136a8c;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
    }

    .support-channel-card a:hover {
        text-decoration: underline;
    }

    .support-alerts {
        padding: 18px 22px 0;
    }

    .support-form-body {
        padding: 18px 22px 22px;
    }

    .support-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .support-form-group {
        display: grid;
        gap: 7px;
    }

    .support-form-group.support-span-2 {
        grid-column: span 2;
    }

    .support-form-group label {
        margin: 0;
        font-size: 0.84rem;
        font-weight: 700;
        color: #345673;
    }

    .support-form-group .form-control {
        height: 46px;
        border-radius: 12px;
        border: 1px solid #d2dfeb;
        box-shadow: none;
        color: #25435f;
        background: #fff;
        font-size: 0.94rem;
    }

    .support-form-group textarea.form-control {
        height: 154px;
        resize: vertical;
        padding-top: 12px;
    }

    .support-form-group .form-control:focus {
        border-color: #1b7d66;
        box-shadow: 0 0 0 0.2rem rgba(27, 125, 102, 0.12);
    }

    .support-form-actions {
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .support-note {
        color: var(--support-soft);
        font-size: 0.84rem;
        line-height: 1.6;
        margin: 0;
    }

    .support-submit {
        border: 0;
        border-radius: 999px;
        padding: 12px 24px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #1b7d66, #0f5a8a);
        box-shadow: 0 12px 24px rgba(15, 70, 108, 0.22);
    }

    .support-submit:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    @media (max-width: 1199.98px) {
        .support-hero-grid,
        .support-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .support-pro-page {
            padding: 20px 0 30px;
        }

        .support-hero-grid {
            padding: 18px;
        }

        .support-hero-copy h1 {
            max-width: none;
            font-size: 2.15rem;
        }

        .support-stat-row,
        .support-form-grid {
            grid-template-columns: 1fr;
        }

        .support-form-group.support-span-2 {
            grid-column: auto;
        }

        .support-panel-head,
        .support-alerts,
        .support-form-body {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>

<section class="support-pro-page">
    <div class="container support-shell">
        <div class="support-hero">
            <div class="support-hero-grid">
                <div class="support-hero-copy">
                    <span class="support-kicker"><i class="fa fa-headset" aria-hidden="true"></i> Support Desk</span>
                    <h1>Professional support for students and guardians.</h1>
                    <p>For admissions, routine information, certificates, or general assistance, our support team is available through verified official channels. Send your query with clear details and we will guide you to the right department.</p>

                    <div class="support-stat-row">
                        <div class="support-stat">
                            <strong>Official</strong>
                            <span>Contact details below are drawn from the institution’s saved profile.</span>
                        </div>
                    </div>
                </div>

                <aside class="support-priority">
                    <h3>Before You Submit</h3>
                    <p>To receive faster support, include enough context so the team can identify your request without follow-up delays.</p>
                    <ul>
                        <li><i class="fa fa-check-circle" aria-hidden="true"></i> Mention student name, class, and session (if applicable).</li>
                        <li><i class="fa fa-check-circle" aria-hidden="true"></i> Add a specific subject line, such as Admission, Result, or Certificate.</li>
                        <li><i class="fa fa-check-circle" aria-hidden="true"></i> For urgent matters, call the office directly during service hours.</li>
                    </ul>
                </aside>
            </div>
        </div>

        <div class="support-grid">
            <aside class="support-channels">
                <div class="support-panel-head">
                    <span><i class="fa fa-phone" aria-hidden="true"></i> Contact Channels</span>
                    <h2>Reach the support team</h2>
                    <p>Choose the channel that best fits your request. For records, policy, or document-related issues, email is recommended.</p>
                </div>

                <div class="support-channel-list">
                    @if($officePhone)<article class="support-channel-card">
                        <span class="support-channel-icon"><i class="fa fa-phone"></i></span>
                        <div>
                            <h4>Office Phone</h4>
                            <p>Call during office hours for immediate guidance.</p>
                            <a href="tel:{{ $phoneForDial }}"><i class="fa fa-arrow-right"></i> {{ $officePhone }}</a>
                        </div>
                    </article>@endif

                    @if($officeEmail)<article class="support-channel-card">
                        <span class="support-channel-icon"><i class="fa fa-envelope"></i></span>
                        <div>
                            <h4>Email Support</h4>
                            <p>Send detailed inquiries and keep a written communication record.</p>
                            <a href="mailto:{{ $officeEmail }}"><i class="fa fa-arrow-right"></i> {{ $officeEmail }}</a>
                        </div>
                    </article>@endif

                    @if($officeAddress || $officeMap)<article class="support-channel-card">
                        <span class="support-channel-icon"><i class="fa fa-map-marker"></i></span>
                        <div>
                            <h4>Office Desk</h4>
                            @if($officeAddress)<p>{{ $officeAddress }}</p>@endif
                            @if($officeMap)<a href="{{ $officeMap }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-arrow-right"></i> Open saved map link</a>@endif
                        </div>
                    </article>@endif
                    @if($contactSocialLinks->isNotEmpty())<article class="support-channel-card">
                        <span class="support-channel-icon"><i class="fa fa-share-alt"></i></span>
                        <div>
                            <h4>Official Social Channels</h4>
                            <p>Follow the institute through its published channels.</p>
                            @foreach($contactSocialLinks as $label => $social)
                                <a class="d-block mb-1" href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"><i class="fa {{ $social['icon'] }}"></i> {{ $label }}</a>
                            @endforeach
                        </div>
                    </article>@endif
                    @unless($officePhone || $officeEmail || $officeAddress || $officeMap || $contactSocialLinks->isNotEmpty())
                        <p class="support-empty-state">Official contact details have not been added yet.</p>
                    @endunless
                </div>
            </aside>

            <div class="support-form-wrap">
                <div class="support-panel-head">
                    <span><i class="fa fa-paper-plane" aria-hidden="true"></i> Message Form</span>
                    <h2>Submit an inquiry</h2>
                    <p>Fill out the form below. A support representative will review your message and respond through the channel you provide.</p>
                </div>

                <div class="support-alerts">
                    @if(Session::get('success'))
                        <div class="alert alert-success mb-0" role="alert">
                            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>{!! Session::get('success') !!}</span>
                        </div>
                    @endif

                    @if(Session::get('error'))
                        <div class="alert alert-warning mb-0" role="alert">
                            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>{!! Session::get('error') !!}</span>
                        </div>
                    @endif
                </div>

                <div class="support-form-body">
                    <form method="post" id="myForm" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="support-form-grid">
                            <div class="support-form-group">
                                <label for="supportName">Full Name</label>
                                <input id="supportName" type="text" class="form-control" name="name" placeholder="Enter your full name" required>
                            </div>

                            <div class="support-form-group">
                                <label for="supportEmail">Email Address</label>
                                <input id="supportEmail" type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>

                            <div class="support-form-group">
                                <label for="supportPhone">Phone Number</label>
                                <input id="supportPhone" type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
                            </div>

                            <div class="support-form-group">
                                <label for="supportSubject">Subject</label>
                                <input id="supportSubject" type="text" name="subject" class="form-control" placeholder="Write a short subject" required>
                            </div>

                            <div class="support-form-group support-span-2">
                                <label for="supportMessage">Message</label>
                                <textarea id="supportMessage" class="form-control" name="message" placeholder="Explain your request with relevant details" required></textarea>
                            </div>
                        </div>

                        <div class="support-form-actions">
                            <p class="support-note mb-0">For urgent issues, please call the office directly instead of waiting for email response.</p>
                            <button type="submit" class="btn support-submit">Submit Inquiry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
