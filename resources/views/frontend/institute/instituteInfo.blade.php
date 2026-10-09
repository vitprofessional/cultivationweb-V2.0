@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'About Us')
@php
$config = App\Models\ServerConfig::query()->latest('id')->first();
@endphp
@section('frontcontent')
<style>
body:has(#about-page) .edu-content-wrap{background:linear-gradient(#edf9ff,#f7fcff);padding:0 0 22px;min-height:0}
body:has(#about-page) .edu-content-wrap>.container{max-width:none;padding:0}
body:has(#about-page) .edu-main-card{border:0;background:transparent;box-shadow:none;border-radius:0;overflow:visible}
body:has(#about-page) .edu-main-inner{padding:0}
body:has(#about-page) .edu-page-title,body:has(#about-page) .homepage-slider-wrap{display:none}
body:has(#about-page) .edu-main-inner>.row{margin:0}
#about-page{padding:0;color:#17375e}
.about-hero{position:relative;isolation:isolate;padding:38px max(7vw,calc((100vw - 1120px)/2)) 88px;min-height:300px;overflow:hidden;background:linear-gradient(115deg,#e9f8ff,#c9ebfc)}
.about-hero-visual{position:absolute;right:0;bottom:0;width:70%;height:100%;z-index:-2;opacity:.85}
.about-hero:after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,#edf9fff7 0%,#e4f6fff0 30%,#d5f0ff7a 68%,#c1e8ff38)}
.about-breadcrumb{font-size:13px;margin-bottom:22px}.about-breadcrumb a{color:#087eae}
.about-hero h1{font-size:44px;line-height:1.2;color:#10315c;margin:0 0 12px;font-weight:800}
.about-hero h1:after{content:"";display:block;width:42px;height:3px;background:#08b4d9;margin-top:16px}
.about-hero p{max-width:52ch;font-size:18px;line-height:1.6;margin:0}
.about-shell{position:relative;max-width:1160px;width:91%;margin:-42px auto 0;display:grid;gap:20px}
.about-overview{display:grid;grid-template-columns:minmax(0,41%) minmax(0,1fr);gap:28px;padding:14px;background:white;border:1px solid #def0f9;border-radius:15px;box-shadow:0 7px 24px #24516c08}
.about-campus{position:relative;min-width:0;border-radius:12px;overflow:hidden;background:linear-gradient(145deg,#e2f4fd,#c5e4ef);min-height:340px}
.about-campus img{width:100%;height:100%;position:absolute;inset:0;object-fit:cover;object-position:center}
.about-campus-caption{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,#102f47e8);color:white;padding:48px 20px 20px}
.about-campus-caption strong{display:block;font-size:18px}.about-campus-caption span{font-size:13px;line-height:1.5}
.about-overview-copy{padding:8px 0;min-width:0}#about-page .about-eyebrow{font-size:12px;letter-spacing:1.8px;color:#049bc7;font-weight:800;margin:0 0 10px}
.about-overview h2{font-size:29px;line-height:1.3;margin:0 0 18px;font-weight:800;color:#143665}
.about-text{font-size:18px;line-height:1.85;color:#4a5c70;overflow-wrap:anywhere}.about-text p{margin:0 0 14px}
.about-facts{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:14px;margin-top:16px}
.about-fact{text-align:center;border:1px solid #dceef9;background:#f8fcff;border-radius:12px;padding:18px 12px}
.about-fact i{display:block;color:#087ded;font-size:25px;margin-bottom:10px}.about-fact strong{display:block;font-size:19px;color:#123b75}.about-fact span{font-size:13px;color:#526885}
.about-purpose{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px;align-items:stretch}
.about-purpose-card{position:relative;overflow:hidden;border:1px solid #d2eefb;border-top:4px solid #0ab0da;border-radius:16px;padding:30px;background:linear-gradient(145deg,#fff,#edf9ff);box-shadow:0 8px 24px #23608009;min-width:0}
.about-purpose-card--vision{background:linear-gradient(145deg,#f7fff9,#effaf3);border-color:#d7efdd}
.about-purpose-card--vision{border-top-color:#369652}
.about-purpose-icon{display:grid;place-items:center;width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#00a5e0,#00c6e3);color:white;border:3px solid white;box-shadow:0 3px 9px #1492a315;font-size:27px;margin-bottom:16px}
.about-purpose-card--vision .about-purpose-icon{background:linear-gradient(135deg,#268831,#50ba44)}
.about-number{position:absolute;right:22px;top:16px;font-size:58px;line-height:1;font-weight:800;color:#00a3e02b}
.about-purpose-card--vision .about-number{color:#37a54425}
.about-purpose-card h3{font-size:29px;color:#123763;font-weight:800;margin:0 0 20px}
.about-purpose-card .about-text{font-size:18px;line-height:1.9}
.about-journey{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;border-radius:13px;padding:28px 34px;background:radial-gradient(ellipse at 10% 50%,#c1e7f6,#ebf8ff 65%);border:1px solid #daedf7}
.about-journey h2{font-size:27px;line-height:1.3;color:#123565;margin:0;font-weight:800;max-width:22ch}
.about-journey-right{padding-left:32px;border-left:1px solid #8bbbd3;min-width:0}.about-journey h3{font-size:21px;margin:0 0 14px;color:#133864}
.about-actions{display:flex;flex-wrap:wrap;gap:12px}.about-action{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;border-radius:24px;padding:10px 20px;background:linear-gradient(110deg,#156cbd,#123858);color:white!important;font-weight:700;font-size:14px;border:1px solid #16699d}
.about-action--outline{background:white;color:#0787bf!important;border-color:#08a6db}
.about-action:hover{background:#1b5481;color:white!important}.about-action:focus-visible{outline:3px solid #00b7dc;outline-offset:3px}
.about-journey-left{display:grid;grid-template-columns:115px minmax(0,1fr);gap:20px;align-items:center;min-width:0}
.about-journey-visual{width:100%;height:auto;filter:drop-shadow(0 7px 10px #37789420)}
@media(max-width:991px){.about-hero{padding:30px 5vw 80px;min-height:270px}.about-hero h1{font-size:38px}.about-overview{grid-template-columns:1fr;padding:16px;gap:20px}.about-campus{min-height:360px}.about-overview-copy{padding:0 6px}.about-purpose{grid-template-columns:1fr}.about-journey{gap:24px;padding:24px}.about-journey-right{padding-left:24px}}
@media(max-width:575px){.about-hero{padding:26px 6vw 72px;min-height:250px}.about-hero h1{font-size:33px}.about-hero p{font-size:16px}.about-shell{width:92%;gap:16px}.about-campus{min-height:290px}.about-overview h2{font-size:25px}.about-text{font-size:17px}.about-purpose-card{padding:22px}.about-journey{grid-template-columns:1fr;gap:20px}.about-journey-right{padding:20px 0 0;border-left:0;border-top:1px solid #8bbbd3}.about-actions{flex-direction:column}.about-action{width:100%}}
@media(max-width:991px){.about-journey-left{grid-template-columns:1fr;gap:12px}.about-journey-visual{width:105px}.about-hero-visual{width:100%;opacity:.6}}
@media(max-width:575px){.about-purpose-card .about-text{font-size:17px}.about-journey-left{grid-template-columns:90px minmax(0,1fr)}.about-journey-visual{width:90px}.about-journey h2{font-size:24px}.about-hero-visual{opacity:.45}}
</style>
@php
    $instituteName = trim((string) ($config?->instituteName ?? ''));
    $contactValue = static fn ($value) => in_array(strtolower(trim((string) $value)), ['', 'n/a', 'na', 'none', '-']) ? null : trim((string) $value);
    $contactAddress = $contactValue($config?->address);

    $heroImage = app(\App\Services\PublicMediaUrl::class)->institutionAboutImage($data?->heroImg);
    $aboutText = (string) ($data?->insDetails ?? '');
    $missionText = (string) ($data?->mission ?? '');
    $visionText = (string) ($data?->vision ?? '');
    $campusArea = trim((string) ($data?->landSize ?? ''));
    $aboutEstYear = '';
    if (preg_match('/(?:19|20)\d{2}/', (string) ($data?->establishDate ?? ''), $year)) $aboutEstYear = $year[0];
    $heroSubtitle = trim((string) ($data?->insHeadline ?? ''));
    if ($heroSubtitle === $instituteName) $heroSubtitle = '';
@endphp
<div id="about-page" class="col-12">
    <header class="about-hero">
        @include('frontend.institute.partials.about-education-visual', ['variant' => 'campus'])
        <nav class="about-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('homePage') }}">Home</a> &rsaquo; About Us</nav>
        <h1>About Us</h1>
        <p>{{ $heroSubtitle ?: 'Our Institution • Our Purpose • Our Journey' }}</p>
    </header>
    <div class="about-shell">
        <section class="about-overview" aria-labelledby="about-overview-title">
            <div class="about-campus">
                @if($heroImage)<img src="{{ $heroImage }}" alt="{{ $instituteName }}" onerror="this.hidden=true">@endif
                @if($instituteName || $contactAddress)
                    <div class="about-campus-caption">
                        @if($instituteName)<strong>{{ $instituteName }}</strong>@endif
                        @if($contactAddress)<span>{{ $contactAddress }}</span>@endif
                    </div>
                @endif
            </div>
            <div class="about-overview-copy">
                <p class="about-eyebrow">ABOUT OUR INSTITUTION</p>
                <h2 id="about-overview-title">{{ $instituteName ?: 'Institution Overview' }}</h2>
                <div class="about-text" data-about-content>
                    @forelse(preg_split('/\R\s*\R/u', trim($aboutText), -1, PREG_SPLIT_NO_EMPTY) as $paragraph)
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @empty
                        <p>About information has not been published yet.</p>
                    @endforelse
                </div>
                @if($aboutEstYear || $campusArea)
                    <div class="about-facts">
                        @if($aboutEstYear)<div class="about-fact"><i class="fa fa-graduation-cap" aria-hidden="true"></i><strong>{{ $aboutEstYear }}</strong><span>Established</span></div>@endif
                        @if($campusArea)<div class="about-fact"><i class="fa fa-map" aria-hidden="true"></i><strong>{{ $campusArea }}</strong><span>Campus Area</span></div>@endif
                    </div>
                @endif
            </div>
        </section>
        <section class="about-purpose" aria-label="Mission and vision">
            @foreach([['mission', 'Our Mission', '01', 'fa-bullseye', $missionText], ['vision', 'Our Vision', '02', 'fa-eye', $visionText]] as $purpose)
                <article class="about-purpose-card about-purpose-card--{{ $purpose[0] }}">
                    <span class="about-number" aria-hidden="true">{{ $purpose[2] }}</span>
                    <span class="about-purpose-icon" aria-hidden="true"><i class="fa {{ $purpose[3] }}"></i></span>
                    <h3>{{ $purpose[1] }}</h3>
                    <div class="about-text" data-purpose="{{ $purpose[0] }}">
                        @forelse(preg_split('/\R\s*\R/u', trim($purpose[4]), -1, PREG_SPLIT_NO_EMPTY) as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @empty
                            <p>{{ $purpose[1] }} have not been published yet.</p>
                        @endforelse
                    </div>
                </article>
            @endforeach
        </section>
        <section class="about-journey">
            <div class="about-journey-left">
                @include('frontend.institute.partials.about-education-visual', ['variant' => 'book'])
                <h2>Together Towards a Brighter Tomorrow</h2>
            </div>
            <div class="about-journey-right">
                <h3>Be a Part of Our Journey</h3>
                <div class="about-actions">
                    <a class="about-action" href="{{ route('supportPage') }}">Contact the Institute <span aria-hidden="true">→</span></a>
                    <a class="about-action about-action--outline" href="{{ route('imagePage') }}">Explore Gallery</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
