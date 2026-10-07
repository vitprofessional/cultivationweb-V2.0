@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Our Staff')
@section('portal-layout', '1')
@section('frontcontent')
@include('frontend.institute.partials.people-directory', ['records' => $Datakey ?? collect(), 'kind' => 'staff'])
@endsection
