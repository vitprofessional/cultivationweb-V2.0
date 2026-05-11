@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Teacher Profile
@endsection
@php $config = App\Models\ServerConfig::first(); @endphp
@section('frontcontent')
<style>
.profile-wrap{max-width:900px;margin:2rem auto;padding:1.5rem 1.75rem;background:#fff;border:1px solid #e4e9f0;border-radius:18px;box-shadow:0 8px 28px -8px rgba(0,0,0,.15);display:grid;grid-template-columns:150px 1fr;gap:1.25rem}
.profile-photo{width:150px;height:150px;object-fit:cover;border-radius:50%;border:5px solid #f5f8fa;box-shadow:0 0 0 3px #198754}
.profile-meta h1{font-size:1.4rem;font-weight:800;margin:0 0 .4rem;color:#143528}
.profile-meta .designation{font-weight:600;color:#198754;margin-bottom:.75rem}
.profile-meta .contact{display:flex;flex-wrap:wrap;gap:.65rem;font-size:.75rem;margin-bottom:.9rem}
.profile-meta .contact span{display:inline-flex;align-items:center;gap:.4rem;padding:.4rem .6rem;background:#f1f6f4;border-radius:6px}
.profile-meta .contact i{color:#198754}
.profile-bio{font-size:.78rem;line-height:1.5;color:#4b5563;margin-top:.5rem}
@media(max-width:680px){.profile-wrap{grid-template-columns:1fr;text-align:center}.profile-meta h1{font-size:1.25rem}.profile-photo{margin:0 auto}}
</style>
@php
    $name = trim(($teacher->firstName ?? '').' '.($teacher->lastName ?? ''));
    $name = $name !== '' ? $name : 'Unknown';
    $designation = \App\Models\TeacherManagement::getDesignationName($teacher->designation ?? null);
    $photo = !empty($teacher->avatar)
        ? config('app.url').'/public/upload/image/teacher/'.rawurlencode(basename($teacher->avatar))
        : config('app.url').'/public/avatar.png';
@endphp
<div class="container">
    <div class="profile-wrap">
        <img class="profile-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy">
        <div class="profile-meta">
            <h1>{{ e($name) }}</h1>
            @if(!empty($designation))<div class="designation">{{ e($designation) }}</div>@endif
            <div class="contact">
                @if(!empty($teacher->email))<span><i class="fa-solid fa-envelope"></i>{{ e($teacher->email) }}</span>@endif
                @if(!empty($teacher->mobile))<span><i class="fa-solid fa-phone"></i>{{ e($teacher->mobile) }}</span>@endif
                @if(!empty($teacher->address))<span><i class="fa-solid fa-location-dot"></i>{{ e($teacher->address) }}</span>@endif
            </div>
            @if(!empty($teacher->description))
                <div class="profile-bio">{!! nl2br(e($teacher->description)) !!}</div>
            @endif
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('teacherPage') }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-arrow-left"></i> Back to all teachers</a>
    </div>
</div>
@endsection