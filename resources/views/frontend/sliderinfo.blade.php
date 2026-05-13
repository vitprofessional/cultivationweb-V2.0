@php
    $sliderHeight = strtolower((string) request()->query('slider_height', 'md'));
    $allowedSliderHeights = ['sm', 'md', 'lg'];
    if (!in_array($sliderHeight, $allowedSliderHeights, true)) {
        $sliderHeight = 'md';
    }

    $fallbackSlides = [
        [
            'image' => config('app.url') . '/public/img/slider/slider1.jpg',
            'title' => 'Learning with Purpose',
            'detail' => 'A dynamic campus environment that inspires academic progress and personal growth.',
        ],
        [
            'image' => config('app.url') . '/public/img/slider/slider2.jpg',
            'title' => 'Excellence in Education',
            'detail' => 'Committed faculty, modern teaching methods, and strong student support systems.',
        ],
        [
            'image' => config('app.url') . '/public/img/slider/slider3.jpg',
            'title' => 'Future-Ready Students',
            'detail' => 'Building confidence, character, and competencies for tomorrow\'s opportunities.',
        ],
    ];
@endphp

@push('styles')
<style>
    .pro-slider {
        --slider-fixed-height: 510px;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(12, 43, 86, 0.2);
    }

    .pro-slider.slider-height-sm {
        --slider-fixed-height: 420px;
    }

    .pro-slider.slider-height-md {
        --slider-fixed-height: 510px;
    }

    .pro-slider.slider-height-lg {
        --slider-fixed-height: 620px;
    }

    .pro-slider .carousel-inner,
    .pro-slider .carousel-item,
    .pro-slider-media {
        height: var(--slider-fixed-height);
    }

    .pro-slider-media {
        position: relative;
    }

    .pro-slider-media::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(7, 24, 51, 0.12) 10%, rgba(7, 24, 51, 0.78) 100%);
    }

    .pro-slider-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transform: scale(1.01);
    }

    .pro-slider .carousel-caption {
        right: auto;
        left: 42px;
        bottom: 38px;
        max-width: min(640px, calc(100% - 84px));
        text-align: left;
        z-index: 2;
        padding: 18px 22px;
        border-radius: 14px;
        background: rgba(11, 40, 83, 0.58);
        backdrop-filter: blur(4px);
    }

    .pro-slider .carousel-caption h5 {
        color: #fff;
        font-size: clamp(20px, 2.1vw, 34px);
        line-height: 1.2;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .pro-slider .carousel-caption p {
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        font-size: clamp(14px, 1vw, 17px);
        line-height: 1.6;
    }

    .pro-slider .carousel-control-prev,
    .pro-slider .carousel-control-next {
        width: 52px;
        top: 50%;
        bottom: auto;
        transform: translateY(-50%);
        opacity: 1;
    }

    .pro-slider .carousel-control-prev {
        left: 18px;
    }

    .pro-slider .carousel-control-next {
        right: 18px;
    }

    .pro-slider .carousel-control-prev-icon,
    .pro-slider .carousel-control-next-icon {
        width: 46px;
        height: 46px;
        border-radius: 999px;
        background-color: rgba(255, 255, 255, 0.9);
        background-size: 50% 50%;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }

    .pro-slider .carousel-indicators {
        margin-bottom: 14px;
        z-index: 3;
    }

    .pro-slider .carousel-indicators [data-bs-target] {
        width: 11px;
        height: 11px;
        border-radius: 999px;
        border: 0;
        margin: 0 5px;
        opacity: 0.65;
        background-color: #fff;
    }

    .pro-slider .carousel-indicators .active {
        opacity: 1;
        width: 28px;
    }

    @media (max-width: 991.98px) {
        .pro-slider.slider-height-sm {
            --slider-fixed-height: 360px;
        }

        .pro-slider.slider-height-md {
            --slider-fixed-height: 420px;
        }

        .pro-slider.slider-height-lg {
            --slider-fixed-height: 480px;
        }

        .pro-slider .carousel-caption {
            left: 22px;
            bottom: 20px;
            max-width: calc(100% - 44px);
            padding: 14px 16px;
        }
    }

    @media (max-width: 575.98px) {
        .pro-slider.slider-height-sm {
            --slider-fixed-height: 260px;
        }

        .pro-slider.slider-height-md {
            --slider-fixed-height: 300px;
        }

        .pro-slider.slider-height-lg {
            --slider-fixed-height: 340px;
        }

        .pro-slider .carousel-caption p {
            display: none;
        }

        .pro-slider .carousel-control-prev,
        .pro-slider .carousel-control-next {
            display: none;
        }
    }
</style>
@endpush

<div class="row">
    <div class="col-12">
        <div id="carouselExampleCaptions" class="carousel slide pro-slider slider-height-{{ $sliderHeight }}" data-bs-ride="carousel" data-bs-pause="hover" data-bs-interval="5000">
            <div class="carousel-indicators">
                @if($sliderData->count() > 0)
                    @foreach($sliderData as $index => $slider)
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="@if($index === 0) active @endif" @if($index === 0) aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                @else
                    @foreach($fallbackSlides as $index => $fallbackSlide)
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="@if($index === 0) active @endif" @if($index === 0) aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                @endif
            </div>

            <div class="carousel-inner">
                @if($sliderData->count() > 0)
                    @foreach($sliderData as $index => $slider)
                        @php
                            $sliderImage = !empty($slider->avatar)
                                ? config('app.url') . '/public/upload/image/webHomepage/' . rawurlencode(basename($slider->avatar))
                                : config('app.url') . '/public/img/slider/slider1.jpg';
                        @endphp
                        <div class="carousel-item @if($index === 0) active @endif">
                            <div class="pro-slider-media">
                                <img src="{{ $sliderImage }}" class="pro-slider-image" alt="Slide {{ $index + 1 }}" />
                            </div>
                            <div class="carousel-caption">
                                <h5>{{ !empty($slider->headLine) ? $slider->headLine : 'Welcome to Our Institution' }}</h5>
                                <p>{{ !empty($slider->detail) ? $slider->detail : 'Dedicated to quality education, leadership, and holistic student success.' }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach($fallbackSlides as $index => $fallbackSlide)
                        <div class="carousel-item @if($index === 0) active @endif">
                            <div class="pro-slider-media">
                                <img src="{{ $fallbackSlide['image'] }}" class="pro-slider-image" alt="Slide {{ $index + 1 }}" />
                            </div>
                            <div class="carousel-caption">
                                <h5>{{ $fallbackSlide['title'] }}</h5>
                                <p>{{ $fallbackSlide['detail'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
