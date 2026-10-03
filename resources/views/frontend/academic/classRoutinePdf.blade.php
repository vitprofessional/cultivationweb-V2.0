<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Routine</title>
    <style>
        @page{size:A4 landscape;margin:8mm}
        *{box-sizing:border-box}body{margin:0;color:#20354d;font-family:Arial,sans-serif;font-size:10px}
        .public-routine-heading{text-align:center;margin:0 0 8px;padding:0 0 8px;border-bottom:2px solid #183f68}
        .public-routine-institution{margin:0 0 3px;color:#315b80;font-size:12px;font-weight:bold}
        .public-routine-heading h1{margin:0;color:#142f4c;font-size:20px;font-weight:bold}
        .public-routine-subtitle{margin:2px 0 6px;color:#5b7084}
        .public-routine-context{display:table;width:100%;table-layout:fixed;border-spacing:5px 0;margin:3px 0 0}
        .public-routine-context span{display:table-cell;width:25%;vertical-align:middle;padding:5px 8px;border:1px solid #cdd9e5;border-radius:4px;background:#f3f7fb;font-size:9px;line-height:1.25;text-align:left}
        .public-routine-context b{display:block;margin-bottom:2px;color:#315b80;font-size:8px;letter-spacing:.02em;text-transform:uppercase}
        .routine-desktop-table{display:block;overflow:visible;border:1px solid #687787}
        .public-routine-table{width:100%;border-collapse:collapse;table-layout:fixed;font-size:9px}
        .public-routine-table th,.public-routine-table td{border:1px solid #687787;padding:4px;vertical-align:top;text-align:left}
        .public-routine-table thead th{background:#183f68;color:#fff;text-align:center;font-size:10px}
        .public-routine-table thead th:first-child{width:100px}
        .public-routine-table .routine-time{background:#eef3f8;text-align:center;vertical-align:middle;white-space:nowrap}
        .routine-cell{display:flex;flex-direction:column;gap:2px;min-height:0;padding:2px}
        .routine-cell span{display:flex;gap:4px;color:#596d80;font-size:8px;line-height:1.2}
        .routine-cell span b{min-width:35px;color:#74879a}
        .routine-subject{color:#173d63;font-size:9px;line-height:1.25;overflow-wrap:anywhere}
        .routine-activity{background:#fff8e8;border-left:2px solid #d5a43b}
        .routine-activity .routine-subject{color:#755413}
        .routine-mobile-days{display:none}
        .routine-no-rows{text-align:center;padding:15px}
        tr{page-break-inside:avoid;break-inside:avoid;-webkit-print-color-adjust:exact;print-color-adjust:exact}
    </style>
</head>
<body>
    @include('frontend.academic.partials._classRoutineGrid')
    @if(!empty($printMode) && $printMode)
        <script>window.addEventListener('load',function(){window.print();});</script>
    @endif
</body>
</html>
