@php
    $socialTitle = trim((string) ($config?->instituteName ?? '')) ?: 'Official Website';
    $socialDescription = 'Official website'.(!empty($config?->instituteName) ? ' of '.$config->instituteName : '').'. Institution information, academic resources and notices.';
    $socialImage = app(\App\Services\SocialPreview::class)->url($config ?? null);
@endphp
<meta property="og:title" content="{{ $socialTitle }}">
<meta property="og:description" content="{{ $socialDescription }}">
<meta property="og:image" content="{{ $socialImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/png">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $socialTitle }}">
<meta name="twitter:description" content="{{ $socialDescription }}">
<meta name="twitter:image" content="{{ $socialImage }}">
