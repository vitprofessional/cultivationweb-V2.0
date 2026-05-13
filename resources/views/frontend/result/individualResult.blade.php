@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Individual Result
@endsection
@section('frontcontent')
<style>
    /* ── Professional wrapper ──────────────────────────────────── */
    .result-shell {
        background: linear-gradient(180deg, #f8fbff 0%, #f2f8fd 100%);
        border: 1px solid #dce9f4;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 12px 28px rgba(19, 54, 102, 0.08);
    }

    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #dce9f4;
    }

    .result-header .kicker {
        display: inline-flex;
        font-size: 11px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #0c7da7;
        font-weight: 800;
        padding: 5px 10px;
        border-radius: 999px;
        border: 1px solid #bce7f4;
        background: #dff4fb;
    }

    .result-header h2 {
        margin: 6px 0 0;
        font-size: 22px;
        color: #112958;
        font-weight: 800;
    }

    .result-header p {
        margin: 6px 0 0;
        color: #5a708a;
        font-size: 14px;
    }

    .result-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e7f9fb;
        color: #1285af;
        border: 1px solid #b8e8f5;
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    /* ── Table wrapper ─────────────────────────────────────────── */
    .result-table-wrap {
        background: #fff;
        border: 1px solid #dce9f4;
        border-radius: 14px;
        padding: 16px;
        overflow: hidden;
    }

    .result-table-wrap .dataTables_wrapper {
        margin-top: 2px;
    }

    .result-table-wrap .dataTables_length,
    .result-table-wrap .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .result-table-wrap .dataTables_length label,
    .result-table-wrap .dataTables_filter label {
        margin: 0;
        color: #415a76;
        font-size: 13px;
        font-weight: 700;
    }

    .result-table-wrap .dataTables_length select {
        min-width: 78px;
        height: 38px;
        border: 1px solid #cfe0ef;
        border-radius: 8px;
        background: #fff;
        color: #3f5772;
        padding: 0 10px;
        box-shadow: none;
    }

    .result-table-wrap .dataTables_filter input {
        min-width: 220px;
        height: 38px;
        border: 1px solid #cfe0ef;
        border-radius: 8px;
        background: #fff;
        color: #3f5772;
        padding: 0 12px;
        box-shadow: none;
    }

    .result-table-wrap .dataTables_filter input:focus,
    .result-table-wrap .dataTables_length select:focus {
        border-color: #1b7096;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(27, 112, 150, .12);
    }

    .result-table-wrap .dataTables_info {
        padding-top: 12px;
        color: #5a708a;
        font-size: 13px;
        font-weight: 600;
    }

    .result-table-wrap .dataTables_paginate {
        padding-top: 10px;
    }

    .result-table-wrap .dataTables_paginate .paginate_button {
        border: 1px solid #cfddec !important;
        border-radius: 8px !important;
        background: #fff !important;
        color: #31506f !important;
        margin: 0 2px !important;
        padding: 6px 12px !important;
        transition: all .2s ease;
    }

    .result-table-wrap .dataTables_paginate .paginate_button:hover {
        background: #eef5fb !important;
        color: #173652 !important;
        border-color: #b7cde0 !important;
    }

    .result-table-wrap .dataTables_paginate .paginate_button.current,
    .result-table-wrap .dataTables_paginate .paginate_button.current:hover {
        background: #1b7096 !important;
        color: #fff !important;
        border-color: #1b7096 !important;
    }

    /* ── Table styling ─────────────────────────────────────────── */
    .result-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 12px;
        table-layout: fixed;
    }

    .result-table thead th {
        border-bottom: 1px solid #d7e6f3;
        color: #1f3f66;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 800;
        background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%);
        padding: 14px 16px;
    }

    .result-table tbody td {
        vertical-align: middle;
        color: #3f5772;
        font-size: 14px;
        padding: 16px;
        line-height: 1.6;
        border-color: #e0ebf4;
    }

    .result-table tbody tr:nth-child(even) td {
        background: #fafcff;
    }

    .result-table tbody tr:hover td {
        background: #f4faff;
    }

    .result-table th:first-child,
    .result-table td:first-child {
        width: 28%;
    }

    .result-table th:nth-child(2),
    .result-table td:nth-child(2) {
        width: auto;
    }

    .result-table th:last-child,
    .result-table td:last-child {
        width: 90px;
        text-align: center;
        white-space: nowrap;
    }

    /* ── Metadata grid ─────────────────────────────────────────── */
    .result-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px 12px;
        width: 100%;
    }

    .result-meta-item {
        background: #fff;
        border: 1px solid #e8f0f7;
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        box-shadow: inset 0 1px 3px rgba(27, 112, 150, 0.04);
    }

    .result-meta-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #0c7da7;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .result-meta-label i {
        font-size: 12px;
        display: inline-block;
        min-width: 12px;
    }

    .result-meta-value {
        font-size: 13px;
        font-weight: 600;
        color: #1f3f66;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── View button ───────────────────────────────────────────── */
    .result-view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid rgba(27, 112, 150, 0.2);
        background: rgba(27, 112, 150, 0.08);
        color: #1b7096;
        transition: all .2s ease;
        text-decoration: none;
    }

    .result-view-btn:hover {
        background: rgba(27, 112, 150, 0.15);
        border-color: rgba(27, 112, 150, 0.3);
        color: #0f4968;
    }

    /* ── Empty state ───────────────────────────────────────────── */
    .result-empty {
        border: 1px dashed #cfe3ec;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        color: #6d7d8b;
        background: #f8fbfc;
    }

    @media (max-width: 575.98px) {
        .result-shell {
            padding: 12px;
        }
        .result-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .result-header h2 {
            font-size: 18px;
        }
        .result-table th:first-child,
        .result-table td:first-child,
        .result-table th:nth-child(2),
        .result-table td:nth-child(2),
        .result-table th:last-child,
        .result-table td:last-child {
            width: auto;
        }
    }
</style>

<section class="container mt-4">
    <div class="result-shell">
        <div class="result-header">
            <div>
                <span class="kicker">Academic</span>
                <h2>Individual Result</h2>
                <p>Browse published individual exam results by semester, department, and session.</p>
            </div>
        </div>

        <div class="result-table-wrap table-responsive">
            <table id="myTable" class="result-table table table-hover align-middle display border" style="width:100%">
                <thead>
                    <tr>
                        <th>Result Title</th>
                        <th>Result Details</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span style="font-weight: 700; color: #1f3f66;">3rd Semester Results</span></td>
                        <td>
                            <div class="result-meta-grid">
                                <div class="result-meta-item result-meta-semester">
                                    <span class="result-meta-label"><i class="fa fa-book"></i> Semester</span>
                                    <span class="result-meta-value">3rd Semester</span>
                                </div>
                                <div class="result-meta-item result-meta-department">
                                    <span class="result-meta-label"><i class="fa fa-university"></i> Department</span>
                                    <span class="result-meta-value">Honours</span>
                                </div>
                                <div class="result-meta-item result-meta-session">
                                    <span class="result-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                    <span class="result-meta-value">2024-25</span>
                                </div>
                                <div class="result-meta-item result-meta-published">
                                    <span class="result-meta-label"><i class="fa fa-clock"></i> Published</span>
                                    <span class="result-meta-value">25 Jan 2025</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="result-view-btn" data-fancybox data-type="iframe" href="#" target="_blank" title="View Result">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection