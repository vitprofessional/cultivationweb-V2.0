@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Photo Gallery
@endsection
@section('frontcontent')
@php
    $galleryItems = collect($Datakey ?? []);
    $usingFallbackPhotos = false;

    if ($galleryItems->isEmpty()) {
        $galleryItems = collect([
            (object) [
                'id' => 'sample-photo-1',
                'title' => 'Annual Science Fair',
                'description' => 'Students presented applied science projects and live demonstrations in front of visitors and guardians.',
                'avatar' => 'public/img/campus.jpeg',
                'created_at' => now()->subMonths(14),
            ],
            (object) [
                'id' => 'sample-photo-2',
                'title' => 'Main Building Morning',
                'description' => 'A calm early-morning look at the academic block before classes begin.',
                'avatar' => 'public/img/mainbuilding.jpg',
                'created_at' => now()->subMonths(11),
            ],
            (object) [
                'id' => 'sample-photo-3',
                'title' => 'Career Counseling Session',
                'description' => 'Faculty mentors discussed higher-study pathways and career planning with final-year students.',
                'avatar' => 'public/img/office.jpg',
                'created_at' => now()->subMonths(8),
            ],
            (object) [
                'id' => 'sample-photo-4',
                'title' => 'Principal Desk Briefing',
                'description' => 'Planning session focused on student support activities and semester targets.',
                'avatar' => 'public/img/principalroom.jpg',
                'created_at' => now()->subMonths(7),
            ],
            (object) [
                'id' => 'sample-photo-5',
                'title' => 'Hostel Community Evening',
                'description' => 'Students gathered for supervised group study and community engagement activities.',
                'avatar' => 'public/img/hostel.jpg',
                'created_at' => now()->subMonths(5),
            ],
            (object) [
                'id' => 'sample-photo-6',
                'title' => 'Cultural Auditorium Program',
                'description' => 'A stage performance highlighting music, recitation, and teamwork across departments.',
                'avatar' => 'public/img/auditoriam.jpg',
                'created_at' => now()->subMonths(3),
            ],
        ]);
        $usingFallbackPhotos = true;
    }

    $pickFirst = static function ($item, array $keys, $fallback = null) {
        foreach ($keys as $key) {
            if (isset($item->{$key}) && $item->{$key} !== null && trim((string) $item->{$key}) !== '') {
                return $item->{$key};
            }
        }

        return $fallback;
    };

    $resolveUploadUrl = static function ($fileName, array $folders) {
        $fileName = trim((string) $fileName);

        if ($fileName === '') {
            return null;
        }

        if (preg_match('/^(https?:)?\/\//i', $fileName)) {
            return $fileName;
        }

        $cleanName = ltrim(str_replace('\\', '/', $fileName), '/');

        if (str_starts_with($cleanName, 'public/')) {
            return url($cleanName);
        }

        if (str_starts_with($cleanName, 'upload/')) {
            return url('public/' . $cleanName);
        }

        foreach ($folders as $folder) {
            $relativePath = trim($folder, '/') . '/' . basename($cleanName);
            $absolutePath = public_path($relativePath);

            if (file_exists($absolutePath)) {
                return url('public/' . $relativePath);
            }
        }

        return url('public/' . $folders[0] . '/' . basename($cleanName));
    };

    $photoCards = $galleryItems
        ->map(function ($item, $index) use ($pickFirst, $resolveUploadUrl) {
            $title = trim((string) $pickFirst($item, ['title', 'headline', 'headLine', 'name'], 'Campus Moment'));
            $description = trim((string) $pickFirst($item, ['description', 'details', 'detail', 'caption'], 'A preserved campus moment from student life, achievements, and institutional events.'));
            $imageUrl = $resolveUploadUrl(
                $pickFirst($item, ['avatar', 'image', 'photo', 'thumbnail']),
                ['upload/image/PhotoGallery', 'upload/image/photogallery']
            );

            return [
                'id' => $item->id ?? ('photo-' . $index),
                'title' => $title,
                'description' => $description,
                'image' => $imageUrl ?: asset('public/img/campus.jpeg'),
                'date' => optional($item->created_at)->format('d M Y') ?: 'Campus Archive',
                'year' => optional($item->created_at)->format('Y') ?: 'Archive',
            ];
        })
        ->filter(fn ($item) => !empty($item['image']))
        ->values();

    $featuredPhotos = $photoCards->take(3)->values();
    $archivePhotos = $photoCards->slice(3)->values();
    $heroPhoto = $featuredPhotos->first() ?? $photoCards->first();
    $photoCount = $photoCards->count();
    $yearCount = $photoCards->pluck('year')->filter(fn ($value) => is_numeric($value))->unique()->count();
    $showcaseCount = $featuredPhotos->count() ?: min($photoCount, 3);
