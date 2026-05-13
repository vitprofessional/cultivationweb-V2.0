@php
    $itemClass = \App\Models\classManage::find($routine->assignClass);
    $itemSection = \App\Models\sectionManage::find($routine->assignSection);
    $itemDepartment = \App\Models\Department::find($routine->assignDepartment);
    $itemSession = \App\Models\sessionManage::find($routine->assignSession);

    $dayHeaders = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
    $slotMap = [];
    $cellMap = [];

    foreach (($entries ?? collect()) as $entry) {
        $dayName = ucfirst(strtolower((string)($entry->class_day ?? '')));
        $start = (string)($entry->start_time ?? '');
        $end = (string)($entry->end_time ?? '');
        $subject = trim((string)($entry->subject_name ?? ''));

        if ($dayName === '' || $start === '' || $end === '') {
            continue;
        }

        if (!in_array($dayName, $dayHeaders, true)) {
            continue;
        }

        $slotKey = $start.'|'.$end;
        if (!isset($slotMap[$slotKey])) {
            $slotMap[$slotKey] = [
                'key' => $slotKey,
                'start' => $start,
                'end' => $end,
                'label' => date('H:i', strtotime($start)).' - '.date('H:i', strtotime($end)),
            ];
        }

        if (!isset($cellMap[$slotKey])) {
            $cellMap[$slotKey] = [];
        }

        $cellMap[$slotKey][$dayName] = $subject;
    }

    $slots = collect(array_values($slotMap))->sortBy('start')->values();
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Routine</title>
    <style>
        @page { size: A4 landscape; margin: 6mm; }
        .routine-view-wrap { max-width: 1200px; margin: 0 auto; }

        .schedule-shell { background: #fff; border-radius: 10px; border: 1px solid #dce9f4; padding: 18px; }

        .schedule-title { margin: 0; font-size: 30px; line-height: 1; font-weight: 900; letter-spacing: 1px; color: #2f4e75; text-transform: uppercase; }

        .schedule-meta { margin-top: 8px; margin-bottom: 14px; color: #4f6783; font-size: 13px; font-weight: 600; }

        .schedule-meta span { margin-right: 16px; }

        .schedule-table { width: 100%; border-collapse: collapse; table-layout: fixed; background: #f7fbff; }

        .schedule-table th, .schedule-table td { border: 1px solid #cfe0ef; text-align: center; vertical-align: middle; padding: 10px 8px; color: #425b76; }

        .schedule-table thead th { font-size: 20px; font-weight: 800; letter-spacing: .5px; text-transform: uppercase; }

        .slot-head { background: #dff4fb; color: #08688e; width: 18%; }
        .day-sunday { background: #e8f6ff; }
        .day-monday { background: #edf8ff; }
        .day-tuesday { background: #f2f9ff; }
        .day-wednesday { background: #f5fbff; }
        .day-thursday { background: #f8fcff; }

        .slot-cell { font-size: 18px; font-weight: 700; width: 18%; background: #eef5fb; }

        .subject-cell { background: #fff; height: auto; font-size: 12px; font-weight: 700; color: #425b76; }

        .subject-empty { color: #a3a8ae; font-weight: 400; }

        .subject-break { background: #fff3cd; color: #6a4b00; font-weight: 800; }

        @media print {
            html, body, #wrapper, .dashboard-page-one, .dashboard-content-one, .routine-view-wrap { height: auto !important; min-height: 0 !important; max-height: none !important; overflow: visible !important; }
            #preloader, .header-menu-one, .sidebar-main, .breadcrumbs-area, .footer-wrap-layout1, .no-print, .card-header { display: none !important; }
            .dashboard-content-one { margin-left: 0 !important; padding: 0 !important; background: #fff !important; }
            .row, .col-12, .card, .card-body, .cultivation { margin: 0 !important; padding: 0 !important; }
            .mb-4, .mb-3, .gutters-20 { margin-bottom: 0 !important; }
            .card, .card-body { border: 0 !important; box-shadow: none !important; padding: 0 !important; background: #fff !important; }
            .table-responsive { overflow: visible !important; border: 0 !important; }
            .schedule-table { width: 100% !important; page-break-inside: auto; }
            .schedule-table tr { page-break-inside: avoid; page-break-after: auto; }
            .schedule-table thead th { font-size: 13px; }
            .schedule-shell { border: 0 !important; border-radius: 0 !important; box-shadow: none !important; background: #fff !important; padding: 0 !important; }
            .schedule-title { font-size: 16px; margin-bottom: 2px !important; }
            .schedule-meta { margin: 2px 0 6px 0 !important; font-size: 10px !important; }
            .slot-cell { font-size: 11px !important; }
            .subject-cell { font-size: 10px; line-height: 1.2; padding: 4px 3px !important; }
            .schedule-table th, .schedule-table td { border-color: #666b72 !important; padding: 5px 4px !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="routine-view-wrap">
    <div class="schedule-shell">
        <h3 class="schedule-title">CLASS SCHEDULE</h3>
        <div class="schedule-meta">
            <span>Class: {{ $itemClass->className ?? '-' }}</span>
            <span>Session: {{ $itemSession->session ?? '-' }}</span>
            <span>Section: {{ $itemSection->section ?? 'All' }}</span>
            <span>Department: {{ $itemDepartment->departmentName ?? 'All' }}</span>
            <span style="float:right;font-size:12px;font-weight:600">Printed on: {{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</span>
        </div>

        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th class="slot-head">TIME/Days</th>
                        <th class="day-sunday">SUNDAY</th>
                        <th class="day-monday">MONDAY</th>
                        <th class="day-tuesday">TUESDAY</th>
                        <th class="day-wednesday">WEDNESDAY</th>
                        <th class="day-thursday">THURSDAY</th>
                    </tr>
                </thead>
                <tbody>
                    @if($slots->count() > 0)
                        @foreach($slots as $slot)
                            <tr>
                                <td class="slot-cell">{{ $slot['label'] }}</td>
                                @foreach($dayHeaders as $dayLabel)
                                    @php
                                        $subjectText = $cellMap[$slot['key']][$dayLabel] ?? '';
                                        $isBreakCell = strtolower(trim((string)$subjectText)) === 'break/tiffin time';
                                    @endphp
                                    <td class="subject-cell {{ $subjectText === '' ? 'subject-empty' : '' }} {{ $isBreakCell ? 'subject-break' : '' }}">{{ $subjectText !== '' ? $subjectText : '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="subject-cell subject-empty">No routine rows found for Sunday to Thursday.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(!empty($printMode) && $printMode)
    <script>
        (function(){
            // Wait for load, fonts and images, then trigger print
            window.addEventListener('load', async function(){
                try {
                    if (document.fonts && document.fonts.ready) {
                        await Promise.race([document.fonts.ready, new Promise(r => setTimeout(r, 1500))]);
                    }
                } catch (e) { }

                // Wait for images to settle (max 1.5s per image)
                try {
                    const imgs = Array.from(document.images || []);
                    await Promise.all(imgs.map(img => new Promise(resolve => {
                        if (img.complete) return resolve();
                        let done = false;
                        img.addEventListener('load', ()=> { if(!done){ done=true; resolve(); } }, { once: true });
                        img.addEventListener('error', ()=> { if(!done){ done=true; resolve(); } }, { once: true });
                        setTimeout(()=> { if(!done){ done=true; resolve(); } }, 1500);
                    })));
                } catch (e) { }

                // small final delay for layout
                setTimeout(function(){ window.print(); }, 300);
            });
        })();
    </script>
@endif
</body>
</html>