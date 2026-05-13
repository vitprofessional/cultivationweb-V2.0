@php
    $itemClass = \App\Models\classManage::find($routine->assignClass);
    $itemDepartment = \App\Models\Department::find($routine->assignDepartment);
    $itemSession = \App\Models\sessionManage::find($routine->assignSession);
    $attachment = $routine->attachment ?? '';
    $url = $attachment ? public_path('upload/image/cultivation/examRoutine/'. $attachment) : '';
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam Routine</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body{font-family:Arial,Helvetica,sans-serif;color:#23364f}
        .header{text-align:center;margin-bottom:10px}
        .header h2{margin:0 0 6px 0;color:#13446f;letter-spacing:.02em}
        .meta{font-size:13px;color:#4f6783;margin-bottom:8px}
        .attachment{width:100%;}
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $routine->title }}</h2>
        <div class="meta"><b>Class:</b> {{ $itemClass->className ?? '-' }} | <b>Session:</b> {{ $itemSession->session ?? '-' }} | <b>Department:</b> {{ $itemDepartment->departmentName ?? '-' }}
            <span style="display:inline-block;margin-left:12px;font-weight:600">&nbsp;</span>
            <span style="float:right;font-size:12px;font-weight:600">Printed on: {{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</span>
        </div>
    </div>

    @if(($entries ?? collect())->count() > 0)
        <table style="width:100%;border-collapse:collapse;border:1px solid #444;margin-bottom:6px">
            <thead>
                <tr style="background:#2bb1a7;color:#fff;text-align:left">
                    <th style="padding:6px;border:1px solid #444;width:15%">Date</th>
                    <th style="padding:6px;border:1px solid #444;width:15%">Day</th>
                    <th style="padding:6px;border:1px solid #444;width:30%">Time</th>
                    <th style="padding:6px;border:1px solid #444;width:40%">Subject</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $row)
                    @php
                        $hasDate = !empty($row->exam_date);
                        $examDate = $hasDate ? \Carbon\Carbon::parse($row->exam_date) : null;
                    @endphp
                    <tr>
                        <td style="padding:6px;border:1px solid #444">{{ $examDate ? $examDate->format('d-m-Y') : '-' }}</td>
                        <td style="padding:6px;border:1px solid #444">{{ $examDate ? $examDate->format('l') : '-' }}</td>
                        <td style="padding:6px;border:1px solid #444">{{ ($row->start_time && $row->end_time) ? date('h:i A', strtotime($row->start_time)).' - '.date('h:i A', strtotime($row->end_time)) : ($row->start_time ?? '-') }}</td>
                        <td style="padding:6px;border:1px solid #444">{{ $row->subject_name ?? ($row->subject_id ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @if($attachment && file_exists(public_path('upload/image/cultivation/examRoutine/'. $attachment)))
            @php $ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION)); @endphp
            @if($ext === 'pdf')
                <div style="height:0;">PDF attachment will be downloaded when using the Download action.</div>
            @else
                <img src="{{ url('/public/upload/image/cultivation/examRoutine/'.rawurlencode($attachment)) }}" class="attachment" alt="Exam Routine">
            @endif
        @else
            <div style="padding:30px;border:1px dashed #ccc">No preview available. Please use the Download button to get the attached file.</div>
        @endif
    @endif

@if(!empty($printMode) && $printMode)
<script>
    window.addEventListener('load', function(){
        setTimeout(function(){ window.print(); }, 400);
    });
</script>
@endif
</body>
</html>