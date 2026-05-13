@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Memorable Moment
@endsection
@section('frontcontent')
@php
    $galleryItems = $Datakey ?? collect();
@endphp
<style>
    .gallery-page-shell {
        background: linear-gradient(165deg, #f8fbff 0%, #eef6fd 100%);
        border: 1px solid #d8e7f3;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(16, 44, 99, 0.08);
        position: relative;
        overflow: hidden;
    }

    .gallery-page-shell::before {
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

    .gallery-page-head {
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

    .gallery-page-head .head-copy {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .gallery-page-head .head-copy span {
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

    .gallery-page-head h2 {
        margin: 0;
        color: #102c63;
        font-size: 34px;
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: 0.2px;
    }

    .gallery-page-head .head-note {
        margin: 0;
        color: #4a6484;
        font-size: 15px;
        line-height: 1.6;
        max-width: 720px;
    }

    .gallery-page-head .head-meta {
        color: #1f567f;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .35px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .gallery-page-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        grid-auto-rows: 92px;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .gallery-page-grid .gallery-tile {
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

    .gallery-page-grid .gallery-tile:first-child {
        grid-column: span 8;
        grid-row: span 4;
    }

    .gallery-page-grid .gallery-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }

    .gallery-page-grid .gallery-tile .g-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 29, 59, 0.12) 20%, rgba(10, 35, 74, 0.82) 100%);
        opacity: .86;
        transition: opacity .25s ease;
    }

    .gallery-page-grid .gallery-tile .g-plus {
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

    .gallery-page-grid .gallery-tile .g-date,
    .gallery-page-grid .gallery-tile .g-title {
        position: absolute;
        left: 12px;
        right: 12px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(7, 20, 39, 0.45);
    }

    .gallery-page-grid .gallery-tile .g-date {
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

    .gallery-page-grid .gallery-tile .g-title {
        bottom: 12px;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-page-grid .gallery-tile:first-child .g-title {
        font-size: 18px;
        max-width: 90%;
    }

    .gallery-page-grid .gallery-tile:hover {
        transform: translateY(-3px);
        border-color: #bdd9ed;
        box-shadow: 0 14px 30px rgba(19, 54, 102, 0.2);
    }

    .gallery-page-grid .gallery-tile:hover img {
        transform: scale(1.07);
    }

    .gallery-page-grid .gallery-tile:hover .g-shade {
        opacity: .58;
    }

    .gallery-page-grid .gallery-tile:hover .g-plus {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    .gallery-empty {
        padding: 18px;
        border-radius: 14px;
        background: #f8fbfc;
        border: 1px dashed #cfe3ec;
        color: #6d7d8b;
        text-align: center;
    }

    .gallery-modal .modal-dialog {
        max-width: 900px;
    }

    .gallery-modal .modal-content {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 24px 56px rgba(17, 41, 88, 0.35);
    }

    .gallery-modal .modal-header {
        background: linear-gradient(135deg, #273c66, #21a7d0);
        color: #fff;
        border-bottom: none;
        padding: 14px 18px;
    }

    .gallery-modal .modal-title {
        color: #fff;
        font-size: 20px;
        font-weight: 800;
    }

    .gallery-modal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: .95;
    }

    .gallery-modal .modal-body {
        padding: 0;
        background: #fff;
    }

    .gallery-modal .modal-image {
        width: 100%;
        max-height: 520px;
        object-fit: cover;
        display: block;
    }

    .gallery-modal .image-info {
        padding: 18px;
        background: #fff;
        border-top: 1px solid #e6eef5;
    }

    .gallery-modal .image-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #112958;
        margin-bottom: 6px;
    }

    .gallery-modal .image-subtitle {
        font-size: .98rem;
        color: #5f728b;
        margin-bottom: 0;
        line-height: 1.7;
    }

    .gallery-modal .modal-footer {
        background: #f8fbfc;
        border-top: 1px solid #e6eef5;
        padding: 14px 18px;
    }

    @media (max-width: 991.98px) {
        .gallery-page-shell {
            padding: 16px;
        }

        .gallery-page-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .gallery-page-head h2 {
            font-size: 28px;
        }

        .gallery-page-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            grid-auto-rows: 86px;
        }

        .gallery-page-grid .gallery-tile {
            grid-column: span 4;
        }

        .gallery-page-grid .gallery-tile:first-child {
            grid-column: span 8;
        }
    }

    @media (max-width: 575.98px) {
        .gallery-page-shell {
            padding: 12px;
        }

        .gallery-page-head h2 {
            font-size: 22px;
        }

        .gallery-page-head .head-note {
            font-size: 13px;
        }

        .gallery-page-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-auto-rows: 76px;
            gap: 8px;
        }

        .gallery-page-grid .gallery-tile {
            grid-column: span 1;
        }

        .gallery-page-grid .gallery-tile:first-child {
            grid-column: span 2;
        }

        .gallery-page-grid .gallery-tile .g-title,
        .gallery-page-grid .gallery-tile:first-child .g-title {
            font-size: 12px;
            left: 10px;
            right: 10px;
            bottom: 8px;
        }
    }
</style>

<section class="container mt-4 mb-4">
    <div class="gallery-page-shell">
        <div class="gallery-page-head">
            <div class="head-copy">
                <span>Campus Memories</span>
                <h2>Memorable Moment</h2>
                <p class="head-note">A curated collection of student achievements, campus events, and everyday moments that reflect the spirit of the institution.</p>
            </div>
            <div class="head-meta">{{ $galleryItems->count() > 0 ? $galleryItems->count() : 0 }} photos</div>
        </div>

        <div class="gallery-page-grid">
            @if($galleryItems->count() > 0)
                @foreach($galleryItems as $data)
                    @php
                        $imageSrc = config('app.url') . '/public/upload/image/PhotoGallery/' . rawurlencode(basename((string) $data->avatar));
                        $title = $data->title ?? 'Gallery Image';
                        $description = $data->description ?? 'Beautiful moment captured from campus life.';
                        $dateText = optional($data->created_at)->format('d M Y');
                    @endphp
                    <button type="button"
                        class="gallery-tile gallery-modal-trigger"
                        data-image="{{ $imageSrc }}"
                        data-title="{{ $title }}"
                        data-description="{{ $description }}"
                        data-date="{{ $dateText }}"
                        aria-label="Open {{ $title }}">
                        <img loading="lazy" decoding="async" src="{{ $imageSrc }}" alt="{{ $title }}">
                        <span class="g-shade"></span>
                        <span class="g-plus"><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                        <span class="g-date">{{ $dateText }}</span>
                        <span class="g-title">{{ $title }}</span>
                    </button>
                @endforeach
            @else
                <div class="col-12">
                    <div class="gallery-empty">Sorry! No content available right now.</div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Bootstrap Modal for Image Viewer -->
<div class="modal fade gallery-modal" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Gallery Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" alt="Gallery Image" class="modal-image">
                <div class="image-info">
                    <div id="imageTitle" class="image-title">Image Title</div>
                    <div id="imageSubtitle" class="image-subtitle">Image subtitle or description</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="downloadImage()">Download</button>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(imageSrc, title, subtitle) {
    // Set the image source
    document.getElementById('modalImage').src = imageSrc;
    
    // Set the title and subtitle
    document.getElementById('imageModalLabel').textContent = title;
    document.getElementById('imageTitle').textContent = title;
    document.getElementById('imageSubtitle').textContent = subtitle;
    
    // Store image source for download
    document.getElementById('imageModal').setAttribute('data-image-src', imageSrc);
    
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

function downloadImage() {
    const imageSrc = document.getElementById('imageModal').getAttribute('data-image-src');
    if(!imageSrc) return;
    let filename = 'gallery-image.jpg';
    try {
        const u = new URL(imageSrc, window.location.href);
        const pathname = decodeURI(u.pathname || '');
        const base = pathname.substring(pathname.lastIndexOf('/') + 1) || filename;
        filename = base;
    } catch(e) { /* fallback */ }
    const link = document.createElement('a');
    link.href = imageSrc;
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Optional: Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('imageModal').classList.contains('show')) {
        if (e.key === 'Escape') {
            bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();
        }
    }
});
</script>

@endsection