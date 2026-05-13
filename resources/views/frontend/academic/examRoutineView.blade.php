@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Exam Routine View
@endsection

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
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dce9f4;
}

.academic-page-head h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #112958;
}

.academic-page-head .btn {
    font-size: 12px;
    font-weight: 700;
}

.routine-meta {
    border: 1px solid #dce9f4;
    background: #f7fbff;
    border-radius: 10px;
    padding: 8px 10px;
    margin-bottom: 12px;
    color: #4f6783;
    font-size: 13px;
    font-weight: 600;
}

.routine-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border-radius: 12px;
}

.routine-table thead th {
    border-bottom: 1px solid #d7e6f3;
    color: #1f3f66;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 800;
    background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%);
    padding-top: 12px;
    padding-bottom: 12px;
}

.routine-table tbody td {
    vertical-align: middle;
    color: #3f5772;
    font-size: 14px;
    padding-top: 12px;
    padding-bottom: 12px;
    border-color: #e0ebf4;
}

.routine-table tbody tr:hover {
    background: #f5faff;
}

.routine-table tbody tr:nth-child(even) td {
    background: #fafcff;
}

.attachment-preview {
    border: 1px solid #dce9f4;
    border-radius: 12px;
    padding: 12px;
    background: #fff;
}

@media (max-width: 767.98px) {
    .academic-page-head {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
<section class="container mt-4">
    <div class="row">
        <div class="col-12 col-xl-10 mx-auto my-4">
            <div class="academic-page-shell">
                <div class="academic-page-head">
                    <h4>Exam Routine Details</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('newExamSchedule') }}" class="btn btn-academic btn-academic-secondary btn-sm">Back</a>
                        <a href="{{ route('examRoutine.download', ['id' => $routine->id]) }}" class="btn btn-academic btn-academic-danger btn-sm">Download</a>
                        <a href="{{ route('examRoutine.print', ['id' => $routine->id]) }}" target="_blank" class="btn btn-academic btn-academic-primary btn-sm">Print</a>
                    </div>
                </div>

                <div>
                    @if(($entries ?? collect())->count() > 0)
                        <div>
                            <h4 class="text-center mb-3">{{ $routine->title ?? 'Exam Routine' }}</h4>
                            
                            @php
                                $itemClass = \App\Models\classManage::find($routine->assignClass);
                                $itemDepartment = \App\Models\Department::find($routine->assignDepartment);
                                $itemSession = \App\Models\sessionManage::find($routine->assignSession);
                            @endphp
                            <div class="routine-meta"><b>Class:</b> {{ $itemClass->className ?? '-' }} | <b>Session:</b> {{ $itemSession->session ?? '-' }} | <b>Department:</b> {{ $itemDepartment->departmentName ?? '-' }}</div>
                            <div class="table-responsive">
                                <table class="routine-table table table-bordered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:15%">Date</th>
                                            <th style="width:15%">Day</th>
                                            <th style="width:30%">Time</th>
                                            <th style="width:40%">Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entries as $row)
                                            @php
                                                $hasDate = !empty($row->exam_date);
                                                $examDate = $hasDate ? \Carbon\Carbon::parse($row->exam_date) : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $examDate ? $examDate->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $examDate ? $examDate->format('l') : '-' }}</td>
                                                <td>{{ ($row->start_time && $row->end_time) ? date('h:i A', strtotime($row->start_time)).' - '.date('h:i A', strtotime($row->end_time)) : ($row->start_time ?? '-') }}</td>
                                                <td>{{ $row->subject_name ?? ($row->subject_id ?? '-') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        @if(empty($routine->attachment))
                            <div class="alert alert-warning">No attachment or DB entries available for this exam routine.</div>
                        @else
                            @php
                                $ext = strtolower(pathinfo($routine->attachment, PATHINFO_EXTENSION));
                                $url = url('/public/upload/image/cultivation/examRoutine/'.rawurlencode($routine->attachment));
                            @endphp
                            <div class="attachment-preview">
                                @if(in_array($ext, ['pdf']))
                                    <object data="{{ $url }}" type="application/pdf" width="100%" height="800px">
                                        <p>Your browser does not support viewing PDFs. <a href="{{ $url }}">Download the PDF</a>.</p>
                                    </object>
                                @elseif(in_array($ext, ['jpg','jpeg','png','webp','gif','avif']))
                                    <img src="{{ $url }}" alt="Exam Routine" class="img-fluid" />
                                @else
                                    <p>Attachment type not previewable. <a href="{{ $url }}">Download</a></p>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
