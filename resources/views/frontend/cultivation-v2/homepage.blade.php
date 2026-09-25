<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    @include('frontend.cultivation-v2.partials._original-language')
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $config = $config ?? null;
        $principalSpeechModel = $principalSpeechModel ?? null;
        $studentCount = $studentCount ?? 0;
        $teacherCount = $teacherCount ?? 0;
        $staffCount = $staffCount ?? 0;
        $classCount = $classCount ?? 0;
        $chairman = $chairman ?? null;
        $facultyPreview = $facultyPreview ?? collect();
        $teamCount = $teacherCount + $staffCount;
        $institutionName = trim((string) ($config?->instituteName ?? ''));
        $foundedYear = !empty($config?->establishDate) ? $config->establishDate : null;
        $safeGallery = $gallery ?? collect();
        $firstImage = $safeGallery->get(0);
        $secondImage = $safeGallery->get(1);
        $thirdImage = $safeGallery->get(2);
        $fourthImage = $safeGallery->get(3);

        $resolveGalleryImage = function ($item) {
            if (!empty($item->demo_image)) {
                return is_file(base_path($item->demo_image)) ? asset($item->demo_image) : null;
            }
            if (!$item || empty($item->avatar)) {
                return null;
            }

            $file = rawurlencode(basename((string) $item->avatar));

            $photoGalleryPath = public_path('upload/image/PhotoGallery/' . basename((string) $item->avatar));
            if (file_exists($photoGalleryPath)) {
                return url('/public/upload/image/PhotoGallery/' . $file);
            }

            $webGalleryPath = public_path('upload/image/webGallery/' . basename((string) $item->avatar));
            if (file_exists($webGalleryPath)) {
                return url('/public/upload/image/webGallery/' . $file);
            }

            return null;
        };

        $publicMedia = app(\App\Services\PublicMediaUrl::class);
        $resolveHeroImage = fn ($slide) => $publicMedia->slider($slide?->avatar);

        $sliderItems = ($sliderData ?? collect())
            ->filter(fn ($slide) => $resolveHeroImage($slide))
            ->take(5);
        if ($sliderItems->count() === 0) {
            $demoSlides = config('cultivation_demo.hero.fallback_slides', []);
            $sliderItems = collect($demoSlides)->map(function ($s) use ($institutionName) {
                return (object) [
                    'headLine' => $s['title'],
                    'detail' => $s['subtitle'],
                    'supporting_text' => $s['subtitle'],
                    'eyebrow' => $institutionName ?: 'Institutional Learning Community',
                    'fallback_image' => asset($s['image']),
                    'button_text' => $s['button_text'] ?? 'Discover More',
                    'button_url' => $s['button_url'] ?? route('institutePage'),
                ];
            });
        }

        $neutralAvatar = asset(config('cultivation_demo.branding.default_avatar'));
        $principalIsReal = filled(trim((string) ($config?->principalName ?? '')));
        $chairmanIsReal = filled(trim((string) ($chairman->name ?? $chairman->fullName ?? '')));
        $demoLeadership = config('cultivation_demo.leadership', []);
        $demoPrincipal = $demoLeadership['head'] ?? [];
        $demoChairman = $demoLeadership['chairman'] ?? [];
        $principalName = !empty($config?->principalName) ? $config->principalName : ($demoPrincipal['name'] ?? null);
        $principalRole = !empty($config?->principalDesignation) ? $config->principalDesignation : null;
        $principalLead = !empty($config?->principalImportantSpeech)
            ? $config->principalImportantSpeech
            : ($principalSpeechModel?->importantSpeech ?? null);
        $principalBody = !empty($config?->principalGeneralSpeech)
            ? $config->principalGeneralSpeech
            : ($principalSpeechModel?->generalSpeech ?? null);
        $principalAvatarFile = !empty($config?->avatar) ? basename((string) $config->avatar) : null;
        $principalAvatar = $principalAvatarFile && file_exists(public_path('upload/image/cultivation/' . $principalAvatarFile))
            ? url('/public/upload/image/cultivation/' . rawurlencode($principalAvatarFile))
            : $neutralAvatar;
        $principalRole = $principalRole ?: ($demoPrincipal['designation'] ?? 'Principal / Head of Institution');
        $principalMessage = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($principalLead ?: $principalBody ?: ($demoPrincipal['message'] ?? '')))));
        $hasLeadershipContent = filled($principalName);
         $chairmanName = trim((string) ($chairman->name ?? $chairman->fullName ?? ''));
         $chairmanRole = trim((string) ($chairman->boardChairmanDesignation ?? $chairman->designation ?? ''));
         $chairmanMessage = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($chairman->boardChairmanMessage ?? $chairman->message ?? ''))));
        $chairmanAvatarFile = !empty($chairman?->avatar) ? basename((string) $chairman->avatar) : null;
        $chairmanAvatar = $chairmanAvatarFile && file_exists(public_path('upload/image/cultivation/' . $chairmanAvatarFile))
            ? url('/public/upload/image/cultivation/' . rawurlencode($chairmanAvatarFile))
            : $neutralAvatar;
        $chairmanName = $chairmanName ?: ($demoChairman['name'] ?? 'Governing Body Chairman');
        $chairmanRole = $chairmanRole ?: ($demoChairman['designation'] ?? 'Governing Body Chairman');
        $chairmanMessage = $chairmanMessage ?: ($demoChairman['message'] ?? '');
        $leadershipCards = collect([
            [
                'label' => 'Chairman / President',
                'name' => $chairmanName,
                'role' => $chairmanRole,
                'avatar' => $chairmanAvatar,
                'message' => $chairmanMessage,
                'route' => route('chairmanMessagePage'),
                'is_demo' => !$chairmanIsReal,
            ],
            [
                'label' => 'Head of Institution',
                'name' => $principalName,
                'role' => $principalRole,
                'avatar' => $principalAvatar,
                'message' => $principalMessage,
                'route' => route('headOfInstituteMessagePage'),
                'is_demo' => !$principalIsReal,
            ],
        ])->values();
        $leadershipColumnClass = $leadershipCards->count() === 1 ? 'col-lg-8 mx-auto' : 'col-lg-6';
        $facultyLabel = match ($config?->institute_type) {
            'college' => 'Lecturers / Teachers',
            'school_college', 'school_and_college' => 'Teachers & Lecturers',
            default => 'Faculty Members',
        };
        $resolveTeacherPhoto = function ($teacher) {
            if (!$teacher || empty($teacher->avatar)) {
                return asset(config('cultivation_demo.branding.default_avatar'));
            }

            $file = basename((string) $teacher->avatar);
            if (file_exists(public_path('upload/image/teacher/' . $file))) {
                return url('/public/upload/image/teacher/' . rawurlencode($file));
            }

            return asset(config('cultivation_demo.branding.default_avatar'));
        };
        $ogImage = !empty($config?->logo) && file_exists(public_path('upload/image/cultivation/' . basename((string) $config->logo)))
            ? url('/public/upload/image/cultivation/' . rawurlencode(basename((string) $config->logo)))
            : asset('public/logo.png');
        $pageTitle = $institutionName ?: 'Institution Website';
        $pageDescription = $institutionName ? $institutionName . ' official website.' : 'Official institution website.';
        $aboutHeading = trim((string) ($insData?->insHeadline ?? ''));
        $aboutDetails = trim(strip_tags((string) ($insData?->insDetails ?? '')));
        $hasAboutContent = filled($aboutHeading) || filled($aboutDetails);
        $overviewImages = collect([$resolveGalleryImage($firstImage), $resolveGalleryImage($secondImage)])->filter()->values();
        $demoStatistics = config('cultivation_demo.statistics', []);
        $establishedYear = preg_match('/\b(18|19|20)\d{2}\b/', (string) $foundedYear, $yearMatch) && (int) $yearMatch[0] <= now()->year ? $yearMatch[0] : null;
        $overviewMetrics = collect([
            ['key' => 'established', 'value' => $establishedYear, 'real' => filled($establishedYear)],
            ['key' => 'students', 'value' => $studentCount > 0 ? $studentCount : null, 'real' => $studentCount > 0],
            ['key' => 'teachers', 'value' => $teacherCount > 0 ? $teacherCount : null, 'real' => $teacherCount > 0],
            ['key' => 'staff', 'value' => $staffCount > 0 ? $staffCount : null, 'real' => $staffCount > 0],
            ['key' => 'classes', 'value' => $classCount > 0 ? $classCount : null, 'real' => $classCount > 0],
            ['key' => 'experience', 'value' => $establishedYear ? max(0, now()->year - (int) $establishedYear) . '+' : null, 'real' => filled($establishedYear)],
        ])->map(function ($metric) use ($demoStatistics) {
            $fallback = $demoStatistics[$metric['key']] ?? [];
            return array_merge($fallback, [
                'value' => $metric['real'] ? $metric['value'] : ($fallback['value'] ?? '—'),
                'real' => $metric['real'],
                'source' => $metric['real'] ? ($metric['key'] === 'experience' ? 'DERIVED' : 'REAL') : 'DEMO',
            ]);
        })->values();

        $demoFaculty = collect(config('cultivation_demo.faculty', []))->map(fn ($teacher) => (object) array_merge($teacher, ['is_demo' => true]));
        $facultyPreview = $facultyPreview->filter(fn ($teacher) => filled(trim(($teacher->firstName ?? '') . ' ' . ($teacher->lastName ?? ''))))->take(4)->map(function ($teacher) {
            $teacher->is_demo = false;
            return $teacher;
        });
        if ($facultyPreview->count() < 4) {
            $facultyPreview = $facultyPreview->concat($demoFaculty->take(4 - $facultyPreview->count()));
        }

        $welcomeFallback = config('cultivation_demo.welcome', []);
        $welcomeTitle = $aboutHeading ?: ($institutionName ? 'Welcome to ' . $institutionName : $welcomeFallback['heading']);
        $welcomeSubHeading = $welcomeFallback['sub_heading'] ?? 'Knowledge, Discipline and Moral Values';
        $welcomeDetails = $aboutDetails ?: ($welcomeFallback['details'] ?? '');
        $welcomeImage = $overviewImages->get(0) ?: asset($welcomeFallback['image'] ?? 'public/cultivation/assets/images/about/history.png');

        $newsEventsList = config('cultivation_demo.news_events', []);

        $facultyCount = $facultyPreview->count();
        $facultyColClass = match(true) {
            $facultyCount === 1 => 'col-6 col-sm-5 col-md-4 col-lg-3 mx-auto',
            $facultyCount === 2 => 'col-6 col-sm-5 col-md-4 col-lg-3',
            $facultyCount === 3 => 'col-6 col-sm-4 col-md-4 col-lg-3',
            default => 'col-6 col-sm-6 col-md-6 col-lg-3',
        };
        $facultyGridJustify = $facultyCount === 2 ? 'justify-content-center' : '';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <link rel="apple-touch-icon" href="{{ asset('public/cultivation/apple-touch-icon.html') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/cultivation/assets/images/fav.png') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/off-canvas.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/fonts/linea-fonts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/fonts/flaticon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/rsmenu-main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/rs-spacing.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/cultivation/assets/css/responsive.css') }}">

    <style>
        .rs-slider.style1 .slider-content {
            min-height: 400px;
            max-height: 450px;
            height: 430px;
            display: flex;
            align-items: center;
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            padding: 0 !important;
            position: relative;
        }

        .visually-hidden-page-title {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }

        .rs-slider.style1 .slider-content::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(11, 29, 68, 0.7) 0%, rgba(11, 29, 68, 0.38) 60%, rgba(11, 29, 68, 0.2) 100%);
        }

        .rs-slider.style1 .slider-content .container {
            position: relative;
            z-index: 2;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .rs-slider.style1 .sl-sub-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            color: #7ed9f4;
            text-transform: uppercase;
            text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }

        .rs-slider.style1 .sl-title {
            font-size: 40px;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 22px;
            max-width: 680px;
            max-height: 100px;
            overflow: hidden;
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(0,0,0,0.6);
        }

        .rs-slider.style1 .sl-support {
            color: rgba(255,255,255,.92);
            font-size: 17px;
            line-height: 1.55;
            margin: 0 0 22px;
            max-width: 590px;
        }

        .news-events-section {
            background: #f4f8fc;
            border-top: 1px solid #e1edf7;
            border-bottom: 1px solid #e1edf7;
        }

        .news-card {
            background: #ffffff;
            border: 1px solid #dce9f4;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 8px 24px rgba(16, 44, 99, 0.06);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(16, 44, 99, 0.12);
        }

        .news-card .news-img {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #eef6fb;
        }

        .news-card .news-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-card .news-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #112958;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .news-card .news-body {
            padding: 20px;
        }

        .news-card .news-date {
            display: inline-block;
            font-size: 12px;
            color: #21a7d0;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .news-card .news-title {
            font-size: 18px;
            font-weight: 700;
            color: #102c63;
            line-height: 1.35;
            margin: 0 0 10px;
        }

        .news-card .news-desc {
            font-size: 14px;
            color: #4a5f7a;
            line-height: 1.6;
            margin: 0;
        }

        .rs-about.style2 .about-intro {
            min-height: 100%;
        }

        .rs-latest-events .event-wrap .events-short {
            min-height: 112px;
        }

        .rs-latest-events .event-wrap .content-part .title {
            line-height: 1.35;
        }

        .home-dynamic-link {
            color: inherit;
        }

        .home-dynamic-link:hover {
            color: #21a7d0;
        }

        .menu-area.menu-sticky {
            background: #ffffff;
            border-bottom: 1px solid #e7edf5;
        }

        .menu-area.menu-sticky .row.y-middle {
            min-height: 90px;
        }

        .menu-area .logo-cat-wrap {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: nowrap;
        }

        .menu-area .logo-part.pr-90,
        .menu-area .main-menu.pr-90 {
            padding-right: 0 !important;
        }

        .menu-area .logo-part a {
            display: inline-flex;
            align-items: center;
        }

        .menu-area .logo-part .light-logo {
            display: none !important;
        }

        .menu-area .logo-part .dark-logo {
            display: inline-flex !important;
            vertical-align: middle;
        }

        .menu-area .logo-part img {
            height: 52px;
            width: auto;
            max-width: 240px;
            object-fit: contain;
        }

        .menu-area .rs-menu-area {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .menu-area .main-menu {
            width: 100%;
        }

        .menu-area .rs-menu ul.nav-menu {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            gap: 0;
        }

        .menu-area .rs-menu ul.nav-menu > li > a {
            color: #273c66;
            font-size: 15px;
            font-weight: 700;
            padding: 0 12px;
            line-height: 90px;
            letter-spacing: 0.2px;
        }

        .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu {
            text-align: left;
        }

        .menu-area .rs-menu ul.nav-menu > li > ul.sub-menu > li > a {
            font-size: 14px;
            font-weight: 600;
        }

        .menu-area .rs-menu ul.nav-menu > li.current-menu-item > a,
        .menu-area .rs-menu ul.nav-menu > li > a:hover {
            color: #21a7d0;
        }

        .menu-area .mobile-menu {
            top: 50%;
            transform: translateY(-50%);
            right: 0;
        }

        @media (max-width: 1199px) {
            .menu-area .logo-part img {
                max-width: 200px;
                height: 46px;
            }

            .menu-area .rs-menu ul.nav-menu > li > a {
                padding: 0 9px;
                line-height: 86px;
                font-size: 14px;
            }

        }

        @media (max-width: 991px) {
            .menu-area.menu-sticky .row.y-middle {
                min-height: 76px;
            }

            .menu-area .logo-part img {
                height: 42px;
                max-width: 150px;
            }

            .menu-area .rs-menu ul.nav-menu {
                display: block;
            }

            .menu-area .rs-menu ul.nav-menu > li > a {
                padding: 10px 0;
                line-height: 1.5;
                color: #ffffff;
            }
        }

        .principal-feature-section {
            background: #f3f8f9;
        }

        .leadership-section-title {
            margin-bottom: 24px;
            text-align: center;
        }

        .leadership-section-title .sub-title {
            margin-bottom: 8px;
        }

        .leadership-profile-card {
            background: #ffffff;
            border: 1px solid #dce9f4;
            border-radius: 10px;
            box-shadow: 0 10px 28px rgba(39, 60, 102, 0.08);
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .leadership-profile-head {
            align-items: center;
            background: linear-gradient(90deg, #112958, #273c66);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
        }

        .leadership-profile-head h3 {
            color: #ffffff;
            font-size: 19px;
            line-height: 1.2;
            margin: 0;
        }

        .leadership-role-label {
            color: #21a7d0;
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .8px;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .leadership-profile-body {
            display: flex;
            flex: 1;
            padding: 24px;
        }

        .leadership-meta-horizontal {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            width: 100%;
        }

        .leadership-portrait {
            width: 120px;
            height: 150px;
            object-fit: cover;
            object-position: center top;
            border: 3px solid #eaf3ff;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(16, 44, 99, 0.1);
            flex-shrink: 0;
        }

        .leadership-copy {
            display: flex;
            flex: 1;
            flex-direction: column;
            min-width: 0;
        }

        .leadership-copy h4 {
            color: #102c63;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.25;
            margin: 0 0 4px;
        }

        .leadership-copy .designation {
            color: #5b6d87;
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 12px;
        }

        .leadership-copy .desc {
            color: #4a5f7a;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            min-height: 68px;
        }

        .leadership-cta {
            margin-top: auto;
            padding-top: 14px;
        }

        .leadership-meta {
            align-items: center;
            display: flex;
            gap: 16px;
            margin-bottom: 14px;
        }

        .leadership-meta img {
            border: 4px solid #eaf3ff;
            border-radius: 10px;
            height: 124px;
            object-fit: cover;
            width: 98px;
        }

        .leadership-meta h4 {
            font-size: 21px;
            line-height: 1.25;
            margin: 0 0 4px;
        }

        .leadership-meta p {
            color: #5b6d87;
            font-weight: 600;
            margin: 0;
        }

        .leadership-profile-body .desc {
            color: #4a5f7a;
            line-height: 1.8;
            margin: 0 0 16px;
        }

        .leadership-read-more {
            color: #0f6d8b;
            font-size: 13px;
            font-weight: 800;
        }

        .faculty-preview-section {
            background: #ffffff;
        }

        .faculty-preview-head {
            align-items: flex-end;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 26px;
        }

        .faculty-preview-head h2 {
            color: #102c63;
            font-size: 34px;
            line-height: 1.1;
            margin: 0;
        }

        .faculty-preview-grid {
            row-gap: 24px;
        }

        .faculty-card {
            background: #ffffff;
            border: 1px solid #dce9f4;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(16, 44, 99, 0.05);
            max-width: 100%;
            margin: 0 auto;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .faculty-card:hover {
            box-shadow: 0 10px 20px rgba(16, 44, 99, 0.10);
            transform: translateY(-2px);
        }

        .faculty-photo-link {
            background: #eef6fb;
            display: block;
            aspect-ratio: 4 / 5;
            height: auto;
            overflow: hidden;
        }

        .faculty-photo-link img {
            display: block;
            height: 100%;
            object-fit: cover;
            object-position: center 15%;
            width: 100%;
        }

        .faculty-card-body {
            padding: 10px 12px;
        }

        .faculty-card-body h3 {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0 0 3px;
        }

        .faculty-card-body h3 a {
            color: #112958;
        }

        .faculty-designation {
            color: #21a7d0;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            margin: 0;
        }

        .faculty-subject {
            color: #71849b;
            font-size: 12px;
            line-height: 1.3;
            margin: 4px 0 0;
        }

        @media (max-width: 767px) {
            .rs-slider.style1 .slider-content {
                height: 400px;
                min-height: 400px;
            }

            .rs-slider.style1 .sl-sub-title { font-size: 15px; }
            .rs-slider.style1 .sl-title { font-size: 30px; max-width: 500px; }
            .rs-slider.style1 .sl-support { font-size: 15px; max-width: 420px; }
            .leadership-meta-horizontal { gap: 14px; }
            .leadership-portrait { width: 96px; height: 120px; }
            .leadership-copy h4 { font-size: 19px; }
        }

        .principal-feature-card {
            background: #ffffff;
            border-radius: 6px;
            border: 1px solid #e3ebf5;
            box-shadow: 0 8px 24px rgba(39, 60, 102, 0.08);
            overflow: hidden;
        }

        .principal-feature-head {
            background: linear-gradient(90deg, #112958, #273c66);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .principal-feature-head h3 {
            margin: 0;
            color: #ffffff;
            font-size: 26px;
            line-height: 1.2;
        }

        .principal-feature-body {
            padding: 24px;
        }

        .principal-meta {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 14px;
        }

        .principal-meta img {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #eaf3ff;
        }

        .principal-meta h4 {
            margin: 0 0 4px;
            font-size: 22px;
            line-height: 1.2;
        }

        .principal-meta p {
            margin: 0;
            color: #5b6d87;
            font-weight: 600;
        }

        .principal-feature-body blockquote {
            margin: 10px 0 14px;
            font-size: 20px;
            line-height: 1.5;
            color: #112958;
            font-weight: 700;
            border-left: 4px solid #21a7d0;
            padding-left: 14px;
        }

        .principal-feature-body .desc {
            color: #4a5f7a;
            line-height: 1.9;
            margin: 0;
        }

        .latest-notice-modern {
            background: #f7fbfe;
            border: 1px solid #d8e8f5;
            border-radius: 14px;
            padding: 20px;
        }

        .latest-notice-wrap {
            padding-top: 0;
            padding-bottom: 56px;
        }

        .latest-notice-modern .notice-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .latest-notice-modern .notice-head h3 {
            margin: 0;
            font-size: 26px;
            line-height: 1.1;
            color: #102c63;
            letter-spacing: 0.2px;
        }

        .latest-notice-modern .notice-shell {
            background: #ffffff;
            border: 1px solid #dce9f4;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .latest-notice-modern .notice-item {
            display: grid;
            grid-template-columns: 76px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            border: 1px solid #e2edf6;
            border-radius: 12px;
            padding: 10px 12px;
            background: #fdfefe;
        }

        .latest-notice-modern .date-box {
            width: 78px;
            min-height: 64px;
            border-radius: 10px;
            background: #d7edf8;
            border: 1px solid #b7d7ea;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #103d6f;
        }

        .latest-notice-modern .date-box .day {
            font-size: 24px;
            line-height: 1;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .latest-notice-modern .date-box .mon {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .latest-notice-modern .notice-title {
            font-size: 19px;
            line-height: 1.35;
            color: #132e63;
            font-weight: 700;
            margin: 0;
            white-space: normal;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .latest-notice-modern .notice-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .latest-notice-modern .notice-btn {
            border: 1px solid #a8c4dc;
            color: #12396e;
            background: #fff;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 20px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            line-height: 1.1;
        }

        .latest-notice-modern .notice-file-pill {
            border: 1px solid #b9d8e7;
            border-radius: 999px;
            color: #1c6d8c;
            font-size: 12px;
            font-weight: 800;
            line-height: 1;
            padding: 6px 9px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .latest-notice-modern .notice-empty-state {
            border: 1px dashed #c7dceb;
            border-radius: 10px;
            color: #4a6484;
            font-size: 16px;
            font-weight: 600;
            padding: 18px;
            text-align: center;
        }

        .latest-notice-modern .notice-btn:hover {
            border-color: #21a7d0;
            color: #0f6d8b;
        }

        .latest-notice-modern .all-notice-btn {
            border: 1px solid #21a7d0;
            color: #21a7d0;
            background: #fff;
            border-radius: 12px;
            padding: 7px 14px;
            font-weight: 700;
            font-size: 16px;
            line-height: 1;
        }

        .latest-notice-modern .all-notice-btn:hover {
            background: #21a7d0;
            color: #fff;
        }

        .home-info-grid {
            background: linear-gradient(180deg, #f8fcff 0%, #f2f8fe 100%);
            border: 1px solid #d7e6f4;
            border-radius: 18px;
            padding: 20px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .home-info-grid .info-card {
            background: #ffffff;
            border: 1px solid #d7e6f4;
            border-radius: 14px;
            height: 100%;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(16, 44, 99, 0.08);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .home-info-grid .info-card:hover {
            transform: translateY(-2px);
            border-color: #bdd8ec;
            box-shadow: 0 16px 30px rgba(16, 44, 99, 0.12);
        }

        .home-info-grid .info-card-head {
            background: linear-gradient(90deg, #1b9cc5, #2377b8);
            color: #ffffff;
            padding: 12px 16px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.2px;
            line-height: 1.2;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .home-info-grid .info-card-body {
            padding: 16px;
        }

        .home-info-grid .info-card-row {
            display: grid;
            grid-template-columns: 72px minmax(0, 1fr);
            gap: 14px;
            align-items: start;
        }

        .home-info-grid .info-card-row img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 10px;
            background: linear-gradient(160deg, #f3f8fd, #ebf4fb);
            padding: 6px;
            border: 1px solid #deebf6;
            box-shadow: 0 6px 14px rgba(16, 44, 99, 0.08);
        }

        .home-info-grid ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 4px;
        }

        .home-info-grid li {
            margin: 0;
            font-size: 17px;
            line-height: 1.4;
            color: #163d75;
            font-weight: 700;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .home-info-grid li i {
            color: #1c95c7;
            margin-top: 4px;
            min-width: 12px;
            font-size: 15px;
        }

        .home-info-grid li a,
        .home-info-grid li .info-item-text {
            color: #163d75;
            transition: color .2s ease;
        }

        .home-info-grid li a:hover {
            color: #21a7d0;
        }

        .ref-photo-gallery {
            background: linear-gradient(165deg, #f8fbff 0%, #ecf6ff 100%);
            border: 1px solid #d2e4f3;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 14px 34px rgba(17, 53, 105, 0.1);
            position: relative;
            overflow: hidden;
        }

        .ref-photo-gallery::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -80px;
            top: -100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(33, 167, 208, 0.2), rgba(33, 167, 208, 0));
            pointer-events: none;
        }

        .ref-photo-gallery .gallery-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 14px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #d9e8f5;
            position: relative;
            z-index: 1;
        }

        .ref-photo-gallery .gallery-head .head-copy {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .ref-photo-gallery .gallery-head .head-copy span {
            display: inline-flex;
            width: fit-content;
            font-size: 11px;
            line-height: 1;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #0d7c9f;
            font-weight: 800;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dff4fb;
            border: 1px solid #bee8f5;
        }

        .ref-photo-gallery .gallery-head h2 {
            margin: 0;
            color: #102c63;
            font-size: 34px;
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .ref-photo-gallery .gallery-head .head-note {
            margin: 0;
            color: #4a6484;
            font-size: 15px;
            line-height: 1.5;
            max-width: 650px;
        }

        .ref-photo-gallery .gallery-head .gallery-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #1f567f;
            font-weight: 700;
            margin-top: 2px;
        }

        .ref-photo-gallery .view-all-btn {
            border: 1px solid #21a7d0;
            color: #ffffff;
            background: linear-gradient(120deg, #21a7d0, #178fb5);
            border-radius: 11px;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 9px 20px rgba(33, 167, 208, 0.25);
            transition: all .25s ease;
            white-space: nowrap;
        }

        .ref-photo-gallery .view-all-btn i {
            margin-left: 6px;
            font-size: 11px;
        }

        .ref-photo-gallery .view-all-btn:hover {
            background: #112958;
            border-color: #112958;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 11px 24px rgba(17, 41, 88, 0.25);
        }

        .ref-photo-gallery .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            grid-auto-rows: 84px;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile {
            grid-column: span 3;
            grid-row: span 2;
            display: block;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #d6e7f5;
            background: #fff;
            cursor: pointer;
            padding: 0;
            width: 100%;
            text-align: left;
            position: relative;
            box-shadow: 0 8px 18px rgba(19, 54, 102, 0.08);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:first-child {
            grid-column: span 6;
            grid-row: span 4;
        }

        .ref-photo-gallery .gallery-grid.gallery-count-1 .gallery-tile:first-child {
            grid-column: span 12;
        }

        .ref-photo-gallery .gallery-grid.gallery-count-2 .gallery-tile {
            grid-column: span 6;
            grid-row: span 4;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile .gallery-shade {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            background: linear-gradient(180deg, rgba(8, 29, 59, 0.12) 20%, rgba(10, 35, 74, 0.82) 100%);
            opacity: .86;
            transition: opacity .25s ease;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile .gallery-plus {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(.9);
            background: rgba(255, 255, 255, 0.95);
            color: #112958;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            opacity: 0;
            transition: all .25s ease;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-date {
            position: absolute;
            left: 12px;
            top: 12px;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: .45px;
            text-transform: uppercase;
            color: #ffffff;
            padding: 6px 9px;
            border-radius: 999px;
            background: rgba(17, 41, 88, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-date:empty {
            display: none;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-title {
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 12px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            text-shadow: 0 2px 8px rgba(7, 20, 39, 0.45);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:first-child .gallery-mini-title {
            font-size: 18px;
            max-width: 88%;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:hover img {
            transform: scale(1.07);
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:hover {
            transform: translateY(-3px);
            border-color: #bdd9ed;
            box-shadow: 0 14px 30px rgba(19, 54, 102, 0.2);
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:hover .gallery-shade {
            opacity: .58;
        }

        .ref-photo-gallery .gallery-grid .gallery-tile:hover .gallery-plus {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .gallery-preview-modal .modal-dialog {
            max-width: 920px;
        }

        .gallery-preview-modal .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 24px 56px rgba(17, 41, 88, 0.35);
        }

        .gallery-preview-modal .modal-header {
            background: #ffffff;
            border-bottom: 1px solid #e0ebf5;
            color: #112958;
            padding: 14px 18px;
        }

        .gallery-preview-modal .modal-title {
            font-size: 20px;
            color: #102c63;
            margin: 0;
            font-weight: 700;
        }

        .gallery-preview-modal .close {
            color: #274268;
            opacity: 0.9;
            text-shadow: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #d4e4f2;
            background: #f4f9fd;
            font-size: 24px;
            line-height: 0;
        }

        .gallery-preview-modal .modal-body {
            padding: 0;
            background: #ffffff;
        }

        .gallery-preview-modal .preview-image {
            width: 100%;
            max-height: 430px;
            object-fit: cover;
            display: block;
        }

        .gallery-preview-modal .preview-info {
            padding: 18px;
            border-top: 1px solid #e6eef5;
        }

        .gallery-preview-modal .preview-info h4 {
            margin: 0 0 6px;
            color: #112958;
            font-size: 22px;
            line-height: 1.25;
        }

        .gallery-preview-modal .preview-date {
            display: inline-block;
            margin-bottom: 10px;
            color: #2e7093;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            background: #ecf8fc;
            border: 1px solid #cde8f1;
            padding: 4px 9px;
            border-radius: 99px;
        }

        .gallery-preview-modal .preview-desc {
            color: #445d7d;
            font-size: 15px;
            line-height: 1.7;
            margin: 0;
        }

        /* Footer styles are centralized in frontend/cultivation-v2/partials/_footer.blade.php */

        @media (max-width: 767px) {
            .topbar-area .topbar-right {
                display: none;
            }

            .topbar-area .topbar-contact {
                text-align: center;
            }

            .principal-feature-head {
                padding: 14px 16px;
            }

            .principal-feature-head h3 {
                font-size: 20px;
            }

            .principal-feature-body {
                padding: 16px;
            }

            .principal-meta {
                align-items: flex-start;
            }

            .principal-meta img {
                width: 72px;
                height: 72px;
            }

            .principal-feature-body blockquote {
                font-size: 17px;
            }

            .latest-notice-modern {
                padding: 14px;
                border-radius: 10px;
            }

            .latest-notice-wrap {
                padding-bottom: 40px;
            }

            .latest-notice-modern .notice-head h3 {
                font-size: 24px;
            }

            .latest-notice-modern .all-notice-btn {
                font-size: 14px;
                padding: 8px 12px;
            }

            .latest-notice-modern .notice-item {
                grid-template-columns: 66px 1fr;
                gap: 10px;
                padding: 8px;
            }

            .latest-notice-modern .date-box {
                width: 66px;
                min-height: 58px;
            }

            .latest-notice-modern .date-box .day {
                font-size: 15px;
            }

            .latest-notice-modern .date-box .mon {
                font-size: 11px;
            }

            .latest-notice-modern .notice-title {
                font-size: 15px;
                white-space: normal;
            }

            .latest-notice-modern .notice-actions {
                grid-column: 1 / span 2;
            }

            .latest-notice-modern .notice-btn {
                font-size: 14px;
                padding: 7px 10px;
                border-radius: 8px;
            }

            .home-info-grid {
                padding: 12px;
                border-radius: 14px;
            }

            .home-info-grid .info-card-head {
                font-size: 18px;
                padding: 10px 12px;
            }

            .home-info-grid .info-card-row {
                grid-template-columns: 52px 1fr;
                gap: 10px;
            }

            .home-info-grid .info-card-row img {
                width: 52px;
                height: 52px;
            }

            .home-info-grid li {
                font-size: 15px;
            }

            .ref-photo-gallery {
                padding: 14px;
            }

            .ref-photo-gallery .gallery-head {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .ref-photo-gallery .gallery-head h2 {
                font-size: 26px;
            }

            .ref-photo-gallery .gallery-head .head-note {
                font-size: 14px;
            }

            .ref-photo-gallery .gallery-grid {
                grid-template-columns: repeat(8, minmax(0, 1fr));
                grid-auto-rows: 82px;
                gap: 8px;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile {
                grid-column: span 4;
                grid-row: span 2;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile:first-child {
                grid-column: span 8;
                grid-row: span 4;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-title {
                font-size: 13px;
            }

            .gallery-preview-modal .modal-header,
            .gallery-preview-modal .preview-info {
                padding: 12px;
            }

            .gallery-preview-modal .preview-info h4 {
                font-size: 18px;
            }

            .home-info-grid li {
                font-size: 14px;
            }

            .ref-photo-gallery {
                padding: 12px;
            }

            .ref-photo-gallery .gallery-head h2 {
                font-size: 22px;
            }

            .ref-photo-gallery .gallery-head .head-note {
                font-size: 13px;
            }

            .ref-photo-gallery .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                grid-auto-rows: 78px;
                gap: 8px;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile {
                grid-column: span 1;
                grid-row: span 2;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile:first-child {
                grid-column: span 2;
                grid-row: span 4;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-date {
                top: 8px;
                left: 8px;
                padding: 5px 7px;
            }

            .ref-photo-gallery .gallery-grid .gallery-tile .gallery-mini-title,
            .ref-photo-gallery .gallery-grid .gallery-tile:first-child .gallery-mini-title {
                font-size: 12px;
                left: 10px;
                right: 10px;
                bottom: 9px;
            }

            .ref-photo-gallery .view-all-btn {
                padding: 8px 10px;
                font-size: 12px;
            }
        }
        /* Consistency pass: preserve the existing section architecture. */
        .rs-slider.style1 .slider-content { background-position: center 45%; }
        .rs-slider.style1 .slider-content::before { background: linear-gradient(90deg,rgba(8,27,57,.88),rgba(8,27,57,.55) 48%,rgba(8,27,57,.12)); }
        .rs-slider.style1 .slider-content .sl-title { font-size:40px; max-width:620px; line-height:1.2; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; margin:0 0 16px; }
        .rs-slider.style1 .slider-content .sl-sub-title { font-size:16px; line-height:1.5; margin:0 0 12px; }
        .rs-slider.style1 .sl-support { max-width:530px; margin-bottom:18px; }
        .hero-demo-note { display:block; width:fit-content; font-size:11px; color:#d7e8f5; margin:0 0 12px; }
        .rs-slider.style1 .owl-nav { display:block !important; }
        .rs-slider.style1 .owl-nav [class*="owl-"] { width:40px !important; height:40px !important; line-height:40px !important; border-radius:50%; background:rgba(8,27,57,.65) !important; color:#fff !important; font-size:24px !important; opacity:1 !important; visibility:visible !important; }
        .rs-slider.style1 .owl-nav .owl-prev { left:18px !important; }
        .rs-slider.style1 .owl-nav .owl-next { right:18px !important; }
        .rs-slider.style1 .owl-dots { position:absolute; bottom:14px; left:0; right:0; text-align:center; }
        .rs-slider.style1 .owl-dot { width:10px; height:10px; margin:0 5px; border-radius:50%; background:#b9d2e1; }
        .rs-slider.style1 .owl-dot.active { background:#21a7d0; box-shadow:0 0 0 3px rgba(255,255,255,.3); }
        #rs-at-a-glance .row { margin-left:-7px; margin-right:-7px; }
        #rs-at-a-glance .row > div { padding-left:7px; padding-right:7px; }
        #rs-at-a-glance .stat-card { padding:26px 10px !important; }
        #rs-at-a-glance .stat-icon { width:56px !important; height:56px !important; font-size:25px !important; }
        #rs-at-a-glance .number { font-size:38px !important; margin-bottom:10px !important; }
        #rs-at-a-glance .stat-body .title { font-size:13px !important; line-height:1.5; min-height:39px; letter-spacing:.2px !important; }
        .leadership-portrait { background:#edf4f9; object-fit:cover; }
        .leadership-copy { align-self:stretch; }
        .leadership-copy h4 { min-height:55px; }
        .leadership-copy .designation { min-height:42px; }
        .leadership-profile-body .desc { height:101px; min-height:101px; line-height:1.8; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; margin:0; }
        .leadership-cta { min-height:60px; display:flex; align-items:flex-end; }
        .leadership-cta .leadership-read-more { display:inline-flex; align-items:center; min-height:50px; padding:12px 20px; border-radius:3px; background:#e8f5fa; }
        .faculty-card { height:100%; border-radius:12px; }
        .faculty-photo-link { aspect-ratio: 5 / 4; }
        .faculty-photo-link img { object-position:center 25%; }
        .faculty-card-body { padding:20px 14px; }
        .faculty-card-body h3 { font-size:17px; min-height:44px; margin-bottom:8px; }
        .faculty-designation { font-size:14px; line-height:1.5; }
        .faculty-subject { font-size:13px; line-height:1.5; }
        @media(max-width:767px) {
            body.home-style2 .menu-area .logo-part img { max-width:42px !important; }
            body.home-style2 .menu-area .header-institute-title { font-size:16px; line-height:1.25; }
            .rs-slider.style1 .slider-content .sl-title { font-size:30px; max-height:none; }
            .rs-slider.style1 .slider-content .sl-sub-title { font-size:13px; }
            .rs-slider.style1 .slider-content .container { padding-left:40px; padding-right:40px; }
            .rs-slider.style1 .owl-nav [class*="owl-"] { width:28px !important; height:28px !important; line-height:28px !important; }
            .rs-slider.style1 .owl-nav .owl-prev { left:5px !important; }
            .rs-slider.style1 .owl-nav .owl-next { right:5px !important; }
            .hero-demo-note { font-size:10px; }
            .faculty-preview-head { align-items:flex-start; flex-direction:column; }
            .faculty-card-body h3 { font-size:14px; }
            .faculty-card-body { padding:16px 10px; }
            .leadership-profile-body { padding:20px; }
            .leadership-meta-horizontal { flex-wrap:wrap; }
            .leadership-copy { flex-basis:100%; }
            .leadership-copy h4,.leadership-copy .designation { min-height:0; }
            .leadership-portrait { width:100px; height:125px; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/homepage-responsive.css') }}?v={{ filemtime(public_path('cultivation/assets/css/homepage-responsive.css')) }}">
</head>
<body class="home-style2 v2-homepage">

    @include('frontend.cultivation-v2.partials._header')

    <div class="main-content">
        <h1 class="visually-hidden-page-title">{{ $pageTitle }}</h1>
        <div class="rs-slider style1">
            <div class="rs-carousel owl-carousel" data-loop="{{ $sliderItems->count() > 1 ? 'true' : 'false' }}" data-items="1" data-margin="0" data-autoplay="{{ $sliderItems->count() > 1 ? 'true' : 'false' }}" data-hoverpause="true" data-autoplay-timeout="5600" data-smart-speed="800" data-dots="true" data-nav="true" data-nav-speed="false" data-center-mode="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="1" data-ipad-device-nav="true" data-ipad-device-dots="true" data-ipad-device2="1" data-ipad-device-nav2="true" data-ipad-device-dots2="true" data-md-device="1" data-md-device-nav="true" data-md-device-dots="true">
                @foreach($sliderItems as $slide)
                    @php
                        $slideImage = isset($slide->fallback_image) ? $slide->fallback_image : ($resolveHeroImage($slide) ?: asset('public/cultivation/assets/images/slider/h2-1.jpg'));
                        $slideInstName = trim((string) (($slide->eyebrow ?? null) ?: $institutionName ?: 'Institutional Learning Community'));
                        $slideHeading = trim((string) ($slide->headLine ?: config('cultivation_demo.hero.fallback_slides.0.title')));
                        $slideSupport = \Illuminate\Support\Str::limit(trim(strip_tags((string) ($slide->supporting_text ?? $slide->description ?? $slide->detail ?? ''))), 150);
                        if (empty($slideSupport) && isset($slide->fallback_image)) {
                            $slideSupport = trim((string) ($slide->detail ?? ''));
                        }
                        $slideBtnText = $slide->button_text ?? 'Discover More →';
                        $slideBtnUrl = $slide->button_url ?? route('institutePage');
                    @endphp
                    <div class="slider-content" style="background-image:url('{{ $slideImage }}');">
                        <div class="container">
                            <div class="sl-sub-title white-color" data-wow-delay="300ms" data-wow-duration="2000ms">{{ $slideInstName }}</div>
                            <div class="sl-title white-color" data-wow-delay="600ms" data-wow-duration="2000ms">{{ $slideHeading }}</div>
                            @if($slideSupport)<p class="sl-support" data-wow-delay="750ms" data-wow-duration="1800ms">{{ $slideSupport }}</p>@endif
                            @if(isset($slide->fallback_image))<span class="hero-demo-note">Illustrative education imagery</span>@endif
                            <div class="sl-btn" data-wow-delay="900ms" data-wow-duration="2000ms">
                                <a class="readon2 banner-style" href="{{ $slideBtnUrl }}">{{ $slideBtnText }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rs-services style1">
            <div class="row no-gutter">
                <div class="col-lg-3 col-md-6">
                    <div class="service-item overly1">
                        <img src="{{ asset('public/cultivation/assets/images/services/1.jpg') }}" alt="">
                        <div class="content-part">
                            <img src="{{ asset('public/cultivation/assets/images/services/icons/1.png') }}" alt="">
                            <h4 class="title"><a href="{{ route('allNotices') }}">Notice Board</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item overly2">
                        <img src="{{ asset('public/cultivation/assets/images/services/1.jpg') }}" alt="">
                        <div class="content-part">
                            <img src="{{ asset('public/cultivation/assets/images/services/icons/2.png') }}" alt="">
                            <h4 class="title"><a href="{{ route('internalResult') }}">Result</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item overly3">
                        <img src="{{ asset('public/cultivation/assets/images/services/1.jpg') }}" alt="">
                        <div class="content-part">
                            <img src="{{ asset('public/cultivation/assets/images/services/icons/3.png') }}" alt="">
                            <h4 class="title"><a href="{{ route('newExamSchedule') }}">Exam Routine</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-item overly4">
                        <img src="{{ asset('public/cultivation/assets/images/services/1.jpg') }}" alt="">
                        <div class="content-part">
                            <img src="{{ asset('public/cultivation/assets/images/services/icons/1.png') }}" alt="">
                            <h4 class="title"><a href="{{ route('imagePage') }}">Gallery</a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 5: Welcome / About Institution --}}
        <div id="rs-about-welcome" class="pt-72 pb-64 md-pt-54 md-pb-42" style="background: #ffffff;">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-6 pr-50 md-pr-15 md-mb-40">
                        <div class="about-img-wrap" style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 16px 36px rgba(16, 44, 99, 0.12); border: 4px solid #f0f6fc;">
                            <img src="{{ $welcomeImage }}" alt="{{ $welcomeTitle }}" style="width: 100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sec-title">
                            <div class="sub-title primary">ABOUT OUR INSTITUTION</div>
                            <h2 class="title mb-20" style="color: #102c63; font-size: 32px; font-weight: 800; line-height: 1.2;">{{ $welcomeTitle }}</h2>
                            @if($welcomeSubHeading)
                                <h4 style="color: #21a7d0; font-size: 18px; font-weight: 700; margin-bottom: 16px;">{{ $welcomeSubHeading }}</h4>
                            @endif
                            <p class="desc" style="color: #4a5f7a; font-size: 16px; line-height: 1.8; margin-bottom: 24px;">{{ $welcomeDetails }}</p>
                            <div class="btn-part">
                                <a class="readon2" href="{{ route('institutePage') }}">Learn More About Us &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 6: At a Glance / Statistics --}}
        @if($overviewMetrics->isNotEmpty())
        <div id="rs-at-a-glance" class="pt-50 pb-50" style="background: #f4f8fc; border-top: 1px solid #e1edf7; border-bottom: 1px solid #e1edf7;">
            <div class="container">
                <div class="sec-title text-center mb-32">
                    <div class="sub-title primary">AT A GLANCE</div>
                    <h2 class="title mb-0">Institutional Key Statistics</h2>
                </div>
                <div class="row {{ $overviewMetrics->count() <= 2 ? 'justify-content-center' : '' }}">
                    @foreach($overviewMetrics as $metric)
                        <div class="col-6 col-md-4 col-lg-2 mb-20">
                            <div class="stat-card" data-source="{{ $metric['source'] }}" style="background: #ffffff; border: 1px solid #d4e2f0; border-radius: 12px; padding: 22px 16px; box-shadow: 0 8px 24px rgba(16, 44, 99, 0.06); height: 100%; text-align: center;">
                                <div class="stat-icon" style="width: 48px; height: 48px; border-radius: 12px; background: #eef6fc; color: #21a7d0; display: flex; align-items: center; justify-content: center; font-size: 20px; margin: 0 auto 14px;">
                                    <i class="fa {{ $metric['icon'] }}"></i>
                                </div>
                                <div class="stat-body">
                                    <h2 class="number" style="font-size: 32px; font-weight: 800; color: #102c63; line-height: 1.1; margin: 0 0 2px;">{{ $metric['value'] }}</h2>
                                    <h4 class="title mb-0" style="font-size: 14px; font-weight: 700; color: #4b6382; text-transform: uppercase; letter-spacing: 0.5px;">{{ $metric['label'] }}</h4>
                                    <small style="display: block; color: #8aa1bb; font-size: 10px; margin-top: 8px;">{{ $metric['real'] ? ($metric['source'] === 'DERIVED' ? 'Since establishment' : 'Institution records') : 'Institutional profile' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @php
            $hasRealLeadershipMessage = collect($leadershipCards)->contains(fn ($l) => filled($l['message'] ?? null));
            $leadershipHeadingText = $hasRealLeadershipMessage ? 'Leadership Messages' : 'Head of Institution';
        @endphp
        @if($leadershipCards->isNotEmpty())
        <div class="principal-feature-section pt-50 pb-56 md-pt-36 md-pb-36" style="background: #ffffff;">
            <div class="container">
                <div class="leadership-section-title sec-title text-center mb-32">
                    <div class="sub-title primary">INSTITUTION LEADERSHIP</div>
                    <h2 class="title mb-0">{{ $leadershipHeadingText }}</h2>
                </div>
                <div class="row justify-content-center">
                    @foreach($leadershipCards as $leader)
                        <div class="{{ $leadershipCards->count() === 1 ? 'col-lg-8 col-xl-7 mx-auto' : 'col-lg-6' }} mb-4 mb-lg-0">
                            <article class="leadership-profile-card" data-source="{{ $leader['is_demo'] ? 'DEMO' : 'REAL' }}" style="background: #ffffff; border: 1px solid #dce9f4; border-radius: 12px; box-shadow: 0 8px 24px rgba(16, 44, 99, 0.07); overflow: hidden; height: 100%;">
                                <div class="leadership-profile-body">
                                    <div class="leadership-meta-horizontal">
                                        <img class="leadership-portrait" src="{{ $leader['avatar'] }}" alt="Photo of {{ $leader['name'] }}" loading="lazy">
                                        <div class="leadership-copy">
                                            <span class="leadership-role-label">{{ $leader['label'] }}</span>
                                            <h4>{{ $leader['name'] }}</h4>
                                            @if($leader['role'])<p class="designation">{{ $leader['role'] }}</p>@endif
                                            <p class="desc">{{ \Illuminate\Support\Str::limit($leader['message'], 220, '...') }}</p>
                                            <div class="leadership-cta">
                                                @if(!$leader['is_demo'])<a class="readon2" href="{{ $leader['route'] }}" aria-label="Read full message for {{ $leader['name'] }}">Read Full Message &rarr;</a>@else<span class="leadership-read-more">Leadership Profile</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="latest-notice-wrap md-pb-42">
            <div class="container">
                <div class="latest-notice-modern">
                    <div class="notice-head d-flex justify-content-between align-items-center mb-16">
                        <h3 class="m-0" style="color: #102c63; font-size: 24px; font-weight: 800;">Latest Notice</h3>
                        <a class="all-notice-btn" href="{{ route('allNotices') }}">All Notices &rarr;</a>
                    </div>
                    <div class="notice-shell">
                        @forelse(($noticeBoard ?? collect())->take(5) as $ntc)
                            @php
                                $nDate = $ntc->created_at;
                                $fileName = !empty($ntc->attachment) ? basename((string)$ntc->attachment) : '';
                                $fileHref = app(\App\Services\PublicMediaUrl::class)->notice($ntc->attachment);
                            @endphp
                            <div class="notice-item">
                                <div class="date-box">
                                    <div class="day">{{ $nDate ? $nDate->format('d') : '--' }}</div>
                                    <div class="mon">{{ $nDate ? $nDate->format('M') : '---' }}</div>
                                </div>
                                <h4 class="notice-title">{{ $ntc->headline }}</h4>
                                <div class="notice-actions">
                                    @if($fileHref)<span class="notice-file-pill">File</span>@endif
                                    <a class="notice-btn" href="{{ route('notice.show', $ntc) }}" data-public-notice-open="{{ $ntc->id }}" aria-haspopup="dialog" aria-controls="public-notice-dialog" aria-label="View notice: {{ $ntc->headline }}"><i class="fa fa-eye"></i> View</a>
                                    @if($fileHref)
                                        <a class="notice-btn" href="{{ $fileHref }}" download="{{ $fileName }}"><i class="fa fa-download"></i> File</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="notice-empty-state text-center py-4 px-3" style="background: #ffffff; border: 1px dashed #b8d4e8; border-radius: 10px; padding: 24px 18px;">
                                <i class="fa fa-bell-o mb-2" style="font-size: 28px; color: #21a7d0; display: block;"></i>
                                <span style="font-size: 15px; font-weight: 700; color: #102c63; display: block; margin-bottom: 2px;">Notice Board</span>
                                <span style="font-size: 14px; color: #5c748d;">No notices are currently published.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-56 md-pb-38">
            <div class="container">
                <div class="home-info-grid">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="info-card">
                                <div class="info-card-head">Admission Info</div>
                                <div class="info-card-body">
                                    <div class="info-card-row">
                                        <img src="{{ asset('public/img/forms.jpg') }}" alt="Admission" onerror="this.onerror=null;this.src='{{ asset('public/cultivation/assets/images/services/icons/2.png') }}';">
                                        <ul>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('supportPage') }}">Admission Information</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="info-card">
                                <div class="info-card-head">Institute Info</div>
                                <div class="info-card-body">
                                    <div class="info-card-row">
                                        <img src="{{ asset('public/img/institute.jpg') }}" alt="Institute" onerror="this.onerror=null;this.src='{{ asset('public/cultivation/assets/images/services/icons/1.png') }}';">
                                        <ul>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('institutePage') }}">About Us</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('teacherPage') }}">Teacher Directory</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('staffPage') }}">Staff Directory</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('headOfInstituteMessagePage') }}">Head of Institute Message</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('comitteePage') }}">Governing Body</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('exprincipalPage') }}">Former Heads of Institution</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <div class="info-card">
                                <div class="info-card-head">Academic</div>
                                <div class="info-card-body">
                                    <div class="info-card-row">
                                        <img src="{{ asset('public/img/academic.png') }}" alt="Academic" onerror="this.onerror=null;this.src='{{ asset('public/cultivation/assets/images/services/icons/3.png') }}';">
                                        <ul>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('newSemister') }}">Semester Plan</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('newSyllabus') }}">Syllabus</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('newClassSchedule') }}">Class Routine</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('newExamSchedule') }}">Exam Routine</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="info-card-head">Student Corner</div>
                                <div class="info-card-body">
                                    <div class="info-card-row">
                                        <img src="{{ asset('public/img/studentCorner.png') }}" alt="Student corner" onerror="this.onerror=null;this.src='{{ asset('public/cultivation/assets/images/services/icons/1.png') }}';">
                                        <ul>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('student') }}">Student Database</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('placementCellView') }}">Placement Cell</a></li>
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('jobNeedyStudentView') }}">Job Seekers</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rs-degree style1 modify gray-bg pt-82 pb-70 md-pt-56 md-pb-40">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="sec-title wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                            <div class="sub-title primary">Academic Services</div>
                            <h2 class="title mb-0">Academic Information</h2>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ $resolveGalleryImage($thirdImage) ?: asset('public/cultivation/assets/images/degrees/1.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Syllabus</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('newSyllabus') }}">Academic Syllabus</a></h4>
                                <p class="desc">Course guidelines, syllabus and class planning are available with updated records.</p>
                                <div class="btn-part"><a href="{{ route('newSyllabus') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ $resolveGalleryImage($fourthImage) ?: asset('public/cultivation/assets/images/degrees/2.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Class Routine</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('newClassSchedule') }}">Class Routine</a></h4>
                                <p class="desc">Daily routine, exam schedule and session planning are maintained dynamically.</p>
                                <div class="btn-part"><a href="{{ route('newClassSchedule') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ asset('public/cultivation/assets/images/degrees/3.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Result Archive</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('internalResult') }}">Result Archive</a></h4>
                                <p class="desc">Internal result records and progress are available for students and guardians.</p>
                                <div class="btn-part"><a href="{{ route('internalResult') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ asset('public/cultivation/assets/images/degrees/4.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Placement Cell</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('placementCellView') }}">Placement Cell</a></h4>
                                <p class="desc">Career opportunities, announcements and placement support are available here.</p>
                                <div class="btn-part"><a href="{{ route('placementCellView') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ asset('public/cultivation/assets/images/degrees/5.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Student Corner</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('student') }}">Student Corner</a></h4>
                                <p class="desc">Student information, guidance and archives are maintained in this section.</p>
                                <div class="btn-part"><a href="{{ route('student') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 11: Faculty / Teachers --}}
        @if(($facultyPreview ?? collect())->isNotEmpty())
        <section class="faculty-preview-section pt-40 pb-40 md-pt-30 md-pb-30" style="background: #ffffff;">
            <div class="container">
                <div class="faculty-preview-head mb-24">
                    <div class="sec-title">
                        <div class="sub-title primary">Our Faculty</div>
                        <h2 class="title mb-0">{{ $facultyLabel }}</h2>
                    </div>
                    <a class="readon2" href="{{ route('teacherPage') }}">View All Teachers &rarr;</a>
                </div>
                <div class="row faculty-preview-grid {{ $facultyGridJustify }}">
                    @foreach($facultyPreview as $teacher)
                        @php
                            $teacherName = $teacher->is_demo ?? false ? $teacher->name : trim(($teacher->firstName ?? '') . ' ' . ($teacher->lastName ?? ''));
                            $teacherDesignation = $teacher->is_demo ?? false ? $teacher->designation : \App\Models\TeacherManagement::getDesignationName($teacher->designation ?? $teacher->designation_id ?? null);
                            $teacherSubject = ($teacher->is_demo ?? false) ? ($teacher->subject ?? null) : ($teacher->subject ?? $teacher->department ?? null);
                            $teacherPhoto = $teacher->is_demo ?? false ? asset($teacher->photo) : $resolveTeacherPhoto($teacher);
                            $teacherUrl = $teacher->is_demo ?? false ? null : route('teacher.show', ['id' => $teacher->id]);
                        @endphp
                        @if($teacherName)
                            <div class="{{ $facultyColClass }}">
                                <article class="faculty-card" data-source="{{ $teacher->is_demo ? 'DEMO' : 'REAL' }}">
                                    @if($teacherUrl)<a class="faculty-photo-link" href="{{ $teacherUrl }}" aria-label="View {{ $teacherName }} profile">@else<a class="faculty-photo-link" href="{{ route('teacherPage') }}" aria-label="View faculty directory">@endif
                                        <img src="{{ $teacherPhoto }}" alt="Photo of {{ $teacherName }}" loading="lazy">
                                    </a>
                                    <div class="faculty-card-body text-center">
                                        <h3>@if($teacherUrl)<a href="{{ $teacherUrl }}">{{ $teacherName }}</a>@else{{ $teacherName }}@endif</h3>
                                        @if($teacherDesignation)<p class="faculty-designation">{{ $teacherDesignation }}</p>@endif
                                        @if($teacherSubject)<p class="faculty-subject">{{ $teacherSubject }}</p>@endif
                                    </div>
                                </article>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @php
            $galleryPreviewItems = ($gallery ?? collect())->filter(fn ($item) => $resolveGalleryImage($item))->take(5);
            $galleryIsDemo = $galleryPreviewItems->isEmpty();
            if ($galleryIsDemo) {
                $galleryPreviewItems = collect(config('cultivation_demo.gallery', []))->map(fn ($item) => (object) [
                    'title' => $item['title'], 'description' => $item['description'],
                    'demo_image' => $item['image'], 'created_at' => null,
                ])->filter(fn ($item) => $resolveGalleryImage($item))->take(5);
            }
        @endphp
        @if($galleryPreviewItems->isNotEmpty())
        <div class="pt-64 pb-64 md-pt-42 md-pb-42">
            <div class="container">
                <div class="ref-photo-gallery" data-source="{{ $galleryIsDemo ? 'DEMO' : 'REAL' }}">
                    <div class="gallery-head">
                        <div class="head-copy">
                            <span>{{ $galleryIsDemo ? 'Learning in Focus' : 'Campus Memories' }}</span>
                            <h2>Photo Gallery</h2>
                            <p class="head-note">{{ $galleryIsDemo ? 'Illustrations of learning spaces, academic activities and student development.' : 'A curated look at student achievements, events, and everyday campus moments.' }}</p>
                            <div class="gallery-meta"><i class="fa fa-camera"></i> {{ $galleryPreviewItems->count() > 0 ? $galleryPreviewItems->count() : 6 }} highlighted photos</div>
                        </div>
                        <a class="view-all-btn" href="{{ route('imagePage') }}">View All <i class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="gallery-grid gallery-count-{{ $galleryPreviewItems->count() }}">
                        @foreach($galleryPreviewItems as $img)
                            @php
                                $galleryImageSrc = $resolveGalleryImage($img);
                                $galleryTitle = trim((string) ($img->title ?? ''));
                                $galleryDesc = trim((string) ($img->description ?? ''));
                                $galleryDate = optional($img->created_at)->format('d M Y');
                            @endphp
                            @if($galleryImageSrc)<button type="button"
                               class="gallery-tile gallery-modal-trigger"
                               data-image="{{ $galleryImageSrc }}"
                               data-title="{{ $galleryTitle }}"
                               data-description="{{ $galleryDesc }}"
                               data-date="{{ $galleryDate }}"
                               aria-label="{{ $galleryTitle ? 'Open ' . $galleryTitle : 'Open photo details' }}">
                                <img src="{{ $galleryImageSrc }}" alt="{{ $galleryTitle }}" loading="lazy">
                                <span class="gallery-shade"></span>
                                <span class="gallery-plus"><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                                <span class="gallery-mini-date">{{ $galleryDate }}</span>
                                @if($galleryTitle)<span class="gallery-mini-title">{{ $galleryTitle }}</span>@endif
                            </button>@endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="modal fade gallery-preview-modal" id="galleryPreviewModal" tabindex="-1" role="dialog" aria-labelledby="galleryPreviewTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="galleryPreviewTitle">Gallery Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img id="galleryPreviewImage" class="preview-image" alt="Preview image">
                        <div class="preview-info">
                            <h4 id="galleryPreviewHeading">Gallery Image</h4>
                            <span class="preview-date" id="galleryPreviewDate"></span>
                            <p class="preview-desc" id="galleryPreviewDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rs-cta style2">
            <div class="partition-bg-wrap home2">
                <div class="container">
                    <div class="row y-bottom">
                        <div class="col-lg-6 pb-50 md-pt-100 md-pb-100">
                            <div class="video-wrap">
                                <div class="popup-videos" aria-hidden="true"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 pl-62 pt-134 pb-150 md-pl-15 md-pt-45 md-pb-50">
                            <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                                <h2 class="title mb-16">Admission Information</h2>
                                <div class="desc">Contact the institution for current admission information and requirements.</div>
                            </div>
                            <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                                <a class="readon2" href="{{ route('supportPage') }}">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('frontend.cultivation-v2.partials._footer')

    {{-- keep search modal --}}
    <div aria-hidden="true" class="modal fade search-modal" role="dialog" tabindex="-1">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="flaticon-cross"></span>
        </button>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="search-block clearfix">
                    <form>
                        <div class="form-group">
                            <input class="form-control" placeholder="Search Here..." type="text">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/cultivation/assets/js/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/jquery.nav.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/skill.bars.jquery.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/jquery.mb.YTPlayer.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/contact.form.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/main.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/homepage-responsive.js') }}"></script>
    <script>
        (function () {
            var galleryGrid = document.querySelector('.ref-photo-gallery .gallery-grid');
            if (!galleryGrid) {
                return;
            }

            var modalElement = document.getElementById('galleryPreviewModal');
            var imageElement = document.getElementById('galleryPreviewImage');
            var titleElement = document.getElementById('galleryPreviewHeading');
            var titleBarElement = document.getElementById('galleryPreviewTitle');
            var dateElement = document.getElementById('galleryPreviewDate');
            var descElement = document.getElementById('galleryPreviewDescription');

            galleryGrid.addEventListener('click', function (event) {
                var trigger = event.target.closest('.gallery-modal-trigger');
                if (!trigger) {
                    return;
                }

                event.preventDefault();

                var imageSrc = trigger.getAttribute('data-image') || '';
                var title = trigger.getAttribute('data-title') || 'Gallery Image';
                var description = trigger.getAttribute('data-description') || 'Memorable moment captured from our campus activities.';
                var date = trigger.getAttribute('data-date') || '';

                imageElement.src = imageSrc;
                imageElement.alt = title;
                titleElement.textContent = title;
                titleBarElement.textContent = title;
                dateElement.textContent = date ? ('Published: ' + date) : '';
                descElement.textContent = description;

                if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
                    window.jQuery(modalElement).modal('show');
                }
            });
        })();
    </script>
@include('frontend.notice._viewer', ['viewerNotices' => ($noticeBoard ?? collect())->take(5)])
</body>
</html>
