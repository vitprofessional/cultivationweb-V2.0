@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Student Profile
@endsection
@php $config = App\Models\ServerConfig::first(); @endphp
@section('frontcontent')
<style>
.profile-wrap{max-width:900px;margin:2rem auto;padding:1.5rem 1.75rem;background:#fff;border:1px solid #e4e9f0;border-radius:18px;box-shadow:0 8px 28px -8px rgba(0,0,0,.15);display:grid;grid-template-columns:150px 1fr;gap:1.25rem}
.profile-photo{width:150px;height:150px;object-fit:cover;border-radius:50%;border:5px solid #f5f8fa;box-shadow:0 0 0 3px #0ea5e9}
.profile-meta h1{font-size:1.35rem;font-weight:800;margin:0 0 .4rem;color:#0f172a}
.profile-meta .chips{display:flex;flex-wrap:wrap;gap:.5rem;margin:.4rem 0 .75rem}
.profile-meta .chips span{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .55rem;background:#f1f6f4;border-radius:6px;font-size:.72rem;color:#334155}
.profile-meta .chips i{color:#0ea5e9}
.profile-meta .facts{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.5rem;font-size:.82rem}
.fact{background:#f8fafc;border:1px solid #eef2f7;border-radius:10px;padding:.6rem .75rem}
.fact strong{display:block;font-size:.72rem;color:#64748b;font-weight:700}
@media(max-width:680px){.profile-wrap{grid-template-columns:1fr;text-align:center}.profile-photo{margin:0 auto}}
</style>
@php
    $name = trim(($student->fullName ?? '').' '.($student->sureName ?? '')) ?: 'Unknown';
    $photo = !empty($student->avatar)
        ? config('app.url').'/public/upload/image/student/'.rawurlencode(basename($student->avatar))
        : config('app.url').'/public/avatar.png';
@endphp
<div class="container">
  <div class="profile-wrap">
    <img class="profile-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy">
    <div class="profile-meta">
      <h1>{{ e($name) }}</h1>
      <div class="chips">
        <span><i class="fa-solid fa-id-badge"></i> {{ e($student->stdId ?? '-') }}</span>
        @if($class)<span><i class="fa-solid fa-graduation-cap"></i> {{ e($class->className) }}</span>@endif
        @if($dept)<span><i class="fa-solid fa-diagram-project"></i> {{ e($dept->departmentName) }}</span>@endif
        @if($section)<span><i class="fa-solid fa-people-group"></i> {{ e($section->section) }}</span>@endif
        @if($session)<span><i class="fa-solid fa-calendar"></i> {{ e($session->session) }}</span>@endif
      </div>
      <div class="facts">
        <div class="fact"><strong>Roll / Student ID</strong>{{ e($student->stdId ?? '-') }}</div>
        <div class="fact"><strong>Class</strong>{{ e(optional($class)->className ?? '-') }}</div>
        <div class="fact"><strong>Department</strong>{{ e(optional($dept)->departmentName ?? '-') }}</div>
        <div class="fact"><strong>Section</strong>{{ e(optional($section)->section ?? '-') }}</div>
        <div class="fact"><strong>Session</strong>{{ e(optional($session)->session ?? '-') }}</div>
      </div>
    </div>
  </div>
  <div class="text-center mt-3">
    <a href="{{ route('student') }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-arrow-left"></i> Back to student list</a>
  </div>
</div>
@endsection