@php
    $acCount = count($allClassSessions);
    $acDateAfter = intdiv($acCount, 2);
    $acNotices = [
        'Students must be present at the exam hall 15 minutes before the exam starts.',
        'Mobile phones, smart watches, or any other electronic device are not allowed.',
        'Progress reports must be submitted by the specified date.',
    ];
    $acNoticeCols = array_chunk($acNotices, (int) ceil(count($acNotices) / 2));
@endphp
<div class="p-header">
    @if(!empty($institutionName))<div class="p-school">{{ $institutionName }}</div>@endif
    @if(!empty($institutionAddress))<div class="p-addr">{{ $institutionAddress }}</div>@endif
    <div class="p-examtitle">{{ $examTitle }}@if(!empty($sessionLabel)) {{ $sessionLabel }}@endif — Exam Routine</div>
</div>
@if($acCount === 0)
    <p style="text-align:center">No routine has been published for this exam yet.</p>
@else
<table class="routine">
    <thead>
        <tr class="grp">
            @foreach($allClassSessions as $i => $s)
                @if($i === $acDateAfter)<th class="date-head" rowspan="2">Date</th>@endif
                <th class="{{ $i % 2 === 0 ? 'ses-a' : 'ses-b' }}" colspan="{{ count($s['class_ids']) }}">{{ $s['label'] }}<span class="grp-time">{{ \Carbon\Carbon::parse($s['start_time'])->format('h:i A') }} – {{ \Carbon\Carbon::parse($s['end_time'])->format('h:i A') }}</span></th>
            @endforeach
        </tr>
        <tr class="sub">
            @foreach($allClassSessions as $i => $s)@foreach($s['class_ids'] as $cid)<th class="{{ $i % 2 === 0 ? 'ses-a' : 'ses-b' }}">{{ $classes->firstWhere('id', $cid)->className }}</th>@endforeach @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($allClassDates as $date)
            <tr>
                @foreach($allClassSessions as $i => $s)
                    @if($i === $acDateAfter)<td class="date-cell">{{ \Carbon\Carbon::parse($date)->format('j M Y') }}<span class="day">{{ \Carbon\Carbon::parse($date)->format('l') }}</span></td>@endif
                    @foreach($s['class_ids'] as $cid)@php($subject = implode(' / ', $allClassGrid[$date][$cid] ?? []))<td class="{{ $subject !== '' ? '' : 'dash' }}">{{ $subject !== '' ? $subject : '---' }}</td>@endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
@endif
<div class="p-footer">
    <b>Notice:</b>
    <table class="p-notice"><tr>
        @foreach($acNoticeCols as $col)<td><ul>@foreach($col as $note)<li>{{ $note }}</li>@endforeach</ul></td>@endforeach
    </tr></table>
</div>
<table class="p-sign"><tr>
    <td><div class="line"></div>Class Teacher</td>
    <td><div class="line"></div>Exam Controller</td>
    <td><div class="line"></div>Head Teacher</td>
</tr></table>
