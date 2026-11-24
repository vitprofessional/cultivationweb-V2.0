{{-- Online copy of the student's transcript (A4 portrait, print only). Same format as the office transcript,
     marked ONLINE COPY and without signature boxes, so the two can never be mistaken for each other. --}}
@php
    $sheet = $result['transcript'] ?? ['mainRows' => [], 'optionalRows' => [], 'gradeLegend' => [], 'meritRank' => null, 'classification' => '', 'missingSubjects' => []];
    $sheetStatus = ($sheet['classification'] ?? '') === 'Absent' ? 'Absent' : ($result['result']['status'] ?? '');
    $student = $result['student'];
    $clean = fn ($value) => ($value === null || $value === '' || $value === '-') ? '—' : $value;
    $failedList = collect($sheet['mainRows'])->map(fn ($row) => $row + ['_optional' => false])
        ->concat(collect($sheet['optionalRows'])->map(fn ($row) => $row + ['_optional' => true]))
        ->filter(fn ($row) => ($row['status'] ?? '') === 'Fail')
        ->map(fn ($row) => $row['name'].($row['_optional'] ? ' (4th)' : ''))->values()->all();
    if (! count($failedList)) $failedList = $result['result']['failedSubjects'] ?? [];
    $issued = now()->setTimezone('Asia/Dhaka');
    $reference = 'OC-'.($result['exam']['id'] ?? '0').'-'.($student['studentId'] ?? '0');
    $verifyUrl = route('internalResult');
