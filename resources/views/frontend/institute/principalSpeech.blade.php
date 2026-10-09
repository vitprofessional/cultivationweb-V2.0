@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Head of Institute Message')
@section('portal-layout', 'leadership-message')
@section('frontcontent')
    @include('frontend.institute.partials.leadership-message')
@endsection
