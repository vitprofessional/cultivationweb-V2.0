@extends('frontend.cultivation-v2.page')
@section('fronttitle', 'Home')
@section('portal-layout', 'modern-homepage')
@push('styles')
<link rel="stylesheet" href="{{ app(\App\Services\PublicAssetUrl::class)->url('cultivation/assets/css/homepage-modern.css') }}">
<link rel="stylesheet" href="{{ app(\App\Services\PublicAssetUrl::class)->url('cultivation/assets/css/homepage-modern-notices.css') }}">
@endpush
@section('sliderninfo')
<main id="modern-homepage">
    <section class="mh-hero" aria-label="Institution highlights">
        @forelse($slides as $slide)
        <article class="mh-slide" @if(!$loop->first) hidden @endif>
            <img class="mh-hero-image" src="{{ $slide['image'] }}" alt="" @if($loop->first) fetchpriority="high" @endif>
            <div class="mh-container mh-hero-copy"><span class="mh-eyebrow">{{ $institutionName }}</span><h1>{{ $slide['title'] ?: $institutionName }}</h1>@if($slide['description'])<p>{{ $slide['description'] }}</p>@endif<a class="mh-button" href="{{ route('institutePage') }}">Explore Our Institution <span aria-hidden="true">→</span></a></div>
        </article>
        @empty
        <article class="mh-slide mh-neutral-hero"><div class="mh-container mh-hero-copy"><span class="mh-eyebrow">Official Institution Website</span><h1>{{ $institutionName ?: 'Our Institution' }}</h1><a class="mh-button" href="{{ route('institutePage') }}">Explore Our Institution →</a></div></article>
        @endforelse
        @if($slides->count() > 1)<div class="mh-slider-controls"><button type="button" data-slide-direction="-1" aria-label="Previous slide">‹</button><button type="button" data-slide-direction="1" aria-label="Next slide">›</button></div>@endif
        @if($slides->count() > 1)<div class="mh-slider-dots">@foreach($slides as $slide)<button type="button" data-slide-index="{{ $loop->index }}" aria-label="Show slide {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>@endforeach</div>@endif
    </section>
    <nav class="mh-container mh-quick mh-grid-four" aria-label="Quick actions">
        @foreach($quickActions as $link)<a class="mh-link-card mh-tone-{{ $loop->index }}" href="{{ $link['url'] }}"><span class="mh-icon"><i class="fa fa-{{ $link['icon'] }}" aria-hidden="true"></i></span><span><strong>{{ $link['title'] }}</strong><small>{{ $link['text'] }}</small></span><span aria-hidden="true">›</span></a>@endforeach
    </nav>
    @if($aboutImage || $aboutText || $aboutTitle)
    <section class="mh-container mh-about mh-section">
        @if($aboutImage)<img class="mh-about-image" src="{{ $aboutImage }}" alt="{{ $institutionName }} campus" loading="lazy" onerror="this.hidden=true">@endif
        <div><span class="mh-eyebrow">About Our Institution</span><h2>{{ $institutionName ?: $aboutTitle }}</h2>@if($aboutTitle && $aboutTitle !== $institutionName)<h3>{{ $aboutTitle }}</h3>@endif<div class="mh-about-text">{{ strip_tags($aboutText) }}</div><a class="mh-button" href="{{ route('institutePage') }}">Learn More About Us →</a></div>
        <aside class="mh-values"><p><i class="fa fa-graduation-cap" aria-hidden="true"></i> Quality Education</p><p><i class="fa fa-book" aria-hidden="true"></i> Learning &amp; Character</p><p><i class="fa fa-users" aria-hidden="true"></i> Our Learning Community</p><p><i class="fa fa-university" aria-hidden="true"></i> A Brighter Future</p></aside>
    </section>
    @endif
    @if($metrics->isNotEmpty())
    <section class="mh-band mh-section"><div class="mh-container"><header class="mh-heading"><span class="mh-eyebrow">At a Glance</span><h2>Institutional Key Statistics</h2></header><div class="mh-stats">@foreach($metrics as $metric)<div class="mh-stat"><i class="fa {{ $metric['icon'] }}" aria-hidden="true"></i><strong>{{ $metric['value'] }}</strong><small>{{ $metric['label'] }}</small></div>@endforeach</div></div></section>
    @endif
    @if($leaders->isNotEmpty())
    <section class="mh-container mh-section"><header class="mh-heading"><span class="mh-eyebrow">Institutional Leadership</span><h2>Guiding Our Institution</h2></header><div class="mh-leaders">@foreach($leaders as $leader)<article class="mh-leader"><x-people-portrait :src="$leader['photo']" :alt="$leader['name']"/><div><span class="mh-eyebrow">{{ $leader['role'] }}</span><h3>{{ $leader['name'] }}</h3><a class="mh-button" href="{{ $leader['url'] }}">Read Message →</a></div></article>@endforeach</div></section>
    @endif
    <section class="mh-container latest-notice-modern" aria-label="Latest Notice">
        <div class="notice-head"><h2>Latest Notice</h2><a class="all-notice-btn" href="{{ route('allNotices') }}">All Notices →</a></div>
        <div class="notice-shell">@forelse($noticeRows as $notice)<div class="notice-item"><div class="date-box"><div class="day">{{ $notice['day'] }}</div><div class="mon">{{ $notice['month'] }}</div></div><h3 class="notice-title">{{ $notice['title'] }}</h3><div class="notice-actions">@if($notice['file'])<span class="notice-file-pill">File</span>@endif<a class="notice-btn" href="{{ $notice['url'] }}" data-public-notice-open="{{ $notice['id'] }}" aria-haspopup="dialog" aria-controls="public-notice-dialog"><i class="fa fa-eye" aria-hidden="true"></i> View</a>@if($notice['file'])<a class="notice-btn" href="{{ $notice['file'] }}" download="{{ $notice['filename'] }}">File</a>@endif</div></div>@empty<p class="notice-empty-state">No notices are currently published.</p>@endforelse</div>
    </section>
    <section class="mh-container mh-section"><header class="mh-heading"><span class="mh-eyebrow">Useful Information</span><h2>Information for Students, Parents and Visitors</h2></header><div class="mh-grid-four">@foreach($usefulLinks as $link)<a class="mh-link-card" href="{{ $link['url'] }}"><span class="mh-icon"><i class="fa fa-{{ $link['icon'] }}" aria-hidden="true"></i></span><span><strong>{{ $link['title'] }}</strong><small>{{ $link['text'] }}</small></span><span aria-hidden="true">›</span></a>@endforeach</div></section>
    <section class="mh-band mh-section"><div class="mh-container mh-academic"><div><span class="mh-eyebrow">Academic Services</span><h2>Academic Information</h2><p>Explore our academic resources for students, parents and teachers.</p><a class="mh-button" href="{{ route('newSyllabus') }}">View Academic Resources →</a></div><div class="mh-grid-four">@foreach($academicLinks as $link)<a class="mh-resource" href="{{ $link['url'] }}"><i class="fa fa-{{ $link['icon'] }}" aria-hidden="true"></i><strong>{{ $link['title'] }}</strong><small>{{ $link['text'] }}</small></a>@endforeach</div></div></section>
    @if($faculty->isNotEmpty())
    <section class="mh-container mh-section mh-faculty"><header><span class="mh-eyebrow">Our Teachers</span><h2>Meet Our Teachers</h2><p>Meet the educators in our institution.</p><a class="mh-button" href="{{ route('teacherPage') }}">View All Teachers →</a></header><div class="mh-grid-four">@foreach($faculty as $teacher)<a class="mh-teacher" href="{{ $teacher['url'] }}"><x-people-portrait :src="$teacher['photo']" :alt="$teacher['name']"/><strong>{{ $teacher['name'] }}</strong><small>{{ $teacher['role'] }}</small></a>@endforeach</div></section>
    @endif
    @if($photos->isNotEmpty())
    <section class="mh-band mh-section"><div class="mh-container"><header class="mh-section-head"><div><span class="mh-eyebrow">Photo Gallery</span><h2>Moments from Our Institution</h2></div><a class="mh-button" href="{{ route('imagePage') }}">View All Photos →</a></header><div class="mh-gallery">@foreach($photos as $photo)<a href="{{ $photo['image'] }}" aria-label="Open {{ $photo['title'] ?: 'gallery photograph' }}" data-gallery-title="{{ $photo['title'] }}"><img src="{{ $photo['image'] }}" alt="{{ $photo['title'] ?: 'Institution gallery photograph' }}" loading="lazy" onerror="this.closest('a').hidden=true">@if($photo['title'])<strong>{{ $photo['title'] }}</strong>@endif<span class="mh-gallery-open" aria-hidden="true"><i class="fa fa-search-plus"></i></span></a>@endforeach</div></div></section>
    @endif
    <section class="mh-admission"><div class="mh-container"><div><span class="mh-eyebrow">Admission Information</span><h2>Join Our Learning Community</h2><p>Contact the institution for admission information.</p></div><a class="mh-button" href="{{ route('supportPage') }}">Contact for Admission →</a></div></section>
