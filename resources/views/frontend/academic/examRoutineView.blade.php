@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Exam Routine View
@endsection

@section('frontcontent')
<section class="container mt-4">
    <div class="row">
        <div class="col-10 mx-auto my-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Exam Routine</span>
                    <div>
                        <a href="{{ route('newExamSchedule') }}" class="btn btn-secondary btn-sm">Back</a>
                        <a href="{{ route('examRoutine.download', ['id' => $routine->id]) }}" class="btn btn-danger btn-sm">Download</a>
                        <a href="{{ route('examRoutine.print', ['id' => $routine->id]) }}" target="_blank" class="btn btn-primary btn-sm">Print</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($entries ?? collect())->count() > 0)
                        <div class="model-test-wrap">
                            <h4 class="text-center mb-3">{{ $routine->title ?? 'Exam Routine' }}</h4>
                            
                            @php
                                $itemClass = \App\Models\classManage::find($routine->assignClass);
                                $itemDepartment = \App\Models\Department::find($routine->assignDepartment);
                                $itemSession = \App\Models\sessionManage::find($routine->assignSession);
                            @endphp
                            <div class="meta"><b>Class:</b> {{ $itemClass->className ?? '-' }} | <b>Session:</b> {{ $itemSession->session ?? '-' }} | <b>Department:</b> {{ $itemDepartment->departmentName ?? '-' }}</div>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:15%">Date</th>
                                            <th style="width:15%">Day</th>
                                            <th style="width:30%">Time</th>
                                            <th style="width:40%">Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entries as $row)
                                            <tr>
                                                <td>{{ optional($row->exam_date) ? date('d-m-Y', strtotime($row->exam_date)) : '-' }}</td>
                                                <td>{{ \Illuminate\Support\Str::title(\Carbon\Carbon::parse($row->exam_date ?? now())->format('l')) ?? '-' }}</td>
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
                            @if(in_array($ext, ['pdf']))
                                <object data="{{ $url }}" type="application/pdf" width="100%" height="800px">
                                    <p>Your browser does not support viewing PDFs. <a href="{{ $url }}">Download the PDF</a>.</p>
                                </object>
                            @elseif(in_array($ext, ['jpg','jpeg','png','webp','gif','avif']))
                                <img src="{{ $url }}" alt="Exam Routine" class="img-fluid" />
                            @else
                                <p>Attachment type not previewable. <a href="{{ $url }}">Download</a></p>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