@endphp

@include('frontend.gallery._shared_theme')

<style>
    .gallery-showcase-page {
        --gallery-ink: #17304f;
        --gallery-subtle: #5d7188;
        --gallery-line: rgba(26, 66, 107, 0.12);
        --gallery-hero: linear-gradient(135deg, #f9f3e8 0%, #e8f4ff 52%, #f4fbf5 100%);
        --gallery-card: rgba(255, 255, 255, 0.82);
        --gallery-accent: #c56d2d;
        --gallery-accent-soft: #fff0df;
        --gallery-shadow: 0 28px 70px rgba(16, 44, 83, 0.14);
        position: relative;
        padding: 34px 0 44px;
        color: var(--gallery-ink);
    }

    .gallery-showcase-page::before,
    .gallery-showcase-page::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
        filter: blur(4px);
    }

    .gallery-showcase-page::before {
        width: 280px;
        height: 280px;
        top: 16px;
        left: -90px;
        background: radial-gradient(circle, rgba(197, 109, 45, 0.16), rgba(197, 109, 45, 0));
    }

    .gallery-showcase-page::after {
        width: 320px;
        height: 320px;
        right: -120px;
        top: 240px;
        background: radial-gradient(circle, rgba(38, 128, 173, 0.18), rgba(38, 128, 173, 0));
    }

    .gallery-shell {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 28px;
    }

    .gallery-hero {
        background: var(--gallery-hero);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 28px;
        box-shadow: var(--gallery-shadow);
        overflow: hidden;
    }

    .gallery-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(320px, .9fr);
        gap: 28px;
        padding: 30px;
        align-items: stretch;
    }

    .gallery-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(23, 48, 79, 0.1);
        color: #8f4e1f;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.22em;
        text-transform: uppercase;
    }

    .gallery-hero-copy h1 {
        margin: 18px 0 14px;
        font-size: clamp(2.2rem, 4vw, 4rem);
        line-height: 0.98;
        font-weight: 800;
        letter-spacing: -0.04em;
        max-width: 10ch;
        color: #16365a;
    }

    .gallery-hero-copy p {
        margin: 0 0 22px;
        max-width: 56ch;
        font-size: 1rem;
        line-height: 1.78;
        color: var(--gallery-subtle);
    }

    .gallery-stat-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .gallery-stat {
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.74);
        border: 1px solid rgba(23, 48, 79, 0.08);
        backdrop-filter: blur(10px);
    }

    .gallery-stat strong {
        display: block;
        margin-bottom: 6px;
        font-size: 1.55rem;
        line-height: 1;
        font-weight: 800;
        color: #17395e;
    }

    .gallery-stat span {
        display: block;
        font-size: 0.82rem;
        line-height: 1.5;
        color: var(--gallery-subtle);
    }

    .gallery-hero-notes {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .gallery-hero-notes span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.68);
        color: #20496d;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .gallery-hero-frame {
        position: relative;
        min-height: 100%;
    }

    .gallery-hero-card {
        position: relative;
        height: 100%;
        min-height: 460px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 38, 66, 0.22);
        background: #d7e4ee;
    }

    .gallery-hero-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.01);
    }

    .gallery-hero-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(11, 25, 45, 0.02), rgba(11, 25, 45, 0.78));
    }

    .gallery-hero-badge,
    .gallery-hero-caption {
        position: absolute;
        z-index: 1;
        left: 22px;
        right: 22px;
    }

    .gallery-hero-badge {
        top: 22px;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }

    .gallery-hero-badge span,
    .gallery-hero-badge strong {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 999px;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .gallery-hero-caption {
        bottom: 22px;
        color: #fff;
    }

    .gallery-hero-caption h2 {
        margin: 0 0 10px;
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        line-height: 1.04;
        font-weight: 800;
        max-width: 13ch;
    }

    .gallery-hero-caption p {
        margin: 0;
        max-width: 34ch;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.65;
        font-size: 0.95rem;
    }

    .gallery-section {
        display: grid;
        gap: 18px;
    }

    .gallery-section-head {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 18px;
    }

    .gallery-section-head h3 {
        margin: 0 0 6px;
        font-size: 1.75rem;
        line-height: 1.1;
        font-weight: 800;
        color: #17395e;
    }

    .gallery-section-head p {
        margin: 0;
        max-width: 58ch;
        line-height: 1.7;
        color: var(--gallery-subtle);
    }

    .gallery-section-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: var(--gallery-accent-soft);
        color: #9f5622;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .gallery-featured-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .gallery-feature-card,
    .gallery-archive-card {
        border: 0;
        padding: 0;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 18px 44px rgba(17, 41, 72, 0.1);
        transition: transform 0.24s ease, box-shadow 0.24s ease;
    }

    .gallery-feature-card:hover,
    .gallery-archive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 24px 50px rgba(17, 41, 72, 0.16);
    }

    .gallery-feature-media,
    .gallery-archive-media {
        position: relative;
        overflow: hidden;
    }

    .gallery-feature-media {
        aspect-ratio: 4 / 4.4;
    }

    .gallery-archive-media {
        aspect-ratio: 16 / 11;
    }

    .gallery-feature-media img,
    .gallery-archive-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .gallery-feature-card:hover img,
    .gallery-archive-card:hover img {
        transform: scale(1.06);
    }

    .gallery-feature-media::after,
    .gallery-archive-media::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(11, 25, 45, 0.08), rgba(11, 25, 45, 0.68));
    }

    .gallery-feature-index,
    .gallery-archive-meta {
        position: absolute;
        z-index: 1;
        left: 16px;
        right: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
    }

    .gallery-feature-index {
        top: 16px;
    }

    .gallery-archive-meta {
        top: 14px;
    }

    .gallery-feature-index span,
    .gallery-feature-index strong,
    .gallery-archive-meta span,
    .gallery-archive-meta strong {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .gallery-feature-body,
    .gallery-archive-body {
        padding: 20px 20px 22px;
    }

    .gallery-feature-body h4,
    .gallery-archive-body h4 {
        margin: 0 0 10px;
        color: #17395e;
        font-weight: 800;
        line-height: 1.2;
    }

    .gallery-feature-body h4 {
        font-size: 1.2rem;
    }

    .gallery-archive-body h4 {
        font-size: 1.05rem;
    }

    .gallery-feature-body p,
    .gallery-archive-body p {
        margin: 0;
        color: var(--gallery-subtle);
        line-height: 1.7;
        font-size: 0.95rem;
    }

    .gallery-archive-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .gallery-archive-body-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--gallery-line);
        font-size: 0.84rem;
        color: #496079;
        font-weight: 700;
    }

    .gallery-archive-body-footer span:last-child,
    .gallery-feature-link {
        color: var(--gallery-accent);
    }

    .gallery-empty {
        padding: 28px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(250, 244, 236, 0.9), rgba(235, 245, 252, 0.95));
        border: 1px dashed rgba(23, 48, 79, 0.16);
        text-align: center;
        color: #5f7389;
        line-height: 1.8;
    }

    .gallery-modal .modal-dialog {
        max-width: 1080px;
    }

    .gallery-modal .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 28px;
        box-shadow: 0 28px 70px rgba(8, 20, 36, 0.38);
        background: #eef4f8;
    }

    .gallery-modal .modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid rgba(18, 48, 80, 0.08);
        background: linear-gradient(135deg, #17395e 0%, #225f83 100%);
        color: #fff;
    }

    .gallery-modal .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
    }

    .gallery-modal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.92;
    }

    .gallery-modal .modal-body {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, .72fr);
        gap: 0;
        padding: 0;
    }

    .gallery-modal-stage {
        min-height: 440px;
        background: #dce7ef;
    }

    .gallery-modal-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-modal-copy {
        padding: 28px 24px;
        background: #f8fbfd;
        display: grid;
        gap: 18px;
        align-content: start;
    }

    .gallery-modal-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .gallery-modal-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid rgba(23, 48, 79, 0.08);
        color: #486079;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .gallery-modal-copy h4 {
        margin: 0;
        font-size: 1.55rem;
        line-height: 1.15;
        font-weight: 800;
        color: #17395e;
    }

    .gallery-modal-copy p {
        margin: 0;
        color: var(--gallery-subtle);
        line-height: 1.82;
        font-size: 0.98rem;
    }

    .gallery-modal-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .gallery-modal-actions .btn {
        border-radius: 999px;
        padding: 11px 18px;
        font-weight: 700;
    }

    @media (max-width: 1199.98px) {
        .gallery-hero-grid,
        .gallery-modal .modal-body {
            grid-template-columns: 1fr;
        }

        .gallery-featured-grid,
        .gallery-archive-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gallery-hero-card {
            min-height: 380px;
        }
    }

    @media (max-width: 767.98px) {
        .gallery-showcase-page {
            padding: 20px 0 30px;
        }

        .gallery-hero-grid {
            padding: 18px;
            gap: 20px;
        }

        .gallery-section-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .gallery-stat-row,
        .gallery-featured-grid,
        .gallery-archive-grid {
            grid-template-columns: 1fr;
        }

        .gallery-hero-copy h1 {
            max-width: none;
            font-size: 2.2rem;
        }

        .gallery-hero-card {
            min-height: 300px;
        }

        .gallery-modal .modal-dialog {
            margin: 0.8rem;
        }

        .gallery-modal-copy {
            padding: 20px 18px;
        }
    }
