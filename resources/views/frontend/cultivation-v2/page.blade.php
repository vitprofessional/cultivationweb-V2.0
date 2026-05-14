<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $config = App\Models\ServerConfig::first();
    @endphp

    <title>
        @if(!empty($config->instituteName))
            {{ $config->instituteName }} | @yield('fronttitle')
        @else
            Jahanara Ayub Academy | @yield('fronttitle')
        @endif
    </title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/cultivation/assets/images/fav.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/fonts/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/fonts/linea-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/off-canvas.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/rsmenu-main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/rs-spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/cultivation/assets/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('public/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('public/lightbox/fancybox/jquery.fancybox.min.css') }}">

    <script src="https://kit.fontawesome.com/163dbb3d41.js" crossorigin="anonymous"></script>

    <style>
        :root {
            --edu-primary: #21a7d0;
            --edu-light-text: #fff;
            --edu-secondary: #273c66;
            --edu-heading: #112958;
            --edu-light-bg: #f3f8f9;
            --edu-soft-accent: #e7f9fb;
            --edu-text: #505050;
            --edu-muted: #6d7d8b;
            --edu-heading-font: 'Nunito', sans-serif;
            --edu-body-font: 'Rubik', sans-serif;
            --edu-space-1: 8px;
            --edu-space-2: 12px;
            --edu-space-3: 16px;
            --edu-space-4: 24px;
            --edu-space-5: 32px;
            --edu-space-6: 40px;
            --edu-radius-sm: 8px;
            --edu-radius-md: 12px;
            --edu-radius-lg: 14px;
        }

        body.home-style2 {
            background: var(--edu-light-bg);
            color: var(--edu-text);
            font-family: var(--edu-body-font);
            font-size: 15px;
            line-height: 1.75;
            letter-spacing: 0;
        }

        .home-style2 h1,
        .home-style2 h2,
        .home-style2 h3,
        .home-style2 h4,
        .home-style2 h5,
        .home-style2 h6 {
            font-family: var(--edu-heading-font);
            color: var(--edu-heading);
            line-height: 1.25;
            font-weight: 700;
            margin-bottom: var(--edu-space-3);
        }

        .home-style2 p,
        .home-style2 li,
        .home-style2 td,
        .home-style2 th,
        .home-style2 label,
        .home-style2 input,
        .home-style2 textarea,
        .home-style2 select,
        .home-style2 small {
            font-family: var(--edu-body-font);
            color: var(--edu-text);
        }

        .home-style2 a {
            color: var(--edu-primary);
        }

        .home-style2 a:hover,
        .home-style2 a:focus {
            color: var(--edu-secondary);
        }

        .edu-content-wrap {
            min-height: 55vh;
            padding: var(--edu-space-5) 0 var(--edu-space-6);
        }

        .edu-main-card {
            background: #fff;
            border-radius: var(--edu-radius-lg);
            border: 1px solid #e6eef1;
            box-shadow: 0 10px 28px rgba(39, 60, 102, 0.08);
            overflow: hidden;
        }

        .edu-main-inner {
            padding: var(--edu-space-4);
        }

        .edu-main-inner > .row {
            row-gap: var(--edu-space-4);
        }

        .edu-main-inner .card {
            border-radius: var(--edu-radius-md);
            border-color: #e6eef1;
        }

        .edu-main-inner .card-header {
            font-family: var(--edu-heading-font);
            font-weight: 700;
        }

        .edu-main-inner .table thead th {
            font-family: var(--edu-heading-font);
            color: var(--edu-heading);
            border-bottom-color: #dceef4;
        }

        .edu-main-inner .table > :not(caption) > * > * {
            padding: 0.8rem 0.75rem;
        }

        .edu-main-inner .table-responsive {
            border: 1px solid #e6eef1;
            border-radius: var(--edu-radius-sm);
            overflow: auto;
        }

        .edu-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .edu-brand img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
        }

        .topbar-area .topbar-contact li i,
        .topbar-area .topbar-right li i {
            margin-right: 6px;
            color: var(--edu-primary);
        }

        .topbar-area {
            background: #f8fbfc;
            border-bottom: 1px solid #dceef4;
        }

        .topbar-area .topbar-contact li a,
        .topbar-area .topbar-right li,
        .topbar-area .topbar-right li span {
            color: var(--edu-light-text);
        }

        .edu-menu .navbar-nav .nav-link {
            color: #ffffff;
            font-weight: 600;
            font-family: var(--edu-heading-font);
            font-size: 15px;
            letter-spacing: 0.2px;
            padding: 20px 14px;
        }

        .edu-menu .navbar-nav .nav-link:hover,
        .edu-menu .navbar-nav .nav-link:focus {
            color: var(--edu-primary);
        }

        .edu-menu .dropdown-menu {
            border: 0;
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(20, 20, 25, 0.18);
        }

        .edu-menu .dropdown-item {
            color: var(--edu-secondary);
            font-weight: 500;
            font-size: 14px;
            padding: 9px 16px;
        }

        .edu-menu .dropdown-item:hover,
        .edu-menu .dropdown-item:focus {
            background: var(--edu-soft-accent);
            color: var(--edu-heading);
        }

        .edu-page-title {
            padding: 18px 24px;
            background: linear-gradient(90deg, var(--edu-secondary), var(--edu-heading));
            color: #fff;
            font-family: var(--edu-heading-font);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        /* Footer styles are centralized in frontend/cultivation-v2/partials/_footer.blade.php */

        .home-style2 .btn-success,
        .home-style2 .bg-success {
            background-color: var(--edu-primary) !important;
            border-color: var(--edu-primary) !important;
        }

        .home-style2 .btn,
        .home-style2 .btn-sm,
        .home-style2 .btn-lg {
            font-family: var(--edu-heading-font);
            letter-spacing: 0.1px;
        }

        .home-style2 .btn {
            border-radius: 8px;
            border-width: 1px;
            padding: 0.5rem 0.9rem;
            line-height: 1.2;
        }

        .home-style2 .btn-sm {
            padding: 0.36rem 0.72rem;
            font-size: 0.8rem;
        }

        .home-style2 .btn,
        .home-style2 .btn-sm,
        .home-style2 .btn-lg {
            font-weight: 700;
        }

        .home-style2 .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(33, 167, 208, 0.2);
        }

        .home-style2 .btn-light {
            color: var(--edu-secondary) !important;
            background-color: #ffffff !important;
            border-color: #cfe3ec !important;
        }

        .home-style2 .btn-light:hover,
        .home-style2 .btn-light:focus {
            color: var(--edu-heading) !important;
            background-color: var(--edu-soft-accent) !important;
            border-color: #b9d7e5 !important;
        }

        .home-style2 .btn-outline-light {
            color: var(--edu-secondary) !important;
            background-color: #ffffff !important;
            border-color: #b9d7e5 !important;
        }

        .home-style2 .btn-outline-light:hover,
        .home-style2 .btn-outline-light:focus {
            color: #ffffff !important;
            background-color: var(--edu-secondary) !important;
            border-color: var(--edu-secondary) !important;
        }

        .home-style2 .btn-secondary {
            color: #ffffff !important;
            background-color: var(--edu-secondary) !important;
            border-color: var(--edu-secondary) !important;
        }

        .home-style2 .btn-secondary:hover,
        .home-style2 .btn-secondary:focus {
            color: #ffffff !important;
            background-color: var(--edu-heading) !important;
            border-color: var(--edu-heading) !important;
        }

        .home-style2 .btn-outline-success {
            color: var(--edu-primary) !important;
            background-color: #ffffff !important;
            border-color: var(--edu-primary) !important;
        }

        .home-style2 .btn-outline-secondary {
            color: var(--edu-secondary) !important;
            background-color: #ffffff !important;
            border-color: #9bb6c7 !important;
        }

        .home-style2 .btn-outline-secondary:hover,
        .home-style2 .btn-outline-secondary:focus {
            color: #ffffff !important;
            background-color: var(--edu-secondary) !important;
            border-color: var(--edu-secondary) !important;
        }

        .home-style2 .btn-outline-success:hover,
        .home-style2 .btn-outline-success:focus {
            color: #fff !important;
            background-color: var(--edu-primary) !important;
            border-color: var(--edu-primary) !important;
        }

        .home-style2 .text-success {
            color: var(--edu-primary) !important;
        }

        .home-style2 .text-primary {
            color: var(--edu-primary) !important;
        }

        .home-style2 .text-secondary {
            color: var(--edu-secondary) !important;
        }

        .home-style2 .text-muted {
            color: var(--edu-muted) !important;
        }

        .home-style2 .badge.bg-success,
        .home-style2 .alert-success {
            background-color: var(--edu-primary) !important;
            border-color: var(--edu-primary) !important;
            color: #ffffff !important;
        }

        .home-style2 .principal-standalone,
        .home-style2 .principal-message-box {
            background: #ffffff;
            border: 1px solid #e3edf1;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(39, 60, 102, 0.06);
            overflow: hidden;
        }

        .home-style2 .principal-standalone .plain-heading,
        .home-style2 .principal-message-box .section-heading {
            background: linear-gradient(90deg, var(--edu-secondary), var(--edu-heading));
            color: #ffffff;
            padding: 0.7rem 0.9rem;
            font-family: var(--edu-heading-font);
            font-size: 0.95rem;
            font-weight: 700;
            margin: 0;
        }

        .home-style2 .principal-message-box .p-3,
        .home-style2 .principal-standalone .principal-caption {
            padding: 1rem;
        }

        .home-style2 .principal-message-box .btn,
        .home-style2 .principal-standalone .btn {
            width: 100%;
        }

        .home-style2 .principal-message-box .text-secondary,
        .home-style2 .principal-message-box .text-muted,
        .home-style2 .principal-standalone .text-muted {
            color: var(--edu-muted) !important;
        }

        /* Final UI polish: smooth interaction language and component consistency */
        .home-style2 .card,
        .home-style2 .btn,
        .home-style2 .nav-link,
        .home-style2 .dropdown-item,
        .home-style2 .form-control,
        .home-style2 .form-select,
        .home-style2 .table tbody tr,
        .home-style2 .list-group-item {
            transition: all 0.24s ease;
        }

        .home-style2 .edu-main-inner .card:hover {
            border-color: #cce8f2;
            box-shadow: 0 10px 24px rgba(33, 167, 208, 0.12);
            transform: translateY(-2px);
        }

        .home-style2 .edu-main-inner .table {
            border-color: #e2edf1;
        }

        .home-style2 .edu-main-inner .table tbody tr:hover {
            background: #f4fbfe;
        }

        .home-style2 .form-control,
        .home-style2 .form-select {
            border: 1px solid #d7e8ee;
            border-radius: var(--edu-radius-sm);
            min-height: 42px;
        }

        .home-style2 .form-control:focus,
        .home-style2 .form-select:focus {
            border-color: var(--edu-primary);
            box-shadow: 0 0 0 0.2rem rgba(33, 167, 208, 0.18);
        }

        .home-style2 .btn {
            border-radius: 8px;
            border-width: 1px;
        }

        .home-style2 .btn-success:hover,
        .home-style2 .btn-success:focus {
            background-color: #1692ba !important;
            border-color: #1692ba !important;
        }

        .home-style2 .edu-main-inner hr,
        .home-style2 hr {
            border-color: #dceef4;
            opacity: 1;
        }

        .home-style2 .list-group-item {
            border-color: #e6eef1;
        }

        .home-style2 .list-group {
            color: #fff;
        }



        .home-style2 .pagination .page-link {
            color: var(--edu-secondary);
            border-color: #dceef4;
        }

        .home-style2 .pagination .page-item.active .page-link,
        .home-style2 .pagination .page-link:hover {
            background-color: var(--edu-primary);
            border-color: var(--edu-primary);
            color: #ffffff;
        }

        /* Section title harmony for page-level headings used across existing views */
        .home-style2 .con-title h2,
        .home-style2 .hedingAbout,
        .home-style2 .home-section-title,
        .home-style2 .edu-main-inner h2:first-child {
            font-size: 30px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: 0.2px;
            margin-bottom: var(--edu-space-4);
            color: var(--edu-heading);
        }

        .home-style2 .con-title h2 span,
        .home-style2 .hedingAbout span,
        .home-style2 .home-section-title span {
            color: var(--edu-primary);
        }

        .home-style2 .edu-main-inner h3 {
            font-size: 24px;
        }

        .home-style2 .edu-main-inner h4 {
            font-size: 20px;
        }

        @media (max-width: 1199px) {
            .topbar-area .topbar-contact {
                display: flex;
                flex-wrap: wrap;
                gap: 10px 16px;
            }

            .topbar-area .topbar-right {
                display: flex;
                justify-content: flex-end;
            }
        }

        @media (max-width: 991px) {
            .edu-main-inner {
                padding: var(--edu-space-3);
            }

            .edu-page-title {
                font-size: 18px;
                padding: 14px 16px;
            }

            .edu-content-wrap {
                padding: var(--edu-space-3) 0 var(--edu-space-4);
            }

            .edu-menu .navbar-nav .nav-link {
                padding: 12px 0;
            }

            .edu-main-inner > .row {
                row-gap: var(--edu-space-3);
            }

            .home-style2 .con-title h2,
            .home-style2 .hedingAbout,
            .home-style2 .home-section-title,
            .home-style2 .edu-main-inner h2:first-child {
                font-size: 24px;
                margin-bottom: var(--edu-space-3);
            }

            .home-style2 .edu-main-inner h3 {
                font-size: 20px;
            }

            .home-style2 .edu-main-inner h4 {
                font-size: 18px;
            }

            .topbar-area {
                display: none;
            }

            .edu-content-wrap {
                min-height: auto;
            }

            .menu-area .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .edu-main-card {
                border-radius: 10px;
            }

            .edu-menu .navbar-collapse {
                background: rgba(39, 60, 102, 0.98);
                border-radius: 0 0 10px 10px;
                padding: 8px 14px 12px;
                margin-top: 6px;
            }

            .edu-menu .navbar-nav .dropdown-menu {
                border-radius: 8px;
                margin: 6px 0 10px;
            }
        }

        @media (max-width: 575px) {
            body.home-style2 {
                font-size: 14px;
            }

            .edu-brand img {
                width: 40px;
                height: 40px;
            }

            .edu-page-title {
                font-size: 17px;
                padding: 12px 13px;
            }

            .edu-main-inner {
                padding: 12px;
            }

            .home-style2 .btn,
            .home-style2 .btn-sm,
            .home-style2 .btn-lg {
                line-height: 1.25;
            }
        }

        @media (min-width: 1200px) {
            .edu-content-wrap .container {
                max-width: 1240px;
            }

            .edu-main-inner {
                padding: 28px;
            }

            .edu-main-inner > .row {
                row-gap: 28px;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="home-style2">
    @include('frontend.cultivation-v2.partials._header')

    <div class="homepage-slider-wrap">
        @yield('sliderninfo')
    </div>

    <section class="edu-content-wrap">
        <div class="container">
            <div class="edu-main-card">
                @hasSection('fronttitle')
                    <div class="edu-page-title">@yield('fronttitle')</div>
                @endif
                <div class="edu-main-inner">
                    <div class="row">
                        @yield('frontcontent')
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.cultivation-v2.partials._footer')

    <script src="{{ asset('public/assets/js/jquery-1.9.1.min.js') }}"></script>
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
    <script src="{{ asset('public/cultivation/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('public/cultivation/assets/js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('public/lightbox/fancybox/jquery.fancybox.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.alert').fadeTo(2000, 500).slideUp(500, function () {
                $('.alert').slideUp(500);
            });

            if ($('#myTable').length) {
                $('#myTable').DataTable({
                    order: [[0, 'asc']],
                    autoWidth: false,
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

