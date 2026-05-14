@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Video Gallery
@endsection
@section('frontcontent')
@php
    $videoItems = collect($Datakey ?? []);
    $usingFallbackVideos = false;

    if ($videoItems->isEmpty()) {
        $videoItems = collect([
            (object) [
                'id' => 'sample-video-1',
                'title' => 'Orientation Day Highlights',
                'description' => 'A short recap of orientation sessions, campus tours, and first-week mentoring activities.',
                'video_url' => 'https://www.youtube.com/watch?v=atMUy_bPoQI',
                'avatar' => 'public/img/mainbuilding.jpg',
                'created_at' => now()->subMonths(10),
            ],
            (object) [
                'id' => 'sample-video-2',
                'title' => 'Annual Cultural Performance',
                'description' => 'Selected stage moments from the annual cultural program and inter-class performances.',
                'video_url' => 'https://youtu.be/sU3FkzUKHXU',
                'avatar' => 'public/img/auditoriam.jpg',
                'created_at' => now()->subMonths(8),
            ],
            (object) [
                'id' => 'sample-video-3',
                'title' => 'Robotics Club Demonstration',
                'description' => 'Student teams presenting autonomous projects during the campus innovation showcase.',
                'video_url' => 'https://vimeo.com/99585787',
                'avatar' => 'public/img/campus.jpeg',
                'created_at' => now()->subMonths(6),
            ],
            (object) [
                'id' => 'sample-video-4',
                'title' => 'Sports Week Recap',
                'description' => 'A compiled recap of matches, prize distribution, and student participation moments.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'avatar' => 'public/img/hostel.jpg',
                'created_at' => now()->subMonths(4),
            ],
        ]);
        $usingFallbackVideos = true;
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

    $normalizeVideoSource = static function ($rawSource) {
        $rawSource = trim((string) $rawSource);

        if ($rawSource === '') {
            return [
                'type' => 'unavailable',
                'source' => null,
                'embed' => null,
                'platform' => 'Archive',
                'thumbnail' => null,
            ];
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $rawSource, $matches)) {
            $rawSource = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }

        if (preg_match('~(?:youtube\.com/watch\?v=|youtube\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{6,})~i', $rawSource, $matches)) {
            $videoId = $matches[1];

            return [
                'type' => 'embed',
                'source' => $rawSource,
                'embed' => 'https://www.youtube.com/embed/' . $videoId . '?rel=0',
                'platform' => 'YouTube',
                'thumbnail' => 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg',
            ];
        }

        if (preg_match('~(?:vimeo\.com/(?:video/)?)(\d+)~i', $rawSource, $matches)) {
            $videoId = $matches[1];

            return [
                'type' => 'embed',
                'source' => $rawSource,
                'embed' => 'https://player.vimeo.com/video/' . $videoId,
                'platform' => 'Vimeo',
                'thumbnail' => null,
            ];
        }

        if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $rawSource)) {
            return [
                'type' => 'file',
                'source' => $rawSource,
                'embed' => $rawSource,
                'platform' => strtoupper(pathinfo(parse_url($rawSource, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'VIDEO'),
                'thumbnail' => null,
            ];
        }

        if (preg_match('/^(https?:)?\/\//i', $rawSource)) {
            return [
                'type' => 'external',
                'source' => $rawSource,
                'embed' => null,
                'platform' => 'External Link',
                'thumbnail' => null,
            ];
        }

        return [
            'type' => 'unavailable',
            'source' => $rawSource,
            'embed' => null,
            'platform' => 'Archive',
            'thumbnail' => null,
        ];
    };

    $videoCards = $videoItems
        ->map(function ($item, $index) use ($pickFirst, $resolveUploadUrl, $normalizeVideoSource) {
            $title = trim((string) $pickFirst($item, ['title', 'headline', 'headLine', 'name'], 'Campus Video Story'));
            $description = trim((string) $pickFirst($item, ['description', 'details', 'detail', 'caption'], 'A video highlight collected from campus activities and institutional events.'));
            $rawSource = $pickFirst($item, ['video_url', 'videoUrl', 'youtube_url', 'youtube', 'embed_url', 'embed', 'link', 'url', 'video', 'iframe', 'avatar']);
            $media = $normalizeVideoSource($rawSource);

            $thumbSource = $pickFirst($item, ['thumbnail', 'thumb', 'cover', 'cover_image', 'image', 'avatar']);
            $thumbnail = $resolveUploadUrl(
                $thumbSource,
                ['upload/image/videoGallery', 'upload/image/VideoGallery', 'upload/image/photogallery', 'upload/image/PhotoGallery']
            );

            if (!$thumbnail && !empty($media['thumbnail'])) {
                $thumbnail = $media['thumbnail'];
            }

            return [
                'id' => $item->id ?? ('video-' . $index),
                'title' => $title,
                'description' => $description,
                'date' => optional($item->created_at)->format('d M Y') ?: 'Media Archive',
                'year' => optional($item->created_at)->format('Y') ?: 'Archive',
                'thumbnail' => $thumbnail ?: asset('public/img/mainbuilding.jpg'),
                'type' => $media['type'],
                'source' => $media['source'],
                'embed' => $media['embed'],
                'platform' => $media['platform'],
            ];
        })
        ->filter(fn ($item) => $item['type'] !== 'unavailable' || !empty($item['thumbnail']))
        ->values();

    $featuredVideos = $videoCards->take(2)->values();
    $archiveVideos = $videoCards->slice(2)->values();
    $leadVideo = $featuredVideos->first() ?? $videoCards->first();
    $videoCount = $videoCards->count();
    $playableCount = $videoCards->filter(fn ($item) => in_array($item['type'], ['embed', 'file'], true))->count();
    $platformCount = $videoCards->pluck('platform')->filter()->unique()->count();
@endphp

@include('frontend.gallery._shared_theme')

<style>
    .video-gallery-page {
        --video-ink: #102b4d;
        --video-soft: #5a6f87;
        --video-line: rgba(16, 43, 77, 0.12);
        --video-bg: linear-gradient(135deg, #edf7f4 0%, #e8f1fb 46%, #fff3e8 100%);
        --video-accent: #0e7a69;
        --video-accent-soft: #def6f0;
        --video-secondary: #d66e2b;
        --video-shadow: 0 28px 68px rgba(9, 31, 53, 0.14);
        position: relative;
        padding: 34px 0 44px;
        color: var(--video-ink);
    }

    .video-gallery-page::before,
    .video-gallery-page::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
        filter: blur(4px);
    }

    .video-gallery-page::before {
        width: 300px;
        height: 300px;
        top: 30px;
        right: -120px;
        background: radial-gradient(circle, rgba(14, 122, 105, 0.16), rgba(14, 122, 105, 0));
    }

    .video-gallery-page::after {
        width: 260px;
        height: 260px;
        left: -100px;
        top: 290px;
        background: radial-gradient(circle, rgba(214, 110, 43, 0.16), rgba(214, 110, 43, 0));
    }

    .video-shell {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 28px;
    }

    .video-hero {
        background: var(--video-bg);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.75);
        box-shadow: var(--video-shadow);
        overflow: hidden;
    }

    .video-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(340px, .95fr);
        gap: 28px;
        padding: 30px;
        align-items: stretch;
    }

    .video-kicker,
    .video-section-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .video-kicker {
        background: rgba(255, 255, 255, 0.76);
        border: 1px solid rgba(16, 43, 77, 0.08);
        color: #0d725f;
    }

    .video-copy h1 {
        margin: 18px 0 14px;
        font-size: clamp(2.2rem, 4vw, 4rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
        font-weight: 800;
        max-width: 10ch;
    }

    .video-copy p {
        margin: 0 0 22px;
        max-width: 56ch;
        color: var(--video-soft);
        line-height: 1.82;
    }

    .video-stat-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .video-stat {
        padding: 16px 18px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.74);
        border: 1px solid rgba(16, 43, 77, 0.08);
        backdrop-filter: blur(10px);
    }

    .video-stat strong {
        display: block;
        margin-bottom: 6px;
        font-size: 1.55rem;
        line-height: 1;
        font-weight: 800;
        color: #12355d;
    }

    .video-stat span {
        display: block;
        color: var(--video-soft);
        font-size: 0.82rem;
        line-height: 1.55;
    }

    .video-note-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .video-note-row span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.68);
        color: #23506f;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .video-hero-stage {
        position: relative;
        min-height: 100%;
    }

    .video-hero-card {
        position: relative;
        min-height: 460px;
        height: 100%;
        border-radius: 26px;
        overflow: hidden;
        background: #dce7ee;
        box-shadow: 0 26px 60px rgba(14, 31, 48, 0.2);
    }

    .video-hero-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-hero-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 22, 39, 0.06), rgba(8, 22, 39, 0.82));
    }

    .video-hero-top,
    .video-hero-bottom {
        position: absolute;
        z-index: 1;
        left: 22px;
        right: 22px;
        color: #fff;
    }

    .video-hero-top {
        top: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .video-hero-top span,
    .video-hero-top strong,
    .video-card-badges span,
    .video-card-badges strong {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .video-play-orb {
        position: absolute;
        z-index: 1;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 82px;
        height: 82px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        color: #14375f;
        box-shadow: 0 16px 32px rgba(9, 25, 42, 0.25);
        font-size: 1.35rem;
    }

    .video-hero-bottom {
        bottom: 22px;
    }

    .video-hero-bottom h2 {
        margin: 0 0 10px;
        font-size: clamp(1.55rem, 3vw, 2.3rem);
        line-height: 1.06;
        font-weight: 800;
        max-width: 13ch;
    }

    .video-hero-bottom p {
        margin: 0;
        max-width: 34ch;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.68;
        font-size: 0.95rem;
    }

    .video-section {
        display: grid;
        gap: 18px;
    }

    .video-section-head {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 18px;
    }

    .video-section-head h3 {
        margin: 0 0 6px;
        font-size: 1.75rem;
        line-height: 1.1;
        font-weight: 800;
        color: #17395e;
    }

    .video-section-head p {
        margin: 0;
        max-width: 58ch;
        line-height: 1.7;
        color: var(--video-soft);
    }

    .video-section-tag {
        background: var(--video-accent-soft);
        color: #0c7461;
        white-space: nowrap;
    }

    .video-feature-grid,
    .video-archive-grid {
        display: grid;
        gap: 18px;
    }

    .video-feature-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .video-archive-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .video-card {
        border: 0;
        width: 100%;
        padding: 0;
        text-align: left;
        cursor: pointer;
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 18px 44px rgba(15, 38, 62, 0.1);
        transition: transform 0.24s ease, box-shadow 0.24s ease;
    }

    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 24px 52px rgba(15, 38, 62, 0.16);
    }

    .video-card-media {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: linear-gradient(135deg, #dce8ef, #bfd4e1);
    }

    .video-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .video-card:hover .video-card-media img {
        transform: scale(1.06);
    }

    .video-card-media::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(10, 24, 44, 0.08), rgba(10, 24, 44, 0.72));
    }

    .video-card-badges,
    .video-card-footerline {
        position: absolute;
        z-index: 1;
        left: 16px;
        right: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        color: #fff;
    }

    .video-card-badges {
        top: 16px;
    }

    .video-card-footerline {
        bottom: 16px;
    }

    .video-card-play {
        position: absolute;
        z-index: 1;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 62px;
        height: 62px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        color: #12345c;
        font-size: 1rem;
        box-shadow: 0 14px 28px rgba(9, 24, 41, 0.25);
    }

    .video-card-body {
        padding: 20px 20px 22px;
    }

    .video-card-body h4 {
        margin: 0 0 10px;
        color: #17395e;
        font-size: 1.12rem;
        line-height: 1.2;
        font-weight: 800;
    }

    .video-card-body p {
        margin: 0;
        color: var(--video-soft);
        font-size: 0.95rem;
        line-height: 1.72;
    }

    .video-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--video-line);
        color: #4a6079;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .video-card-meta span:last-child {
        color: var(--video-secondary);
    }

    .video-empty {
        padding: 28px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(229, 246, 241, 0.92), rgba(239, 244, 252, 0.95));
        border: 1px dashed rgba(16, 43, 77, 0.16);
        text-align: center;
        color: #5f7389;
        line-height: 1.8;
    }

    .video-modal .modal-dialog {
        max-width: 1120px;
    }

    .video-modal .modal-content {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 28px 70px rgba(8, 20, 36, 0.42);
        background: #eef4f8;
    }

    .video-modal .modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid rgba(18, 48, 80, 0.08);
        background: linear-gradient(135deg, #103457 0%, #0e7a69 100%);
        color: #fff;
    }

    .video-modal .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
    }

    .video-modal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.92;
    }

    .video-modal .modal-body {
        padding: 0;
        display: grid;
        grid-template-columns: minmax(0, 1.24fr) minmax(290px, .76fr);
    }

    .video-modal-stage {
        min-height: 460px;
        background: #d7e4eb;
        position: relative;
    }

    .video-modal-stage iframe,
    .video-modal-stage video {
        width: 100%;
        height: 100%;
        border: 0;
        display: none;
        background: #000;
    }

    .video-modal-fallback {
        display: none;
        height: 100%;
        align-items: center;
        justify-content: center;
        padding: 32px;
        text-align: center;
        color: #325170;
        background: linear-gradient(135deg, #edf6f3, #eef3f9);
    }

    .video-modal-fallback-inner {
        max-width: 360px;
        display: grid;
        gap: 14px;
    }

    .video-modal-fallback-inner a {
        justify-self: center;
        border-radius: 999px;
        padding: 11px 18px;
        font-weight: 700;
    }

    .video-modal-copy {
        padding: 28px 24px;
        background: #f8fbfd;
        display: grid;
        gap: 18px;
        align-content: start;
    }

    .video-modal-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .video-modal-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid rgba(16, 43, 77, 0.08);
        color: #486079;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .video-modal-copy h4 {
        margin: 0;
        font-size: 1.55rem;
        line-height: 1.15;
        font-weight: 800;
        color: #17395e;
    }

    .video-modal-copy p {
        margin: 0;
        color: var(--video-soft);
        line-height: 1.82;
        font-size: 0.98rem;
    }

    .video-modal-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .video-modal-actions .btn {
        border-radius: 999px;
        padding: 11px 18px;
        font-weight: 700;
    }

    @media (max-width: 1199.98px) {
        .video-hero-grid,
        .video-modal .modal-body {
            grid-template-columns: 1fr;
        }

        .video-archive-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .video-hero-card {
            min-height: 380px;
        }
    }

    @media (max-width: 767.98px) {
        .video-gallery-page {
            padding: 20px 0 30px;
        }

        .video-hero-grid {
            padding: 18px;
            gap: 20px;
        }

        .video-copy h1 {
            max-width: none;
            font-size: 2.2rem;
        }

        .video-stat-row,
        .video-feature-grid,
        .video-archive-grid {
            grid-template-columns: 1fr;
        }

        .video-section-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .video-hero-card,
        .video-modal-stage {
            min-height: 280px;
        }

        .video-modal .modal-dialog {
            margin: 0.8rem;
        }

        .video-modal-copy {
            padding: 20px 18px;
        }
    }
