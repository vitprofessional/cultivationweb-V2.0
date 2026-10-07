@extends('frontend.cultivation-v2.page')
@section('fronttitle', 'Login')
@section('portal-layout', '1')
@section('frontcontent')
<div class="col-12">
<section class="portal-hub" aria-labelledby="portal-heading">
@php
    $portalIcons = [
        'admin' => 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M8 9h1m6 0h1M8 13h1m6 0h1',
        'teacher' => 'M3 3h18v12H11M7 21v-5m-3 5v-5m0-7a3 3 0 1 0 6 0 3 3 0 1 0-6 0m6 5 5-4M14 7h4',
        'student' => 'm2 8 10-5 10 5-10 5-10-5m4 3v6c4 3 8 3 12 0v-6m4-3v8',
        'guardian' => 'M8 11a4 4 0 1 0 0-8 4 4 0 1 0 0 8M2 21v-3a6 6 0 0 1 12 0v3m3-9a3 3 0 1 0 0-6m0 9a5 5 0 0 1 5 5v1',
    ];
@endphp
<header class="portal-intro"><span class="portal-eyebrow">INSTITUTION PORTALS</span><h1 id="portal-heading">Choose Your Portal</h1><p>Select your role to securely access the institution portal.</p></header>
<div class="portal-grid">
@foreach($portals as $portal)
<article class="portal-card" data-portal="{{ $portal['key'] }}"><span class="portal-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $portalIcons[$portal['key']] }}"/></svg></span><h2>{{ $portal['name'] }}</h2><p>{{ $portal['description'] }}</p>
@if($portal['url'])<a class="portal-action" href="{{ $portal['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $portal['name'] }} login">Login <span aria-hidden="true">&#8594;</span></a>@else<button class="portal-action portal-unavailable" type="button" disabled>Not configured</button>@endif
</article>
@endforeach
</div></section></div>
<style>
.portal-hub{max-width:1000px;margin:auto;padding:12px 0 24px;color:#17334f}.portal-intro{max-width:620px;margin-bottom:28px}.portal-eyebrow{text-transform:uppercase;font-size:12px;font-weight:700;letter-spacing:1.2px;color:#476788}.portal-intro h1{font-size:clamp(28px,4vw,38px);margin:10px 0 12px;color:#102c63}.portal-intro p,.portal-card p{color:#52667c;line-height:1.65}.portal-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.portal-card{min-width:0;display:flex;flex-direction:column;padding:24px;border:1px solid #dce5ef;border-radius:14px;background:#fff;box-shadow:0 6px 18px #102c6308}.portal-mark{display:grid;place-items:center;width:42px;height:42px;background:#edf3fa;color:#173f70;border-radius:10px;font-weight:800;margin-bottom:18px}.portal-card h2{font-size:21px;margin:0 0 10px}.portal-card p{margin:0 0 24px}.portal-action{margin-top:auto;display:flex;justify-content:space-between;gap:12px;align-items:center;min-height:46px;border-radius:8px;padding:12px 16px;background:#173f70;color:#fff!important;font-weight:600}.portal-action:hover{background:#102c63}.portal-action:focus-visible{outline:3px solid #21a7d0;outline-offset:3px}.portal-unavailable{margin-top:auto;font-size:13px;line-height:1.6;color:#66788b}@media(max-width:767px){.portal-grid{grid-template-columns:1fr}.portal-card{padding:20px}.portal-hub{padding-top:0}}
</style>
<style>
.portal-page-shell.edu-main-card{border:0;box-shadow:none;background:transparent;padding:0}.portal-page-shell .edu-main-inner{padding:0}.portal-hub{max-width:1160px;padding:18px 0 32px}.portal-brand{display:flex;justify-content:center;align-items:center;gap:14px;margin-bottom:24px;text-align:center}.portal-brand img{width:56px;height:56px;object-fit:contain}.portal-brand strong{font-size:clamp(18px,2.5vw,25px);overflow-wrap:anywhere}.portal-intro{text-align:center;margin:0 auto 30px}.portal-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}.portal-card{align-items:center;text-align:center;padding:26px 20px 20px}.portal-mark{width:58px;height:58px;border-radius:14px}.portal-mark svg{width:30px;height:30px}.portal-card p{font-size:14px}.portal-action{box-sizing:border-box;width:100%;justify-content:center;border:0;font:inherit;font-weight:600;text-decoration:none}.portal-action:focus-visible{outline:3px solid #21a7d0;outline-offset:4px}.portal-action.portal-unavailable{background:#e8edf3;color:#5d6c7c!important;cursor:not-allowed}.portal-card p{overflow-wrap:anywhere}@media(max-width:991px){.portal-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575px){.portal-grid{grid-template-columns:1fr}.portal-card{padding:22px}.portal-hub{padding-top:4px}}
.portal-intro{margin:0 auto 24px;padding:4px 12px 0}.portal-intro h1{font-weight:800;line-height:1.18;margin:8px 0 10px}.portal-intro p{font-size:15px;line-height:1.6;margin:0}.portal-eyebrow{font-size:11px;letter-spacing:1.5px}
</style>
@endsection
