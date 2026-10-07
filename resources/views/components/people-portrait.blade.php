@props(['src', 'alt', 'variant' => 'directory', 'imageClass' => '', 'frameClass' => ''])
@php
    // Inline vector fallback: no runtime asset request or stored-image modification.
    $silhouette = '<svg xmlns="http://www.w3.org/2000/svg" width="240" height="300" viewBox="0 0 240 300"><rect width="240" height="300" fill="#eef3f7"/><ellipse cx="120" cy="103" rx="44" ry="53" fill="#8295a8"/><path d="M25 300v-36c0-49 38-83 95-83s95 34 95 83v36Z" fill="#8295a8"/><path d="M99 151h42v39c-13 12-29 12-42 0Z" fill="#8295a8"/></svg>';
    $fallback = 'data:image/svg+xml,'.rawurlencode($silhouette);
    $portraitSource = !$src || $src === app(\App\Services\PublicAssetUrl::class)->url('avatar.png') ? $fallback : $src;
@endphp
<span class="people-photo-frame people-photo-frame--{{ $variant }} {{ $frameClass }}">
    <img class="people-portrait {{ $imageClass }}" src="{{ $portraitSource }}" alt="{{ $alt }}" loading="lazy" decoding="async" data-people-portrait data-portrait-fallback="{{ $fallback }}" onerror="this.onerror=null;this.dataset.portraitFallbackActive='true';this.src=this.dataset.portraitFallback;">
</span>
