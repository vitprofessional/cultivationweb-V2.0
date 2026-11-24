@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Internal Result
@endsection
@section('frontcontent')
@include('frontend.academic.partials._theme')
<style data-ir-style>
.ir-shell{background:linear-gradient(180deg,#f8fbff 0%,#f2f8fd 100%);border:1px solid #dce9f4;border-radius:18px;padding:22px;box-shadow:0 12px 28px rgba(19,54,102,.08)}
.ir-head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:12px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #dce9f4}
.ir-kicker{display:inline-flex;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#0c7da7;font-weight:800;padding:5px 10px;border-radius:999px;border:1px solid #bce7f4;background:#dff4fb}
.ir-head p{margin:6px 0 0;color:#5a708a;font-size:14px}
.ir-search{display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1.3fr) auto;gap:12px;align-items:end;padding:16px;border:1px solid #dce9f4;border-radius:14px;background:#fff}
.ir-search label{display:block;margin-bottom:6px;font-size:12px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:#1f3f66}
.ir-search select,.ir-search input{width:100%;min-height:44px;padding:8px 12px;border:1px solid #cfe0ef;border-radius:10px;font-size:15px;color:#112958;background:#fff}
.ir-search button{min-height:44px;padding:0 22px;border:0;border-radius:10px;background:#112958;color:#fff;font-weight:800;font-size:15px;cursor:pointer}
.ir-search button:hover{background:#0c7da7}
.ir-note{margin:10px 2px 0;font-size:13px;color:#6b7f95}
.ir-alert{margin-top:16px;padding:14px 16px;border-radius:12px;font-size:14px;font-weight:600}
.ir-alert.is-warn{background:#fff7ed;border:1px solid #fed7aa;color:#9a3412}
.ir-alert.is-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a}
.ir-card{margin-top:18px;border:1px solid #dce9f4;border-radius:16px;background:#fff;overflow:hidden}
.ir-card-top{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:16px;align-items:center;padding:18px 20px;background:#112958;color:#fff}
.ir-card .ir-card-top h3{margin:0;font-size:24px;font-weight:800;letter-spacing:.01em;color:#fff!important}
.ir-card-top p{margin:4px 0 0;color:#c7d6ea;font-size:14px}
.ir-verdict{display:flex;gap:10px}
.ir-verdict div{min-width:86px;padding:8px 12px;border-radius:12px;background:rgba(255,255,255,.1);text-align:center}
.ir-verdict span{display:block;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#c7d6ea}
.ir-verdict strong{display:block;font-size:22px;font-weight:800}
.ir-verdict .is-pass strong{color:#86efac}.ir-verdict .is-fail strong{color:#fca5a5}
.ir-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1px;background:#e5eef7}
.ir-info div{padding:10px 16px;background:#f8fbff}
.ir-info span{display:block;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#6e859d}
.ir-info strong{display:block;margin-top:2px;font-size:15px;color:#112958}
.ir-table-wrap{overflow-x:auto}
.ir-table{width:100%;border-collapse:collapse;font-size:14px}
.ir-table th{padding:10px 12px;background:#eef5fb;color:#1f3f66;font-size:12px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;text-align:center;border-bottom:1px solid #d7e6f3}
.ir-table th:first-child,.ir-table td:first-child{text-align:left}
.ir-table td{padding:10px 12px;border-bottom:1px solid #edf3f9;text-align:center;color:#3f5772}
.ir-table tbody tr:nth-child(even) td{background:#fafcff}
.ir-table td.ir-subject{font-weight:700;color:#264561}
.ir-table td.ir-total{font-weight:800;color:#112958}
.ir-table tr.is-fail td{color:#b91c1c}
.ir-table tr.is-fail td.ir-subject{color:#b91c1c}
.ir-section-title{padding:9px 20px;background:#f1f7fb;border-top:1px solid #e5eef7;border-bottom:1px solid #e5eef7;color:#0c4a6e;font-size:12px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}
.ir-transcript{table-layout:fixed;min-width:620px}
.ir-transcript th:first-child{width:38%}
.ir-transcript td{border-right:1px solid #edf3f9}
.ir-transcript td.tr-subject{text-align:left;font-weight:700;color:#264561}
.ir-transcript td.tr-total{font-weight:800;font-size:15px;color:#112958;background:#f8fbfd}
.ir-transcript td.tr-grade strong{display:block;color:#112958;font-size:14px}
.ir-transcript td.tr-grade span{display:block;font-size:11px;color:#6b7f95}
.ir-transcript td.tr-grade strong.tr-fail{color:#b91c1c}
.ir-transcript tr.tr-group-start td{border-top:2px solid #c7d6e6}
.ir-transcript tr.tr-group-end td,.ir-transcript td.tr-group-end{border-bottom:2px solid #c7d6e6}
.ir-transcript tr.tr-combined td{background:#eef6fa!important}
.ir-transcript td.tr-combined-label{font-size:11px;font-weight:800;color:#0c7da7;text-transform:uppercase;letter-spacing:.08em}
.ir-tag{display:inline-block;margin-left:6px;padding:1px 7px;border-radius:999px;background:#fef3c7;color:#92400e;font-size:11px;font-weight:700}
.ir-foot{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;padding:14px 20px;border-top:1px solid #e5eef7;font-size:13px;color:#5a708a}
.ir-foot .ir-failed{color:#b91c1c;font-weight:700}
.ir-print{min-height:38px;padding:0 16px;border:1px solid #112958;border-radius:10px;background:#fff;color:#112958;font-weight:800;cursor:pointer}
.ir-archive{margin-top:26px}
.ir-archive h3{margin:0 0 12px;font-size:16px;font-weight:800;color:#112958}
.ir-archive-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px}
.ir-file{display:flex;gap:12px;align-items:center;padding:12px 14px;border:1px solid #dce9f4;border-radius:12px;background:#fff}
.ir-file i{font-size:26px;color:#dc2626}
.ir-file div{min-width:0;flex:1}
.ir-file strong{display:block;color:#112958;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ir-file small{color:#6b7f95}
.ir-file a{font-size:13px;font-weight:700;color:#0c7da7;white-space:nowrap}
@media (max-width:767.98px){.ir-search{grid-template-columns:1fr}.ir-card-top{grid-template-columns:1fr}.ir-verdict div{flex:1}}
.ir-tabs{display:inline-flex;gap:4px;padding:4px;border:1px solid #dce9f4;border-radius:12px;background:#fff}
.ir-tabs a{padding:9px 18px;border-radius:9px;font-weight:800;font-size:14px;color:#1f3f66;text-decoration:none}
.ir-tabs a:hover{background:#eef5fb}
.ir-tabs a.is-active{background:#112958;color:#fff}
.ir-print-head{display:none}
@media print{
 @page{margin:12mm}
 body *{visibility:hidden}
 .ir-printable,.ir-printable *{visibility:visible}
 .ir-printable{position:absolute;inset:0 auto auto 0;width:100%;border:0;box-shadow:none}
 .ir-print,.ir-no-print{display:none!important}
 .ir-print-head{display:block}
 *{-webkit-print-color-adjust:exact;print-color-adjust:exact}
}
</style>

<section class="container mt-4 mb-5">
    <div class="ir-shell">
        <div class="ir-head">
            <div>
                <span class="ir-kicker">Internal Result</span>
                <p>{{ $tab === 'class' ? 'Class-wise results published by the school.' : 'Choose the exam and enter your Student ID to see your result.' }}</p>
            </div>
            <nav class="ir-tabs" aria-label="Result views">
                <a href="{{ route('internalResult') }}" class="{{ $tab === 'my' ? 'is-active' : '' }}" @if($tab === 'my') aria-current="page" @endif>My Result</a>
                <a href="{{ route('internalResult', ['tab' => 'class']) }}" class="{{ $tab === 'class' ? 'is-active' : '' }}" @if($tab === 'class') aria-current="page" @endif>Class Results</a>
            </nav>
        </div>

        @if($serviceDown)
            <div class="ir-alert is-info" role="status">Online results are not available right now. Please try again later.</div>
        @elseif(empty($exams))
            <div class="ir-alert is-info" role="status">No exam results have been published on the website yet.</div>
        @elseif($tab === 'class')
            @include('frontend.result.partials._class_results')
        @else
            <form method="GET" action="{{ route('internalResult') }}" class="ir-search" aria-label="Search result">
                <div>
                    <label for="ir-exam">Exam</label>
                    <select id="ir-exam" name="exam" required>
                        <option value="">Select exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam['id'] }}" @selected((string) $examId === (string) $exam['id'])>{{ $exam['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ir-student">Student ID</label>
                    <input id="ir-student" type="text" name="student_id" value="{{ $studentId }}" maxlength="40" inputmode="numeric" autocomplete="off" placeholder="e.g. 26000001" required>
                </div>
                <button type="submit">View Result</button>
            </form>
            <p class="ir-note">Your Student ID is printed on your ID card and admit card.</p>
        @endif

        @if($tab === 'my')
        @if($searchError)
            <div class="ir-alert is-warn" role="alert">{{ $searchError }}</div>
        @elseif($notFound)
            <div class="ir-alert is-warn" role="alert">No published result was found for this Student ID and exam. Please check the Student ID and the exam you selected.</div>
        @endif

        @if($result)
            @php $status = $result['result']['status'] ?? ''; @endphp
            <article class="ir-card" aria-label="Result of {{ $result['student']['name'] }}">
                <div class="ir-card-top">
                    <div>
                        <h3>{{ $result['student']['name'] }}</h3>
                        <p>{{ $result['exam']['name'] }} · {{ $result['student']['class'] }} · Session {{ $result['student']['session'] }}</p>
                    </div>
                    <div class="ir-verdict">
                        <div><span>GPA</span><strong>{{ $result['result']['gpa'] }}</strong></div>
                        <div><span>Grade</span><strong>{{ $result['result']['grade'] }}</strong></div>
                        <div class="{{ $status === 'Pass' ? 'is-pass' : ($status === 'Fail' ? 'is-fail' : '') }}"><span>Result</span><strong>{{ $status ?: '—' }}</strong></div>
                    </div>
                </div>
                <div class="ir-info">
                    <div><span>Student ID</span><strong>{{ $result['student']['studentId'] }}</strong></div>
                    <div><span>Roll</span><strong>{{ $result['student']['roll'] ?: '—' }}</strong></div>
                    <div><span>Class</span><strong>{{ $result['student']['class'] ?: '—' }}</strong></div>
                    <div><span>Section</span><strong>{{ $result['student']['section'] !== '-' ? $result['student']['section'] : '—' }}</strong></div>
                    <div><span>Group</span><strong>{{ $result['student']['group'] !== '-' ? $result['student']['group'] : '—' }}</strong></div>
                    <div><span>Total Marks</span><strong>{{ $result['result']['totalMarks'] ?? '—' }}</strong></div>
                </div>
                @foreach([['Main Subjects', $result['transcript']['mainRows'] ?? [], null], ['Optional / Fourth Subject', $result['transcript']['optionalRows'] ?? [], '(4th)']] as [$sectionTitle, $sectionRows, $sectionLabel])
                    @if(count($sectionRows))
                        <div class="ir-section-title">{{ $sectionTitle }}</div>
                        <div class="ir-table-wrap">
                            <table class="ir-table ir-transcript">
                                <thead><tr><th scope="col">Subject</th><th scope="col">CQ</th><th scope="col">MCQ</th><th scope="col">PR</th><th scope="col">Total</th><th scope="col">Grade / GP</th></tr></thead>
                                <tbody>@include('frontend.result.partials._transcript_rows', ['rows' => $sectionRows, 'optionalLabel' => $sectionLabel])</tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
                <div class="ir-foot">
                    <span>@if(!empty($result['result']['failedSubjects']))<span class="ir-failed">Failed in: {{ implode(', ', $result['result']['failedSubjects']) }}</span>@else This is an online copy of the published result. @endif</span>
                    <button type="button" class="ir-print" onclick="irPrintResultSheet()">Print Result Sheet</button>
                </div>
            </article>
            @include('frontend.result.partials._result_sheet')
        @endif

        @endif

        @if($tab === 'my' && isset($archive) && $archive->count() > 0)
            <div class="ir-archive">
                <h3>Earlier result files</h3>
                <div class="ir-archive-grid">
                    @foreach($archive as $file)
                        @php
                            $fileClass = !empty($file->assignClass) ? \App\Models\classManage::find($file->assignClass) : null;
                            $fileDate = $file->published_at ?? $file->created_at ?? null;
                        @endphp
                        <div class="ir-file">
                            <i class="fa {{ preg_match('/\.pdf$/i', $file->attachment) ? 'fa-file-pdf' : 'fa-file-image' }}" aria-hidden="true"></i>
                            <div>
                                <strong title="{{ $file->title ?? 'Result' }}">{{ $file->title ?? 'Result' }}</strong>
                                <small>{{ $fileClass->className ?? '' }}{{ $fileClass && $fileDate ? ' · ' : '' }}{{ $fileDate ? \Carbon\Carbon::parse($fileDate)->format('d M Y') : '' }}</small>
                            </div>
                            <a href="{{ asset('upload/image/cultivation/internalResult/'.$file->attachment) }}" target="_blank" rel="noopener">Open</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