</style>

<section class="video-gallery-page">
    <div class="container video-shell">
        <div class="video-hero">
            <div class="video-hero-grid">
                <div class="video-copy">
                    <span class="video-kicker"><i class="fa fa-play-circle" aria-hidden="true"></i> Media Showcase</span>
                    <h1>Video coverage with a stronger presentation.</h1>
                    <p>The video gallery now reads like a real media page instead of an image grid. It highlights featured stories first, surfaces platform details, and opens each item in a proper preview experience.</p>

                    <div class="video-stat-row">
                        <div class="video-stat">
                            <strong>{{ $videoCount }}</strong>
                            <span>Total published video entries visible on the page.</span>
                        </div>
                        <div class="video-stat">
                            <strong>{{ $playableCount }}</strong>
                            <span>Entries that can be previewed directly in the modal.</span>
                        </div>
                        <div class="video-stat">
                            <strong>{{ $platformCount }}</strong>
                            <span>Distinct video source types detected from the data.</span>
                        </div>
                    </div>

                    <div class="video-note-row">
                        <span><i class="fa fa-film" aria-hidden="true"></i> Better hero and archive hierarchy</span>
                        <span><i class="fa fa-external-link" aria-hidden="true"></i> Safe fallback for external-only links</span>
                    </div>
                </div>

                <div class="video-hero-stage">
                    <button
                        type="button"
                        class="video-card video-trigger"
                        data-title="{{ e($leadVideo['title'] ?? 'Campus video highlight') }}"
                        data-description="{{ e($leadVideo['description'] ?? 'Featured campus video story.') }}"
                        data-date="{{ e($leadVideo['date'] ?? 'Media Archive') }}"
                        data-platform="{{ e($leadVideo['platform'] ?? 'Archive') }}"
                        data-type="{{ $leadVideo['type'] ?? 'unavailable' }}"
                        data-source="{{ e($leadVideo['source'] ?? '') }}"
                        data-embed="{{ e($leadVideo['embed'] ?? '') }}"
                        aria-label="Open {{ $leadVideo['title'] ?? 'campus video highlight' }}">
                        <div class="video-hero-card">
                            <img src="{{ $leadVideo['thumbnail'] ?? asset('public/img/mainbuilding.jpg') }}" alt="{{ $leadVideo['title'] ?? 'Campus video highlight' }}">
                            <div class="video-hero-top">
                                <span><i class="fa fa-video-camera" aria-hidden="true"></i> Featured Story</span>
                                <strong>{{ $leadVideo['platform'] ?? 'Archive' }}</strong>
                            </div>
                            <span class="video-play-orb"><i class="fa fa-play" aria-hidden="true"></i></span>
                            <div class="video-hero-bottom">
                                <h2>{{ $leadVideo['title'] ?? 'Campus video highlight' }}</h2>
                                <p>{{ \Illuminate\Support\Str::limit($leadVideo['description'] ?? 'A featured campus video story.', 130) }}</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        @if($usingFallbackVideos)
            <div class="media-demo-banner">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <span>Showing professional sample videos because no live records were found yet. Add video entries in admin to replace these automatically.</span>
            </div>
        @endif

        @if($videoCards->isNotEmpty())
            <div class="video-section">
                <div class="video-section-head">
                    <div>
                        <span class="video-section-tag media-chip">Featured Coverage</span>
                        <h3>Priority stories</h3>
                        <p>The first section gives the page more editorial intent by lifting the strongest media items out of the archive and presenting them as featured content.</p>
                    </div>
                    <div class="video-section-tag media-chip">{{ $featuredVideos->count() }} selected videos</div>
                </div>

                <div class="video-feature-grid">
                    @foreach($featuredVideos as $video)
                        <button
                            type="button"
                            class="video-card video-trigger"
                            data-title="{{ e($video['title']) }}"
                            data-description="{{ e($video['description']) }}"
                            data-date="{{ e($video['date']) }}"
                            data-platform="{{ e($video['platform']) }}"
                            data-type="{{ $video['type'] }}"
                            data-source="{{ e($video['source'] ?? '') }}"
                            data-embed="{{ e($video['embed'] ?? '') }}"
                            aria-label="Open {{ $video['title'] }}">
                            <div class="video-card-media">
                                <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}">
                                <div class="video-card-badges">
                                    <span>{{ $video['platform'] }}</span>
                                    <strong>{{ $video['date'] }}</strong>
                                </div>
                                <span class="video-card-play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                <div class="video-card-footerline">
                                    <span>{{ strtoupper($video['type']) }}</span>
                                    <span>Preview</span>
                                </div>
                            </div>
                            <div class="video-card-body">
                                <h4>{{ $video['title'] }}</h4>
                                <p>{{ \Illuminate\Support\Str::limit($video['description'], 118) }}</p>
                                <div class="video-card-meta">
                                    <span>{{ $video['year'] }}</span>
                                    <span>Open media</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="video-section">
                <div class="video-section-head">
                    <div>
                        <span class="video-section-tag media-chip">Media Archive</span>
                        <h3>All video entries</h3>
                        <p>The remaining items sit in a denser archive grid with source labels and a modal flow that supports embedded players, direct video files, and external fallback links.</p>
                    </div>
                    <div class="video-section-tag media-chip">{{ $archiveVideos->count() ?: $videoCards->count() }} archive cards</div>
                </div>

                <div class="video-archive-grid">
                    @foreach(($archiveVideos->isNotEmpty() ? $archiveVideos : $videoCards) as $video)
                        <button
                            type="button"
                            class="video-card video-trigger"
                            data-title="{{ e($video['title']) }}"
                            data-description="{{ e($video['description']) }}"
                            data-date="{{ e($video['date']) }}"
                            data-platform="{{ e($video['platform']) }}"
                            data-type="{{ $video['type'] }}"
                            data-source="{{ e($video['source'] ?? '') }}"
                            data-embed="{{ e($video['embed'] ?? '') }}"
                            aria-label="Open {{ $video['title'] }}">
                            <div class="video-card-media">
                                <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}">
                                <div class="video-card-badges">
                                    <span>{{ $video['platform'] }}</span>
                                    <strong>{{ $video['date'] }}</strong>
                                </div>
                                <span class="video-card-play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                <div class="video-card-footerline">
                                    <span>{{ strtoupper($video['type']) }}</span>
                                    <span>Launch</span>
                                </div>
                            </div>
                            <div class="video-card-body">
                                <h4>{{ $video['title'] }}</h4>
                                <p>{{ \Illuminate\Support\Str::limit($video['description'], 96) }}</p>
                                <div class="video-card-meta">
                                    <span>{{ $video['year'] }}</span>
                                    <span>View video</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="video-empty">
                <h3>No video archive is published yet.</h3>
                <p>The new layout is in place, but there are no saved video entries to render right now.</p>
            </div>
        @endif
    </div>
