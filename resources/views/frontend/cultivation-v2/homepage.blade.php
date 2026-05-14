<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $config = App\Models\ServerConfig::first();
        $principalSpeechModel = App\Models\PrincipalSpeech::first();
        $studentCount = App\Models\StudentManagement::count();
        $teacherCount = App\Models\TeacherManagement::count() + App\Models\StaffManagement::count();
        $foundedYear = !empty($config?->foundedYear) ? $config->foundedYear : '1997';
        $safeGallery = $gallery ?? collect();
        $firstImage = $safeGallery->get(0);
        $secondImage = $safeGallery->get(1);
        $thirdImage = $safeGallery->get(2);
        $fourthImage = $safeGallery->get(3);

        $resolveGalleryImage = function ($item, $fallback) {
            if (!$item || empty($item->avatar)) {
                return asset($fallback);
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

            return asset($fallback);
        };

        $sliderItems = ($sliderData ?? collect())->take(5);
        if ($sliderItems->count() === 0) {
            $sliderItems = collect([
                (object) ['headLine' => 'World Leading University', 'detail' => 'Enter to learn and leave to serve', 'avatar' => ''],
            ]);
        }

        $principalName = !empty($config?->principalName) ? $config->principalName : 'Engr. Abu Yousuf';
        $principalRole = !empty($config?->principalDesignation) ? $config->principalDesignation : 'Principal';
        $principalLead = !empty($config?->principalImportantSpeech)
            ? $config->principalImportantSpeech
            : (!empty($principalSpeechModel?->importantSpeech) ? $principalSpeechModel->importantSpeech : 'We want to build good students as well as good people.');
        $principalBody = !empty($config?->principalGeneralSpeech)
            ? $config->principalGeneralSpeech
            : (!empty($principalSpeechModel?->generalSpeech) ? $principalSpeechModel->generalSpeech : 'Our institution is committed to quality education and character building through discipline, dedication and innovation.');
        $principalAvatar = !empty($config?->avatar)
            ? url('/public/upload/image/cultivation/' . rawurlencode(basename($config->avatar)))
            : asset('public/avatar.png');
    @endphp

    <title>{{ !empty($config?->instituteName) ? $config->instituteName : 'Cultivation High School' }}</title>

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
            min-height: 460px;
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            padding: 25px !important;
        }

        .rs-slider.style1 .slider-content::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(17, 41, 88, 0.45);
        }

        .rs-slider.style1 .slider-content .container {
            position: relative;
            z-index: 2;
            padding-top: 150px;
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
            grid-template-columns: 78px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            border: 1px solid #e2edf6;
            border-radius: 10px;
            padding: 8px 10px;
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
            font-size: 25px;
            line-height: 1.2;
            color: #132e63;
            font-weight: 700;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
    </style>
</head>
<body class="home-style2">
    @include('frontend.cultivation-v2.partials._header')

    <div class="main-content">
        <div class="rs-slider style1">
            <div class="rs-carousel owl-carousel" data-loop="true" data-items="1" data-margin="0" data-autoplay="true" data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800" data-dots="false" data-nav="false" data-nav-speed="false" data-center-mode="false" data-mobile-device="1" data-mobile-device-nav="false" data-mobile-device-dots="false" data-ipad-device="1" data-ipad-device-nav="false" data-ipad-device-dots="false" data-ipad-device2="1" data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="1" data-md-device-nav="true" data-md-device-dots="false">
                @foreach($sliderItems as $slide)
                    @php
                        $slideImage = !empty($slide->avatar)
                            ? url('/public/upload/image/webHomepage/' . rawurlencode(basename($slide->avatar)))
                            : asset('public/cultivation/assets/images/slider/h2-1.jpg');
                        $slideHeading = !empty($slide->headLine) ? $slide->headLine : 'World Leading University';
                        $slideDetail = !empty($slide->detail) ? $slide->detail : (!empty($config?->instituteName) ? $config->instituteName : 'Cultivation University');
                    @endphp
                    <div class="slider-content" style="background-image:url('{{ $slideImage }}');">
                        <div class="container">
                            <div class="sl-sub-title white-color wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="2000ms">{{ $slideHeading }}</div>
                            <h1 class="sl-title white-color wow fadeInRight" data-wow-delay="600ms" data-wow-duration="2000ms">{{ $slideDetail }}</h1>
                            <div class="sl-btn wow fadeInUp" data-wow-delay="900ms" data-wow-duration="2000ms">
                                <a class="readon2 banner-style" href="{{ route('institutePage') }}">Discover More</a>
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
                            <h4 class="title"><a href="{{ route('student') }}">Student Life</a></h4>
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
                            <h4 class="title"><a href="{{ route('newExamSchedule') }}">Academics</a></h4>
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

        <div id="rs-about" class="rs-about style2 pt-72 pb-56 md-pt-54 md-pb-38">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 pr-65 md-pr-15 md-mb-50">
                        <div class="about-intro">
                            <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                                <div class="sub-title primary">About {{ !empty($config?->instituteShortName) ? $config->instituteShortName : 'Institute' }}</div>
                                <h2 class="title mb-21 white-color">{{ !empty($insData?->insHeadline) ? $insData->insHeadline : 'Welcome to our institute' }}</h2>
                                <div class="desc big white-color">{{ \Illuminate\Support\Str::limit(strip_tags((string)($insData->insDetails ?? '')), 180, '...') }}</div>
                            </div>
                            <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                                <a class="readon2" href="{{ route('institutePage') }}">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 lg-pl-0 ml--25 md-ml-0">
                        <div class="row rs-counter couter-area mb-40">
                            <div class="col-md-4">
                                <div class="counter-item one">
                                    <h2 class="number">{{ $studentCount }}</h2>
                                    <h4 class="title mb-0">Students</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="counter-item two">
                                    <h2 class="number">{{ $teacherCount }}</h2>
                                    <h4 class="title mb-0">Teacher & Staff</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="counter-item three">
                                    <h2 class="number">{{ $foundedYear }}</h2>
                                    <h4 class="title mb-0">Founded</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row grid-area">
                            <div class="col-md-6 sm-mb-30">
                                <div class="image-grid">
                                    <img src="{{ $resolveGalleryImage($firstImage, 'public/cultivation/assets/images/about/style2/grid1.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="image-grid">
                                    <img src="{{ $resolveGalleryImage($secondImage, 'public/cultivation/assets/images/about/style2/grid2.jpg') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="principal-feature-section pt-10 pb-64 md-pt-10 md-pb-42">
            <div class="container">
                <div class="principal-feature-card">
                    <div class="principal-feature-head">
                        <h3>Head of Institute Message</h3>
                        <a class="readon2" href="{{ route('headOfInstituteMessagePage') }}">Read Full Message</a>
                    </div>
                    <div class="principal-feature-body">
                        <div class="principal-meta">
                            <img src="{{ $principalAvatar }}" alt="Principal avatar" onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';">
                            <div>
                                <h4>{{ $principalName }}</h4>
                                <p>{{ $principalRole }}</p>
                            </div>
                        </div>
                        <blockquote>"{{ $principalLead }}"</blockquote>
                        <p class="desc">{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string)$principalBody))), 420, '...') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="latest-notice-wrap md-pb-42">
            <div class="container">
                <div class="latest-notice-modern">
                    <div class="notice-head">
                        <h3>Latest Notice</h3>
                        <a class="all-notice-btn" href="{{ route('allNotices') }}">All Notice</a>
                    </div>
                    <div class="notice-shell">
                        @forelse(($noticeBoard ?? collect())->take(5) as $ntc)
                            @php
                                $nDate = $ntc->created_at;
                                $fileName = !empty($ntc->attachment) ? basename((string)$ntc->attachment) : '';
                                $fileHref = !empty($fileName) ? url('/public/upload/notice/' . rawurlencode($fileName)) : '';
                            @endphp
                            <div class="notice-item">
                                <div class="date-box">
                                    <div class="day">{{ $nDate ? $nDate->format('d') : '--' }}</div>
                                    <div class="mon">{{ $nDate ? $nDate->format('M') : '---' }}</div>
                                </div>
                                <h4 class="notice-title">{{ $ntc->headline }}</h4>
                                <div class="notice-actions">
                                    <a class="notice-btn" href="{{ route('allNotices') }}"><i class="fa fa-eye"></i> View</a>
                                    @if($fileHref)
                                        <a class="notice-btn" href="{{ $fileHref }}" download="{{ $fileName }}"><i class="fa fa-download"></i> File</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="notice-item">
                                <div class="date-box">
                                    <div class="day">--</div>
                                    <div class="mon">---</div>
                                </div>
                                <h4 class="notice-title">No notices available right now.</h4>
                                <div class="notice-actions">
                                    <a class="notice-btn" href="{{ route('allNotices') }}"><i class="fa fa-eye"></i> View</a>
                                </div>
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
                                            <li><i class="fa fa-angle-right"></i><span class="info-item-text">Honors Admission</span></li>
                                            <li><i class="fa fa-angle-right"></i><span class="info-item-text">XI Class Admission</span></li>
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
                                            <li><i class="fa fa-angle-right"></i><a href="{{ route('newSemister') }}">Semister Plan</a></li>
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
                                            <li><i class="fa fa-angle-right"></i><span class="info-item-text">X-Student Archive</span></li>
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
                            <div class="sub-title primary">Academic categories</div>
                            <h2 class="title mb-0">Successfully Complete A Degree at {{ !empty($config?->instituteShortName) ? $config->instituteShortName : 'Our Institute' }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ $resolveGalleryImage($thirdImage, 'public/cultivation/assets/images/degrees/1.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Undergraduate</h4></div>
                            <div class="content-part">
                                <h4 class="title"><a href="{{ route('newSyllabus') }}">Undergraduate</a></h4>
                                <p class="desc">Course guidelines, syllabus and class planning are available with updated records.</p>
                                <div class="btn-part"><a href="{{ route('newSyllabus') }}">Read More</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="degree-wrap">
                            <img src="{{ $resolveGalleryImage($fourthImage, 'public/cultivation/assets/images/degrees/2.jpg') }}" alt="">
                            <div class="title-part"><h4 class="title">Routine</h4></div>
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

        @php
            $galleryPreviewItems = ($gallery ?? collect())->take(6);
        @endphp
        <div class="pt-64 pb-64 md-pt-42 md-pb-42">
            <div class="container">
                <div class="ref-photo-gallery">
                    <div class="gallery-head">
                        <div class="head-copy">
                            <span>Campus Memories</span>
                            <h2>Photo Gallery</h2>
                            <p class="head-note">A curated look at student achievements, events, and everyday campus moments.</p>
                            <div class="gallery-meta"><i class="fa fa-camera"></i> {{ $galleryPreviewItems->count() > 0 ? $galleryPreviewItems->count() : 6 }} highlighted photos</div>
                        </div>
                        <a class="view-all-btn" href="{{ route('imagePage') }}">View All <i class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="gallery-grid">
                        @forelse($galleryPreviewItems as $img)
                            @php
                                $galleryImageSrc = $resolveGalleryImage($img, 'public/cultivation/assets/images/blog/style2/1.jpg');
                                $galleryTitle = trim((string) ($img->title ?? 'Gallery Image'));
                                $galleryDesc = trim((string) ($img->description ?? 'Memorable moment captured from our campus activities.'));
                                $galleryDate = optional($img->created_at)->format('d M Y');
                            @endphp
                            <button type="button"
                               class="gallery-tile gallery-modal-trigger"
                               data-image="{{ $galleryImageSrc }}"
                               data-title="{{ $galleryTitle }}"
                               data-description="{{ $galleryDesc }}"
                               data-date="{{ $galleryDate }}"
                               aria-label="Open photo details">
                                <img src="{{ $galleryImageSrc }}" alt="{{ $galleryTitle }}">
                                <span class="gallery-shade"></span>
                                <span class="gallery-plus"><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                                <span class="gallery-mini-date">{{ $galleryDate }}</span>
                                <span class="gallery-mini-title">{{ $galleryTitle }}</span>
                            </button>
                        @empty
                            @for($i = 0; $i < 6; $i++)
                                <button type="button"
                                        class="gallery-tile gallery-modal-trigger"
                                        data-image="{{ asset('public/cultivation/assets/images/blog/style2/1.jpg') }}"
                                        data-title="Gallery Image"
                                        data-description="Memorable moment captured from our campus activities."
                                        data-date=""
                                        aria-label="Open photo details">
                                    <img src="{{ asset('public/cultivation/assets/images/blog/style2/1.jpg') }}" alt="Gallery image">
                                    <span class="gallery-shade"></span>
                                    <span class="gallery-plus"><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                                    <span class="gallery-mini-date"></span>
                                    <span class="gallery-mini-title">Gallery Image</span>
                                </button>
                            @endfor
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

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
                        <img id="galleryPreviewImage" class="preview-image" src="" alt="Preview image">
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
                                <a class="popup-videos" href="https://www.youtube.com/watch?v=atMUy_bPoQI">
                                    <i class="fa fa-play"></i>
                                    <h4 class="title mb-0">Take a Video Tour at {{ !empty($config?->instituteShortName) ? $config->instituteShortName : 'Institute' }}</h4>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 pl-62 pt-134 pb-150 md-pl-15 md-pt-45 md-pb-50">
                            <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                                <h2 class="title mb-16">Admission Open</h2>
                                <div class="desc">Apply for admission and check the latest notices, routines and institutional information from the official portal.</div>
                            </div>
                            <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                                <a class="readon2" href="{{ route('supportPage') }}">Apply Now</a>
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
    <script src="{{ asset('public/cultivation/assets/js/rsmenu-main.js') }}"></script>
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
</body>
</html>

