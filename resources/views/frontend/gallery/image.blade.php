@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Memorable Moment
@endsection
@section('frontcontent')
<style>
#myTable th, #myTable td {
    text-align: left !important;
    vertical-align: center;
}
#myTable th {
    font-weight: bold;
}

/* Gallery styles */
.gallery-item {
    margin-bottom: 20px;
}

.gallery-item img {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    cursor: pointer;
}

.gallery-item img:hover {
    transform: scale(1.05);
}

/* Modal styles */
.modal-dialog {
    max-width: 800px;
}

.modal-body {
    padding: 0;
}

.modal-image {
    width: 100%;
    height: auto;
    border-radius: 0.375rem;
}

.modal-content {
    background: transparent;
    border: none;
}

.modal-header {
    background: linear-gradient(135deg, #198754, #20c997);
    color: white;
    border-bottom: none;
    border-radius: 0.375rem 0.375rem 0 0;
}

.modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 0.375rem 0.375rem;
}

.image-info {
    padding: 15px;
    background: white;
    border-radius: 0 0 0.375rem 0.375rem;
}

.image-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #198754;
    margin-bottom: 5px;
}

.image-subtitle {
    font-size: 0.95rem;
    color: #6c757d;
    margin-bottom: 0;
}
</style>

<section class="container mt-4">
    <div class="row">
        <div class="col-md-12 text-center con-title my-4">
            <h2 class="hedingAbout wow fadeInLeft animated" data-wow-delay=".60s">
                Memorable <span>Moment</span>
            </h2>
        </div>
    </div>
    
    <div class="row g-3 gallery-grid">
        @if($Datakey->count() > 0) 
            @foreach($Datakey as $data)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="gallery-card" role="button" tabindex="0" onclick="showImageModal('{{ config('app.url') }}/public/upload/image/PhotoGallery/{{ $data->avatar }}', '{{ $data->title ?? 'Gallery Image' }}', '{{ $data->description ?? 'Beautiful moment captured' }}')" onkeypress="if(event.key==='Enter'){showImageModal('{{ config('app.url') }}/public/upload/image/PhotoGallery/{{ $data->avatar }}', '{{ $data->title ?? 'Gallery Image' }}', '{{ $data->description ?? 'Beautiful moment captured' }}')}">
                        <img loading="lazy" decoding="async" src="{{ config('app.url') }}/public/upload/image/PhotoGallery/{{ $data->avatar }}" 
                             alt="{{ $data->title ?? 'Gallery image' }}"
                             class="g-img" />
                        <div class="g-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Sorry! No content available right now
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Bootstrap Modal for Image Viewer -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
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