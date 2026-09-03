@extends($frontendLayout ?? config('frontend.layout'))

@section('fronttitle', $notice->headline ?? 'Notice')

@section('frontcontent')
    @php
        $title = trim((string) ($notice->headline ?? $notice->title ?? $notice->name ?? 'Notice'));
        $body = $notice->body ?? $notice->details ?? $notice->description ?? '';
        $attachmentFile = !empty($notice->attachment) ? basename((string) $notice->attachment) : null;
        $attachmentUrl = $attachmentFile ? url('/public/upload/notice/' . rawurlencode($attachmentFile)) : null;
    @endphp

    <div class="col-12 col-lg-10 mx-auto">
        <article class="edu-main-card">
            <div class="edu-main-inner">
                <p class="text-muted mb-2">Published {{ optional($notice->created_at)->format('d M Y') }}</p>
                <h1 class="h2 mb-4">{{ $title }}</h1>
                @if(filled($body))
                    <div class="mb-4">{!! nl2br(e($body)) !!}</div>
                @endif
                @if($attachmentUrl)
                    <a class="btn btn-outline-primary" href="{{ $attachmentUrl }}" target="_blank" rel="noopener">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> Open attachment
                    </a>
                @endif
                <div class="mt-4">
                    <a href="{{ route('allNotices') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> All Notices
                    </a>
                </div>
            </div>
        </article>
    </div>
@endsection