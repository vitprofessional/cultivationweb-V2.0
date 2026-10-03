@extends($frontendLayout ?? config('frontend.layout'))

@section('fronttitle', 'Class Routine')

@section('frontcontent')
<style>
    .public-routine-wrap{max-width:1440px;margin:28px auto;padding:0 16px;color:#20354d}
    .public-routine-card{background:#fff;border:1px solid #dbe5ef;border-radius:16px;box-shadow:0 12px 32px rgba(19,48,79,.08);overflow:hidden}
    .routine-toolbar{display:flex;justify-content:flex-end;gap:8px;padding:14px 18px;border-bottom:1px solid #e4ebf2;background:#f8fafc}
    .routine-toolbar button,.routine-toolbar a{border:0;border-radius:8px;padding:9px 14px;font-weight:700;text-decoration:none;cursor:pointer}
    .routine-toolbar .routine-back{background:#e9eff5;color:#29435d}.routine-toolbar .routine-print{background:#143b63;color:#fff}.routine-toolbar .routine-pdf{background:#e9f4f1;color:#146754}
    .public-routine-content{padding:20px}
    .public-routine-heading{text-align:center;margin:0 0 18px;padding:16px 12px 18px;border-bottom:2px solid #183f68}
    .public-routine-institution{margin:0 0 4px;color:#315b80;font-size:14px;font-weight:800;letter-spacing:.04em}
    .public-routine-heading h1{margin:0;color:#142f4c;font-size:28px;font-weight:850}
    .public-routine-subtitle{margin:5px 0 14px;color:#5b7084}
    .public-routine-context{display:flex;justify-content:center;flex-wrap:wrap;gap:8px}
    .public-routine-context span{display:flex;gap:5px;padding:6px 10px;border:1px solid #dce6ef;border-radius:7px;background:#f7fafc;font-size:12px}
    .public-routine-context b{color:#315b80}
    .routine-desktop-table{overflow-x:auto;border:1px solid #cdd9e5;border-radius:10px}
    .public-routine-table{width:100%;min-width:900px;border-collapse:collapse;table-layout:fixed}
    .public-routine-table th,.public-routine-table td{border:1px solid #cdd9e5;padding:8px;vertical-align:top;text-align:left}
    .public-routine-table thead th{background:#183f68;color:#fff;text-align:center;font-size:13px}
    .public-routine-table thead th:first-child{width:132px}
    .public-routine-table tbody tr:nth-child(even){background:#f8fafc}
    .public-routine-table .routine-time{background:#eef3f8;color:#25415c;text-align:center;vertical-align:middle;font-size:12px;white-space:nowrap}
    .routine-cell{display:flex;flex-direction:column;gap:4px;min-height:60px;padding:6px;border-radius:6px;background:#fff}
    .routine-cell span,.routine-mobile-period span{display:flex;gap:5px;color:#596d80;font-size:11px;line-height:1.35}
    .routine-cell span b,.routine-mobile-period span b{min-width:48px;color:#74879a;font-weight:700}
    .routine-subject{color:#173d63;font-size:13px;line-height:1.35;overflow-wrap:anywhere}
    .routine-activity{background:#fff8e8;border-left:3px solid #d5a43b}
    .routine-activity .routine-subject{color:#755413}
    .routine-empty{background:#fbfcfd}
    .routine-no-rows{padding:18px;text-align:center;color:#687b8e}
    .routine-mobile-days{display:none}
    @media(max-width:767.98px){.public-routine-wrap{margin:14px auto;padding:0 10px}.public-routine-content{padding:12px}.routine-toolbar{justify-content:stretch;padding:10px;flex-wrap:wrap}.routine-toolbar>*{flex:1;text-align:center}.public-routine-heading{padding:12px 4px}.public-routine-heading h1{font-size:23px}.public-routine-context{display:grid;grid-template-columns:1fr 1fr;text-align:left}.public-routine-context span{display:block;min-width:0;overflow-wrap:anywhere}.public-routine-context b{display:block;margin-bottom:2px}.routine-desktop-table{display:none}.routine-mobile-days{display:grid;gap:12px}.routine-day-card{min-width:0;padding:12px;border:1px solid #dbe5ef;border-radius:10px;background:#f8fafc}.routine-day-card h2{margin:0 0 9px;color:#173d63;font-size:16px}.routine-mobile-period{display:flex;flex-direction:column;gap:4px;margin-top:8px;padding:10px;border:1px solid #e1e8ef;border-radius:8px;background:#fff}.routine-mobile-time{margin:0;color:#65798d;font-size:11px;font-weight:700}.routine-mobile-period .routine-subject{font-size:14px}.routine-mobile-period span b{min-width:62px}}
    @page{size:A4 landscape;margin:6mm}
    @media print{html,body,#wrapper,.dashboard-page-one,.dashboard-content-one{height:auto!important;min-height:0!important;max-height:none!important;overflow:visible!important;background:#fff!important}.rs-header,.rs-footer,.header-menu-one,.sidebar-main,.breadcrumbs-area,.footer-wrap-layout1,.no-print,#preloader,#loader{display:none!important}.dashboard-content-one{margin:0!important;padding:0!important}.public-routine-wrap{max-width:none;margin:0;padding:0}.public-routine-card{border:0;border-radius:0;box-shadow:none}.routine-toolbar{display:none!important}.public-routine-content{padding:0}.public-routine-heading{padding:0 0 8px;margin-bottom:8px}.public-routine-heading h1{font-size:20px}.public-routine-subtitle{margin:2px 0 6px}.public-routine-context{gap:4px}.public-routine-context span{padding:3px 6px;font-size:9px}.routine-desktop-table{display:block;overflow:visible;border-radius:0}.public-routine-table{min-width:0;width:100%;font-size:9px}.public-routine-table th,.public-routine-table td{padding:4px;border-color:#687787!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}.public-routine-table thead th{font-size:10px;background:#183f68!important;color:#fff!important}.public-routine-table thead th:first-child{width:100px}.routine-cell{min-height:0;gap:2px;padding:2px}.routine-subject{font-size:9px}.routine-cell span{font-size:8px}.routine-cell span b{min-width:35px}.routine-mobile-days{display:none!important}.public-routine-table tr{break-inside:avoid;page-break-inside:avoid}}
    @media print {
        html, body {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-sizing: border-box !important;
        }
        #wrapper, .dashboard-page-one, .dashboard-content-one, .edu-content-wrap,
        .container, .edu-main-card, .edu-main-inner, .row, .public-routine-wrap,
        .public-routine-card, .public-routine-content, .routine-desktop-table {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-sizing: border-box !important;
        }
        .public-routine-table {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            table-layout: fixed !important;
        }
        .public-routine-table thead th:first-child { width: 88px !important; }
        .routine-cell, .routine-cell span, .routine-subject {
            min-width: 0 !important;
            overflow-wrap: anywhere !important;
        }
        .routine-cell span b { flex: 0 0 35px !important; }
        .edu-page-title { display: none !important; }
        .edu-main-card {
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .edu-content-wrap, .edu-main-inner { padding-top: 0 !important; padding-bottom: 0 !important; }
        .public-routine-context {
            display: table !important;
            width: 100% !important;
            table-layout: fixed !important;
            border-spacing: 5px 0 !important;
            margin: 3px 0 0 !important;
        }
        .public-routine-context span {
            display: table-cell !important;
            width: 25% !important;
            vertical-align: middle !important;
            padding: 5px 8px !important;
            border: 1px solid #cdd9e5 !important;
            border-radius: 4px !important;
            background: #f3f7fb !important;
            font-size: 9px !important;
            line-height: 1.25 !important;
            text-align: left !important;
        }
        .public-routine-context b {
            display: block !important;
            margin-bottom: 2px !important;
            color: #315b80 !important;
            font-size: 8px !important;
            letter-spacing: .02em !important;
            text-transform: uppercase !important;
        }
    }
</style>

<main class="public-routine-wrap">
    <div class="public-routine-card">
        <nav class="routine-toolbar no-print" aria-label="Routine actions">
            <a class="routine-back" href="{{ route('newClassSchedule') }}">Back to routines</a>
            <a class="routine-pdf" href="{{ route('classRoutine.download', ['id' => $routine->id]) }}">Download PDF</a>
            <button class="routine-print" type="button" onclick="window.print()">Print</button>
        </nav>
        <div class="public-routine-content">
            @include('frontend.academic.partials._classRoutineGrid')
        </div>
    </div>
</main>
@endsection
