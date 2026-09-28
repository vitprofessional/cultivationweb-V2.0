@extends($frontendLayout ?? config('frontend.layout'))

@section('fronttitle', 'Governing Body Chairman / President')

@section('frontcontent')
    @php
        $name = trim((string) ($chairman->name ?? $chairman->fullName ?? ''));
        $designation = trim((string) ($chairman->designation ?? ''));
        $details = trim((string) ($chairman->jobDetails ?? ''));
        $message = trim((string) ($chairman->message ?? ''));
        $validYear = trim((string) ($chairman->validYear ?? ''));
        $avatar = $chairman?->photoUrl ?: asset('public/avatar.jpeg');
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
                            @if($validYear)<p class="text-muted small mt-2 mb-0">Term: {{ $validYear }}</p>@endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="h4 mb-3">Governing Body profile details</h2>
                            @if($details)
                                <div style="line-height:1.9">{!! nl2br(e($details)) !!}</div>
                            @else
                                <p class="text-muted mb-0">Profile details have not been added yet.</p>
                            @endif
                            <h2 class="h4 mt-4 mb-3">Message</h2>
                            @if($message)
                                <div style="line-height:1.9">{!! nl2br(e($message)) !!}</div>
                            @else
                                <p class="text-muted mb-0">No Chairman / President message has been added yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="col-12">
                            <h1 class="h4 mb-3">Governing Body Chairman / President</h1>
                            @if($chairmanAmbiguous ?? false)
                                <p class="text-muted mb-0">Multiple active Chairman / President records exist. The Governing Body directory needs review before one can be displayed.</p>
                            @else
                                <p class="text-muted mb-0">No active Chairman / President is listed in the Governing Body.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </article>
    </div>
@endsection