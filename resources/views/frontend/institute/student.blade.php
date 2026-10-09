@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Student List
@endsection
@php
$config = App\Models\ServerConfig::first();
@endphp
@section('frontcontent')
<style>
    /* ── Professional wrapper ──────────────────────────────────── */
    .student-shell {
        background: linear-gradient(180deg, #f8fbff 0%, #f2f8fd 100%);
        border: 1px solid #dce9f4;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 12px 28px rgba(19, 54, 102, 0.08);
    }

    .student-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #dce9f4;
    }

    .student-header .kicker {
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

    .student-header h2 {
        margin: 6px 0 0;
        font-size: 22px;
        color: #112958;
        font-weight: 800;
    }

    .student-header p {
        margin: 6px 0 0;
        color: #5a708a;
        font-size: 14px;
    }

    .student-count {
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

    /* ── DataTable overrides ───────────────────────────────────── */
    .student-table-wrap {
        background: #fff;
        border: 1px solid #dce9f4;
        border-radius: 14px;
        padding: 16px;
        overflow: hidden;
    }

    .student-table-wrap .dataTables_wrapper {
        margin-top: 2px;
    }

    .student-table-wrap .dataTables_length,
    .student-table-wrap .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .student-table-wrap .dataTables_length label,
    .student-table-wrap .dataTables_filter label {
        margin: 0;
        color: #415a76;
        font-size: 13px;
        font-weight: 700;
    }

    .student-table-wrap .dataTables_length select {
        min-width: 78px;
        height: 38px;
        border: 1px solid #cfe0ef;
        border-radius: 8px;
        background: #fff;
        color: #3f5772;
        padding: 0 10px;
        box-shadow: none;
    }

    .student-table-wrap .dataTables_filter input {
        min-width: 220px;
        height: 38px;
        border: 1px solid #cfe0ef;
        border-radius: 8px;
        background: #fff;
        color: #3f5772;
        padding: 0 12px;
        box-shadow: none;
    }

    .student-table-wrap .dataTables_filter input:focus,
    .student-table-wrap .dataTables_length select:focus {
        border-color: #1b7096;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(27, 112, 150, .12);
    }

    .student-table-wrap .dataTables_info {
        padding-top: 12px;
        color: #5a708a;
        font-size: 13px;
        font-weight: 600;
    }

    .student-table-wrap .dataTables_paginate {
        padding-top: 10px;
    }

    .student-table-wrap .dataTables_paginate .paginate_button {
        border: 1px solid #cfddec !important;
        border-radius: 8px !important;
        background: #fff !important;
        color: #31506f !important;
        margin: 0 2px !important;
        padding: 6px 12px !important;
        transition: all .2s ease;
    }

    .student-table-wrap .dataTables_paginate .paginate_button:hover {
        background: #eef5fb !important;
        color: #173652 !important;
        border-color: #b7cde0 !important;
    }

    .student-table-wrap .dataTables_paginate .paginate_button.current,
    .student-table-wrap .dataTables_paginate .paginate_button.current:hover {
        background: #1b7096 !important;
        color: #fff !important;
        border-color: #1b7096 !important;
    }

    /* ── Table styling ─────────────────────────────────────────── */
    .student-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 12px;
        table-layout: fixed;
    }

    .student-table thead th {
        border-bottom: 1px solid #d7e6f3;
        color: #1f3f66;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 800;
        background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%);
        padding: 14px 16px;
    }

    .student-table tbody td {
        vertical-align: middle;
        color: #3f5772;
        font-size: 14px;
        padding: 16px;
        line-height: 1.6;
        border-color: #e0ebf4;
    }

    .student-table tbody tr:nth-child(even) td {
        background: #fafcff;
    }

    .student-table tbody tr:hover td {
        background: #f4faff;
    }

    .student-table th:first-child,
    .student-table td:first-child {
        width: 32%;
    }

    .student-table th:nth-child(2),
    .student-table td:nth-child(2) {
        width: auto;
    }

    .student-table th:last-child,
    .student-table td:last-child {
        width: 100px;
        text-align: center;
        white-space: nowrap;
    }

    /* ── Student info cell ─────────────────────────────────────── */
    .student-info-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        object-fit: cover;
        border: 1.5px solid #dce9f4;
        box-shadow: 0 2px 6px rgba(27, 112, 150, 0.1);
        flex-shrink: 0;
    }

    .student-name-id {
        flex: 1;
    }

    .student-name {
        font-weight: 700;
        color: #1f3f66;
        font-size: 13px;
        display: block;
    }

    .student-id {
        font-size: 12px;
        color: #6d7d8b;
        margin-top: 2px;
    }

    /* ── Metadata grid ─────────────────────────────────────────── */
    .student-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px 12px;
        width: 100%;
    }

    .student-meta-item {
        background: #fff;
        border: 1px solid #e8f0f7;
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        box-shadow: inset 0 1px 3px rgba(27, 112, 150, 0.04);
    }

    .student-meta-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #0c7da7;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .student-meta-label i {
        font-size: 12px;
        display: inline-block;
        min-width: 12px;
    }

    .student-meta-value {
        font-size: 13px;
        font-weight: 600;
        color: #1f3f66;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── View button ───────────────────────────────────────────── */
    .student-view-btn {
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

    .student-view-btn:hover {
        background: rgba(27, 112, 150, 0.15);
        border-color: rgba(27, 112, 150, 0.3);
        color: #0f4968;
    }

    /* ── Empty state ───────────────────────────────────────────── */
    .student-empty {
        border: 1px dashed #cfe3ec;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        color: #6d7d8b;
        background: #f8fbfc;
    }

    @media (max-width: 575.98px) {
        .student-shell {
            padding: 12px;
        }
        .student-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .student-header h2 {
            font-size: 18px;
        }
        .student-table th:first-child,
        .student-table td:first-child,
        .student-table th:nth-child(2),
        .student-table td:nth-child(2),
        .student-table th:last-child,
        .student-table td:last-child {
            width: auto;
        }
    }
</style>
<style>
body:has(#student-database-page) .edu-page-title{display:none}
body:has(#student-database-page) .edu-main-card{border:0;box-shadow:none;background:transparent}
body:has(#student-database-page) .edu-main-inner{padding:0}
#student-database-page{width:100%;max-width:1180px;margin:0 auto;padding:12px;min-width:0}
.student-database-hero{padding:30px 32px;border:1px solid #d9edf9;border-radius:17px;background:radial-gradient(ellipse at 90% 10%,#bce9fc,transparent 60%),linear-gradient(120deg,#f4fbff,#e0f2fc);margin-bottom:18px}
.student-database-hero nav{font-size:13px;color:#56718c;margin-bottom:14px}
.student-database-hero h1{font-size:36px;line-height:1.25;color:#123665;font-weight:800;margin:0 0 10px}
.student-database-hero p{font-size:16px;color:#53708b;margin:0}
.student-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}
.student-summary-item{display:flex;align-items:center;gap:14px;border:1px solid #deecf7;border-radius:13px;padding:18px;background:white;box-shadow:0 4px 16px #1c537008}
.student-summary-item i{display:grid;place-items:center;width:44px;height:44px;border-radius:12px;background:#e8f6ff;color:#087bb8;flex-shrink:0;font-size:20px}
.student-summary-item strong{display:block;color:#153965;font-size:24px;line-height:1.25}.student-summary-item span{font-size:13px;color:#637c92}
.student-filter-panel{background:white;border:1px solid #daeaf5;border-radius:15px;padding:22px;box-shadow:0 5px 18px #23526c08;margin-bottom:20px}
.student-filter-panel h2{font-size:19px;color:#173f67;margin:0 0 16px}
.student-filters{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;align-items:end}
.student-filters label{display:flex;flex-direction:column;gap:7px;font-size:13px;font-weight:700;color:#3c5874;min-width:0;margin:0}
.student-filters input,.student-filters select{width:100%;height:44px;border:1px solid #cedeeb;border-radius:8px;padding:0 12px;background:#fbfdff;color:#294f71;font-size:14px;min-width:0}
.student-filters input:focus-visible,.student-filters select:focus-visible,.student-reset:focus-visible{outline:3px solid #49bed9;outline-offset:2px}
.student-reset{height:44px;border-radius:8px;border:1px solid #c6deed;background:#edf7fd;color:#185f8a;font-weight:700}
.student-reset:hover{background:#175882;color:white}
#student-database-page .student-shell{background:white;border-radius:16px;padding:22px}
#student-database-page .student-header h2{font-size:23px}
#student-database-page .student-table-wrap{min-width:0;overflow-x:auto}
#student-database-page .student-table{min-width:660px}
#student-database-page .dataTables_paginate{display:flex;flex-wrap:wrap;gap:4px;justify-content:flex-end}
#student-database-page .dataTables_paginate span{display:flex;flex-wrap:wrap;gap:4px}
@media(max-width:991px){.student-summary{grid-template-columns:repeat(2,minmax(0,1fr))}.student-filters{grid-template-columns:repeat(2,minmax(0,1fr))}.student-database-hero{padding:26px}.student-database-hero h1{font-size:32px}}
@media(max-width:575px){#student-database-page{padding:0}.student-filters{grid-template-columns:1fr}.student-filter-panel,#student-database-page .student-shell{padding:16px}.student-database-hero{padding:22px 18px}.student-database-hero h1{font-size:28px}.student-summary{gap:10px}.student-summary-item{padding:12px;gap:9px}.student-summary-item i{width:34px;height:34px;font-size:16px}.student-summary-item strong{font-size:21px}.student-summary-item span{font-size:12px}}
</style>

@php
    $sessions    = \App\Models\sessionManage::all()->keyBy('id');
    $classes     = \App\Models\classManage::all()->keyBy('id');
    $sections    = \App\Models\sectionManage::all()->keyBy('id');
    $departments = \App\Models\Department::all()->keyBy('id');
    $studentFilters = [
        ['class', 'Class', 'className', $classes, 'className'],
        ['section', 'Section', 'sectionName', $sections, 'section'],
        ['session', 'Session', 'sessName', $sessions, 'session'],
        ['department', 'Department', 'departmentName', $departments, 'departmentName'],
    ];
    $studentMetrics = [
        ['Total Students', $Datakey->count(), 'fa-users'],
        ['Classes', $Datakey->pluck('className')->unique()->intersect($classes->keys())->count(), 'fa-book'],
        ['Sessions', $Datakey->pluck('sessName')->unique()->intersect($sessions->keys())->count(), 'fa-calendar'],
        ['Departments', $Datakey->pluck('departmentName')->unique()->intersect($departments->keys())->count(), 'fa-university'],
    ];
@endphp

<section id="student-database-page">
    <header class="student-database-hero">
        <nav aria-label="Breadcrumb"><a href="{{ route('homePage') }}">Home</a> &rsaquo; Student Database</nav>
        <h1>Student Database</h1>
        <p>Browse student records by class, section, session and department.</p>
    </header>
    <div class="student-summary" aria-label="Student database summary">
        @foreach($studentMetrics as $metric)
            <div class="student-summary-item"><i class="fa {{ $metric[2] }}" aria-hidden="true"></i><div><strong>{{ $metric[1] }}</strong><span>{{ $metric[0] }}</span></div></div>
        @endforeach
    </div>
    <section class="student-filter-panel" aria-labelledby="student-filter-title">
        <h2 id="student-filter-title">Find a Student</h2>
        <form class="student-filters" id="student-filters" role="search">
            <label>Search Student<input id="student-search" type="search" placeholder="Name, student ID or details" autocomplete="off"></label>
            @foreach($studentFilters as [$key, $label, $field, $lookup, $nameField])
                <label>{{ $label }}
                    <select data-student-filter="{{ $key }}" aria-label="{{ $label }}">
                        <option value="">All {{ $label }}</option>
                        @foreach($lookup->only($Datakey->pluck($field)->filter()->unique()->all()) as $option)
                            @if(filled($option->{$nameField}))
                                <option value="{{ $option->id }}">{{ $option->{$nameField} }}</option>
                            @endif
                        @endforeach
                    </select>
                </label>
            @endforeach
            <label>Per-page<select id="student-per-page"><option>10</option><option selected>25</option><option>50</option><option>100</option></select></label>
            <button class="student-reset" type="reset">Reset</button>
        </form>
    </section>
    <div class="student-shell">
        <div class="student-header">
            <div>
                <h2>Student Records</h2>
                <p>Student information, details and profile access.</p>
            </div>
            <span class="student-count"><i class="fa fa-users"></i> {{ $Datakey->count() }} {{ $Datakey->count() === 1 ? 'Student' : 'Students' }}</span>
        </div>

        @if($Datakey->count() > 0)
            <div class="student-table-wrap table-responsive">
                <table id="studentTable" class="student-table table table-hover align-middle display border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Student Info</th>
                            <th>Student Details</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($Datakey as $std)
                            @php
                                $fullName    = $std->student_name ?: 'Unknown';
                                $sessionName = optional($sessions[$std->sessName]    ?? null)->session             ?? 'N/A';
                                $className   = optional($classes[$std->className]    ?? null)->className           ?? 'N/A';
                                $sectionName = optional($sections[$std->sectionName] ?? null)->section             ?? 'N/A';
                                $deptName    = optional($departments[$std->departmentName] ?? null)->departmentName ?? 'N/A';
                                $photo = app(\App\Services\PublicMediaUrl::class)->studentPortrait($std->avatar)
                                    ?: app(\App\Services\PublicAssetUrl::class)->url('avatar.png');
                            @endphp
                            <tr data-class="{{ $std->className }}" data-section="{{ $std->sectionName }}" data-session="{{ $std->sessName }}" data-department="{{ $std->departmentName }}">
                                <td>
                                    <div class="student-info-wrap">
                                        <x-people-portrait :src="$photo" :alt="$fullName" variant="compact" image-class="student-avatar" />
                                        <div class="student-name-id">
                                            <span class="student-name">{{ e($fullName) }}</span>
                                            <span class="student-id">ID: {{ e($std->stdId) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-meta-grid">
                                        <div class="student-meta-item student-meta-class">
                                            <span class="student-meta-label"><i class="fa fa-book"></i> Class</span>
                                            <span class="student-meta-value">{{ $className }}</span>
                                        </div>
                                        <div class="student-meta-item student-meta-session">
                                            <span class="student-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                            <span class="student-meta-value">{{ $sessionName }}</span>
                                        </div>
                                        <div class="student-meta-item student-meta-department">
                                            <span class="student-meta-label"><i class="fa fa-university"></i> Department</span>
                                            <span class="student-meta-value">{{ $deptName }}</span>
                                        </div>
                                        <div class="student-meta-item student-meta-section">
                                            <span class="student-meta-label"><i class="fa fa-list"></i> Section</span>
                                            <span class="student-meta-value">{{ $sectionName }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('student.show', ['id' => $std->id]) }}" class="student-view-btn" title="View Student Profile">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-0">
                                    <div class="student-empty">
                                        <i class="fa fa-user-slash"></i>
                                        No student records found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="student-empty">
                <i class="fa fa-user-slash"></i>
                No student records found.
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $('#studentTable').length) {
        const table = $('#studentTable').DataTable({
            dom: 'rtip',
            order: [[0, 'asc']],
            pageLength: 25,
            autoWidth: false,
            responsive: false,
            columnDefs: [{ targets: [2], orderable: false, searchable: false }],
            language: {
                search: '',
                searchPlaceholder: 'Search students…',
                lengthMenu: 'Show _MENU_ per page',
                info: 'Showing _START_–_END_ of _TOTAL_ students',
                infoEmpty: 'No students found',
                zeroRecords: 'No matching students found',
                paginate: { previous: '‹', next: '›' }
            }
        });
        const filters = Array.from(document.querySelectorAll('[data-student-filter]'));
        const filterRows = function (settings, data, index) {
            if (settings.nTable.id !== 'studentTable') return true;
            const row = settings.aoData[index].nTr;
            return filters.every(control => !control.value || row.dataset[control.dataset.studentFilter] === control.value);
        };
        $.fn.dataTable.ext.search.push(filterRows);
        filters.forEach(control => control.addEventListener('change', () => table.draw()));
        document.getElementById('student-search').addEventListener('input', event => table.search(event.target.value).draw());
        document.getElementById('student-per-page').addEventListener('change', event => table.page.len(Number(event.target.value)).draw());
        const form = document.getElementById('student-filters');
        form.addEventListener('submit', event => event.preventDefault());
        form.addEventListener('reset', () => setTimeout(() => table.search('').page.len(25).draw(), 0));
        $('#studentTable').on('destroy.dt', () => {
            const index = $.fn.dataTable.ext.search.indexOf(filterRows);
            if (index >= 0) $.fn.dataTable.ext.search.splice(index, 1);
        });
    }
});
</script>
@endsection
