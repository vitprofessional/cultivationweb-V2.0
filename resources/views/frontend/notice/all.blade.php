@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle','All Notices')
@section('frontcontent')
@php
    // Expecting $notices (LengthAwarePaginator or Collection)
@endphp
<div class="container py-4">
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <h2 class="mb-4 fw-bold">All Notices</h2>
            @if($notices->count())
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                    @foreach($notices as $notice)
                        @php
                            // Use 'headline' as seen elsewhere; fallback chain ensures some text
                            $rawTitle = $notice->headline ?? $notice->title ?? $notice->name ?? 'Untitled';
                            $title = e($rawTitle);
                            $body  = $notice->body ?? $notice->details ?? $notice->description ?? '';
                            // Base64 encode body for safe transport (avoid attribute HTML breakage)
                            $body64 = base64_encode($body ?? '');
                            $date = optional($notice->created_at)->format('d M Y');
                            $attachment = $notice->attachment ?? '';
                            $attachmentUrl = app(\App\Services\PublicMediaUrl::class)->notice($attachment);
                        @endphp
                        <a href="{{ route('notice.show', $notice) }}" class="list-group-item list-group-item-action notice-view py-3"
                           data-title="{{ $title }}"
                           data-body64="{{ $body64 }}"
                           data-date="{{ $date }}"
                           @if($attachment) data-attachment="{{ $attachment }}" @endif
                           @if($attachmentUrl) data-attachment-url="{{ $attachmentUrl }}" @endif
                        >
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h5 class="mb-1 fw-semibold text-truncate" style="max-width:70%">{{ $title }}</h5>
                                <small class="text-muted"><i class="fa-solid fa-clock me-1"></i>{{ $date }}</small>
                            </div>
                            <p class="mb-0 text-muted small">
                                @php
                                    $plain = strip_tags($body);
                                    $short = mb_substr($plain,0,140,'UTF-8');
                                    if(mb_strlen($plain,'UTF-8')>140){ $short .= '…'; }
                                @endphp
                                {{ $short }}
                            </p>
                            @if($attachment)
                                <span class="badge bg-success mt-2"><i class="fa-solid fa-paperclip me-1"></i>Attachment</span>
                            @endif
                        </a>
                    @endforeach
                        </div>
                    </div>
                </div>
                @if(method_exists($notices,'links'))
                    <div class="d-flex justify-content-center">
                        {{ $notices->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">No notices found.</div>
            @endif
            <div class="mt-3">
                <a href="{{ route('homePage') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
