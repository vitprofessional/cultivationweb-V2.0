@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Governing Body Chairman / President')
@section('portal-layout', 'leadership-message')
@section('frontcontent')
    @include('frontend.institute.partials.leadership-message', [
        'isGoverningMessage' => true,
        'messagePageTitle' => 'সভাপতির বাণী',
        'messagePageSubtitle' => 'Message from the Governing Body President',
        'breadcrumbLabel' => 'President’s Message',
        'leadershipProfile' => [
            'name' => trim((string) ($chairman?->name ?? $chairman?->fullName ?? '')),
            'designation' => trim((string) ($chairman?->designation ?? '')),
            'photoUrl' => $chairman?->photoUrl,
            'message' => $chairman?->message,
        ],
        'messageNotice' => ($chairmanAmbiguous ?? false)
            ? 'Multiple active Chairman / President records exist. The Governing Body directory needs review before one can be displayed.'
            : ($chairman ? 'No Chairman / President message has been added yet.' : 'No active Chairman / President is listed in the Governing Body.'),
    ])
@endsection
