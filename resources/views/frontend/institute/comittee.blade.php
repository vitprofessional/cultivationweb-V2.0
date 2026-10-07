@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Governing Body')
@section('portal-layout', '1')
@section('frontcontent')
@include('frontend.institute.partials.people-directory', ['records' => $Datakey ?? collect(), 'kind' => 'committee'])
@endsection