</section>

<div class="modal fade video-modal" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoPreviewModalLabel">Video Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="video-modal-stage">
                    <iframe id="videoPreviewFrame" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <video id="videoPreviewFile" controls playsinline></video>
                    <div class="video-modal-fallback" id="videoPreviewFallback">
                        <div class="video-modal-fallback-inner">
                            <h4>Preview not available here</h4>
                            <p>This media entry points to an external resource that cannot be embedded directly in the modal.</p>
                            <a id="videoPreviewExternal" href="#" target="_blank" rel="noopener" class="btn btn-warning">Open original media</a>
                        </div>
                    </div>
                </div>
                <div class="video-modal-copy">
                    <div class="video-modal-meta">
                        <span><i class="fa fa-video-camera" aria-hidden="true"></i> Video Gallery</span>
                        <span id="videoPreviewDate"><i class="fa fa-calendar" aria-hidden="true"></i> Media Archive</span>
                        <span id="videoPreviewPlatform"><i class="fa fa-globe" aria-hidden="true"></i> Archive</span>
                    </div>
                    <h4 id="videoPreviewTitle">Campus video story</h4>
                    <p id="videoPreviewDescription">A larger preview and context note for the selected video entry.</p>
                    <div class="video-modal-actions media-modal-actions">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                        <a id="videoPreviewLink" class="btn btn-success" href="#" target="_blank" rel="noopener">Open source</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const modalElement = document.getElementById('videoPreviewModal');
        const iframeElement = document.getElementById('videoPreviewFrame');
        const videoElement = document.getElementById('videoPreviewFile');
        const fallbackElement = document.getElementById('videoPreviewFallback');
        const externalButton = document.getElementById('videoPreviewExternal');
        const sourceButton = document.getElementById('videoPreviewLink');
        const titleElement = document.getElementById('videoPreviewTitle');
        const descriptionElement = document.getElementById('videoPreviewDescription');
        const dateElement = document.getElementById('videoPreviewDate');
        const platformElement = document.getElementById('videoPreviewPlatform');
        const modalTitleElement = document.getElementById('videoPreviewModalLabel');

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

        const resetStage = function () {
            iframeElement.style.display = 'none';
            iframeElement.src = '';
            videoElement.style.display = 'none';
            videoElement.pause();
            videoElement.removeAttribute('src');
            videoElement.load();
            fallbackElement.style.display = 'none';
            externalButton.href = '#';
        };

        const bindVideoHandlers = function () {
            document.querySelectorAll('.video-trigger').forEach(function (trigger) {
                if (trigger.getAttribute('data-modal-bound') === '1') {
                    return;
                }
                trigger.setAttribute('data-modal-bound', '1');

                trigger.addEventListener('click', function () {
                    const title = trigger.getAttribute('data-title') || 'Campus video story';
                    const description = trigger.getAttribute('data-description') || 'A campus video highlight.';
                    const date = trigger.getAttribute('data-date') || 'Media Archive';
                    const platform = trigger.getAttribute('data-platform') || 'Archive';
                    const type = trigger.getAttribute('data-type') || 'unavailable';
                    const source = trigger.getAttribute('data-source') || '';
                    const embed = trigger.getAttribute('data-embed') || '';
                    const resolvedUrl = embed || source;

                    resetStage();

                    modalTitleElement.textContent = title;
                    titleElement.textContent = title;
                    descriptionElement.textContent = description;
                    dateElement.innerHTML = '<i class="fa fa-calendar" aria-hidden="true"></i> ' + date;
                    platformElement.innerHTML = '<i class="fa fa-globe" aria-hidden="true"></i> ' + platform;
                    sourceButton.href = resolvedUrl || '#';
                    sourceButton.style.display = resolvedUrl ? 'inline-flex' : 'none';

                    if (type === 'embed' && embed) {
                        iframeElement.src = embed;
                        iframeElement.style.display = 'block';
                    } else if (type === 'file' && source) {
                        videoElement.src = source;
                        videoElement.style.display = 'block';
                    } else if (resolvedUrl) {
                        externalButton.href = resolvedUrl;
                        fallbackElement.style.display = 'flex';
                    } else {
                        fallbackElement.style.display = 'flex';
                    }

                    if (!openModal() && resolvedUrl) {
                        window.open(resolvedUrl, '_blank', 'noopener');
                    }
                });
            });
        };

        const boot = function () {
            bindVideoHandlers();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }

        window.addEventListener('load', boot);
        setTimeout(boot, 250);
        setTimeout(boot, 900);

        modalElement.addEventListener('hidden.bs.modal', resetStage);
    })();
</script>

@endsection