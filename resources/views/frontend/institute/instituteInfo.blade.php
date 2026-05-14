@extends($frontendLayout ?? config('frontend.layout'))

@section('fronttitle')
About Us
@endsection

@php
$config = App\Models\ServerConfig::first();
@endphp

@push('styles')
<style>
    .about-pro-breadcrumb {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 44px 38px;
        margin-bottom: 28px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 24px 50px rgba(17, 41, 88, 0.16);
    }

    .about-pro-breadcrumb::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(17, 41, 88, 0.9), rgba(33, 167, 208, 0.72));
    }

    .about-pro-breadcrumb > * {
        position: relative;
        z-index: 1;
    }

    .about-pro-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .about-pro-breadcrumb h1,
    .about-pro-breadcrumb p,
    .about-pro-breadcrumb li,
    .about-pro-breadcrumb a {
        color: #fff;
    }

    .about-pro-breadcrumb h1 {
        font-size: 42px;
        line-height: 1.12;
        margin-bottom: 12px;
    }

    .about-pro-breadcrumb p {
        max-width: 720px;
        margin-bottom: 18px;
        font-size: 16px;
        opacity: 0.96;
    }

    .about-pro-breadcrumb-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        list-style: none;
        padding: 0;
        margin: 0;
        font-weight: 600;
    }

    .about-pro-breadcrumb-list li {
        position: relative;
    }

    .about-pro-breadcrumb-list li + li::before {
        content: "/";
        position: absolute;
        left: -13px;
        top: 0;
        opacity: 0.7;
    }

    .about-pro-panel {
        background: #fff;
        border: 1px solid rgba(17, 41, 88, 0.08);
        border-radius: 22px;
        box-shadow: 0 18px 40px rgba(17, 41, 88, 0.08);
        overflow: hidden;
    }

    body.home-style2 .edu-content-wrap {
        display: flex;
        align-items: center;
    }

    body.home-style2 .edu-content-wrap .container {
        width: 100%;
    }

    body.home-style2 .edu-main-card {
        width: 100%;
    }

    body.home-style2 .edu-main-inner > .row {
        align-items: center;
    }

    .about-pro-media {
        position: relative;
        min-height: 100%;
        background: linear-gradient(180deg, #f1f8fb 0%, #e4f4f9 100%);
        padding: 26px;
    }

    .about-pro-media img {
        width: 100%;
        min-height: 360px;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .about-pro-media-card {
        position: absolute;
        right: 42px;
        bottom: 42px;
        max-width: 240px;
        background: #fff;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 20px 40px rgba(17, 41, 88, 0.16);
    }

    .about-pro-media-card .label {
        display: block;
        color: #21a7d0;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .about-pro-media-card strong {
        display: block;
        color: #112958;
        font-size: 22px;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    .about-pro-content {
        padding: 34px;
    }

    .about-pro-sec-title {
        margin-bottom: 18px;
    }

    .about-pro-sec-title .sub {
        display: inline-block;
        color: #ff6f1a;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .about-pro-sec-title h2 {
        margin-bottom: 12px;
        font-size: 34px;
        line-height: 1.2;
    }

    .about-pro-lead {
        font-size: 17px;
        line-height: 1.85;
        color: #41566f;
        margin-bottom: 0;
    }

    .about-pro-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin: 26px 0;
    }

    .about-pro-meta-item {
        padding: 20px 18px;
        border-radius: 18px;
        background: #f7fbfd;
        border: 1px solid rgba(33, 167, 208, 0.12);
        min-height: 100%;
    }

    .about-pro-meta-item span {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6f8199;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .about-pro-meta-item strong {
        display: block;
        font-size: 20px;
        line-height: 1.3;
        color: #112958;
        margin-bottom: 6px;
    }

    .about-pro-meta-item p {
        margin: 0;
        color: #5b6d84;
        line-height: 1.7;
    }

    .about-pro-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 28px;
    }

    .about-pro-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 50px;
        padding: 12px 22px;
        border-radius: 999px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .about-pro-btn-primary {
        background: linear-gradient(135deg, #21a7d0, #112958);
        color: #fff;
        box-shadow: 0 12px 26px rgba(33, 167, 208, 0.22);
    }

    .about-pro-btn-primary:hover,
    .about-pro-btn-primary:focus {
        color: #fff;
        transform: translateY(-1px);
    }

    .about-pro-btn-light {
        background: #fff;
        color: #112958;
        border: 1px solid rgba(17, 41, 88, 0.15);
    }

    .about-pro-btn-light:hover,
    .about-pro-btn-light:focus {
        color: #112958;
        border-color: rgba(17, 41, 88, 0.28);
    }

    .about-pro-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
        margin-top: 28px;
    }

    .about-pro-card {
        height: 100%;
        padding: 28px;
        border-radius: 22px;
        background: #fff;
        border: 1px solid rgba(17, 41, 88, 0.08);
        box-shadow: 0 18px 36px rgba(17, 41, 88, 0.07);
    }

    .about-pro-card-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
        margin-bottom: 18px;
        background: linear-gradient(135deg, #ff8a34, #ff6f1a);
        box-shadow: 0 14px 26px rgba(255, 111, 26, 0.2);
    }

    .about-pro-card p {
        margin: 0;
        line-height: 1.85;
        color: #53677f;
    }

    .about-pro-contact {
        padding: 28px;
        border-radius: 22px;
        background: linear-gradient(135deg, #112958, #1b4b86);
        color: #fff;
        box-shadow: 0 24px 44px rgba(17, 41, 88, 0.18);
    }

    .about-pro-contact h3,
    .about-pro-contact p,
    .about-pro-contact li,
    .about-pro-contact a {
        color: #fff;
    }

    .about-pro-contact ul {
        list-style: none;
        padding: 0;
        margin: 18px 0 0;
    }

    .about-pro-contact li {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
    }

    .about-pro-contact li:first-child {
        border-top: 0;
        padding-top: 0;
    }

    .about-pro-contact i {
        width: 22px;
        text-align: center;
        margin-top: 4px;
        color: #8fe6ff;
    }

    .about-pro-empty {
        padding: 40px;
        text-align: center;
        border-radius: 22px;
        background: #fff8ef;
        border: 1px solid rgba(255, 111, 26, 0.16);
        color: #8a5b26;
    }

    @media (max-width: 991.98px) {
        .about-pro-breadcrumb {
            padding: 34px 24px;
        }

        .about-pro-breadcrumb h1 {
            font-size: 34px;
        }

        .about-pro-media-card {
            position: static;
            margin-top: 18px;
            max-width: none;
        }

        .about-pro-meta {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .about-pro-content,
        .about-pro-card,
        .about-pro-contact {
            padding: 22px;
        }

        .about-pro-breadcrumb h1 {
            font-size: 28px;
        }

        .about-pro-grid {
            grid-template-columns: 1fr;
        }

        .about-pro-media {
            padding: 18px;
        }

        .about-pro-media img {
            min-height: 260px;
        }
    }
</style>
@endpush

@section('frontcontent')
@php
    $instituteName = !empty($config?->instituteName) ? $config->instituteName : 'Jahanara Ayub Academy';
    $heroImage = !empty($data?->heroImg)
        ? config('app.url') . '/public/upload/image/cultivation/' . $data->heroImg
        : asset('public/cultivation/assets/images/breadcrumbs/2.jpg');

    $aboutEstYear = '';
    if (!empty($data?->establishDate)) {
        try {
            $aboutEstYear = \Carbon\Carbon::parse($data->establishDate)->format('Y');
        } catch (\Exception $e) {
            if (preg_match('/(19|20)\d{2}/', $data->establishDate, $match)) {
                $aboutEstYear = $match[0];
            }
        }
    }

    $headline = !empty($data?->insHeadline) ? $data->insHeadline : 'A learning community committed to growth, values, and academic excellence.';
    $aboutText = trim((string) ($data->insDetails ?? ''));
    $missionText = trim((string) ($data->mission ?? ''));
    $visionText = trim((string) ($data->vision ?? ''));
    $campusArea = trim((string) ($data->landSize ?? ''));
@endphp

<div class="col-12">
    <section class="about-pro-breadcrumb" style="background-image: url('{{ asset('public/cultivation/assets/images/breadcrumbs/2.jpg') }}');">
        <div class="about-pro-kicker">Institute Profile</div>
        <h1>{{ $instituteName }}</h1>
        <p>{{ $headline }}</p>
        <ul class="about-pro-breadcrumb-list">
            <li><a href="{{ route('homePage') }}">Home</a></li>
            <li>About Us</li>
        </ul>
    </section>
</div>

@if($data)
<div class="col-12 mb-4">
    <section class="about-pro-panel">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5">
                <div class="about-pro-media h-100">
                    <img src="{{ $heroImage }}" alt="{{ $instituteName }}">
                    <div class="about-pro-media-card">
                        <span class="label">Our Identity</span>
                        <strong>{{ $aboutEstYear ? 'Established ' . $aboutEstYear : 'Institution Overview' }}</strong>
                        <p class="mb-0">{{ $campusArea ? $campusArea : 'A focused academic environment dedicated to student development and institutional progress.' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-pro-content">
                    <div class="about-pro-sec-title">
                        <span class="sub">About Us</span>
                        <h2>{{ $headline }}</h2>
                    </div>
                    <p class="about-pro-lead">
                        {{ $aboutText ? $aboutText : 'Our institution is dedicated to building strong academic foundations, a disciplined learning culture, and meaningful opportunities for every learner.' }}
                    </p>

                    <div class="about-pro-meta">
                        <div class="about-pro-meta-item">
                            <span>Established</span>
                            <strong>{{ $aboutEstYear ? $aboutEstYear : 'Not Available' }}</strong>
                            <p>Institutional foundation and academic legacy.</p>
                        </div>
                        <div class="about-pro-meta-item">
                            <span>Campus Area</span>
                            <strong>{{ $campusArea ? $campusArea : 'To Be Updated' }}</strong>
                            <p>Learning spaces designed to support education and community life.</p>
                        </div>
                        <div class="about-pro-meta-item">
                            <span>Focus</span>
                            <strong>Academic Excellence</strong>
                            <p>Student-centered teaching, values, and continuous development.</p>
                        </div>
                    </div>

                    <div class="about-pro-actions">
                        <a class="about-pro-btn about-pro-btn-primary" href="{{ route('supportPage') }}">Contact the Institute</a>
                        <a class="about-pro-btn about-pro-btn-light" href="{{ route('imagePage') }}">Explore Gallery</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="col-lg-8 mb-4">
    <div class="about-pro-grid mt-0">
        <div class="about-pro-card">
            <div class="about-pro-card-icon"><i class="fa fa-bullseye"></i></div>
            <h3>Our Mission</h3>
            <p>{{ $missionText ? $missionText : 'We aim to provide meaningful education that strengthens knowledge, discipline, and character while preparing learners for responsible citizenship.' }}</p>
        </div>
        <div class="about-pro-card">
            <div class="about-pro-card-icon"><i class="fa fa-eye"></i></div>
            <h3>Our Vision</h3>
            <p>{{ $visionText ? $visionText : 'We envision an inclusive academic environment where learners grow with confidence, integrity, and readiness for future challenges.' }}</p>
        </div>
    </div>
</div>

<div class="col-lg-4 mb-4">
    <aside class="about-pro-contact">
        <div class="about-pro-sec-title mb-0">
            <span class="sub" style="color:#8fe6ff;">Connect With Us</span>
            <h3 class="mb-2">Institution Information</h3>
        </div>
        <p class="mb-0">For admissions, academic support, or general enquiries, connect with the institute through the official channels below.</p>
        <ul>
            <li>
                <i class="fa fa-map-marker"></i>
                <div>{{ !empty($config?->address) ? $config->address : 'Address information will be updated soon.' }}</div>
            </li>
            <li>
                <i class="fa fa-phone"></i>
                <div>
                    @if(!empty($config?->officeMobile))
                        <a href="tel:{{ preg_replace('/\s+/', '', $config->officeMobile) }}">{{ $config->officeMobile }}</a>
                    @else
                        Phone information will be updated soon.
                    @endif
                </div>
            </li>
            <li>
                <i class="fa fa-envelope"></i>
                <div>
                    @if(!empty($config?->officeEmail))
                        <a href="mailto:{{ $config->officeEmail }}">{{ $config->officeEmail }}</a>
                    @else
                        Email information will be updated soon.
                    @endif
                </div>
            </li>
            <li>
                <i class="fa fa-globe"></i>
                <div><a href="{{ url('/') }}">{{ url('/') }}</a></div>
            </li>
        </ul>
    </aside>
</div>
@else
<div class="col-12">
    <div class="about-pro-empty">
        <h3 class="mb-2">About information is not available yet</h3>
        <p class="mb-0">Please update the institute profile from the admin panel to show the full About Us content on this page.</p>
    </div>
</div>
@endif
@endsection
