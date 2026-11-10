@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Exam Routine
@endsection

@php
$rows = $Datakey ?? collect();
$groups = $examGroups ?? collect();
@endphp

@section('frontcontent')
@include('frontend.academic.partials._theme')
@include('frontend.academic.partials._table_theme')
<style>
.academic-page-shell {
    background: linear-gradient(180deg, #f8fbff 0%, #f2f8fd 100%);
    border: 1px solid #dce9f4;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 12px 28px rgba(19, 54, 102, 0.08);
}
.academic-page-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 12px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #dce9f4;
}
.academic-page-head .kicker {
    display: inline-flex;
    font-size: 11px;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #0c7da7;
    font-weight: 800;
    padding: 5px 10px;
    border-radius: 999px;
    border: 1px solid #bce7f4;
    background: #dff4fb;
}
.academic-page-head h2 { margin: 6px 0 0; font-size: 22px; color: #112958; font-weight: 800; }
.academic-page-head p { margin: 6px 0 0; color: #5a708a; font-size: 14px; }
.academic-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e7f9fb;
    color: #1285af;
    border: 1px solid #b8e8f5;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
    white-space: nowrap;
}
.academic-table-wrap { background: #fff; border: 1px solid #dce9f4; border-radius: 14px; padding: 16px; }
.academic-filter .form-select, .academic-filter .btn { min-height: 36px; font-size: 13px; }
.academic-filter .form-select { border-color: #cfe0ef; }

/* ---- one card per exam group ---- */
.exam-group-list { display: grid; gap: 14px; }
.exam-group-card {
    border: 1px solid #dce9f4;
    border-radius: 14px;
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    padding: 16px 18px;
}
.exam-group-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; flex-wrap: wrap; }
.exam-group-title { margin: 0; font-size: 18px; font-weight: 800; color: #112958; line-height: 1.35; }
.exam-group-sub { margin: 3px 0 0; font-size: 12px; color: #6e859d; font-weight: 600; }
.exam-all-wrap { text-align: right; }
.exam-all-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: #112958; color: #fff !important; border: 1px solid #112958;
    border-radius: 10px; padding: 10px 18px; font-size: 14px; font-weight: 800;
    text-decoration: none; box-shadow: 0 6px 14px rgba(17, 41, 88, .22);
}
.exam-all-btn:hover { background: #0c7da7; border-color: #0c7da7; }
.exam-all-btn.is-disabled {
    background: #eef2f6; color: #8a99a8 !important; border-color: #dce4ec; box-shadow: none; cursor: not-allowed;
}
.exam-all-note { display: block; margin-top: 5px; font-size: 11.5px; color: #8a99a8; font-weight: 600; }

.exam-class-label { margin: 14px 0 6px; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #6e859d; }
.exam-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.exam-pill {
    border: 1px solid #cadeef; background: #e8f2fb; color: #22698e;
    border-radius: 999px; padding: 6px 14px; font-size: 13px; font-weight: 700; cursor: pointer; line-height: 1.2;
}
.exam-pill:hover { background: #d7e9f7; }
.exam-pill.is-active { background: #1b7096; border-color: #1b7096; color: #fff; }
.exam-pill small { font-weight: 600; opacity: .85; color: inherit !important; }

.exam-panel { display: none; margin-top: 12px; padding: 12px 14px; border: 1px solid #deebf5; border-radius: 10px; background: #fff; }
.exam-panel.is-active { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; }
.exam-panel-meta { font-size: 13px; color: #3f5772; font-weight: 600; }
.exam-panel-meta b { color: #244766; }
.exam-panel .routine-actions { display: flex; gap: 6px; }
.exam-panel .routine-actions .btn { min-width: 56px; font-size: 12px; font-weight: 700; }
.routine-actions .btn-academic-info { color: #1f7a69 !important; background: #e5f5f1 !important; border-color: #c7e8df !important; }
.routine-actions .btn-academic-info:hover { color: #fff !important; background: #2b8a78 !important; border-color: #2b8a78 !important; }
.routine-actions .btn-academic-primary { color: #22698e !important; background: #e8f2fb !important; border-color: #cadeef !important; }
.routine-actions .btn-academic-primary:hover { color: #fff !important; background: #1b7096 !important; border-color: #1b7096 !important; }
.routine-actions .btn-academic-danger { color: #a64b4b !important; background: #fbeaea !important; border-color: #f0cccc !important; }
.routine-actions .btn-academic-danger:hover { color: #fff !important; background: #c45858 !important; border-color: #c45858 !important; }

.academic-empty { border: 1px dashed #cfe3ec; border-radius: 10px; padding: 24px; text-align: center; color: #6d7d8b; background: #f8fbfc; }

@media (max-width: 575.98px) {
    .academic-page-shell { padding: 12px; }
    .academic-page-head { flex-direction: column; align-items: flex-start; }
    .academic-page-head h2 { font-size: 18px; }
    .exam-all-wrap { text-align: left; width: 100%; }
    .exam-all-btn { width: 100%; justify-content: center; }
}
</style>

<section class="container mt-4">
    <div class="academic-page-shell">
        <div class="academic-page-head">
            <div>
                <span class="kicker">Academic</span>
                <h2>Exam Routine Archive</h2>
                <p>Pick an exam, choose a class, then view, print or download its routine.</p>
            </div>
            <span class="academic-count"><i class="fa fa-clipboard"></i> {{ $groups->count() }} {{ $groups->count() === 1 ? 'Exam' : 'Exams' }}</span>
        </div>

        <div class="academic-table-wrap">
            <form method="GET" class="academic-filter row g-2 mb-3">
                <div class="col-12 col-md-3">
                    <select name="class" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class') == $c->id ? 'selected' : '' }}>{{ $c->className }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="department" class="form-select form-select-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" {{ request('department') == $d->id ? 'selected' : '' }}>{{ $d->departmentName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="session" class="form-select form-select-sm">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $s)
                            <option value="{{ $s->id }}" {{ request('session') == $s->id ? 'selected' : '' }}>{{ $s->session }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-academic btn-academic-primary btn-sm">Filter</button>
                    <a href="{{ route('newExamSchedule') }}" class="btn btn-academic btn-academic-secondary btn-sm">Reset</a>
                </div>
            </form>

            @if($groups->count() > 0)
                <div class="exam-group-list">
                    @foreach($groups as $gi => $group)
                        <div class="exam-group-card" data-exam-group>
                            <div class="exam-group-top">
                                <div>
                                    <h3 class="exam-group-title">{{ $group['title'] }}</h3>
                                    <p class="exam-group-sub">{{ $group['routines']->count() }} {{ $group['routines']->count() === 1 ? 'class routine' : 'class routines' }} published</p>
                                </div>
                                @if($group['grouped'])
                                    <div class="exam-all-wrap">
                                        @if($group['allAvailable'])
                                            <a href="{{ route('examRoutine.allClass.view', ['id' => $group['allClassId']]) }}" class="exam-all-btn"><i class="fa fa-th-large"></i> View All Classes Together</a>
                                        @else
                                            <span class="exam-all-btn is-disabled" aria-disabled="true"><i class="fa fa-th-large"></i> View All Classes Together</span>
                                            <span class="exam-all-note">Available once every class routine is published</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="exam-class-label">Select a class</div>
                            <div class="exam-pills" role="tablist">
                                @foreach($group['routines'] as $ri => $r)
                                    <button type="button" class="exam-pill {{ $ri === 0 ? 'is-active' : '' }}" data-pill="{{ $gi }}-{{ $r->id }}" role="tab">
                                        {{ optional($r->class)->className ?? 'N/A' }}@if($r->department) <small>· {{ $r->department->departmentName }}</small>@endif
                                    </button>
                                @endforeach
                            </div>

                            @foreach($group['routines'] as $ri => $r)
                                <div class="exam-panel {{ $ri === 0 ? 'is-active' : '' }}" data-panel="{{ $gi }}-{{ $r->id }}">
                                    <div class="exam-panel-meta">
                                        <b>{{ optional($r->class)->className ?? 'N/A' }}</b>
                                        @if($r->department) · {{ $r->department->departmentName }} @endif
                                        · Session {{ optional($r->session)->session ?? 'N/A' }}
                                        @if($r->published_at) · Published {{ \Carbon\Carbon::parse($r->published_at)->format('d M Y') }} @endif
                                    </div>
                                    <div class="routine-actions">
                                        <a href="{{ route('examRoutine.view', ['id' => $r->id]) }}" class="btn btn-academic btn-academic-info btn-sm">View</a>
                                        <a href="{{ route('examRoutine.print', ['id' => $r->id]) }}" target="_blank" class="btn btn-academic btn-academic-primary btn-sm">Print</a>
                                        <a href="{{ route('examRoutine.download', ['id' => $r->id]) }}" class="btn btn-academic btn-academic-danger btn-sm">PDF</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                <div class="academic-empty">No published exam routines found for the selected filters.</div>
            @endif
        </div>
    </div>
</section>
<script>
document.querySelectorAll('[data-exam-group]').forEach(function (card) {
    card.querySelectorAll('.exam-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            card.querySelectorAll('.exam-pill').forEach(function (p) { p.classList.toggle('is-active', p === pill); });
            card.querySelectorAll('.exam-panel').forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-panel') === pill.getAttribute('data-pill'));
            });
        });
    });
});
</script>
@endsection