</style>

<section class="gallery-showcase-page">
    <div class="container gallery-shell">
        <div class="gallery-hero">
            <div class="gallery-hero-grid">
                <div class="gallery-hero-copy">
                    <span class="gallery-kicker"><i class="fa fa-camera-retro" aria-hidden="true"></i> Visual Archive</span>
                    <h1>Photo stories from campus life.</h1>
                    <p>The gallery now presents the institution more like an editorial archive: stronger hierarchy, cleaner cards, and a more deliberate way to open and review each moment.</p>

                    <div class="gallery-stat-row">
                        <div class="gallery-stat">
                            <strong>{{ $photoCount }}</strong>
                            <span>Published photos in the current archive.</span>
                        </div>
                        <div class="gallery-stat">
                            <strong>{{ $showcaseCount }}</strong>
                            <span>Featured highlights surfaced at the top.</span>
                        </div>
                        <div class="gallery-stat">
                            <strong>{{ $yearCount }}</strong>
                            <span>Distinct years represented by uploaded items.</span>
                        </div>
                    </div>

                    <div class="gallery-hero-notes">
                        <span><i class="fa fa-check-circle" aria-hidden="true"></i> Cleaner presentation for events and achievements</span>
                        <span><i class="fa fa-search-plus" aria-hidden="true"></i> Large-format modal preview with quick download</span>
                    </div>
                </div>

                <div class="gallery-hero-frame">
                    <div class="gallery-hero-card">
                        <img src="{{ $heroPhoto['image'] ?? asset('public/img/campus.jpeg') }}" alt="{{ $heroPhoto['title'] ?? 'Campus photo highlight' }}">
                        <div class="gallery-hero-badge">
                            <span><i class="fa fa-image" aria-hidden="true"></i> Featured Memory</span>
                            <strong>{{ $heroPhoto['date'] ?? 'Campus Archive' }}</strong>
                        </div>
                        <div class="gallery-hero-caption">
                            <h2>{{ $heroPhoto['title'] ?? 'Campus highlight' }}</h2>
                            <p>{{ \Illuminate\Support\Str::limit($heroPhoto['description'] ?? 'A documented moment from campus life.', 130) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($usingFallbackPhotos)
            <div class="media-demo-banner">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <span>Showing professional sample photos because no live gallery records were found yet. Upload items in admin to replace these automatically.</span>
            </div>
        @endif

        @if($photoCards->isNotEmpty())
            <div class="gallery-section">
                <div class="gallery-section-head">
                    <div>
                        <span class="gallery-section-tag media-chip">Featured Selection</span>
                        <h3>Curated highlights</h3>
                        <p>The first layer of the page gives larger visual weight to the strongest items so the gallery feels composed rather than dumped into a plain grid.</p>
                    </div>
                    <div class="gallery-section-tag media-chip">{{ $featuredPhotos->count() }} spotlight items</div>
                </div>

                <div class="gallery-featured-grid">
                    @foreach($featuredPhotos as $index => $photo)
                        <button
                            type="button"
                            class="gallery-feature-card gallery-photo-trigger"
                            data-image="{{ $photo['image'] }}"
                            data-title="{{ e($photo['title']) }}"
                            data-description="{{ e($photo['description']) }}"
                            data-date="{{ e($photo['date']) }}"
                            aria-label="Open {{ $photo['title'] }}">
                            <div class="gallery-feature-media">
                                <img src="{{ $photo['image'] }}" alt="{{ $photo['title'] }}">
                                <div class="gallery-feature-index">
                                    <span>0{{ $index + 1 }}</span>
                                    <strong>{{ $photo['date'] }}</strong>
                                </div>
                            </div>
                            <div class="gallery-feature-body">
                                <h4>{{ $photo['title'] }}</h4>
                                <p>{{ \Illuminate\Support\Str::limit($photo['description'], 118) }}</p>
                                <div class="gallery-archive-body-footer">
                                    <span>Campus documentation</span>
                                    <span class="gallery-feature-link">Open preview</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="gallery-section">
                <div class="gallery-section-head">
                    <div>
                        <span class="gallery-section-tag media-chip">Archive Grid</span>
                        <h3>Full gallery archive</h3>
                        <p>Everything else sits in a cleaner, more compact archive layout, keeping the page easier to scan while still feeling premium.</p>
                    </div>
                    <div class="gallery-section-tag media-chip">{{ $archivePhotos->count() ?: $photoCards->count() }} visible cards</div>
                </div>

                <div class="gallery-archive-grid">
                    @foreach(($archivePhotos->isNotEmpty() ? $archivePhotos : $photoCards) as $photo)
                        <button
                            type="button"
                            class="gallery-archive-card gallery-photo-trigger"
                            data-image="{{ $photo['image'] }}"
                            data-title="{{ e($photo['title']) }}"
                            data-description="{{ e($photo['description']) }}"
                            data-date="{{ e($photo['date']) }}"
                            aria-label="Open {{ $photo['title'] }}">
                            <div class="gallery-archive-media">
                                <img src="{{ $photo['image'] }}" alt="{{ $photo['title'] }}">
                                <div class="gallery-archive-meta">
                                    <span>{{ $photo['date'] }}</span>
                                    <strong>Photo</strong>
                                </div>
                            </div>
                            <div class="gallery-archive-body">
                                <h4>{{ $photo['title'] }}</h4>
                                <p>{{ \Illuminate\Support\Str::limit($photo['description'], 102) }}</p>
                                <div class="gallery-archive-body-footer">
                                    <span>{{ $photo['year'] }}</span>
                                    <span>View details</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="gallery-empty">
                <h3>No photo archive is published yet.</h3>
                <p>The page layout is ready, but there are no uploaded gallery items to display right now.</p>
            </div>
        @endif
    </div>
</section>

<div class="modal fade gallery-modal" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Photo Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="gallery-modal-stage">
                    <img id="galleryModalImage" src="" alt="Gallery preview image">
                </div>
                <div class="gallery-modal-copy">
                    <div class="gallery-modal-meta">
                        <span><i class="fa fa-camera" aria-hidden="true"></i> Photo Gallery</span>
                        <span id="galleryModalDate"><i class="fa fa-calendar" aria-hidden="true"></i> Campus Archive</span>
                    </div>
                    <h4 id="galleryModalTitle">Campus memory</h4>
                    <p id="galleryModalDescription">A larger preview and context note for the selected gallery item.</p>
                    <div class="gallery-modal-actions media-modal-actions">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                        <a id="galleryModalDownload" class="btn btn-warning" href="#" download>
                            <i class="fa fa-download" aria-hidden="true"></i> Download image
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const modalElement = document.getElementById('imageModal');
        const modalImage = document.getElementById('galleryModalImage');
        const modalTitle = document.getElementById('galleryModalTitle');
        const modalDescription = document.getElementById('galleryModalDescription');
        const modalDate = document.getElementById('galleryModalDate');
        const modalDownload = document.getElementById('galleryModalDownload');

        if (!modalElement) {
            return;
        }

        const openModal = function () {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal && typeof bootstrap.Modal.getOrCreateInstance === 'function') {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                    return true;
                }

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    new bootstrap.Modal(modalElement).show();
                    return true;
                }

                if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.modal === 'function') {
                    window.jQuery(modalElement).modal('show');
                    return true;
                }
            } catch (error) {
                return false;
            }

            return false;
        };

        const bindGalleryHandlers = function () {
            document.querySelectorAll('.gallery-photo-trigger').forEach(function (trigger) {
                if (trigger.getAttribute('data-modal-bound') === '1') {
                    return;
                }
                trigger.setAttribute('data-modal-bound', '1');

                trigger.addEventListener('click', function () {
                    const image = trigger.getAttribute('data-image') || '';
                    const title = trigger.getAttribute('data-title') || 'Campus memory';
                    const description = trigger.getAttribute('data-description') || 'A documented campus highlight.';
                    const date = trigger.getAttribute('data-date') || 'Campus Archive';

                    modalImage.src = image;
                    modalImage.alt = title;
                    document.getElementById('imageModalLabel').textContent = title;
                    modalTitle.textContent = title;
                    modalDescription.textContent = description;
                    modalDate.innerHTML = '<i class="fa fa-calendar" aria-hidden="true"></i> ' + date;
                    modalDownload.href = image;

                    let filename = 'gallery-image';
                    try {
                        const parsed = new URL(image, window.location.href);
                        filename = parsed.pathname.split('/').pop() || filename;
                    } catch (error) {
                        filename = 'gallery-image';
                    }

                    modalDownload.setAttribute('download', filename);

                    if (!openModal() && image) {
                        window.open(image, '_blank', 'noopener');
                    }
                });
            });
        };

        const boot = function () {
            bindGalleryHandlers();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }

        window.addEventListener('load', boot);
        setTimeout(boot, 250);
        setTimeout(boot, 900);

        modalElement.addEventListener('hidden.bs.modal', function () {
            modalImage.src = '';
            modalDownload.href = '#';
        });
    })();
</script>

@endsection