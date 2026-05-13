@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Memorable Moment(Video)
@endsection
@section('frontcontent')
@php
    $videoItems = $Datakey ?? collect();
@endphp
<style>
    .video-gallery-shell {
        background: linear-gradient(165deg, #f8fbff 0%, #eef6fd 100%);
        border: 1px solid #d8e7f3;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(16, 44, 99, 0.08);
        position: relative;
        overflow: hidden;
    }

    .video-gallery-shell::before {
        content: "";
        position: absolute;
        right: -90px;
        top: -90px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(33, 167, 208, 0.18), rgba(33, 167, 208, 0));
        pointer-events: none;
    }

    .video-gallery-head {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 14px;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid #d9e8f5;
    }

    .video-gallery-head .head-copy {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .video-gallery-head .head-copy span {
        display: inline-flex;
        width: fit-content;
        font-size: 11px;
        line-height: 1;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: #0d7c9f;
        font-weight: 800;
        padding: 6px 10px;
        border-radius: 999px;
        background: #dff4fb;
        border: 1px solid #bee8f5;
    }

    .video-gallery-head h2 {
        margin: 0;
        color: #102c63;
        font-size: 34px;
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: 0.2px;
    }

    .video-gallery-head .head-note {
        margin: 0;
        color: #4a6484;
        font-size: 15px;
        line-height: 1.6;
        max-width: 720px;
    }

    .video-gallery-head .head-meta {
        color: #1f567f;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .35px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .video-gallery-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        grid-auto-rows: 92px;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .video-gallery-grid .video-tile {
        grid-column: span 4;
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

    .video-gallery-grid .video-tile:first-child {
        grid-column: span 8;
        grid-row: span 4;
    }

    .video-gallery-grid .video-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }

    .video-gallery-grid .video-tile .video-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 29, 59, 0.08) 20%, rgba(10, 35, 74, 0.84) 100%);
        opacity: .88;
        transition: opacity .25s ease;
    }

    .video-gallery-grid .video-tile .video-plus {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(.9);
        background: rgba(255, 255, 255, 0.96);
        color: #112958;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        opacity: 0;
        transition: all .25s ease;
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
    }

    .video-gallery-grid .video-tile .video-title,
    .video-gallery-grid .video-tile .video-meta {
        position: absolute;
        left: 12px;
        right: 12px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(7, 20, 39, 0.45);
    }

    .video-gallery-grid .video-tile .video-meta {
        top: 12px;
        width: fit-content;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .45px;
        text-transform: uppercase;
        padding: 6px 9px;
        border-radius: 999px;
        background: rgba(17, 41, 88, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .video-gallery-grid .video-tile .video-title {
        bottom: 12px;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .video-gallery-grid .video-tile:first-child .video-title {
        font-size: 18px;
        max-width: 90%;
    }

    .video-gallery-grid .video-tile .video-play-pill {
        position: absolute;
        left: 12px;
        bottom: 12px;
        width: fit-content;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .45px;
        text-transform: uppercase;
        padding: 6px 9px;
        border-radius: 999px;
        background: rgba(33, 167, 208, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #fff;
    }

    .video-gallery-grid .video-tile:hover {
        transform: translateY(-3px);
        border-color: #bdd9ed;
        box-shadow: 0 14px 30px rgba(19, 54, 102, 0.2);
    }

    .video-gallery-grid .video-tile:hover img {
        transform: scale(1.07);
    }

    .video-gallery-grid .video-tile:hover .video-shade {
        opacity: .62;
    }

    .video-gallery-grid .video-tile:hover .video-plus {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    .video-empty {
        padding: 18px;
        border-radius: 14px;
        background: #f8fbfc;
        border: 1px dashed #cfe3ec;
        color: #6d7d8b;
        text-align: center;
    }

    @media (max-width: 991.98px) {
        .video-gallery-shell {
            padding: 16px;
        }

        .video-gallery-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .video-gallery-head h2 {
            font-size: 28px;
        }

        .video-gallery-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            grid-auto-rows: 86px;
        }

        .video-gallery-grid .video-tile {
            grid-column: span 4;
        }

        .video-gallery-grid .video-tile:first-child {
            grid-column: span 8;
        }
    }

    @media (max-width: 575.98px) {
        .video-gallery-shell {
            padding: 12px;
        }

        .video-gallery-head h2 {
            font-size: 22px;
        }

        .video-gallery-head .head-note {
            font-size: 13px;
        }

        .video-gallery-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-auto-rows: 76px;
            gap: 8px;
        }

        .video-gallery-grid .video-tile {
            grid-column: span 1;
        }

        .video-gallery-grid .video-tile:first-child {
            grid-column: span 2;
        }

        .video-gallery-grid .video-tile .video-title,
        .video-gallery-grid .video-tile:first-child .video-title {
            font-size: 12px;
            left: 10px;
            right: 10px;
            bottom: 8px;
        }
    }
</style>

<section class="container mt-4 mb-4">
    <div class="video-gallery-shell">
        <div class="video-gallery-head">
            <div class="head-copy">
                <span>Campus Memories</span>
                <h2>Memorable Moment(Video)</h2>
                <p class="head-note">A curated collection of campus stories and event highlights presented with a clean, modern gallery layout.</p>
            </div>
            <div class="head-meta">{{ $videoItems->count() > 0 ? $videoItems->count() : 0 }} videos</div>
        </div>

        <div class="video-gallery-grid">
            @if($videoItems->count() > 0)
                @foreach($videoItems as $data)
                    @php
                        $thumbSrc = asset('/public/upload/image/photogallery/') . '/' . $data->avatar;
                        $videoTitle = $data->title ?? $data->headline ?? ('Video ' . $loop->iteration);
                        $videoNote = $data->description ?? 'Click to open the media preview.';
                    @endphp
                    <a class="video-tile wow fadeIn animated" data-wow-delay=".60s" href="{{ $thumbSrc }}" data-lightbox="mygallery" data-toggle="modal" data-target="#" aria-label="Open {{ $videoTitle }}">
                        <img data-bs-toggle="modal" data-bs-target="#staticBackdrop" src="{{ $thumbSrc }}" alt="{{ $videoTitle }}" loading="lazy" decoding="async" />
                        <span class="video-shade"></span>
                        <span class="video-plus"><i class="fa fa-play" aria-hidden="true"></i></span>
                        <span class="video-meta">Video</span>
                        <span class="video-title">{{ $videoTitle }}</span>
                        <span class="video-play-pill">{{ $videoNote }}</span>
                    </a>
                @endforeach
            @else
                <div class="col-12">
                    <div class="video-empty">Sorry! No content available right now.</div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection