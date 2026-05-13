@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Class Routine
@endsection

@php
$rows = $Datakey ?? collect();
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

.academic-page-head h2 {
    margin: 6px 0 0;
    font-size: 22px;
    color: #112958;
    font-weight: 800;
}

.academic-page-head p {
    margin: 6px 0 0;
    color: #5a708a;
    font-size: 14px;
}

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

.academic-table-wrap {
    background: #fff;
    border: 1px solid #dce9f4;
    border-radius: 14px;
    padding: 16px;
    overflow: hidden;
}

.academic-filter .form-select,
.academic-filter .btn {
    min-height: 36px;
    font-size: 13px;
}

.academic-filter .form-select {
    border-color: #cfe0ef;
}

.academic-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border-radius: 12px;
    table-layout: auto;
}

.academic-table thead th {
    border-bottom: 1px solid #d7e6f3;
    color: #1f3f66;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .02em;
    font-weight: 800;
    background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%);
    padding: 13px 10px;
    white-space: nowrap;
}

.academic-table tbody td {
    vertical-align: middle;
    color: #3f5772;
    font-size: 14px;
    padding: 14px 10px;
    line-height: 1.6;
    border-color: #e0ebf4;
}

.academic-table tbody tr:hover {
    background: #f5faff;
}

.academic-table tbody tr:nth-child(even) td {
    background: #fafcff;
}

.academic-table th:first-child,
.academic-table td:first-child {
    width: 34%;
}

.academic-table th:nth-child(2),
.academic-table td:nth-child(2) {
    width: auto;
}

.academic-table th:last-child,
.academic-table td:last-child {
    width: 184px;
    white-space: nowrap;
    text-align: center;
}

.academic-cell-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-weight: 700;
    line-height: 1.5;
    color: #264561;
}

.academic-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 12px;
}

.academic-meta-item {
    min-width: 0;
    padding: 10px 12px;
    border: 1px solid #deebf5;
    border-radius: 10px;
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
}

.academic-meta-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #6e859d;
}

.academic-meta-label i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #eaf3fb;
    color: #3d6b8f;
    font-size: 10px;
    line-height: 1;
}

.academic-meta-value {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #244766;
    line-height: 1.4;
    white-space: normal;
}

.routine-actions {
    display: flex;
    flex-wrap: nowrap;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.routine-actions .btn {
    min-width: 52px;
    font-size: 11px;
    font-weight: 700;
}

.routine-actions .btn-academic {
    min-width: 52px;
    min-height: 34px;
    padding-left: 10px;
    padding-right: 10px;
    border-width: 1px;
    box-shadow: none;
}

.routine-actions .btn-academic-info {
    color: #1f7a69 !important;
    background: #e5f5f1 !important;
    border-color: #c7e8df !important;
}

.routine-actions .btn-academic-info:hover {
    color: #fff !important;
    background: #2b8a78 !important;
    border-color: #2b8a78 !important;
}

.routine-actions .btn-academic-primary {
    color: #22698e !important;
    background: #e8f2fb !important;
    border-color: #cadeef !important;
}

.routine-actions .btn-academic-primary:hover {
    color: #fff !important;
    background: #1b7096 !important;
    border-color: #1b7096 !important;
}

.routine-actions .btn-academic-danger {
    color: #a64b4b !important;
    background: #fbeaea !important;
    border-color: #f0cccc !important;
}

.routine-actions .btn-academic-danger:hover {
    color: #fff !important;
    background: #c45858 !important;
    border-color: #c45858 !important;
}

.academic-empty {
    border: 1px dashed #cfe3ec;
    border-radius: 10px;
    padding: 24px;
    text-align: center;
    color: #6d7d8b;
    background: #f8fbfc;
}

@media (max-width: 575.98px) {
    .academic-page-shell {
        padding: 12px;
    }

    .academic-page-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .academic-page-head h2 {
        font-size: 18px;
    }

    .routine-actions {
        flex-wrap: wrap;
    }

    .academic-table th:first-child,
    .academic-table td:first-child,
    .academic-table th:nth-child(2),
    .academic-table td:nth-child(2),
    .academic-table th:last-child,
    .academic-table td:last-child {
        width: auto;
        white-space: normal;
    }

    .academic-meta-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}
</style>

<section class="container mt-4">
    <div class="academic-page-shell">
        <div class="academic-page-head">
            <div>
                <span class="kicker">Academic</span>
                <h2>Class Routine Archive</h2>
                <p>Filter and access published class routines with view, print, and PDF actions.</p>
            </div>
            <span class="academic-count"><i class="fa fa-table"></i> {{ $rows->count() }} {{ $rows->count() === 1 ? 'Routine' : 'Routines' }}</span>
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
                    <a href="{{ route('newClassSchedule') }}" class="btn btn-academic btn-academic-secondary btn-sm">Reset</a>
                </div>
            </form>

            @if($rows->count() > 0)
                <div class="table-responsive">
                    <table id="myTable" class="academic-table table table-hover align-middle display border">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Routine Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $data)
                                @php
                                    $sectionVal = $data->assignSection ?? $data->section ?? $data->sectionName ?? 'All';
                                @endphp
                                <tr>
                                    <td><span class="academic-cell-title">{{ $data->title }}</span></td>
                                    <td>
                                        <div class="academic-meta-grid">
                                            <div class="academic-meta-item academic-meta-class">
                                                <span class="academic-meta-label"><i class="fa fa-book"></i> Class</span>
                                                <span class="academic-meta-value">{{ optional($data->class)->className ?? 'N/A' }}</span>
                                            </div>
                                            <div class="academic-meta-item academic-meta-section">
                                                <span class="academic-meta-label"><i class="fa fa-list"></i> Section</span>
                                                <span class="academic-meta-value">{{ $sectionVal }}</span>
                                            </div>
                                            <div class="academic-meta-item academic-meta-department">
                                                <span class="academic-meta-label"><i class="fa fa-university"></i> Department</span>
                                                <span class="academic-meta-value">{{ optional($data->department)->departmentName ?? 'N/A' }}</span>
                                            </div>
                                            <div class="academic-meta-item academic-meta-session">
                                                <span class="academic-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                                <span class="academic-meta-value">{{ optional($data->session)->session ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="routine-actions">
                                            <a href="{{ route('classRoutine.view', ['id' => $data->id]) }}" class="btn btn-academic btn-academic-info btn-sm" title="View Routine">View</a>
                                            <a href="{{ route('classRoutine.print', ['id' => $data->id]) }}" target="_blank" class="btn btn-academic btn-academic-primary btn-sm" title="Print Routine">Print</a>
                                            <a href="{{ route('classRoutine.download', ['id' => $data->id]) }}" class="btn btn-academic btn-academic-danger btn-sm" title="Download PDF">PDF</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="academic-empty">No class routine records found for the selected filters.</div>
            @endif
        </div>
    </div>
</section>
@endsection
