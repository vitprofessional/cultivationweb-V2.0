<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        @php
        $config =App\Models\ServerConfig::first();
        @endphp
        <title>
        @if(!empty($config->instituteName))
        {{$config->instituteName}} | @yield('fronttitle')
        @else 
        Jahanara Ayiub Acadimic | @yield('fronttitle')
        @endif  </title>
        {{-- Load Vite assets only when available (hot or manifest) --}}
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link href="{{ asset('/public/') }}/assets/css/custom.css" rel="stylesheet" />
        <link href="{{ asset('/public/') }}/assets/css/style.css" rel="stylesheet" />

        <!-- Owl Carousel Assets -->
        <link href="{{ asset('/public/') }}/owl-carousel/owl.carousel.css" rel="stylesheet" />
        <link href="{{ asset('/public/') }}/owl-carousel/owl.theme.css" rel="stylesheet" />

        <!-- Prettify -->
        <link href="{{ asset('/public/') }}/assets/js/google-code-prettify/prettify.css" rel="stylesheet" />
        <!-- font awesome kit setup -->
    <!-- Updated Font Awesome Kit -->
    <script src="https://kit.fontawesome.com/163dbb3d41.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300;400;500;700;900&family=Skranji&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
        <!--Fancy box-->
        <link rel="stylesheet" type="text/css" href="{{ asset('/') }}public/lightbox/fancybox/jquery.fancybox.min.css" />

        <link href="{{asset('/public/')}}/lightbox/css/animate.min.css" rel="stylesheet" />

        <style>
            /* Professional Typography */
            * {
                font-family: 'Roboto', sans-serif;
            }

            
            
            /* Header Top Section Styles */
            .header-top {
                background: linear-gradient(135deg, #1e7e34 0%, #155724 50%, #0d4016 100%);
                color: white;
                padding: 30px 0;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                position: relative;
                overflow: hidden;
            }
            
            .header-top::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
                pointer-events: none;
            }
            
            .header-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                padding-right: 25px;
                position: relative;
                z-index: 2;
            }
            
            .header-logo img {
                max-width: 140px;
                height: auto;
                background: rgba(255,255,255,0.15);
                border-radius: 20px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.3);
                border: 2px solid rgba(255,255,255,0.2);
                transition: transform 0.3s ease;
            }
            
            .header-logo img:hover {
                transform: scale(1.05);
            }

            .instituvte-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                position: relative;
                z-index: 2;
            }
            
            .institute-name {
                font-size: 3.2rem;
                font-weight: 900;
                margin: 0 0 12px 0;
                color: #fff;
                text-shadow: 3px 3px 8px rgba(0,0,0,0.5);
                line-height: 1.1;
                letter-spacing: -1px;
                font-family: 'Roboto', sans-serif;
            }
            
            .institute-location {
                font-size: 1.3rem;
                color: #e8f5e8;
                margin-bottom: 12px;
                font-weight: 500;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            
            .institute-mobile {
                font-size: 1.1rem;
                color: #e8f5e8;
                margin-bottom: 0;
                font-weight: 500;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 25px;
                flex-wrap: wrap;
            }
            
            .contact-item {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .contact-icon {
                color: #ffc107;
                font-size: 1.1rem;
                width: 20px;
                text-align: center;
            }

            /* Center the entire header content */
            .header-content {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 40px;
                max-width: 1200px;
                margin: 0 auto;
            }

            /* Professional Navbar Styles */
            .menubar {
                background: #f8f9fa;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                position: sticky;
                top: 0;
                z-index: 1000;
            }
            
            .navbar-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 15px;
            }
            
            /* Fix navbar centering and layout */
            .navbar {
                padding: 0 !important;
            }
            
            .navbar-nav {
                width: 100% !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                gap: 0 !important;
                margin: 0 !important;
            }
            
            .nav-item {
                margin: 0 5px;
            }
            
            .nav-link {
                font-size: 1rem !important;
                font-weight: 600 !important;
                color: #fff !important;
                padding: 15px 18px !important;
                border-radius: 8px !important;
                transition: all 0.3s ease !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                white-space: nowrap !important;
                display: flex !important;
                align-items: center !important;
            }
            
            .nav-link:hover,
            .nav-link:focus {
                background: rgba(255,255,255,0.2) !important;
                color: #fff !important;
                transform: translateY(-2px) !important;
            }
            
            .dropdown-menu {
                background: #198754 !important;
                border: none !important;
                border-radius: 10px !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
                margin-top: 5px !important;
                min-width: 220px !important;
            }
            
            .dropdown-item {
                color: #fff !important;
                font-weight: 500 !important;
                padding: 12px 20px !important;
                transition: all 0.3s ease !important;
                font-size: 0.95rem !important;
            }
            
            .dropdown-item:hover,
            .dropdown-item:focus {
                background: rgba(255,255,255,0.2) !important;
                color: #fff !important;
            }

            

            /* Professional Carousel Styles */
            .carousel {
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                border-radius: 0 0 15px 15px;
                overflow: hidden;
            }
            
            .carousel-item img {
                height: 500px !important;
                object-fit: cover;
                width: 100%;
                filter: brightness(0.9);
            }
            
            .carousel-caption {
                background: linear-gradient(transparent, rgba(0,0,0,0.7));
                bottom: 0;
                left: 0;
                right: 0;
                padding: 40px 20px 20px;
            }
            
            .carousel-control-prev,
            .carousel-control-next {
                width: 5%;
            }
            
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                background-size: 100%;
                border-radius: 50%;
                background-color: rgba(0,0,0,0.5);
                padding: 20px;
            }
            
            .carousel-indicators {
                bottom: 20px;
            }
            
            .carousel-indicators [data-bs-target] {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 5px;
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .header-top {
                    padding: 25px 0;
                }
                
                .header-content {
                    flex-direction: column;
                    gap: 25px;
                }
                
                .header-logo {
                    padding-right: 0;
                }
                
                .header-logo img {
                    max-width: 120px;
                }
                
                .institute-name {
                    font-size: 2rem;
                }
                
                .institute-location {
                    font-size: 1.1rem;
                }
                
                .institute-mobile {
                    flex-direction: column;
                    gap: 15px;
                    font-size: 1rem;
                }
                
                .nav-link {
                    font-size: 0.9rem !important;
                    padding: 12px 15px !important;
                }
                
                .carousel-item img {
                    height: 250px !important;
                }
                
                .carousel-caption {
                    padding: 20px 15px 15px;
                }
            }
            
            @media (max-width: 576px) {
                .institute-name {
                    font-size: 1.3rem;
                }
                
                .institute-location,
                .institute-mobile {
                    font-size: 0.95rem;
                }
                
                .header-content {
                    gap: 20px;
                }
                
                .carousel-item img {
                    height: 200px !important;
                }
            }
            
            /* Tablet Responsive */
            @media (min-width: 769px) and (max-width: 992px) {
                .carousel-item img {
                    height: 400px !important;
                }
                
                .institute-name {
                    font-size: 2.5rem;
                }
            }

            /* Professional Footer */
            footer {
                background: linear-gradient(135deg, #212529 0%, #343a40 100%);
                color: #fff;
                padding: 40px 0 20px;
                margin-top: 50px !important;
            }
            
            footer h3 {
                color: #ffc107;
                font-weight: 700;
                margin-bottom: 20px;
            }
            
            footer p {
                color: #adb5bd;
                line-height: 1.6;
            }
            
            footer .text-muted {
                color: #adb5bd !important;
            }

            /* Professional Animation */
            .header-top {
                animation: fadeInDown 0.8s ease-out;
            }
            
            .menubar {
                animation: fadeInUp 0.8s ease-out 0.2s both;
            }
            
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Additional Professional Touches */
            .call-box {
                color: #fff;
                display: flex; 
                align-items: center; 
                gap: 15px;
                font-family: 'Roboto', sans-serif;
                background: rgba(255,255,255,0.1);
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 10px;
            }
            
            .call-icon {
                display: inline-flex; 
                align-items: center; 
                justify-content: center;
                font-size: 1.8rem;
                color: #ffc107;
            }
            
            .call-text { 
                font-size: 0.9rem; 
                line-height: 1.4; 
            }
            
            .call-label {
                font-size: 0.7rem; 
                letter-spacing: 0.05em;
                text-transform: uppercase;
                font-weight: 700;
            }
            
            .call-phone {
                font-weight: 700; 
                color: #fff; 
                text-decoration: none;
            }
            
            .call-phone:hover { 
                text-decoration: underline; 
                color: #ffc107;
            }


            /* Mobile Menu Fixes */
            /* Fix offcanvas full height */
.offcanvas {
    height: 100vh !important;
    max-height: 100vh !important;
}

.offcanvas-start {
    background-color: #198754 !important;
    border: none !important;
    box-shadow: 0 0 20px rgba(0,0,0,0.5) !important;
    width: 75% !important;
    max-width: 320px !important;
}

.offcanvas-header {
    background-color: #155724 !important;
    border-bottom: 1px solid rgba(255,255,255,0.2) !important;
    padding: 1rem 1.5rem !important;
    min-height: 70px !important;
}

.offcanvas-body {
    background-color: #198754 !important;
    padding: 1.5rem !important;
    overflow-y: auto !important;
    flex: 1 1 auto !important;
}

/* Fix for mobile viewport */
.offcanvas-backdrop {
    background-color: rgba(0,0,0,0.5) !important;
}

/* Ensure proper positioning */
.offcanvas.show {
    transform: none !important;
}

/* Fix nav brand text wrapping */
.navbar-brand {
    font-size: 0.9rem !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 200px !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .offcanvas-start {
        width: 85% !important;
    }
    
    .navbar-brand {
        font-size: 0.8rem !important;
        max-width: 150px !important;
    }
}
            /* Mobile menu button override (scoped only to header toggle) */
            .header-top .btn.btn-success {
                background-color: rgba(255,255,255,0.1) !important;
                border: 1px solid rgba(255,255,255,0.3) !important;
                color: #fff !important;
            }
            .header-top .btn.btn-success:hover,
            .header-top .btn.btn-success:focus {
                background-color: rgba(255,255,255,0.2) !important;
                border-color: rgba(255,255,255,0.5) !important;
                color: #fff !important;
            }
        </style>
    </head>
    <body>
        <!-- Header Top Section -->
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header-content text-center">
                            <!-- Logo on the left -->
                            <div class="header-logo">
                                @if(!empty($config->logo))
                                    <img src="{{ config('app.url') }}/public/upload/image/cultivation/{{$config->logo}}" alt="Logo" />
                                @else
                                    <img src="{{ asset('/public/') }}/logo.png" alt="Jahanara Ayub Academic" />
                                @endif
                            </div>
                            
                            <!-- Institute Information beside logo -->
                            <div class="institute-info">
                                <!-- Institute Name (Highlighted) -->
                                <h1 class="institute-name">
                                    @if(!empty($config->instituteName))
                                        {{ $config->instituteName }}
                                    @else
                                        Jahanara Ayub Academy
                                    @endif
                                </h1>
                                
                                <!-- Location under institute name -->
                                <div class="institute-location">
                                    <i class="fa-solid fa-location-dot contact-icon"></i>
                                    <span>
                                        @if(!empty($config->address))
                                            {{ $config->address }}
                                        @else
                                            North Shampur, Burichong, Cumilla
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Mobile and Email in same line -->
                                <div class="institute-mobile">
                                    <div class="contact-item">
                                        <i class="fa-solid fa-phone contact-icon"></i>
                                        <span>
                                            @if(!empty($config->officeMobile))
                                                {{ $config->officeMobile }}
                                            @else
                                                +(012) 345 6789
                                            @endif
                                        </span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="fa-solid fa-envelope contact-icon"></i>
                                        <span>
                                            @if(!empty($config->officeEmail))
                                                {{ $config->officeEmail }}
                                            @else
                                                ja@gmail.com
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Navbar -->
        <div class="menubar bg-success">
            <div class="navbar-container">
                <!-- Mobile Menu -->
                <div class="d-block d-md-none">
                    <nav class="navbar bg-success navbar-expand-lg" data-bs-theme="dark">
                        <div class="container-fluid">
                            <button class="btn btn-success me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <span class="navbar-brand text-white mb-0 h1 text-truncate">
                                 @if(!empty($config->logo))
                                    <img class="img-fluid" style="max-width: 50px;" src="{{ config('app.url') }}/public/upload/image/cultivation/{{$config->logo}}" alt="Logo" />
                                @else
                                    <img class="img-fluid" style="max-width: 50px;" src="{{ asset('/public/') }}/logo.png" alt="Jahanara Ayub Academic" />
                                @endif
                            </span>
                        </div>
                    </nav>
                </div>

                <!-- Offcanvas Side Menu -->
                <div class="offcanvas offcanvas-start bg-success" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-white" id="mobileMenuLabel">Navigation Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="text-center mb-4">
                            @if(!empty($config->logo))
                                <img class="img-fluid" style="max-width: 80px;" src="{{ config('app.url') }}/public/upload/image/cultivation/{{$config->logo}}" alt="Logo" />
                            @else
                                <img class="img-fluid" style="max-width: 80px;" src="{{ asset('/public/') }}/logo.png" alt="Jahanara Ayub Academic" />
                            @endif
                        </div>
                        
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('homePage')}}">
                                    <i class="fa-solid fa-home me-2"></i>Home
                                </a>
                            </li>
                            <!-- Institute submenu -->
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#mobileInstituteMenu" role="button" aria-expanded="false">
                                    <i class="fa-solid fa-building me-2"></i>Institute 
                                    <i class="fa fa-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="mobileInstituteMenu">
                                    <ul class="list-unstyled ps-4">
                                        <li><a class="nav-link py-2" href="{{route('institutePage')}}">About Us</a></li>
                                        <li><a class="nav-link py-2" href="{{route('principalSpeechPage')}}">{{ $frontendSpeechNavLabel ?? "Principal's Message" }}</a></li>
                                        <li><a class="nav-link py-2" href="{{route('student')}}">Student List</a></li>
                                        <li><a class="nav-link py-2" href="{{route('exprincipalPage')}}">EX-Principals</a></li>
                                        <li><a class="nav-link py-2" href="{{route('teacherPage')}}">Lecturer Corner</a></li>
                                        <li><a class="nav-link py-2" href="{{route('staffPage')}}">Staff Panel</a></li>
                                        <li><a class="nav-link py-2" href="{{route('comitteePage')}}">Governing Body</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Add other menu items similarly... -->
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#mobileAcademicMenu" role="button" aria-expanded="false">
                                    <i class="fa-solid fa-graduation-cap me-2"></i>Academic 
                                    <i class="fa fa-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="mobileAcademicMenu">
                                    <ul class="list-unstyled ps-4">
                                        <li><a class="nav-link py-2" href="{{route('newSyllabus')}}">Syllabus</a></li>
                                        <li><a class="nav-link py-2" href="{{route('newClassSchedule')}}">Class Routine</a></li>
                                        <li><a class="nav-link py-2" href="{{route('newExamSchedule')}}">Exam Routine</a></li>
                                        <li><a class="nav-link py-2" href="{{route('newSemister')}}">Semister Plans</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#mobileResultArchiveMenu" role="button" aria-expanded="false">
                                    <i class="fa-solid fa-chart-line me-2"></i>Result Archive 
                                    <i class="fa fa-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="mobileResultArchiveMenu">
                                    <ul class="list-unstyled ps-4">
                                        <li><a class="nav-link py-2" href="{{route('internalResult')}}">Internal Result</a></li>
                                        <li><a class="nav-link py-2" href="#">Individual Result</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#mobileJobPlacementMenu" role="button" aria-expanded="false">
                                    <i class="fa-solid fa-briefcase me-2"></i>Job Placement
                                    <i class="fa fa-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="mobileJobPlacementMenu">
                                    <ul class="list-unstyled ps-4">
                                        <li><a class="nav-link py-2" href="{{route('placementCellView')}}">Placement Cell</a></li>
                                        <li><a class="nav-link py-2" href="{{route('jobNeedyStudentView')}}">Job Needy Students</a></li>
                                        <li><a class="nav-link py-2" target="_blank" href="https://www.bdjobs.com">Job Circulars</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#mobileGalleryMenu" role="button" aria-expanded="false">
                                    <i class="fa-solid fa-images me-2"></i>Gallery
                                    <i class="fa fa-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="mobileGalleryMenu">
                                    <ul class="list-unstyled ps-4">
                                        <li><a class="nav-link py-2" href="{{route('imagePage')}}">Photo Gallery</a></li>
                                        <li><a class="nav-link py-2" href="{{route('videoPage')}}">Video Gallery</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('supportPage')}}">
                                    <i class="fa-solid fa-headset me-2"></i> Support
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="d-none d-md-block">
                    <nav class="navbar bg-success navbar-expand-lg" data-bs-theme="dark">
                        <div class="container-fluid">
                            <div class="navbar-collapse">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('homePage')}}">
                                            <i class="fa-solid fa-home me-2"></i>Home
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-building me-2"></i>Institute
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('institutePage')}}">About Us</a></li>
                                            <li><a class="dropdown-item" href="{{route('principalSpeechPage')}}">{{ $frontendSpeechNavLabel ?? "Principal's Message" }}</a></li>
                                            <li><a class="dropdown-item" href="{{route('student')}}">Student List</a></li>
                                            <li><a class="dropdown-item" href="{{route('exprincipalPage')}}">EX-Principals</a></li>
                                            <li><a class="dropdown-item" href="{{route('teacherPage')}}">Lecturer Corner</a></li>
                                            <li><a class="dropdown-item" href="{{route('staffPage')}}">Staff Panel</a></li>
                                            <li><a class="dropdown-item" href="{{route('comitteePage')}}">Governing Body</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-graduation-cap me-2"></i>Academic
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('newSyllabus')}}">Syllabus</a></li>
                                            <li><a class="dropdown-item" href="{{route('newClassSchedule')}}">Class Routine</a></li>
                                            <li><a class="dropdown-item" href="{{route('newExamSchedule')}}">Exam Routine</a></li>
                                            <li><a class="dropdown-item" href="{{route('newSemister')}}">Semister Plans</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-chart-line me-2"></i>Result Archive
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('internalResult')}}">Internal Result</a></li>
                                            <li><a class="dropdown-item" href="#">Individual Result</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-briefcase me-2"></i>Job Placement
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('placementCellView')}}">Placement Cell</a></li>
                                            <li><a class="dropdown-item" href="{{route('jobNeedyStudentView')}}">Job Needy Student</a></li>
                                            <li><a class="dropdown-item" href="https://bdjobs.com/" target="_blank">Job Circular</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-images me-2"></i>Gallery
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{route('imagePage')}}">Photo Gallery</a></li>
                                            <li><a class="dropdown-item" href="{{route('videoPage')}}">Video Gallery</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('supportPage')}}">
                                            <i class="fa-solid fa-headset me-2"></i>Support
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

    <div class="homepage-slider-wrap">
        @yield('sliderninfo')
    </div>
        <div class="container-fluid">
            <div class="row">
                @yield('frontcontent')
            </div>
        </div>
        <footer class="mt-5 container-fluid">
             @if(!empty($config))
            <div class="row g-0">
                <div class="col-12 col-md-3 mx-auto">
                    <h3>Contact Details</h3>
                    <p><i class="fa-solid fa-link"></i> {{  url('/') }}</p>
                    <p><i class="fa-solid fa-phone-office"></i>@if(!empty($config->officeMobile)) {{$config->officeMobile}} @else 01836994770 @endif</p>
                    <p><i class="fa-solid fa-buildings"></i> @if(!empty($config->address)) {{$config->address}} @else North Shampur, Burichong, Cumilla. @endif</p>
                    <p><i class="fa-solid fa-envelopes"></i> <a class="text-muted" style="text-decoration:none" href="mailto:@if(!empty($config->officeEmail)) {{ $config->officeEmail }} @else cultivation@virtualitprofessional.com @endif">@if(!empty($config)) {{$config->officeEmail}} @else cultivation@virtualitprofessional.com @endif</a></p>
                    <p>
                        <i class="fa-brands fa-square-facebook"></i> <a class="text-muted" style="text-decoration:none" target="_blank" href="{{ $config->facebookPage }}">@if(!empty($config->facebookPage)){{$config->facebookPage}} @else <a class="text-muted" style="text-decoration:none" href="https://www.facebook.com/profile.php?id=61572769304729">Cultivation-The Education Manager</a> @endif</a>
                    </p>
                </div>
                <div class="col-12 col-md-3 mx-auto">
                    <h3>Visitor Counter</h3>
                    @include('visitorCounter')
                </div>
                <div class="col-12 col-md-4 mx-auto">
                    <h3>Google Map</h3>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=@if($config->mapEmbed){{$config->mapEmbed }} @else!1m18!1m12!1m3!1d3658.720943010397!2d91.14681007428437!3d23.50655879809593!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3754796e7c90d6e3%3A0x210c98d19ee0bc9c!2z4Ka44KeH4Ka-4Kao4Ka-4KawIOCmrOCmvuCmguCmsuCmviDgppXgprLgp4fgppw!5e0!3m2!1sen!2suk!4v1695524774546!5m2!1sen!2suk @endif"
                        width="100%"
                        height="300"
                        class="rounded"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
                <div class="col-12 mt-2">
                    <img class="w-100" src="{{ asset('/public/') }}/img/footer_top_bg.png" alt="" />
                </div>
            </div>
            <div class="p-2">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <p><span class="fw-bold text-center text-md-start">Planning and Implementation:</span> Principal   ({{$config->instituteName}})</p>
                    </div>
                    <div class="col-md-6 col-12 text-center text-md-end">
                        <p><span class="fw-bold">Powered By:</span> Cultivation(Version 1.0.2) by Virtual IT Professional</p>
                    </div>
                    <div class="col-12 text-center">
                        @php
                            $estYear = '';
                            if(!empty($config) && !empty($config->establishDate)){
                                try {
                                    $estYear = \Carbon\Carbon::parse($config->establishDate)->format('Y');
                                } catch (Exception $e) {
                                    // Fallback: extract first 4 consecutive digits as year
                                    if(preg_match('/(19|20)\d{2}/', $config->establishDate, $m)){
                                        $estYear = $m[0];
                                    }
                                }
                            }
                        @endphp
                        <p class="fw-bold">Copyright &copy; {{ $estYear }}-@php echo date('Y'); @endphp | All Rights Reserved {{$config->instituteName}} </p>
                    </div>
                </div>
            </div>
            @else
            <div class="row g-0">
                <div class="col-12 col-md-3 mx-auto">
                    <h3>Contact Details</h3>
                    <p><i class="fa-solid fa-link"></i> www.jahanaraayubacademy.edu.bd</p>
                    <p><i class="fa-solid fa-phone-office"></i> 0123 4567 890</p>
                    <p><i class="fa-solid fa-envelopes"></i> ja@gmail.com</p>
                    <p><i class="fa-brands fa-square-whatsapp"></i> 0123 4567 890</p>
                    <p><i class="fa-brands fa-square-facebook"></i> Jahanara Ayub Academy</p>
                    <p><i class="fa-solid fa-buildings"></i> Northshampur, Pirjatrapur, Burichong, Cumilla</p>
                </div>
                <div class="col-12 col-md-3 mx-auto">
                    <h3>Visitor Counter</h3>
                    @include('visitorCounter')
                </div>
                <div class="col-12 col-md-4 mx-auto">
                    <h3>Google Map</h3>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=@if($config){{ $config->mapEmbed }}@else!1m18!1m12!1m3!1d3658.720943010397!2d91.14681007428437!3d23.50655879809593!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3754796e7c90d6e3%3A0x210c98d19ee0bc9c!2z4Ka44KeH4Ka-4Kao4Ka-4KawIOCmrOCmvuCmguCmsuCmviDgppXgprLgp4fgppw!5e0!3m2!1sen!2suk!4v1695524774546!5m2!1sen!2suk @endif"
                        width="100%"
                        height="300"
                        class="rounded"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
                <div class="col-12 mt-2">
                    <img class="w-100" src="{{ asset('/public/') }}/img/footer_top_bg.png" alt="" />
                </div>
            </div>
            <div class="p-2">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <p><span class="fw-bold">Planning and Implementation:</span> Principal(SBC)</p>
                    </div>
                    <div class="col-md-6 col-12 text-end">
                        <p><span class="fw-bold">Powered By:</span> Cultivation(Version 0.0.5) by Virtual IT Professional</p>
                    </div>
                    <div class="col-12 text-center">
                        <p class="fw-bold">Copyright &copy; 2000-@php echo date('Y'); @endphp | All Rights Reserved SBC Cumilla</p>
                    </div>
                </div>
            </div>
            @endif
        </footer>
        
        <!-- Global Notice View Modal -->
        <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-bullhorn"></i>
                            <h5 class="modal-title mb-0" id="noticeModalLabel">Notice</h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button id="noticePrintBtn" type="button" class="btn btn-light btn-sm">
                                <i class="fa-solid fa-print"></i> Print
                            </button>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <!-- A4 styled sheet for notice -->
                        <div id="noticeSheet" class="notice-sheet bg-white">
                            <div class="ns-header text-center position-relative">
                                <div class="ns-logo mx-auto mb-2">
                                    @if(!empty($config->logo))
                                        <img src="{{ config('app.url') }}/public/upload/image/cultivation/{{$config->logo}}" alt="Logo">
                                    @else
                                        <img src="{{ asset('/public/') }}/logo.png" alt="Logo">
                                    @endif
                                </div>
                                <h2 class="ns-title mb-1">@if(!empty($config->instituteName)) {{ $config->instituteName }} @else Institute Name @endif</h2>
                                <div class="ns-meta">
                                    <h3><i class="fa-solid fa-location-dot"></i> @if(!empty($config->address)){{ $config->address }}@else Address @endif</h3>
                                    <h4 class="ms-3"><i class="fa-solid fa-phone"></i> @if(!empty($config->officeMobile)){{ $config->officeMobile }}@endif</h4>
                                    <h4 class="ms-3"><i class="fa-solid fa-envelope"></i> @if(!empty($config->officeEmail)){{ $config->officeEmail }}@endif</h4>
                                </div>
                                <div class="ns-date-abs text-end small">
                                    <div>Date: <span id="noticeSheetDate">—</span></div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <h4 id="noticeSheetHeading" class="text-center fw-bold my-3">NOTICE</h4>
                            <h5 id="noticeSheetTitle" class="text-center mb-2"></h5>
                            <div id="noticeModalBody" class="ns-content"></div>
                            <div id="noticeAttachmentPreview" class="mt-3 d-none"></div>
                            <div id="noticeSignWrap" class="ns-sign mt-5">
                                <div class="text-end">
                                    @if(!empty($config) && !empty($config->principalSign))
                                        <div class="mb-1">
                                            <img src="{{ config('app.url') }}/public/upload/image/cultivation/{{ rawurlencode(basename($config->principalSign)) }}" alt="Authorized Signature" style="max-height:60px;max-width:220px;object-fit:contain;margin-right: 3rem !important;margin-bottom: -1.5rem;">
                                        </div>
                                    @endif
                                    <div class="ns-sign-line"></div>
                                    <div class="fw-semibold" style="margin-right: 2rem !important;">Authorized Signature</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="noticePdfBtn" type="button" class="btn btn-success">
                            <i class="fa-regular fa-file-pdf me-1"></i> Download PDF
                        </button>
                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            /* Notice sheet (A4-like) */
            .notice-sheet{width:100%;max-width:850px;margin:0 auto;padding:24px 28px;color:#212529;position:relative}
            .notice-sheet .ns-header{position:relative}
            .notice-sheet .ns-header .ns-date-abs{position:absolute;right:0;bottom:-6px;top:auto;text-align:right;font-size:.8rem}
            .notice-sheet .ns-header img{width:70px;height:70px;object-fit:contain}
            .notice-sheet .ns-title{font-size:1.75rem;font-family: 'Roboto', sans-serif;}
            .notice-sheet .ns-meta h3{font-size:1.25rem;font-family: 'Roboto', sans-serif;}
            .notice-sheet .ns-meta h4{font-size:1rem;font-family: 'Roboto', sans-serif;}
            .notice-sheet .ns-meta{font-size:.9rem;color:#6c757d}
            .notice-sheet .ns-content{font-size:1rem;line-height:1.7}
            .notice-sheet .ns-sign{page-break-inside: avoid}
            .notice-sheet .ns-sign-line{width:220px;border-bottom:2px solid #6c757d;height:24px;margin-left:auto}
            @media print{ body *{visibility:hidden} #noticeSheet, #noticeSheet *{visibility:visible} #noticeSheet{position:absolute;left:0;top:0;margin:0;padding:0;width:100%} }
        </style>
    
    <script src="{{ asset('/public/') }}/assets/js/jquery-1.9.1.min.js"></script>   
    
        <script src="{{ asset('/public/') }}/owl-carousel/owl.carousel.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="{{asset('/public/')}}/lightbox/js/bootstrap.min.js"></script>
    <!-- html2pdf for Notice PDF download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-YcsIPoQkWjz2pHnqG0d0T8b0Xf7YkIFyvJd0kUXU+q8sSezgs9HhK5qWQkUOybC+I6q4YV2s3Zs1q7QGqKqR3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <!--Fancybox-->
        <script src="{{ asset('/') }}public/lightbox/fancybox/jquery.fancybox.min.js"></script>
        <script>
            // Base URL for building absolute paths (falls back to url('/'))
            let APP_URL = {!! json_encode(config('app.url') ?: url('/')) !!};
            APP_URL = (APP_URL || '').replace(/\/+$/,'');
            // Normalize: if APP_URL ends with /public, strip it to avoid duplicating /public in constructed paths
            if(/\/public$/i.test(APP_URL)){ APP_URL = APP_URL.replace(/\/public$/i,''); }
            // Align scheme with current page to avoid mixed-content
            if(window.location?.protocol === 'https:' && APP_URL.startsWith('http:')){
                APP_URL = APP_URL.replace(/^http:/,'https:');
            }
            $(document).ready(function() {
                $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                    $(".alert").slideUp(500);
                });
            });
                $(document).ready(function () {
                $("#myTable").DataTable({
                    order: [[0, "asc"]],
                });
                    // Default Owl init (skips if page marks it customized)
                    if($("#owl-demo").length && !$("#owl-demo").data('owlCustomized')){
                        $("#owl-demo").owlCarousel({
                            autoPlay: 3000,
                            items: 4,
                            itemsDesktop: [1199, 4],
                            itemsDesktopSmall: [979, 3]
                        });
                    }

                // Notice modal wiring (works for marquee and notice list)
                $(document).on('click', '.notice-view', function (e) {
                    e.preventDefault();
                    const title = $(this).data('title') || 'Notice';
                    let body = $(this).data('body') || '';
                    const body64 = $(this).data('body64') || '';
                    const date = $(this).data('date') || '';
                    const attachment = $(this).data('attachment') || '';
                    const attachmentUrl = $(this).data('attachmentUrl') || '';

                    // Safely resolve attachment path under APP_URL/public/... (default to upload/notice for bare filenames)
                    function safeBasename(p){
                        try{
                            if(!p) return '';
                            p = String(p);
                            // strip query/hash
                            p = p.split('?')[0].split('#')[0];
                            // normalize separators
                            p = p.replace(/\\/g,'/');
                            const parts = p.split('/');
                            return parts.pop();
                        }catch(_){
                            return '';
                        }
                    }

                    function sanitizeRelativePath(p){
                        try{
                            if(!p) return '';
                            p = String(p);
                            p = p.split('?')[0].split('#')[0];
                            p = p.replace(/\\/g,'/');
                            // remove leading slashes
                            p = p.replace(/^\/+/, '');
                            // prevent path traversal
                            p = p.replace(/\.\./g,'');
                            // collapse multiple slashes
                            p = p.replace(/\/+/g,'/');
                            return p;
                        }catch(_){
                            return '';
                        }
                    }
                    function encodeRelPath(p){
                        try{
                            if(!p) return '';
                            return p.split('/').map(seg => encodeURIComponent(seg)).join('/');
                        }catch(_){ return p; }
                    }

                    function getUrlOrigin(u){
                        try{
                            const full = new URL(u, window.location.href);
                            return full.origin;
                        }catch(_){ return ''; }
                    }
                    function isSameOrigin(u){
                        const o = getUrlOrigin(u);
                        return !!o && o === window.location.origin;
                    }

                    let attachmentPath = '';
                    let candidateUrls = [];
                    // If server provided an absolute attachment URL, normalize and use it
                    if (attachmentUrl && typeof attachmentUrl === 'string') {
                        let abs = attachmentUrl;
                        if(window.location?.protocol === 'https:' && abs.startsWith('http:')){
                            try{ abs = abs.replace(/^http:/,'https:'); }catch(_){}
                        }
                        candidateUrls.push(abs);
                    } else if (attachment && attachment.length) {
                        const isAbsolute = /^(?:https?:)?\/\//i.test(attachment) || /^data:/i.test(attachment);
                        if (isAbsolute) {
                            let abs = attachment;
                            // If page is HTTPS, try to use HTTPS for absolute HTTP URLs
                            if(window.location?.protocol === 'https:' && abs.startsWith('http:')){
                                try{ abs = abs.replace(/^http:/,'https:'); }catch(_){}
                            }
                            candidateUrls.push(abs);
                        } else {
                            const rel = sanitizeRelativePath(attachment);
                            const fname = safeBasename(rel);
                            if (rel && rel.includes('/')) {
                                // Relative path provided
                                const encRel = encodeRelPath(rel);
                                // Force the desired pattern when rel looks like upload/notice/...
                                if(/^upload\/notice\//i.test(encRel)){
                                    candidateUrls.push(APP_URL + '/public/' + encRel);
                                } else {
                                    candidateUrls.push(APP_URL + '/public/' + encRel);
                                }
                            } else if (fname) {
                                // Bare filename: try common buckets
                                candidateUrls.push(APP_URL + '/public/upload/notice/' + encodeURIComponent(fname));
                            }
                        }
                    }
                    attachmentPath = candidateUrls[0] || '';
                    const attachExt = (safeBasename(attachmentPath).split('.').pop() || '').toLowerCase();

                    // Prefer base64 body to safely carry HTML and decode as UTF-8
                    function decodeBase64Utf8(b64){
                        try {
                            const binary = atob(b64);
                            const bytes = Uint8Array.from(binary, c => c.charCodeAt(0));
                            if (window.TextDecoder) {
                                return new TextDecoder('utf-8').decode(bytes);
                            }
                            // Fallback for very old browsers
                            return decodeURIComponent(escape(binary));
                        } catch (e) {
                            return '';
                        }
                    }
                    if(body64 && typeof body64 === 'string'){
                        const decoded = decodeBase64Utf8(body64);
                        if(decoded) body = decoded;
                    }

                    // If body is plain text (no HTML tags), preserve new lines
                    if(body && !/<[a-z][\s\S]*>/i.test(body)){
                        body = String(body).replace(/\r?\n/g, '<br>');
                    }

                    $('#noticeModalLabel').text(title);
                    $('#noticeSheetTitle').text(title);
                    $('#noticeSheetDate').text(date);
                    if (body && body.length) {
                        $('#noticeModalBody').html(body);
                    } else {
                        $('#noticeModalBody').html('<em>No additional details provided.</em>');
                    }

                    if (attachmentPath && attachmentPath.length) {
                        // Hide heading and print button when attachment data present
                        $('#noticeSheetHeading').addClass('d-none');
                        $('#noticePrintBtn').addClass('d-none');
                        $('#noticeSignWrap').addClass('d-none');
                        // Ensure Download PDF button is visible for attachments
                        $('#noticePdfBtn').removeClass('d-none');
                        // Record attachment info for Download PDF button behavior
                        const dlName = safeBasename(attachmentPath) || 'attachment';
                        try{ window.__noticeAttachment = { url: attachmentPath, filename: dlName, ext: attachExt }; }catch(_){ }
                        // inline preview for images/pdf
                        let previewHtml = '';
                        if(['jpg','jpeg','png','webp','gif','avif'].includes(attachExt)){
                            const sameOrigin = isSameOrigin(attachmentPath);
                            const mcBlocked = (window.location.protocol === 'https:' && attachmentPath.startsWith('http:'));
                            if(mcBlocked){
                                $('#noticeAttachmentPreview').html(`<div class="alert alert-warning">Cannot load HTTP resource on HTTPS page. <a href="${attachmentPath}" target="_blank" rel="noopener">Open image in new tab</a></div>`).removeClass('d-none');
                            } else if(sameOrigin){
                                $('#noticeAttachmentPreview').html('<div class="text-muted small">Loading image…</div>').removeClass('d-none');
                                (async function(){
                                    try{
                                        const res = await fetch(attachmentPath, {cache:'no-cache'});
                                        if(!res.ok){ throw new Error('HTTP '+res.status); }
                                        const blob = await res.blob();
                                        const blobUrl = URL.createObjectURL(blob);
                                        window.__noticeBlobUrl = blobUrl;
                                        const html = `<img src="${blobUrl}" alt="attachment" class="img-fluid rounded border">`;
                                        $('#noticeAttachmentPreview').html(html).removeClass('d-none');
                                        try{ window.__noticeAttachment = { url: attachmentPath, filename: dlName, ext: attachExt }; }catch(_){}
                                    }catch(e){
                                        // Fallback to direct image src
                                        const html = `<img src="${attachmentPath}" alt="attachment" class="img-fluid rounded border">`;
                                        $('#noticeAttachmentPreview').html(html).removeClass('d-none');
                                    }
                                })();
                            } else {
                                // Cross-origin image: let browser load directly
                                previewHtml = `<img src="${attachmentPath}" alt="attachment" class="img-fluid rounded border">`;
                                $('#noticeAttachmentPreview').html(previewHtml).removeClass('d-none');
                            }
                        } else if(attachExt === 'pdf'){
                            const sameOrigin = isSameOrigin(attachmentPath);
                            const mcBlocked = (window.location.protocol === 'https:' && attachmentPath.startsWith('http:'));
                            if(mcBlocked){
                                $('#noticeAttachmentPreview').html(`<div class="alert alert-warning">Cannot load HTTP resource on HTTPS page. <a href="${attachmentPath}" target="_blank" rel="noopener">Open PDF in new tab</a></div>`).removeClass('d-none');
                            } else if(sameOrigin){
                                // Prefer Blob if same-origin to bypass attachment disposition
                                $('#noticeAttachmentPreview').html('<div class="text-muted small">Loading PDF preview…</div>').removeClass('d-none');
                                (async function tryEmbedPdf(urls){
                                    try{
                                        let lastErr; let loadedInfo = '';
                                        for(const u of urls){
                                            try{
                                                const res = await fetch(u, {cache:'no-cache'});
                                                const status = res.status;
                                                const ctype = res.headers.get('content-type') || '';
                                                if(!res.ok){ lastErr = new Error('HTTP '+status); continue; }
                                                const blob = await res.blob();
                                                const blobUrl = URL.createObjectURL(blob);
                                                window.__noticeBlobUrl = blobUrl;
                                                const html = `<object data=\"${blobUrl}\" type=\"application/pdf\" style=\"width:100%;height:70vh\"><embed src=\"${blobUrl}\" type=\"application/pdf\" /></object>`;
                                                $('#noticeAttachmentPreview').html(html).removeClass('d-none');
                                                // Point the download button and global to the blob for reliable downloading
                                                try{ window.__noticeBlobUrl = blobUrl; window.__noticeAttachment = { url: attachmentPath, filename: dlName, ext: 'pdf' }; }catch(_){}
                                                // Removed debug info line
                                                return;
                                            }catch(e){ lastErr = e; }
                                        }
                                        throw lastErr || new Error('Preview failed');
                                    }catch(e){
                                        console.warn('Notice PDF blob preview failed:', e);
                                        // Fallback to direct embed/iframe; try candidates in order
                                        let html = '';
                                        for(const u of candidateUrls){
                                            html = `<object data=\"${u}\" type=\"application/pdf\" style=\"width:100%;height:70vh\"><embed src=\"${u}\" type=\"application/pdf\" /></object>`;
                                            $('#noticeAttachmentPreview').html(html).removeClass('d-none');
                                            // Removed debug info line
                                            break;
                                        }
                                    }
                                })(candidateUrls.length ? candidateUrls : [attachmentPath]);
                            } else {
                                // Cross-origin: embed directly, browsers will handle
                                let html = '';
                                for(const u of candidateUrls){
                                    html = `<object data=\"${u}\" type=\"application/pdf\" style=\"width:100%;height:70vh\"><embed src=\"${u}\" type=\"application/pdf\" /></object>`;
                                    $('#noticeAttachmentPreview').html(html).removeClass('d-none');
                                    // Removed debug info line
                                    break;
                                }
                            }
                        } else {
                            $('#noticeAttachmentPreview').addClass('d-none').empty();
                        }
                    } else {
                        // Restore heading and print button when no attachment
                        $('#noticeSheetHeading').removeClass('d-none');
                        $('#noticePrintBtn').removeClass('d-none');
                        $('#noticeSignWrap').removeClass('d-none');
                        // Hide Download PDF for text-only notices
                        $('#noticePdfBtn').addClass('d-none');
                        $('#noticeAttachmentPreview').addClass('d-none').empty();
                        try{ window.__noticeAttachment = null; }catch(_){}
                    }

                    const modalEl = document.getElementById('noticeModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                });

                // Print current notice
                $('#noticePrintBtn').on('click', function(){
                    printNoticeSheet();
                });

                $('#noticePdfBtn').on('click', function(){
                    downloadNoticePdf();
                });

                // Homepage "File" button: fetch + blob download when same-origin
                $(document).on('click', 'a.notice-file-download', async function(e){
                    try{
                        const url = $(this).data('url') || this.href;
                        const filename = $(this).data('filename') || $(this).attr('download') || safeBasename(url) || 'file';
                        const sameOrigin = isSameOrigin(url);
                        if(!url || !sameOrigin){ return; }
                        e.preventDefault();
                        const res = await fetch(url, {cache:'no-cache'});
                        if(!res.ok){ window.open(url, '_blank'); return; }
                        const blob = await res.blob();
                        const blobUrl = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = blobUrl; a.download = filename;
                        document.body.appendChild(a); a.click(); a.remove();
                        setTimeout(()=>{ try{ URL.revokeObjectURL(blobUrl); }catch(_){} }, 1000);
                    }catch(_){ /* fall back to default */ }
                });

                async function inlineNoticeImages(root){
                    const imgs = root.querySelectorAll('.ns-header img, .ns-sign img, #noticeModalBody img');
                    const tasks = Array.from(imgs).map(async (img)=>{
                        try{
                            const url = img.getAttribute('src');
                            if(!url || url.startsWith('data:')) return;
                            const res = await fetch(url, {cache:'no-cache'});
                            if(!res.ok) return;
                            const blob = await res.blob();
                            const reader = new FileReader();
                            const dataUrl = await new Promise((resolve)=>{
                                reader.onload = ()=> resolve(reader.result);
                                reader.readAsDataURL(blob);
                            });
                            img.setAttribute('src', dataUrl);
                        }catch(e){ /* ignore */ }
                    });
                    await Promise.all(tasks);
                }

                async function printNoticeSheet(){
                    const sheetEl = document.getElementById('noticeSheet');
                    if(!sheetEl) return;
                    const printable = sheetEl.cloneNode(true);
                    await inlineNoticeImages(printable);
                    const win = window.open('', '_blank');
                    const styles = `
                        <style>
                        body{margin:0;padding:0;background:#fff;color:#212529;font-family:Roboto,Arial,Helvetica,sans-serif}
                        .notice-sheet{width:210mm;max-height:297mm;margin:0 auto;padding:18mm 16mm;box-sizing:border-box;overflow:hidden}
                        .ns-header{text-align:center;position:relative}
                        .ns-header img{width:70px;height:70px;object-fit:contain}
                        .ns-date-abs{position:absolute;right:0;bottom:-6px;top:auto;text-align:right;font-size:12px}
                        .notice-sheet .ns-title{font-size:1.75rem;font-family: 'Roboto', sans-serif;}
                        .notice-sheet .ns-meta h3{font-size:1.25rem;font-family: 'Roboto', sans-serif;}
                        .notice-sheet .ns-meta h4{font-size:1rem;font-family: 'Roboto', sans-serif;}
                        .ns-title{font-size:18px;margin:4px 0 0 0;font-weight:700}
                        .ns-meta{font-size:11px;color:#6c757d;margin-top:2px}
                        h4{text-transform:uppercase;letter-spacing:1px;margin:10px 0}
                        .ns-content{font-size:13px;line-height:1.6;white-space:pre-wrap}
                        #noticeAttachmentPreview{display:none !important}
                        .ns-sign{margin-top:28px;page-break-inside: avoid}
                        .ns-sign img{max-height:70px;max-width:220px;object-fit:contain}
                        .ns-sign-line{width:220px;border-bottom:2px solid #6c757d;height:24px;margin-left:auto}
                        @page{size:A4;margin:0}
                        </style>`;
                    // Give the printed sheet a stable id for scaling logic
                    
                    printable.id = 'printSheet';
                    win.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Notice</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
                        <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300;400;500;700;900&family=Skranji&display=swap" rel="stylesheet">
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
                        ${styles}
                        </head><body></body></html>`);
                    win.document.body.appendChild(printable);
                    win.document.close();
                    win.focus();
                    // Ensure external stylesheets and webfonts are loaded before printing
                    try{
                        const d = win.document;
                        const linkPromises = Array.from(d.querySelectorAll('link[rel="stylesheet"]')).map(link => new Promise(resolve => {
                            // If stylesheet already parsed, proceed
                            if(link.sheet){ resolve(true); return; }
                            link.addEventListener('load', () => resolve(true), { once: true });
                            link.addEventListener('error', () => resolve(true), { once: true });
                            // Fallback timeout in case no events fire
                            setTimeout(() => resolve(true), 1500);
                        }));
                        await Promise.all(linkPromises);
                        if(d.fonts && d.fonts.ready){
                            // Wait for webfonts to finish loading, but cap to avoid hanging
                            await Promise.race([ d.fonts.ready, new Promise(r => setTimeout(r, 1500)) ]);
                        } else {
                            // Minimal delay for environments without FontFaceSet
                            await new Promise(r => setTimeout(r, 800));
                        }
                    }catch(_){ /* ignore */ }
                    // Auto-scale to fit exactly one A4 page
                    (function(){
                        try{
                            const ref = win.document.createElement('div');
                            ref.style.width = '1mm';
                            ref.style.height = '0';
                            win.document.body.appendChild(ref);
                            const pxPerMm = ref.getBoundingClientRect().width || 3.78; // fallback
                            ref.remove();
                            const pageW = 210 * pxPerMm;
                            const pageH = 297 * pxPerMm;
                            const el = win.document.getElementById('printSheet');
                            // Measure after a tick to allow layout
                            setTimeout(()=>{
                                const elW = el.scrollWidth;
                                const elH = el.scrollHeight;
                                const scale = Math.min(pageW / elW, pageH / elH, 1);
                                el.style.transformOrigin = 'top left';
                                el.style.transform = `scale(${scale})`;
                                // Ensure page size
                                win.document.body.style.width = '210mm';
                                win.document.body.style.height = '297mm';
                                setTimeout(()=>{ win.print(); win.close(); }, 200);
                            }, 50);
                        }catch(e){
                            setTimeout(()=>{ win.print(); win.close(); }, 200);
                        }
                    })();
                }

                function ensureHtml2Pdf(){
                    return new Promise((resolve,reject)=>{
                        if(typeof html2pdf !== 'undefined'){ resolve(true); return; }
                        const s = document.createElement('script');
                        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                        s.onload = ()=> resolve(true);
                        s.onerror = ()=> reject(new Error('Failed to load pdf lib'));
                        document.head.appendChild(s);
                    });
                }

                async function downloadNoticePdf(){
                    const el = document.getElementById('noticeSheet');
                    if(!el) return;
                    // If an attachment exists (pdf/image), download it from source
                    try{
                        const att = window.__noticeAttachment;
                        if(att && att.url && ['pdf','jpg','jpeg','png','webp','gif','avif'].includes((att.ext||'').toLowerCase())){
                            const href = window.__noticeBlobUrl ? window.__noticeBlobUrl : att.url;
                            const a = document.createElement('a');
                            a.href = href;
                            a.download = att.filename || 'attachment';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            return;
                        }
                    }catch(_){ }
                    // Otherwise, convert the text notice to PDF
                    ensureHtml2Pdf().then(()=>{
                        // Clone into an offscreen wrapper to ensure visibility for html2canvas
                        const wrapper = document.createElement('div');
                        wrapper.style.position = 'fixed';
                        wrapper.style.left = '0';
                        wrapper.style.top = '0';
                        wrapper.style.background = '#fff';
                        wrapper.style.opacity = '0.01';
                        wrapper.style.pointerEvents = 'none';
                        const clone = el.cloneNode(true);
                        // Remove attachment preview for PDF
                        const prev = clone.querySelector('#noticeAttachmentPreview');
                        if(prev){ prev.remove(); }
                        const runPdf = async ()=>{
                            await inlineNoticeImages(clone);
                            clone.style.margin = '0';
                            clone.style.boxSizing = 'border-box';
                            clone.style.width = '210mm';
                            clone.style.padding = '18mm 16mm';
                            clone.id = 'pdfSheet';
                            wrapper.appendChild(clone);
                            document.body.appendChild(wrapper);
                            // Allow layout to settle
                            await new Promise(r=>setTimeout(r,50));
                            const makePdf = async ()=>{
                                try{
                                    const h2c = window.html2canvas;
                                    const jspdfCtor = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF || null);
                                    if(!h2c || !jspdfCtor){ throw new Error('PDF libs missing'); }
                                    const canvas = await h2c(clone, { scale: 2, useCORS: true, backgroundColor: '#ffffff' });
                                    const imgData = canvas.toDataURL('image/jpeg', 0.98);
                                    const pdf = new jspdfCtor('p','mm','a4');
                                    const pageW = 210; const pageH = 297;
                                    let imgW = pageW; let imgH = canvas.height * imgW / canvas.width;
                                    if(imgH > pageH){
                                        imgH = pageH; imgW = canvas.width * imgH / canvas.height;
                                        if(imgW > pageW){
                                            const s = pageW / imgW; imgW *= s; imgH *= s;
                                        }
                                    }
                                    const x = (pageW - imgW)/2; const y = (pageH - imgH)/2;
                                    pdf.addImage(imgData, 'JPEG', x, y, imgW, imgH);
                                    const fname = (document.getElementById('noticeModalLabel')?.textContent || 'notice') + '.pdf';
                                    pdf.save(fname);
                                }catch(e){
                                    // Fallback to html2pdf if available
                                    try{
                                        const opt = {
                                            margin: 0,
                                            filename: (document.getElementById('noticeModalLabel')?.textContent || 'notice') + '.pdf',
                                            image: { type: 'jpeg', quality: 0.98 },
                                            html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
                                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                        };
                                        await html2pdf().set(opt).from(clone).save();
                                    }catch(_){
                                        printNoticeSheet();
                                    }
                                }finally{
                                    wrapper.remove();
                                }
                            };
                            makePdf();
                        };
                        runPdf();
                    }).catch(()=>{
                        // fallback: open print dialog (user can save as PDF)
                        printNoticeSheet();
                    });
                }

                // Ensure body scroll restores after modal closed
                document.getElementById('noticeModal').addEventListener('hidden.bs.modal', function(){
                    $('#noticeAttachmentPreview').addClass('d-none').empty();
                    $('#noticeDebugInfo').remove();
                    // Reset visibility state
                    $('#noticeSheetHeading').removeClass('d-none');
                    $('#noticePrintBtn').removeClass('d-none');
                    $('#noticeSignWrap').removeClass('d-none');
                    $('#noticePdfBtn').removeClass('d-none');
                    // Revoke any blob URL created for PDF preview
                    if(window.__noticeBlobUrl){ try{ URL.revokeObjectURL(window.__noticeBlobUrl); }catch(_){} window.__noticeBlobUrl = null; }
                    try{ window.__noticeAttachment = null; }catch(_){}
                    $('body').removeClass('modal-open').css('overflow','');
                    $('.modal-backdrop').remove();
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>