{{-- Class Results tab of the Internal Result page. Data comes from the Admin application's public report API. --}}
<style data-ir-style>
.ir-class-form{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1.4fr) auto;gap:12px;align-items:end;padding:16px;border:1px solid #dce9f4;border-radius:14px;background:#fff}
.ir-class-form label{display:block;margin-bottom:6px;font-size:12px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:#1f3f66}
.ir-class-form select{width:100%;min-height:44px;padding:8px 12px;border:1px solid #cfe0ef;border-radius:10px;font-size:15px;color:#112958;background:#fff}
.ir-class-form button{min-height:44px;padding:0 22px;border:0;border-radius:10px;background:#112958;color:#fff;font-weight:800;font-size:15px;cursor:pointer}
.ir-class-form select:disabled{background:#f1f5f9;color:#94a3b8}
.ir-report{margin-top:18px;border:1px solid #dce9f4;border-radius:16px;background:#fff;overflow:hidden}
.ir-report-top{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;padding:18px 20px;background:#112958;color:#fff}
.ir-report .ir-report-top h3{margin:0;font-size:22px;font-weight:800;color:#fff!important}
.ir-report-top p{margin:4px 0 0;color:#c7d6ea;font-size:14px}
.ir-report-top .ir-print{border-color:#fff;background:transparent;color:#fff}
.ir-report-nav{display:flex;flex-wrap:wrap;gap:6px;padding:12px 20px;border-bottom:1px solid #e5eef7;background:#f8fbff}
.ir-report-nav a{padding:7px 14px;border-radius:999px;border:1px solid #cfe0ef;background:#fff;color:#1f3f66;font-size:13px;font-weight:800;text-decoration:none}
.ir-report-nav a.is-active{background:#0c7da7;border-color:#0c7da7;color:#fff}
.ir-report-body{padding:18px 20px}
.ir-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:16px}
.ir-stat{padding:12px 14px;border:1px solid #e5eef7;border-radius:12px;background:#f8fbff}
.ir-stat span{display:block;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#6e859d}
.ir-stat strong{display:block;margin-top:4px;font-size:24px;font-weight:800;color:#112958}
.ir-stat.is-pass strong{color:#15803d}.ir-stat.is-fail strong{color:#b91c1c}.ir-stat.is-gold strong{color:#b45309}
.ir-block{margin-top:18px}
.ir-block h4{margin:0 0 10px;font-size:15px;font-weight:800;color:#112958;text-transform:uppercase;letter-spacing:.04em}
.ir-grades{display:flex;height:34px;border-radius:10px;overflow:hidden;border:1px solid #e5eef7}
.ir-grades div{display:flex;align-items:center;justify-content:center;min-width:0;color:#fff;font-size:12px;font-weight:800;white-space:nowrap;overflow:hidden}
.ir-grade-legend{display:flex;flex-wrap:wrap;gap:6px 14px;margin-top:8px;font-size:13px;color:#3f5772}
.ir-grade-legend i{display:inline-block;width:10px;height:10px;margin-right:5px;border-radius:3px;vertical-align:middle}
.ir-tools{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;margin-bottom:10px}
.ir-tools input{min-height:40px;width:min(320px,100%);padding:6px 12px;border:1px solid #cfe0ef;border-radius:10px;font-size:14px}
.ir-tools small{color:#6b7f95;font-size:13px}
.ir-report .ir-table td.ir-left,.ir-report .ir-table th.ir-left{text-align:left}
.ir-pos{display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:26px;padding:0 6px;border-radius:999px;background:#eef5fb;color:#112958;font-weight:800;font-size:13px}
.ir-pos.is-top{background:#fef3c7;color:#92400e}
.ir-pill{display:inline-block;padding:2px 9px;border-radius:999px;font-size:12px;font-weight:800}
.ir-pill.is-pass{background:#dcfce7;color:#166534}.ir-pill.is-fail{background:#fee2e2;color:#991b1b}.ir-pill.is-other{background:#f1f5f9;color:#475569}
.ir-group{margin-top:16px;border:1px solid #e5eef7;border-radius:12px;overflow:hidden}
.ir-group h4{margin:0;padding:10px 14px;background:#f8fbff;border-bottom:1px solid #e5eef7;font-size:14px}
.ir-group.is-pass h4{color:#166534}.ir-group.is-fail h4{color:#991b1b}
.ir-pager{display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin-top:12px}
.ir-pager button{min-width:36px;height:34px;padding:0 10px;border:1px solid #cfe0ef;border-radius:8px;background:#fff;color:#112958;font-weight:700;cursor:pointer}
.ir-pager button.is-active{background:#112958;border-color:#112958;color:#fff}
.ir-report-foot{padding:12px 20px;border-top:1px solid #e5eef7;font-size:12px;color:#6b7f95}
.oc-head,.oc-foot,.oc-watermark{display:none}

/* ===== Tabulation sheet — online copy (same columns as the office copy, website styling) ===== */
.tb-legend{display:flex;flex-wrap:wrap;gap:6px 14px;font-size:12px;color:#3f5772}
.tb-legend b{color:#112958}
.tb-key-fail{display:inline-block;width:12px;height:12px;margin-right:4px;border-radius:3px;background:#fde8e8;border:1px solid #f5b5b5;vertical-align:-2px}
.tb-wrap{overflow:auto;max-height:78vh;border:1px solid #cfdceb;border-radius:12px;background:#fff}
.tb{border-collapse:separate;border-spacing:0;width:max-content;min-width:100%;font-size:13px;color:#1e293b}
.tb th,.tb td{padding:6px 8px;border-right:1px solid #e6edf5;border-bottom:1px solid #e6edf5;text-align:center;white-space:nowrap;background:#fff}
.tb thead th{position:sticky;z-index:3}
.tb thead tr:first-child th{top:0;height:44px;background:#112958;color:#fff;font-size:12px;font-weight:800;letter-spacing:.03em;border-color:#26406e}
.tb thead tr:first-child th.is-band{background:#1a3670}
.tb thead tr:first-child th small{display:block;font-size:10px;font-weight:600;color:#9fc3e6;letter-spacing:0}
.tb thead tr:first-child th em{margin-left:5px;padding:1px 6px;border-radius:999px;background:#f5c451;color:#4a3405;font-style:normal;font-size:10px}
.tb thead tr:nth-child(2) th{top:44px;background:#e9f1f9;color:#1f3f66;font-size:11px;font-weight:800}
.tb thead tr:nth-child(2) th.is-band{background:#dde8f4}
.tb thead tr:nth-child(2) th.tb-total-head{background:#d3e1f0}
.tb thead th.tb-overall-group{background:#0c7da7!important;border-color:#0c7da7}
.tb thead tr:nth-child(2) th.tb-ov{background:#d6eef7;color:#0b5f80}
.tb .tb-start{border-left:2px solid #c7d6e6}
.tb td.is-band{background:#f7fafd}
.tb tbody tr:nth-child(even) td,.tb tbody tr:nth-child(even) th{background-color:#fbfcfe}
.tb tbody tr:hover td,.tb tbody tr:hover th{background-color:#eef6fd}
.tb .tb-id{position:sticky;z-index:2;background:#fff}
.tb thead .tb-id{z-index:5;top:0}
.tb .tb-merit{left:0;min-width:58px}
.tb .tb-roll{left:58px;min-width:52px}
.tb .tb-name{left:110px;min-width:190px;text-align:left;font-weight:800;color:#112958;box-shadow:3px 0 0 #c7d6e6}
.tb thead .tb-name{color:#fff}
.tb td.tb-mark{min-width:40px;color:#334155}
.tb td.tb-pair{min-width:66px;padding:4px 8px;line-height:1.25;text-align:center}
.tb .tb-papers{display:grid;grid-template-columns:auto auto;justify-content:center;column-gap:7px;font-size:11.5px;color:#475569}
.tb .tb-papers small{color:#94a3b8;font-weight:800;font-size:9.5px;text-align:left;line-height:1.5}
.tb .tb-papers span{text-align:right;font-variant-numeric:tabular-nums}
.tb td.tb-pair strong{display:block;margin:3px auto 0;padding-top:2px;border-top:1px solid #cbd5e1;font-size:13px;font-weight:800;color:#0f172a;text-align:center}
.tb td.tb-total{min-width:62px;background:#f1f6fb;border-right:2px solid #c7d6e6}
.tb td.tb-total strong{display:block;font-size:14px;font-weight:800;color:#0f172a}
.tb td.tb-total small{display:block;font-size:10.5px;font-weight:700;color:#64748b}
.tb td.tb-total.is-fail{background:#fde8e8!important}
.tb td.tb-total.is-fail strong,.tb td.tb-total.is-fail small{color:#b91c1c}
.tb td.tb-na{color:#cbd5e1;background:#fafbfd}
.tb td.tb-ov{background:#f2fafd;color:#0f3d57;min-width:62px}
.tb td.tb-ov strong{font-size:14px}

/* ===== Online copy print (rendered in its own print window) ===== */
.oc-print body{margin:0;background:#fff;font-family:"Segoe UI",Roboto,Arial,sans-serif}
.oc-print .ir-report{border:0;border-radius:0;margin:0}
.oc-print .ir-report-top,.oc-print .ir-report-nav,.oc-print .ir-tools,.oc-print .ir-pager,.oc-print .ir-report-foot{display:none!important}
.oc-print .ir-report-body{padding:0}
.oc-print [data-ir-row],.oc-print .ir-group{display:revert!important}
.oc-print body{font-family:"Segoe UI",Roboto,Arial,sans-serif;color:#1e293b}
.oc-print .oc-head{display:block;margin-bottom:8px}
.oc-print .oc-topbar{height:6px;margin-bottom:10px;background:#0b2545;border-bottom:3px solid #0c7da7}
.oc-print .oc-letter{width:100%;border-collapse:collapse;table-layout:fixed}
.oc-print .oc-letter td{padding:0;vertical-align:middle}
.oc-print .oc-logo-cell{width:84px}
.oc-print .oc-seal-cell{width:140px}
.oc-print .oc-logo{width:68px;height:68px;padding:3px;border:1.5px solid #0c7da7;border-radius:50%;display:flex;align-items:center;justify-content:center;overflow:hidden}
.oc-print .oc-logo img{max-width:100%;max-height:100%;border-radius:50%}
.oc-print .oc-logo span{font-weight:900;color:#0b2545;font-size:18px}
.oc-print .oc-school{text-align:center}
.oc-print .oc-school strong{display:block;font-size:21px;font-weight:800;color:#0b2545;text-transform:uppercase;letter-spacing:.05em}
.oc-print .oc-school span{display:block;margin-top:2px;font-size:10px;color:#475569}
.oc-print .oc-seal{float:right;width:118px;padding:3px;border:1.5px solid #0c7da7}
.oc-print .oc-seal div{padding:4px;border:1px dashed #0c7da7;text-align:center;color:#0c7da7}
.oc-print .oc-seal b{display:block;font-size:11.5px;letter-spacing:.22em}
.oc-print .oc-seal small{display:block;margin-top:2px;font-size:7.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#475569}
.oc-print .oc-rule{height:0;margin-top:10px;padding-top:2px;border-top:2.5px solid #0b2545;border-bottom:1px solid #0c7da7}
.oc-print .oc-strip{width:100%;margin-top:7px;border-collapse:collapse;table-layout:fixed;background:#f3f6fa;border:1px solid #d5dde8}
.oc-print .oc-strip td{padding:4px 9px;border-right:1px solid #d5dde8;font-size:10px;font-weight:700;color:#0b2545}
.oc-print .oc-strip td:last-child{border-right:0}
.oc-print .oc-strip span{display:block;font-size:7.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#64748b}
.oc-print .oc-title{width:100%;margin:10px 0 2px;border-collapse:collapse}
.oc-print .oc-title td{padding:0;vertical-align:middle}
.oc-print .oc-title .oc-line{border-top:1px solid #c9d3df}
.oc-print .oc-title .oc-word{width:1%;white-space:nowrap;padding:0 14px;font-size:14px;font-weight:800;letter-spacing:.28em;color:#0b2545}
.oc-print .oc-watermark{display:flex;align-items:center;justify-content:center;position:fixed;left:50%;top:50%;width:300px;height:300px;transform:translate(-50%,-50%);opacity:.045;font-size:110px;font-weight:900;color:#0b2545;pointer-events:none;z-index:0}
.oc-print .oc-watermark img{max-width:100%;max-height:100%}
.oc-print .oc-foot{display:block;margin-top:10px}
.oc-print .oc-foot table{width:100%;border-collapse:collapse;border-top:1.5px solid #0b2545}
.oc-print .oc-foot td{padding:5px 0 0;font-size:9px;color:#475569}
.oc-print .oc-foot td+td{text-align:right}
.oc-print .oc-foot b{color:#0b2545}
.oc-print .oc-note{margin-top:5px;padding:4px 8px;background:#f3f6fa;border-left:3px solid #0c7da7;font-size:8.5px;color:#334155}
.oc-print .ir-stats{display:grid!important;grid-template-columns:repeat(5,1fr);gap:0;margin:8px 0 6px;border:1.5px solid #0b2545}
.oc-print .ir-stat{border:0;border-right:1px solid #c9d3df;border-radius:0;padding:5px 8px;background:#fff;text-align:center}
.oc-print .ir-stat:last-child{border-right:0}
.oc-print .ir-stat span{font-size:7.5px;letter-spacing:.14em}
.oc-print .ir-stat strong{font-size:16px;margin-top:1px}
.oc-print .ir-table th{background:#0b2545;color:#fff;font-size:9px;letter-spacing:.1em;border-color:#0b2545}
.oc-print .ir-block h4{padding:0 0 3px 8px;border-left:3px solid #0b2545;border-bottom:1px solid #dde4ee;font-size:10px;letter-spacing:.16em;color:#0b2545}
.oc-print .tb thead tr:first-child th{background:#0b2545}
.oc-print .tb thead tr:first-child th.is-band{background:#163a66}
.oc-print .ir-block{margin-top:10px}
.oc-print .ir-block h4{font-size:12px}
.oc-print .ir-table{font-size:11px}
.oc-print .ir-table th,.oc-print .ir-table td{padding:5px 7px;border:1px solid #dbe5ef}
.oc-print .tb-wrap{overflow:visible;max-height:none;border:0;border-radius:0}
.oc-print .tb{font-size:9.5px;width:auto;min-width:0}
.oc-print .tb th,.oc-print .tb td{position:static!important;box-shadow:none!important;padding:3px 4px;border-color:#cfdbe8}
.oc-print .tb thead tr:first-child th{height:auto}
.oc-print .tb .tb-name{min-width:120px}
.oc-print .tb tbody tr{break-inside:avoid}
.oc-print .tb th,.oc-print .tb td{min-width:0!important;padding:2px 4px!important;font-size:10px}
.oc-print .tb thead tr:first-child th{white-space:normal;font-size:10px;letter-spacing:0}
.oc-print .tb thead tr:first-child th small{font-size:8px}
.oc-print .tb .tb-name{min-width:130px!important;white-space:normal}
.oc-print .tb td.tb-total strong,.oc-print .tb td.tb-ov strong,.oc-print .tb td.tb-pair strong{font-size:11px}
.oc-print .tb td.tb-total small{font-size:8.5px}
.oc-print .tb .tb-papers{font-size:9.5px;column-gap:4px}
.oc-print .tb .tb-papers small{font-size:8px}
.oc-print .ir-pos{min-width:0;height:auto;padding:0 5px;font-size:10px}
.oc-print .ir-pill{padding:0 5px;font-size:9.5px}
.oc-print .tb-part{margin:0 0 4px;font-size:11px;font-weight:800;color:#0c7da7;letter-spacing:.06em;text-transform:uppercase}
.oc-print .tb-part + .tb-wrap{margin-bottom:6px}
.oc-print .tb-break{break-before:page}
.oc-print *{-webkit-print-color-adjust:exact;print-color-adjust:exact}
@media (max-width:767.98px){.ir-class-form{grid-template-columns:1fr}}
</style>

<form method="GET" action="{{ route('internalResult') }}" class="ir-class-form ir-no-print" aria-label="Choose class result">
    <input type="hidden" name="tab" value="class">
    <div>
        <label for="ir-class-exam">Exam</label>
        <select id="ir-class-exam" name="class_exam" onchange="this.form.scope && (this.form.scope.value=''); this.form.submit()" required>
            <option value="">Select exam</option>
            @foreach($exams as $exam)
                <option value="{{ $exam['id'] }}" @selected((string) $classExamId === (string) $exam['id'])>{{ $exam['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="ir-class-scope">Class / Section</label>
        <select id="ir-class-scope" name="scope" onchange="this.form.submit()" @disabled(empty($classScopes))>
            <option value="">{{ $classExamId ? (empty($classScopes) ? 'No class result published' : 'Select class') : 'Select an exam first' }}</option>
            @foreach($classScopes as $classScope)
                <option value="{{ $classScope['key'] }}" @selected($scopeKey === $classScope['key'])>{{ $classScope['label'] }}{{ $classScope['session'] ? ' · '.$classScope['session'] : '' }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit">Show Result</button>
</form>

@if($classError)
    <div class="ir-alert is-warn" role="alert">{{ $classError }}</div>
@elseif($classExamId && empty($classScopes))
    <div class="ir-alert is-info" role="status">The school has not published class-wise results for this exam on the website. You can still check your own result from the <a href="{{ route('internalResult', ['exam' => $classExamId]) }}">My Result</a> tab.</div>
@elseif(! $classExamId)
    <p class="ir-note">Choose an exam, then a class to see its result summary and merit list.</p>
@endif

@if($report)
    @php
        $meta = $report['meta'];
        $summary = $report['summary'] ?? null;
        $reportTitle = $reportNames[$reportType] ?? 'Result';
        $scopeLine = trim($meta['class'].($meta['section'] ? ' — Section '.$meta['section'] : ''));
        $pct = fn ($value) => $value === null ? '—' : rtrim(rtrim(number_format((float) $value, 2), '0'), '.').'%';
        $statusPill = fn ($status) => $status === 'Pass' ? 'is-pass' : ($status === 'Fail' ? 'is-fail' : 'is-other');
        $gradeColors = ['A+' => '#15803d', 'A' => '#22a35a', 'A-' => '#65a30d', 'B' => '#0c7da7', 'C' => '#6366f1', 'D' => '#d97706', 'F' => '#dc2626'];
        $routeFor = fn ($type) => route('internalResult', ['tab' => 'class', 'class_exam' => $classExamId, 'scope' => $scopeKey, 'report' => $type]);
    @endphp
    <article class="ir-report ir-printable" data-ir-report data-paper="{{ $reportType === 'tabulation' ? 'landscape' : 'portrait' }}" aria-label="{{ $reportTitle }}">
        <div class="oc-watermark" aria-hidden="true">@if($institution['logoUrl'])<img src="{{ $institution['logoUrl'] }}" alt="">@else{{ $institution['initials'] }}@endif</div>
        <header class="oc-head">
            <div class="oc-topbar"></div>
            <table class="oc-letter" role="presentation"><tr>
                <td class="oc-logo-cell"><div class="oc-logo">@if($institution['logoUrl'])<img src="{{ $institution['logoUrl'] }}" alt="">@else<span>{{ $institution['initials'] }}</span>@endif</div></td>
                <td class="oc-school">
                    <strong>{{ $institution['name'] }}</strong>
                    @if($institution['address'])<span>{{ $institution['address'] }}</span>@endif
                    <span>{{ collect([$institution['phone'] ? 'Phone: '.$institution['phone'] : null, $institution['email'] ? 'Email: '.$institution['email'] : null])->filter()->implode('  |  ') }}</span>
                </td>
                <td class="oc-seal-cell"><div class="oc-seal"><div><b>ONLINE</b><b>COPY</b><small>Not for official use</small></div></div></td>
            </tr></table>
            <div class="oc-rule"></div>
            <table class="oc-strip" role="presentation"><tr>
                <td><span>Document</span>{{ $reportTitle }} — Online Copy</td>
                <td><span>Examination</span>{{ $meta['exam'] }}</td>
                <td><span>Class / Section</span>{{ $scopeLine }}</td>
                <td><span>Session</span>{{ $meta['session'] }}</td>
                <td><span>Published</span>{{ $meta['publishedAt'] ?: '—' }}</td>
            </tr></table>
            <table class="oc-title" role="presentation"><tr><td class="oc-line"></td><td class="oc-word">{{ strtoupper($reportTitle) }}</td><td class="oc-line"></td></tr></table>
        </header>
        <div class="ir-report-top">
            <div>
                <h3>{{ $reportTitle }}</h3>
                <p>{{ $meta['exam'] }} · {{ $scopeLine }} · Session {{ $meta['session'] }}</p>
            </div>
            <button type="button" class="ir-print" onclick="irPrintReport()">Print / Save PDF</button>
        </div>
        @if(count($report['reports']) > 1)
            <nav class="ir-report-nav" aria-label="Reports">
                @foreach($report['reports'] as $type)
                    <a href="{{ $routeFor($type) }}" class="{{ $type === $reportType ? 'is-active' : '' }}" @if($type === $reportType) aria-current="page" @endif>{{ $reportNames[$type] ?? $type }}</a>
                @endforeach
            </nav>
        @endif

        <div class="ir-report-body">
            @if($summary)
                <div class="ir-stats">
                    <div class="ir-stat"><span>Examinees</span><strong>{{ $summary['overall']['examined'] }}</strong></div>
                    <div class="ir-stat is-pass"><span>Passed</span><strong>{{ $summary['overall']['passed'] }}</strong></div>
                    <div class="ir-stat is-fail"><span>Failed</span><strong>{{ $summary['overall']['failed'] }}</strong></div>
                    <div class="ir-stat is-pass"><span>Pass rate</span><strong>{{ $pct($summary['overall']['passRate']) }}</strong></div>
                    <div class="ir-stat is-gold"><span>GPA 5.00</span><strong>{{ $summary['gpa5'] }}</strong></div>
                </div>
            @endif

            @if($reportType === 'summary' && $summary)
                @php $gradeTotal = max(1, array_sum($summary['grades'])); @endphp
                <div class="ir-block">
                    <h4>Grade distribution</h4>
                    <div class="ir-grades" role="img" aria-label="Grade distribution">
                        @foreach($summary['grades'] as $grade => $count)
                            @if($count > 0)<div style="flex:{{ $count }};background:{{ $gradeColors[$grade] ?? '#64748b' }}" title="{{ $grade }}: {{ $count }}">{{ $count / $gradeTotal >= .06 ? $grade : '' }}</div>@endif
                        @endforeach
                    </div>
                    <div class="ir-grade-legend">
                        @foreach($summary['grades'] as $grade => $count)
                            <span><i style="background:{{ $gradeColors[$grade] ?? '#64748b' }}"></i>{{ $grade }}: <strong>{{ $count }}</strong></span>
                        @endforeach
                    </div>
                </div>

                @if(! empty($summary['gender']))
                    <div class="ir-block">
                        <h4>Boys and girls</h4>
                        <div class="ir-table-wrap"><table class="ir-table">
                            <thead><tr><th class="ir-left">Group</th><th>Students</th><th>Examinees</th><th>Passed</th><th>Failed</th><th>Pass rate</th><th>Fail rate</th></tr></thead>
                            <tbody>
                            @foreach($summary['gender'] as $line)
                                <tr><td class="ir-left ir-subject">{{ $line['label'] }}</td><td>{{ $line['total'] }}</td><td>{{ $line['examined'] }}</td><td>{{ $line['passed'] }}</td><td>{{ $line['failed'] }}</td><td>{{ $pct($line['passRate']) }}</td><td>{{ $pct($line['failRate']) }}</td></tr>
                            @endforeach
                            <tr><td class="ir-left ir-total">Total</td><td class="ir-total">{{ $summary['overall']['total'] }}</td><td class="ir-total">{{ $summary['overall']['examined'] }}</td><td class="ir-total">{{ $summary['overall']['passed'] }}</td><td class="ir-total">{{ $summary['overall']['failed'] }}</td><td class="ir-total">{{ $pct($summary['overall']['passRate']) }}</td><td class="ir-total">{{ $pct($summary['overall']['failRate']) }}</td></tr>
                            </tbody>
                        </table></div>
                    </div>
                @endif

                <div class="ir-block">
                    <h4>Subject-wise result</h4>
                    <div class="ir-table-wrap"><table class="ir-table">
                        <thead><tr><th class="ir-left">Subject</th><th>Students</th><th>Examinees</th><th>Passed</th><th>Failed</th><th>Pass rate</th><th>Fail rate</th></tr></thead>
                        <tbody>
                        @foreach($summary['subjects'] as $line)
                            <tr class="{{ $line['failed'] > 0 && ($line['failRate'] ?? 0) >= 20 ? 'is-fail' : '' }}"><td class="ir-left ir-subject">{{ $line['name'] }}</td><td>{{ $line['enrolled'] }}</td><td>{{ $line['examined'] }}</td><td>{{ $line['passed'] }}</td><td>{{ $line['failed'] }}</td><td>{{ $pct($line['passRate']) }}</td><td>{{ $pct($line['failRate']) }}</td></tr>
                        @endforeach
                        </tbody>
                    </table></div>
                </div>
            @endif

            @if($reportType === 'merit')
                <div class="ir-block" data-ir-paged>
                    <div class="ir-tools"><h4 style="margin:0">Passed students in merit order</h4><input type="search" placeholder="Search by roll or name" aria-label="Search by roll or name" data-ir-filter></div>
                    <div class="ir-table-wrap"><table class="ir-table">
                        <thead><tr><th>Merit</th><th>Roll</th><th class="ir-left">Name</th><th>Total Marks</th><th>GPA</th><th>Grade</th></tr></thead>
                        <tbody>
                        @forelse($report['rows'] as $row)
                            <tr data-ir-row data-ir-text="{{ strtolower($row['roll'].' '.$row['name']) }}"><td><span class="ir-pos {{ ($row['position'] ?? 99) <= 3 ? 'is-top' : '' }}">{{ $row['position'] ?? '—' }}</span></td><td>{{ $row['roll'] }}</td><td class="ir-left ir-subject">{{ $row['name'] }}</td><td>{{ $row['totalMarks'] }}</td><td class="ir-total">{{ $row['gpa'] }}</td><td>{{ $row['grade'] }}</td></tr>
                        @empty
                            <tr><td colspan="6">No student has passed in this result.</td></tr>
                        @endforelse
                        </tbody>
                    </table></div>
                    <div class="ir-pager" data-ir-pager></div>
                </div>
            @endif

            @if($reportType === 'tabulation')
                @php
                    $tabSubjects = $report['tabulation']['subjects'];
                    $tabCells = $report['tabulation']['cells'];
                @endphp
                <div class="ir-block" data-ir-paged>
                    <div class="ir-tools">
                        <div class="tb-legend" aria-label="Column guide">
                            <span><b>CQ</b> Creative</span><span><b>MCQ</b> Multiple choice</span><span><b>PR</b> Practical</span>
                            <span><b>P1 / P2</b> 1st / 2nd paper</span><span><i class="tb-key-fail"></i> Failed</span><span><b>A+ · 5.00</b> Grade · Point</span>
                        </div>
                        <input type="search" placeholder="Search by roll or name" aria-label="Search by roll or name" data-ir-filter>
                    </div>
                    <div class="tb-wrap" role="region" aria-label="Tabulation sheet" tabindex="0">
                        <table class="tb">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="tb-id tb-merit" scope="col">Merit</th>
                                    <th rowspan="2" class="tb-id tb-roll" scope="col">Roll</th>
                                    <th rowspan="2" class="tb-id tb-name" scope="col">Student Name</th>
                                    @foreach($tabSubjects as $si => $subject)
                                        <th colspan="{{ count($subject['components']) + 1 }}" scope="colgroup" class="tb-subject {{ $si % 2 ? 'is-band' : '' }}" title="{{ $subject['name'] }}">{{ $subject['short'] }}@if($subject['optional'])<em>4th</em>@endif @if($subject['paired'])<small>1st + 2nd paper</small>@endif</th>
                                    @endforeach
                                    <th colspan="4" scope="colgroup" class="tb-overall-group">Overall Result</th>
                                </tr>
                                <tr>
                                    @foreach($tabSubjects as $si => $subject)
                                        @foreach($subject['components'] as $component)<th scope="col" class="tb-comp {{ $si % 2 ? 'is-band' : '' }} {{ $loop->first ? 'tb-start' : '' }}">{{ $component['label'] }}</th>@endforeach
                                        <th scope="col" class="tb-total-head {{ $si % 2 ? 'is-band' : '' }}">Total</th>
                                    @endforeach
                                    <th scope="col" class="tb-ov tb-start">Total Marks</th><th scope="col" class="tb-ov">GPA</th><th scope="col" class="tb-ov">Grade</th><th scope="col" class="tb-ov">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($report['rows'] as $index => $row)
                                <tr data-ir-row data-ir-text="{{ strtolower($row['roll'].' '.$row['name']) }}">
                                    <td class="tb-id tb-merit"><span class="ir-pos {{ ($row['position'] ?? 99) <= 3 ? 'is-top' : '' }}">{{ $row['position'] ?? '—' }}</span></td>
                                    <td class="tb-id tb-roll">{{ $row['roll'] ?: '—' }}</td>
                                    <th scope="row" class="tb-id tb-name">{{ $row['name'] }}</th>
                                    @foreach($tabSubjects as $si => $subject)
                                        @php $cell = $tabCells[$index][$subject['key']] ?? null; @endphp
                                        @if(! $cell)
                                            <td colspan="{{ count($subject['components']) + 1 }}" class="tb-na tb-start {{ $si % 2 ? 'is-band' : '' }}" title="Subject not taken">—</td>
                                        @else
                                            @foreach($subject['components'] as $component)
                                                @php $part = $cell['components'][$component['key']] ?? null; @endphp
                                                @if($subject['paired'])
                                                    <td class="tb-pair {{ $si % 2 ? 'is-band' : '' }} {{ $loop->first ? 'tb-start' : '' }}">@if($part)<span class="tb-papers">@foreach($part['papers'] as $paperMark)<small>{{ $paperMark['label'] }}</small><span>{{ $paperMark['value'] }}</span>@endforeach</span><strong>{{ $part['total'] }}</strong>@else — @endif</td>
                                                @else
                                                    <td class="tb-mark {{ $si % 2 ? 'is-band' : '' }} {{ $loop->first ? 'tb-start' : '' }}">{{ $part['value'] ?? '—' }}</td>
                                                @endif
                                            @endforeach
                                            <td class="tb-total {{ $cell['failed'] ? 'is-fail' : '' }}"><strong>{{ $cell['total'] }}</strong><small>{{ $cell['grade'] }} · {{ $cell['gradePoint'] }}</small></td>
                                        @endif
                                    @endforeach
                                    <td class="tb-ov tb-start"><strong>{{ $row['totalMarks'] ?? '—' }}</strong></td>
                                    <td class="tb-ov"><strong>{{ $row['gpa'] }}</strong></td>
                                    <td class="tb-ov">{{ $row['grade'] }}</td>
                                    <td class="tb-ov"><span class="ir-pill {{ $statusPill($row['status']) }}">{{ $row['status'] === 'Fail' && $row['failedIn'] ? 'Fail ('.$row['failedIn'].')' : $row['status'] }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="ir-pager" data-ir-pager></div>
                </div>
            @endif

            @if($reportType === 'performance')
                <div class="ir-block" data-ir-filter-scope>
                    <div class="ir-tools"><h4 style="margin:0">Students grouped by the number of failed subjects</h4><input type="search" placeholder="Search by roll or name" aria-label="Search by roll or name" data-ir-filter></div>
                    @forelse($report['groups'] as $group)
                        <section class="ir-group {{ $group['failureCount'] === 0 ? 'is-pass' : 'is-fail' }}">
                            <h4>{{ $group['label'] }} ({{ count($group['rows']) }})</h4>
                            <div class="ir-table-wrap"><table class="ir-table">
                                <thead><tr><th>Roll</th><th class="ir-left">Name</th><th>GPA</th><th>Grade</th><th class="ir-left">{{ $group['failureCount'] === 0 ? 'Merit' : 'Failed subjects' }}</th></tr></thead>
                                <tbody>
                                @foreach($group['rows'] as $row)
                                    <tr data-ir-row data-ir-text="{{ strtolower($row['roll'].' '.$row['name']) }}"><td>{{ $row['roll'] }}</td><td class="ir-left ir-subject">{{ $row['name'] }}</td><td class="ir-total">{{ $row['gpa'] }}</td><td>{{ $row['grade'] }}</td><td class="ir-left">{{ $group['failureCount'] === 0 ? ($row['position'] ?? '—') : implode(', ', $row['failedSubjects']) }}</td></tr>
                                @endforeach
                                </tbody>
                            </table></div>
                        </section>
                    @empty
                        <p class="ir-note">No final results in this class yet.</p>
                    @endforelse
                </div>
            @endif
        </div>
        <footer class="oc-foot">
            <table role="presentation"><tr><td><b>{{ $institution['name'] }}</b> · Online result service</td><td>Generated <span data-oc-time></span></td></tr></table>
            <div class="oc-note">This online copy is issued for information only and carries no signature. The signed sheet kept by the school office is the official record.</div>
        </footer>
        <div class="ir-report-foot">Online copy of the published result{{ $meta['publishedAt'] ? ' · Published '.$meta['publishedAt'] : '' }}{{ ($meta['revision'] ?? 1) > 1 ? ' · Revision '.$meta['revision'] : '' }}. The school's signed result sheet is the final record.</div>
    </article>
    <script>
    /* A wide tabulation is printed in parts (subjects split across pages, each part repeats Merit/Roll/Name
       and the last part carries the Overall Result), so marks stay readable instead of shrinking the whole sheet. */
    function irSplitTabulation(doc, available) {
        var table = doc.querySelector('.tb');
        if (!table || table.scrollWidth <= available * 1.15) return;
        var head1 = table.tHead.rows[0], head2 = table.tHead.rows[1];
        var idCount = 3, groups = [], subIndex = 0;
        for (var c = idCount; c < head1.cells.length; c++) {
            var span = head1.cells[c].colSpan;
            groups.push({head: head1.cells[c], subs: Array.prototype.slice.call(head2.cells, subIndex, subIndex + span), span: span, overall: c === head1.cells.length - 1});
            subIndex += span;
        }
        var bodyCells = Array.prototype.map.call(table.tBodies[0].rows, function (row) {
            var cells = Array.prototype.slice.call(row.cells), id = cells.slice(0, idCount), rest = cells.slice(idCount), out = [], k = 0;
            groups.forEach(function (g) {
                var taken = [], width = 0;
                while (width < g.span && k < rest.length) { taken.push(rest[k]); width += rest[k].colSpan; k++; }
                out.push(taken);
            });
            return {row: row, id: id, groups: out};
        });
        var subjectGroups = groups.filter(function (g) { return !g.overall; });
        var overall = groups[groups.length - 1];
        var widthOf = function (g) { return g.head.getBoundingClientRect().width; };
        var idWidth = Array.prototype.slice.call(head1.cells, 0, idCount).reduce(function (sum, cell) { return sum + cell.getBoundingClientRect().width; }, 0);
        var budget = available / 0.85 - idWidth, parts = [], current = [], used = 0;
        subjectGroups.forEach(function (g) {
            var w = widthOf(g);
            if (current.length && used + w > budget) { parts.push(current); current = []; used = 0; }
            current.push(g); used += w;
        });
        if (current.length) parts.push(current);
        if (used + widthOf(overall) > budget) parts.push([]);
        parts[parts.length - 1].push(overall);
        var wrap = table.parentNode, holder = doc.createElement('div');
        parts.forEach(function (part, p) {
            var t = doc.createElement('table'); t.className = table.className;
            var thead = t.createTHead(), r1 = thead.insertRow(), r2 = thead.insertRow();
            Array.prototype.slice.call(head1.cells, 0, idCount).forEach(function (cell) { r1.appendChild(cell.cloneNode(true)); });
            part.forEach(function (g) { r1.appendChild(g.head.cloneNode(true)); g.subs.forEach(function (cell) { r2.appendChild(cell.cloneNode(true)); }); });
            var tbody = t.createTBody();
            bodyCells.forEach(function (entry) {
                var tr = entry.row.cloneNode(false);
                entry.id.forEach(function (cell) { tr.appendChild(cell.cloneNode(true)); });
                part.forEach(function (g) { entry.groups[groups.indexOf(g)].forEach(function (cell) { tr.appendChild(cell.cloneNode(true)); }); });
                tbody.appendChild(tr);
            });
            var label = doc.createElement('p'); label.className = 'tb-part' + (p ? ' tb-break' : '');
            label.textContent = 'Part ' + (p + 1) + ' of ' + parts.length;
            var w = doc.createElement('div'); w.className = 'tb-wrap'; w.appendChild(t);
            holder.appendChild(label); holder.appendChild(w);
        });
        wrap.parentNode.replaceChild(holder, wrap);
    }
    function irPrintReport() {
        var report = document.querySelector('[data-ir-report]');
        if (!report) return;
        var landscape = report.getAttribute('data-paper') === 'landscape';
        var win = window.open('', '_blank', 'width=1200,height=900');
        if (!win) { window.print(); return; }
        var styles = Array.prototype.map.call(document.querySelectorAll('style[data-ir-style]'), function (el) { return el.outerHTML; }).join('');
        var clone = report.cloneNode(true);
        clone.querySelectorAll('[data-ir-row],.ir-group').forEach(function (el) { el.style.display = ''; });
        var stamp = new Date().toLocaleString('en-GB', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'});
        clone.querySelectorAll('[data-oc-time]').forEach(function (el) { el.textContent = stamp; });
        win.document.open();
        win.document.write('<!DOCTYPE html><html class="oc-print"><head><meta charset="utf-8"><title>' + document.title.replace(/</g, '') + ' - Online Copy</title>' + styles
            + '<style>@page{size:' + (landscape ? 'legal landscape' : 'A4 portrait') + ';margin:9mm 8mm 11mm;@bottom-right{content:"Page " counter(page) " of " counter(pages);font:600 8px Arial,sans-serif;color:#64748b}@bottom-left{content:"Online copy · not for official use";font:600 8px Arial,sans-serif;color:#64748b}}</style></head><body>' + clone.outerHTML + '</body></html>');
        win.document.close();
        var available = (landscape ? 1340 : 760) - 40;
        var fit = function () {
            irSplitTabulation(win.document, available);
            Array.prototype.forEach.call(win.document.querySelectorAll('.tb'), function (table) {
                var scale = Math.min(1, available / table.scrollWidth);
                table.style.zoom = scale.toFixed(3);
            });
            win.focus(); win.print();
        };
        var images = win.document.images, pending = images.length;
        if (!pending) { setTimeout(fit, 200); return; }
        Array.prototype.forEach.call(images, function (img) {
            if (img.complete) { if (--pending === 0) setTimeout(fit, 200); return; }
            img.onload = img.onerror = function () { if (--pending === 0) setTimeout(fit, 200); };
        });
    }
    (function () {
        var PAGE = 25;
        document.querySelectorAll('[data-ir-paged], [data-ir-filter-scope]').forEach(function (block) {
            var rows = Array.prototype.slice.call(block.querySelectorAll('[data-ir-row]'));
            var input = block.querySelector('[data-ir-filter]');
            var pager = block.querySelector('[data-ir-pager]');
            var page = 1;
            function render() {
                var q = input ? input.value.trim().toLowerCase() : '';
                var hits = rows.filter(function (r) { return !q || r.getAttribute('data-ir-text').indexOf(q) !== -1; });
                var pages = pager ? Math.max(1, Math.ceil(hits.length / PAGE)) : 1;
                if (page > pages) page = pages;
                rows.forEach(function (r) { r.style.display = 'none'; });
                hits.forEach(function (r, i) { if (!pager || (i >= (page - 1) * PAGE && i < page * PAGE)) r.style.display = ''; });
                block.querySelectorAll('.ir-group').forEach(function (g) {
                    g.style.display = !q || g.querySelector('[data-ir-row]:not([style*="none"])') ? '' : 'none';
                });
                if (!pager) return;
                pager.innerHTML = '';
                if (pages < 2) return;
                for (var p = 1; p <= pages; p++) {
                    var b = document.createElement('button');
                    b.type = 'button'; b.textContent = p;
                    if (p === page) { b.className = 'is-active'; b.setAttribute('aria-current', 'page'); }
                    b.addEventListener('click', (function (n) { return function () { page = n; render(); block.scrollIntoView({block: 'start'}); }; })(p));
                    pager.appendChild(b);
                }
            }
            if (input) input.addEventListener('input', function () { page = 1; render(); });
            render();
        });
    })();
    </script>
@endif
