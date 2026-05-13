@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Semester Plan
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

.academic-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border-radius: 12px;
    table-layout: fixed;
}

.academic-table thead th {
    border-bottom: 1px solid #d7e6f3;
    color: #1f3f66;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 800;
    background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%);
    padding: 14px 16px;
}

.academic-table tbody td {
    vertical-align: middle;
    color: #3f5772;
    font-size: 14px;
    padding: 16px;
    line-height: 1.6;
    border-color: #e0ebf4;
}

.academic-table tbody tr:nth-child(even) td {
    background: #fafcff;
}

.academic-table tbody tr:hover td {
    background: #f4faff;
}

.academic-view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 1px solid #b7deef;
    background: #e7f5fd;
    color: #1b7096;
    box-shadow: 0 4px 10px rgba(27, 112, 150, .14);
    transition: all .2s ease;
}

.academic-view-btn:hover {
    background: #1b7096;
    border-color: #1b7096;
    color: #fff;
    transform: translateY(-1px);
}

.academic-table th:first-child,
.academic-table td:first-child {
    width: 28%;
}

.academic-table th:nth-child(2),
.academic-table td:nth-child(2) {
    width: auto;
}

.academic-table th:last-child,
.academic-table td:last-child {
    width: 90px;
    text-align: center;
    white-space: nowrap;
}

.academic-table td:first-child {
    white-space: normal;
}

.academic-cell-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-weight: 700;
    line-height: 1.6;
    color: #264561;
}

.academic-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px 12px;
    width: 100%;
}

.academic-meta-item {
    background: #fff;
    border: 1px solid #e8f0f7;
    border-radius: 8px;
    padding: 8px 10px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    box-shadow: inset 0 1px 3px rgba(27, 112, 150, 0.04);
}

.academic-meta-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #0c7da7;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.academic-meta-label i {
    font-size: 12px;
    display: inline-block;
    min-width: 12px;
}

.academic-meta-value {
    font-size: 13px;
    font-weight: 600;
    color: #1f3f66;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.routine-actions {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.routine-actions a,
.routine-actions button {
    padding: 0 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.routine-actions .btn-info {
    background: rgba(43, 138, 120, 0.1) !important;
    border-color: rgba(43, 138, 120, 0.2) !important;
    color: #2b8a78 !important;
}

.routine-actions .btn-info:hover {
    background: rgba(43, 138, 120, 0.2) !important;
    border-color: rgba(43, 138, 120, 0.3) !important;
    color: #1d5d52 !important;
}

.routine-actions .btn-primary {
    background: rgba(27, 112, 150, 0.1) !important;
    border-color: rgba(27, 112, 150, 0.2) !important;
    color: #1b7096 !important;
}

.routine-actions .btn-primary:hover {
    background: rgba(27, 112, 150, 0.2) !important;
    border-color: rgba(27, 112, 150, 0.3) !important;
    color: #0f4968 !important;
}

.routine-actions .btn-danger {
    background: rgba(196, 88, 88, 0.1) !important;
    border-color: rgba(196, 88, 88, 0.2) !important;
    color: #c45858 !important;
}

.routine-actions .btn-danger:hover {
    background: rgba(196, 88, 88, 0.2) !important;
    border-color: rgba(196, 88, 88, 0.3) !important;
    color: #9a3f3f !important;
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

    .academic-table th:first-child,
    .academic-table td:first-child,
    .academic-table th:last-child,
    .academic-table td:last-child {
        width: auto;
        white-space: normal;
    }
}
</style>
<section class="container mt-4">
    <div class="academic-page-shell">
        <div class="academic-page-head">
            <div>
                <span class="kicker">Academic</span>
                <h2>Semester Plan Archive</h2>
                <p>Access published semester plans by class, department, and session.</p>
            </div>
            <span class="academic-count"><i class="fa fa-calendar"></i> {{ $rows->count() }} {{ $rows->count() === 1 ? 'Record' : 'Records' }}</span>
        </div>

        @if($rows->count() > 0)
            <div class="academic-table-wrap table-responsive">
                <table id="myTable" class="academic-table table table-hover align-middle display border">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Semester Details</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $data)
                            <tr>
                                <td><span class="academic-cell-title">{{ $data->title }}</span></td>
                                @php
                                    $itemClass      = \App\Models\classManage::find($data->assignClass);
                                    $itemDepartment = \App\Models\Department::find($data->assignDepartment);
                                    $itemSession    = \App\Models\sessionManage::find($data->assignSession);
                                    $attachment = $data->attachment ?? '';
                                    $fileUrl = config('app.url').'/public/upload/image/cultivation/semisterPlan/'.rawurlencode(basename($attachment));
                                @endphp
                                <td>
                                    <div class="academic-meta-grid">
                                        <div class="academic-meta-item academic-meta-class">
                                            <span class="academic-meta-label"><i class="fa fa-book"></i> Class</span>
                                            <span class="academic-meta-value">{{ $itemClass->className ?? 'N/A' }}</span>
                                        </div>
                                        <div class="academic-meta-item academic-meta-department">
                                            <span class="academic-meta-label"><i class="fa fa-university"></i> Department</span>
                                            <span class="academic-meta-value">{{ $itemDepartment->departmentName ?? 'N/A' }}</span>
                                        </div>
                                        <div class="academic-meta-item academic-meta-session">
                                            <span class="academic-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                            <span class="academic-meta-value">{{ $itemSession->session ?? 'N/A' }}</span>
                                        </div>
                                        <div class="academic-meta-item academic-meta-published">
                                            <span class="academic-meta-label"><i class="fa fa-clock"></i> Published</span>
                                            <span class="academic-meta-value">{{ optional($data->created_at)->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($attachment))
                                        <a class="academic-view-btn" data-fancybox data-type="iframe" href="{{ $fileUrl }}" target="_blank" title="View Semester Plan">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach          
                        </tbody>
                </table>
            </div>
        @else
            <div class="academic-empty">No semester plan records found.</div>
        @endif
    </div>
</section>
@endsection