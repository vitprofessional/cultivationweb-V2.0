@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
All Classes Exam Routine
@endsection

@section('frontcontent')
@include('frontend.academic.partials._all_class_css')
<style>
.acr-sheet{max-width:1100px;margin:0 auto;padding:28px 26px;border:1px solid var(--border);border-radius:6px;overflow-x:auto}
.acr-links{max-width:1100px;margin:12px auto 0;display:flex;flex-wrap:wrap;gap:6px 18px;justify-content:center;font-size:12.5px}
.acr-links a{color:var(--text2);text-decoration:underline}.acr-links a:hover{color:var(--navy)}
</style>
<section class="container mt-4 mb-4">
    <div class="acr-page">
        <div class="acr-sheet">@include('frontend.academic.partials._all_class_grid')</div>
        <div class="acr-links acr-noprint">
            <a href="{{ route('newExamSchedule') }}">&larr; Back to Exam Routines</a>
            <a href="{{ route('examRoutine.allClass.print', ['id' => $routine->id, 'orientation' => 'landscape']) }}" target="_blank">Print (Landscape)</a>
            <a href="{{ route('examRoutine.allClass.print', ['id' => $routine->id, 'orientation' => 'portrait']) }}" target="_blank">Print (Portrait)</a>
            <a href="{{ route('examRoutine.allClass.download', ['id' => $routine->id, 'orientation' => 'landscape']) }}">Download PDF (Landscape)</a>
            <a href="{{ route('examRoutine.allClass.download', ['id' => $routine->id, 'orientation' => 'portrait']) }}">Download PDF (Portrait)</a>
        </div>
    </div>
</section>
@endsection
