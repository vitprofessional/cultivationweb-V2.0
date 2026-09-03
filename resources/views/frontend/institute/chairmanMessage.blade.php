@extends($frontendLayout ?? config('frontend.layout'))

@section('fronttitle', 'Chairman Message')

@section('frontcontent')
    @php
        $name = trim((string) ($chairman->name ?? $chairman->fullName ?? ''));
        $designation = trim((string) ($chairman->designation ?? ''));
        $message = trim((string) ($chairman->message ?? $chairman->jobDetails ?? ''));
        $avatarFile = !empty($chairman?->avatar) ? basename((string) $chairman->avatar) : null;
        $avatar = $avatarFile && file_exists(public_path('upload/image/cultivation/' . $avatarFile))
            ? url('/public/upload/image/cultivation/' . rawurlencode($avatarFile))
            : asset('public/avatar.jpeg');
    @endphp

    <div class="col-12 col-lg-10 mx-auto">
        <article class="edu-main-card">
            <div class="edu-main-inner">
                <div class="row align-items-start g-4">
                    @if($name)
                        <div class="col-md-4 text-center">
                            <img src="{{ $avatar }}" alt="Photo of {{ $name }}" class="img-fluid rounded" style="max-width:220px;aspect-ratio:4/5;object-fit:cover;">
                            <h1 class="h4 mt-3 mb-1">{{ $name }}</h1>
                            @if($designation)<p class="text-muted fw-semibold mb-0">{{ $designation }}</p>@endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="h4 mb-3">Chairman Message</h2>
                            @if($message)
                                <div style="line-height:1.9">{!! nl2br(e($message)) !!}</div>
                            @else
                                <p class="text-muted mb-0">A full Chairman message has not been published yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="col-12">
                            <h1 class="h4 mb-3">Chairman Message</h1>
                            <p class="text-muted mb-0">Chairman information has not been published yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
@endsection