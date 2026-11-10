{{-- "All Class" exam routine sheet styles. Web/print use the locked palette as CSS variables; the PDF (DomPDF has no
     var() support) gets the same values as literals. $fit (print/PDF only) scales every size so the sheet fits one page. --}}
@php
    $acrPdf = !empty($isPdf);
    $acrPal = ['navy' => '#1B3A5C', 'navy-dark' => '#12263D', 'gold' => '#92400E', 'gold-dark' => '#6B2F0A', 'cream-bg' => '#F7F5EF',
        'cream-bg2' => '#FAF8F2', 'border' => '#E7E1D4', 'border2' => '#ECE4D1', 'rule' => '#D9D2C0', 'text' => '#262A30',
        'text2' => '#5C564A', 'text3' => '#8A8276', 'navy-tint' => '#E7EDF2', 'gold-tint' => '#FDF0D5'];
    $c = fn ($k) => $acrPdf ? $acrPal[$k] : 'var(--'.$k.')';
    $z = array_merge(['font' => 12.5, 'head_font' => 13.5, 'sub_font' => 11, 'cell_pad_y' => 7, 'cell_pad_x' => 8, 'head_pad' => 8,
        'school_font' => 22, 'addr_font' => 12, 'title_font' => 14, 'note_font' => 11, 'sign_font' => 11.5, 'gap' => 14, 'sign_gap' => 34], $fit ?? []);
    $acrOrientation = $orientation ?? 'landscape';
@endphp
<style>
@if(!$acrPdf)
:root{@foreach($acrPal as $k => $hex)--{{ $k }}:{{ $hex }};@endforeach}
@endif
.acr-page{color:{{ $c('text') }};font-family:Inter,system-ui,Arial,sans-serif}
.acr-sheet{background:#fff}
.p-header{text-align:center;margin-bottom:{{ $z['gap'] }}px}
.p-school{font-family:'Source Serif 4',Georgia,serif;font-size:{{ $z['school_font'] }}px;font-weight:700;color:{{ $c('navy-dark') }};line-height:1.25}
.p-addr{font-size:{{ $z['addr_font'] }}px;color:{{ $c('text2') }};margin-top:2px}
.p-examtitle{display:inline-block;margin-top:{{ $z['gap'] }}px;padding:{{ round($z['gap'] * .5, 2) }}px 30px;border-top:1.5px solid {{ $c('navy') }};border-bottom:1.5px solid {{ $c('navy') }};font-family:'Source Serif 4',Georgia,serif;font-size:{{ $z['title_font'] }}px;font-weight:700;letter-spacing:.03em;color:{{ $c('navy') }}}
table.routine{width:100%;border-collapse:collapse;background:#fff}
table.routine th,table.routine td{border:1px solid {{ $c('rule') }}}
table.routine thead th{font-weight:700;text-align:center;vertical-align:middle;padding:{{ $z['head_pad'] }}px {{ round($z['head_pad'] * .75, 2) }}px}
table.routine thead tr.grp th{font-family:'Source Serif 4',Georgia,serif;font-size:{{ $z['head_font'] }}px;letter-spacing:.01em;line-height:1.5;background:{{ $c('cream-bg') }};color:{{ $c('navy-dark') }};font-weight:700}
table.routine thead tr.grp th .grp-time{display:block;font-family:Inter,system-ui,Arial,sans-serif;font-size:{{ $z['sub_font'] }}px;font-weight:500;letter-spacing:.02em;margin-top:2px}
table.routine thead tr.grp th.date-head{background:{{ $c('cream-bg2') }};color:{{ $c('navy-dark') }};font-weight:700}
table.routine thead tr.sub th{font-size:{{ $z['sub_font'] + 1 }}px;font-weight:700;background:#fff;color:{{ $c('text2') }}}
table.routine tbody td{padding:{{ $z['cell_pad_y'] }}px {{ $z['cell_pad_x'] }}px;text-align:center;font-size:{{ $z['font'] }}px;vertical-align:middle}
table.routine tbody td.date-cell{white-space:nowrap;font-weight:700;background:{{ $c('navy-dark') }};color:#fff}
table.routine tbody td.date-cell .day{display:block;font-weight:500;font-size:{{ $z['sub_font'] }}px}
table.routine tbody td.dash{color:{{ $c('text3') }}}
table.routine tbody tr:nth-child(even) td:not(.date-cell){background:{{ $c('cream-bg2') }}}
table.routine tr{page-break-inside:avoid}
.p-footer{margin-top:{{ $z['gap'] }}px;border-top:1px dashed {{ $c('border') }};padding-top:{{ round($z['gap'] * .8, 2) }}px;font-size:{{ $z['note_font'] }}px;color:{{ $c('text2') }}}
.p-footer b{display:block;margin-bottom:{{ round($z['gap'] * .5, 2) }}px;font-size:{{ $z['note_font'] + .5 }}px;color:{{ $c('navy-dark') }}}
table.p-notice{width:100%;border-collapse:collapse;border:0}
table.p-notice td{border:0;vertical-align:top;width:50%;padding:0 12px 0 0}
.p-footer ul{margin:0;padding:0;list-style:none}
.p-footer li{position:relative;padding-left:14px;margin-bottom:{{ round($z['gap'] * .4, 2) }}px;line-height:1.45}
.p-footer li:before{content:'\2022';position:absolute;left:0;color:{{ $c('gold-dark') }};font-weight:700}
table.p-sign{width:100%;margin-top:{{ $z['sign_gap'] }}px;font-size:{{ $z['sign_font'] }}px;color:{{ $c('text2') }};border:0;border-collapse:collapse}
table.p-sign td{border:0;text-align:center;width:33%}
.p-sign .line{width:150px;border-top:1px solid {{ $c('text3') }};margin:0 auto 5px}
@media print{@page{size:A4 {{ $acrOrientation }};margin:8mm}.acr-noprint{display:none!important}*{-webkit-print-color-adjust:exact;print-color-adjust:exact}.acr-sheet{border:0!important;padding:0!important}}
</style>