</main>
@endsection
@push('scripts')
@include('frontend.notice._viewer', ['viewerNotices' => $noticeBoard])
<script>
(() => {
    const root = document.getElementById('modern-homepage');
    const slides = Array.from(root.querySelectorAll('.mh-slide'));
    let current = 0;
    const showSlide = index => {
        slides[current].hidden = true;
        current = (index + slides.length) % slides.length;
        slides[current].hidden = false;
        root.querySelectorAll('[data-slide-index]').forEach(button => button.setAttribute('aria-current', String(Number(button.dataset.slideIndex) === current)));
    };
    root.querySelectorAll('[data-slide-direction]').forEach(button => button.addEventListener('click', () => showSlide(current + Number(button.dataset.slideDirection))));
    root.querySelectorAll('[data-slide-index]').forEach(button => button.addEventListener('click', () => showSlide(Number(button.dataset.slideIndex))));
    root.querySelectorAll('.mh-gallery').forEach(gallery => {
        const refresh = () => { gallery.dataset.visibleCount = gallery.querySelectorAll('a:not([hidden])').length; };
        gallery.querySelectorAll('img').forEach(image => image.addEventListener('error', () => { image.closest('a').hidden = true; refresh(); }));
        refresh();
        // Reuse the bundled image viewer; only successfully decoded real media enter it.
        gallery.addEventListener('click', async event => {
            const trigger = event.target.closest('a');
            if (!trigger || !gallery.contains(trigger)) return;
            event.preventDefault();
            const candidates = Array.from(gallery.querySelectorAll('a:not([hidden])'));
            const valid = (await Promise.all(candidates.map(async link => {
                const image = link.querySelector('img');
                try { await image.decode(); } catch { link.hidden = true; return null; }
                return image.naturalWidth > 0 ? link : null;
            }))).filter(Boolean);
            refresh();
            const index = valid.indexOf(trigger);
            if (index < 0) return;
            if (window.jQuery?.magnificPopup) {
                window.jQuery.magnificPopup.open({
                    items: valid.map(link => ({ src: link.href, title: link.dataset.galleryTitle })),
                    type: 'image', gallery: { enabled: valid.length > 1 },
                    closeOnContentClick: false, closeBtnInside: true,
                    mainClass: 'mh-gallery-viewer', autoFocusLast: true,
                    callbacks: { close: () => trigger.focus({ preventScroll: true }) }
                }, index);
            } else {
                window.location.assign(trigger.href);
            }
        });
    });
})();
</script>
@endpush