@endphp
<style>
.rs-sheet{display:none}
@media print{@page{size:A4 portrait;margin:8mm}.rs-sheet{display:block}}
.rs-sheet{width:100%;font-family:"Segoe UI",Roboto,Arial,sans-serif;color:#1e293b;font-size:11.5px;line-height:1.3}
.rs-sheet *{box-sizing:border-box;-webkit-print-color-adjust:exact;print-color-adjust:exact}
.rs-frame{position:relative;display:flex;flex-direction:column;min-height:276mm;padding:0 26px 14px;border:1.5px solid #0b2545;overflow:hidden;background:#fff}
.rs-topbar{height:7px;margin:0 -26px 13px;background:#0b2545;border-bottom:3px solid #0c7da7}
.rs-watermark{position:absolute;left:50%;top:54%;width:330px;height:330px;transform:translate(-50%,-50%);opacity:.045;pointer-events:none;display:flex;align-items:center;justify-content:center}
.rs-watermark img{max-width:100%;max-height:100%}
.rs-watermark span{font-size:120px;font-weight:900;color:#0b2545}
.rs-head{position:relative;width:100%;border-collapse:collapse;table-layout:fixed}
.rs-head td{padding:0;vertical-align:middle}
.rs-logo{width:74px;height:74px;padding:3px;border:1.5px solid #0c7da7;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;background:#fff}
.rs-logo img{max-width:100%;max-height:100%;border-radius:50%}
.rs-logo span{font-size:22px;font-weight:900;color:#0b2545}
.rs-brand{text-align:center}
.rs-brand h1{margin:0;font-size:22px;font-weight:800;letter-spacing:.05em;color:#0b2545;text-transform:uppercase}
.rs-brand p{margin:3px 0 0;font-size:10.5px;color:#475569;letter-spacing:.01em}
.rs-seal{float:right;width:118px;padding:3px;border:1.5px solid #0c7da7}
.rs-seal div{padding:5px 4px;border:1px dashed #0c7da7;text-align:center;color:#0c7da7}
.rs-seal b{display:block;font-size:12px;letter-spacing:.22em}
.rs-seal small{display:block;margin-top:2px;font-size:7.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#475569}
.rs-rule{position:relative;height:0;margin:12px 0 0;border-top:2.5px solid #0b2545;padding-top:2px;border-bottom:1px solid #0c7da7}
.rs-strip{position:relative;width:100%;margin-top:8px;border-collapse:collapse;table-layout:fixed;background:#f3f6fa;border:1px solid #d5dde8}
.rs-strip td{padding:5px 10px;border-right:1px solid #d5dde8;font-size:10.5px;color:#0b2545;font-weight:700}
.rs-strip td:last-child{border-right:0;text-align:right}
.rs-strip span{display:block;font-size:8px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#64748b}
.rs-title{position:relative;width:100%;margin:11px 0 8px;border-collapse:collapse}
.rs-title td{padding:0;vertical-align:middle}
.rs-title .rs-line{border-top:1px solid #c9d3df}
.rs-title .rs-word{width:1%;white-space:nowrap;padding:0 14px;text-align:center}
.rs-title strong{display:block;font-size:16px;font-weight:800;letter-spacing:.3em;color:#0b2545}
.rs-title em{display:block;margin-top:3px;font-style:normal;font-size:11px;font-weight:800;letter-spacing:.12em;color:#0c7da7;text-transform:uppercase}
.rs-meta{position:relative;width:100%;border-collapse:collapse;table-layout:fixed;margin-bottom:6px}
.rs-meta>tbody>tr>td{padding:0;vertical-align:top}
.rs-info{width:100%;border-collapse:collapse;table-layout:fixed;border:1px solid #c9d3df}
.rs-info th,.rs-info td{padding:4px 8px;border:1px solid #dde4ee;text-align:left;vertical-align:middle}
.rs-info th{width:23%;background:#f3f6fa;color:#475569;font-size:8.5px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;white-space:nowrap}
.rs-info td{font-size:11.5px;font-weight:700;color:#0b2545}
.rs-info .rs-name{font-size:14.5px;font-weight:800;letter-spacing:.02em;text-transform:uppercase}
.rs-info .rs-merit{color:#0c7da7}
.rs-grading-wrap{border:1px solid #0b2545}
.rs-grading{width:100%;border-collapse:collapse;border-style:hidden}
.rs-grading caption{padding:5px;background:#0b2545;color:#fff;font-size:9.5px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}
.rs-grading th,.rs-grading td{padding:2.5px 6px;border:1px solid #e2e8f0;font-size:10px;text-align:center}
.rs-grading th{background:#f3f6fa;color:#475569;font-size:8.5px;letter-spacing:.08em;text-transform:uppercase}
.rs-grading td+td{font-weight:800;color:#0b2545}
.rs-section{position:relative;margin:10px 0 4px;padding:0 0 3px 9px;border-left:3px solid #0b2545;border-bottom:1px solid #dde4ee;color:#0b2545;font-size:10px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}
.rs-marks{position:relative;width:100%;border-collapse:collapse;table-layout:fixed}
.rs-marks th{padding:6px;background:#0b2545;color:#fff;border:1px solid #0b2545;font-size:9px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;text-align:center}
.rs-marks th:first-child{text-align:left;width:38%}
.rs-marks th:nth-child(5){width:11%}
.rs-marks th:nth-child(6){width:12%}
.rs-marks td{padding:3.5px 7px;border:1px solid #c9d3df;text-align:center;font-size:11.5px;background:#fff;font-variant-numeric:tabular-nums}
.rs-marks .tr-subject{text-align:left;font-weight:600;color:#0f172a}
.rs-marks .tr-total{font-size:13px;font-weight:800;color:#0b2545;background:#f3f6fa}
.rs-marks .tr-grade strong{display:block;font-size:12.5px;line-height:1.2;color:#0b2545}
.rs-marks .tr-grade span{display:block;font-size:9px;line-height:1.25;color:#64748b;font-weight:700}
.rs-marks .tr-grade strong.tr-fail{color:#b91c1c}
.rs-marks .tr-group-start td{border-top:1.5px solid #0b2545}
.rs-marks .tr-group-end,.rs-marks tr.tr-group-end td{border-bottom:1.5px solid #0b2545}
.rs-marks .tr-paper td{border-top-color:#e8edf3;border-bottom-color:#e8edf3}
.rs-marks .tr-combined td{background:#f3f6fa}
.rs-summary{position:relative;width:100%;margin-top:12px;border-collapse:collapse;table-layout:fixed;border:1.5px solid #0b2545}
.rs-summary td{padding:7px 6px 8px;border-right:1px solid #c9d3df;text-align:center}
.rs-summary td:last-child{border-right:0}
.rs-summary span{display:block;font-size:8.5px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:#64748b}
.rs-summary b{display:block;margin-top:2px;font-size:19px;font-weight:800;color:#0b2545}
.rs-summary .is-pass{background:#f0fdf4}.rs-summary .is-pass b{color:#15803d}
.rs-summary .is-fail{background:#fef2f2}.rs-summary .is-fail b{color:#b91c1c}
.rs-remark{position:relative;width:100%;margin-top:9px;border-collapse:collapse;table-layout:fixed}
.rs-remark th,.rs-remark td{padding:6px 10px;border:1px solid #c9d3df;font-size:11px;text-align:left;vertical-align:middle}
.rs-remark th{width:24%;background:#0b2545;color:#fff;font-size:8.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}
.rs-remark td{background:#f8fafc;font-weight:700;color:#0b2545}
.rs-remark.is-fail th{background:#b91c1c;border-color:#b91c1c}
.rs-remark.is-fail td{background:#fef2f2;color:#7f1d1d;border-color:#f1b5b5}
.rs-foot{position:relative;margin-top:auto;padding-top:14px}
.rs-foot table{width:100%;border-collapse:collapse;table-layout:fixed;border-top:1.5px solid #0b2545}
.rs-foot td{padding:6px 0 0;font-size:9px;line-height:1.45;color:#475569;vertical-align:top}
.rs-foot td+td{text-align:right}
.rs-foot b{color:#0b2545}
.rs-foot .rs-note{margin-top:6px;padding:5px 8px;background:#f3f6fa;border-left:3px solid #0c7da7;font-size:9px;color:#334155}
</style>
<section class="rs-sheet ir-printable" aria-hidden="true" data-rs-sheet>
    <div class="rs-frame">
        <div class="rs-topbar"></div>
        <div class="rs-watermark">@if($institution['logoUrl'])<img src="{{ $institution['logoUrl'] }}" alt="">@else<span>{{ $institution['initials'] }}</span>@endif</div>
        <table class="rs-head" role="presentation"><tr>
            <td style="width:19%"><div class="rs-logo">@if($institution['logoUrl'])<img src="{{ $institution['logoUrl'] }}" alt="">@else<span>{{ $institution['initials'] }}</span>@endif</div></td>
            <td class="rs-brand" style="width:62%">
                <h1>{{ $institution['name'] }}</h1>
                @if($institution['address'])<p>{{ $institution['address'] }}</p>@endif
                @if($institution['phone'] || $institution['email'])<p>{{ collect([$institution['phone'] ? 'Phone: '.$institution['phone'] : null, $institution['email'] ? 'Email: '.$institution['email'] : null])->filter()->implode('  |  ') }}</p>@endif
            </td>
            <td style="width:19%"><div class="rs-seal"><div><b>ONLINE</b><b>COPY</b><small>Not for official use</small></div></div></td>
        </tr></table>
        <div class="rs-rule"></div>
        <table class="rs-strip" role="presentation"><tr>
            <td><span>Document</span>Academic Transcript — Online Copy</td>
            <td><span>Reference</span>{{ $reference }}</td>
            <td><span>Issued</span>{{ $issued->format('d M Y') }}</td>
        </tr></table>
        <table class="rs-title" role="presentation"><tr>
            <td class="rs-line"></td>
            <td class="rs-word"><strong>ACADEMIC TRANSCRIPT</strong><em>{{ $result['exam']['name'] }} · Session {{ $clean($student['session']) }}</em></td>
            <td class="rs-line"></td>
        </tr></table>

        <table class="rs-meta" role="presentation"><tr>
            <td style="width:66%;padding-right:12px">
                <table class="rs-info">
                    <tr><th>Student's Name</th><td colspan="3" class="rs-name">{{ $student['name'] }}</td></tr>
                    <tr><th>Student ID</th><td>{{ $clean($student['studentId']) }}</td><th style="width:18%">Roll No.</th><td>{{ $clean($student['roll']) }}</td></tr>
                    <tr><th>Class</th><td>{{ $clean($student['class']) }}</td><th>Section</th><td>{{ $clean($student['section']) }}</td></tr>
                    <tr><th>Group</th><td>{{ $clean($student['group']) }}</td><th>Session</th><td>{{ $clean($student['session']) }}</td></tr>
                    <tr><th>Merit Position</th><td colspan="3" class="rs-merit">{{ $clean($sheet['meritRank'] ?? null) }}</td></tr>
                </table>
            </td>
            <td style="width:34%">
                <div class="rs-grading-wrap"><table class="rs-grading">
                    <caption>Grading Scale</caption>
                    <tr><th>Marks</th><th>Grade</th><th>Point</th></tr>
                    @foreach($sheet['gradeLegend'] as $grade)<tr><td>{{ $grade['range'] }}</td><td>{{ $grade['grade'] }}</td><td>{{ $grade['point'] }}</td></tr>@endforeach
                </table></div>
            </td>
        </tr></table>

        <div class="rs-section">Main Subjects</div>
        <table class="rs-marks">
            <thead><tr><th>Subject</th><th>CQ</th><th>MCQ</th><th>PR</th><th>Total</th><th>Grade / GP</th></tr></thead>
            <tbody>@include('frontend.result.partials._transcript_rows', ['rows' => $sheet['mainRows'], 'optionalLabel' => null])</tbody>
        </table>
        @if(count($sheet['optionalRows']))
            <div class="rs-section">Optional / Fourth Subject</div>
            <table class="rs-marks">
                <thead><tr><th>Subject</th><th>CQ</th><th>MCQ</th><th>PR</th><th>Total</th><th>Grade / GP</th></tr></thead>
                <tbody>@include('frontend.result.partials._transcript_rows', ['rows' => $sheet['optionalRows'], 'optionalLabel' => '(4th)'])</tbody>
            </table>
        @endif

        <div class="rs-section">Result Summary</div>
        <table class="rs-summary" role="presentation"><tr>
            <td><span>Total Marks</span><b>{{ $clean($result['result']['totalMarks']) }}</b></td>
            <td><span>Letter Grade</span><b>{{ $clean($result['result']['grade']) }}</b></td>
            <td><span>GPA</span><b>{{ $clean($result['result']['gpa']) }}</b></td>
            <td class="{{ $sheetStatus === 'Pass' ? 'is-pass' : ($sheetStatus === 'Fail' ? 'is-fail' : '') }}"><span>Result</span><b>{{ $sheetStatus === 'Pass' ? 'PASSED' : ($sheetStatus === 'Fail' ? 'FAILED' : strtoupper($sheetStatus ?: '—')) }}</b></td>
        </tr></table>

        @if(($sheet['classification'] ?? '') === 'Incomplete' && count($sheet['missingSubjects'] ?? []))
            <table class="rs-remark is-fail" role="presentation"><tr><th>Incomplete</th><td>Missing marks for {{ implode(', ', $sheet['missingSubjects']) }}</td></tr></table>
        @elseif(count($failedList))
            <table class="rs-remark is-fail" role="presentation"><tr><th>Failed Subjects ({{ count($failedList) }})</th><td>{{ implode(',  ', $failedList) }}</td></tr></table>
        @elseif($sheetStatus === 'Pass')
            <table class="rs-remark" role="presentation"><tr><th>Remarks</th><td>Passed in all subjects</td></tr></table>
        @endif

        <footer class="rs-foot">
            <table role="presentation"><tr>
                <td><b>{{ $institution['name'] }}</b><br>Online result service</td>
                <td>Generated {{ $issued->format('d M Y, h:i A') }}<br>Verify at {{ preg_replace('~^https?://~', '', $verifyUrl) }}</td>
            </tr></table>
            <div class="rs-note">This online copy is issued for information only and carries no signature. The signed transcript issued by the school office is the official record.</div>
        </footer>
    </div>
</section>
<script>
function irPrintResultSheet() {
    var sheet = document.querySelector('[data-rs-sheet]');
    var styles = sheet ? sheet.previousElementSibling : null;
    var win = sheet ? window.open('', '_blank', 'width=900,height=1100') : null;
    if (!win) { window.print(); return; }
    win.document.open();
    win.document.write('<!DOCTYPE html><html><head><meta charset="utf-8"><title>' + document.title.replace(/</g, '') + ' - Result Sheet</title>'
        + (styles && styles.tagName === 'STYLE' ? styles.outerHTML : '')
        + '<style>body{margin:0;background:#e2e8f0}.rs-sheet{display:block!important;width:194mm;max-width:194mm;margin:12px auto;background:#fff}@media print{body{background:#fff}.rs-sheet{margin:0}}</style>'
        + '</head><body>' + sheet.outerHTML + '</body></html>');
    win.document.close();
    var go = function () {
        // One A4 page always: a long subject list scales the sheet down evenly instead of spilling onto page 2.
        var frame = win.document.querySelector('.rs-frame'), sheetEl = win.document.querySelector('.rs-sheet');
        if (frame && sheetEl) {
            frame.style.minHeight = '0';
            var max = 276 * 96 / 25.4, height = frame.getBoundingClientRect().height;
            frame.style.minHeight = '';
            if (height > max) sheetEl.style.zoom = (max / height).toFixed(3);
        }
        win.focus(); win.print();
    };
    var images = win.document.images, pending = images.length;
    if (!pending) { setTimeout(go, 150); return; }
    Array.prototype.forEach.call(images, function (img) {
        if (img.complete) { if (--pending === 0) setTimeout(go, 150); return; }
        img.onload = img.onerror = function () { if (--pending === 0) setTimeout(go, 150); };
    });
}
</script>
