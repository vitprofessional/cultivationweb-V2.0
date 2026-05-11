@extends('frontend.include')
@section('backTitle')
At a glance result
@endsection
@section('backIndex')
<div class="container py-4">
    <div class="alert alert-warning">This is a scaffolded placeholder of the Cultivation-V2 At-a-glance result view.
        Filters are present but data assembly is not yet implemented. Use this to confirm layout.</div>
    <form method="GET" action="{{ route('atGlanceResult') }}" class="row g-2 mb-3">
        <div class="col-md-3"><label>Exam</label>
            <select name="examId" class="form-control"><option value="">Select</option></select>
        </div>
        <div class="col-md-3"><label>Class</label>
            <select name="classId" class="form-control"><option value="">Select</option></select>
        </div>
        <div class="col-md-3"><label>Session</label>
            <select name="sessionId" class="form-control"><option value="">Select</option></select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-success">Load</button>
        </div>
    </form>

    @if(!$examId || !$classId || !$sessionId)
        <div class="alert alert-info">Please select required filters (Exam, Class & Session) to view results.</div>
    @else
        <div class="card">
            <div class="card-body">Placeholder: no results computed yet.</div>
        </div>
    @endif
</div>
@endsection
