<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $examTitle }} — All Classes Exam Routine</title>
    <style>@page{size:A4 {{ $orientation ?? 'landscape' }};margin:8mm}body{margin:0;font-family:DejaVu Sans,Arial,Helvetica,sans-serif}</style>
    @include('frontend.academic.partials._all_class_css')
</head>
<body>
    <div class="acr-page acr-sheet">
        @include('frontend.academic.partials._all_class_grid')
    </div>
    @if(!empty($autoPrint))
        {{-- Print route: open the browser print dialog as soon as the sheet has loaded. --}}
        <script>window.addEventListener('load', function () { window.print(); });</script>
    @endif
</body>
</html>
