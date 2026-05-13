<style>
.edu-main-inner .table-responsive{
    border: 0px !important;
}
.academic-table-wrap .dataTables_wrapper {
    margin-top: 2px;
}

.academic-table-wrap .dataTables_length,
.academic-table-wrap .dataTables_filter {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}


.academic-table-wrap .dataTables_length label,
.academic-table-wrap .dataTables_filter label {
    margin: 0;
    color: #415a76;
    font-size: 13px;
    font-weight: 700;
}

.academic-table-wrap .dataTables_length select {
    min-width: 78px;
    height: 38px;
    border: 1px solid #cfe0ef;
    border-radius: 8px;
    background: #fff;
    color: #3f5772;
    padding: 0 10px;
    box-shadow: none;
}

.academic-table-wrap .dataTables_filter input {
    min-width: 220px;
    height: 38px;
    border: 1px solid #cfe0ef;
    border-radius: 8px;
    background: #fff;
    color: #3f5772;
    padding: 0 12px;
    box-shadow: none;
}

.academic-table-wrap .dataTables_filter input:focus,
.academic-table-wrap .dataTables_length select:focus {
    border-color: #1b7096;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(27, 112, 150, .12);
}

.academic-table-wrap .dataTables_info {
    padding-top: 12px;
    color: #5a708a;
    font-size: 13px;
    font-weight: 600;
}

.academic-table-wrap .dataTables_paginate {
    padding-top: 10px;
}

.academic-table-wrap .dataTables_paginate .paginate_button {
    border: 1px solid #cfddec !important;
    border-radius: 8px !important;
    background: #fff !important;
    color: #31506f !important;
    margin: 0 2px !important;
    padding: 6px 12px !important;
    transition: all .2s ease;
}

.academic-table-wrap .dataTables_paginate .paginate_button:hover {
    background: #eef5fb !important;
    color: #173652 !important;
    border-color: #b7cde0 !important;
}

.academic-table-wrap .dataTables_paginate .paginate_button.current,
.academic-table-wrap .dataTables_paginate .paginate_button.current:hover {
    background: #1b7096 !important;
    color: #fff !important;
    border-color: #1b7096 !important;
}

.academic-table-wrap .dataTables_paginate .paginate_button.disabled,
.academic-table-wrap .dataTables_paginate .paginate_button.disabled:hover {
    color: #a4b3c2 !important;
    background: #f6f9fc !important;
    border-color: #dde7ef !important;
}

.academic-table-wrap .table {
    margin-bottom: 0;
}

.academic-table-wrap .table thead th,
.academic-table-wrap .table tbody td {
    vertical-align: middle;
}

.academic-table-wrap .table thead th {
    padding-left: 16px;
    padding-right: 16px;
}

.academic-table-wrap .table tbody td {
    padding-left: 16px;
    padding-right: 16px;
}

.academic-table-wrap .table-bordered > :not(caption) > * > * {
    border-color: #e0ebf4;
}

.academic-table-wrap .table-hover > tbody > tr:hover > * {
    background-color: #f4faff;
}

.academic-table-wrap .table tbody tr:nth-child(even) > * {
    background: #fafcff;
}

@media (max-width: 575.98px) {
    .academic-table-wrap .dataTables_length,
    .academic-table-wrap .dataTables_filter {
        width: 100%;
        flex-wrap: wrap;
    }

    .academic-table-wrap .dataTables_filter input {
        min-width: 0;
        width: 100%;
    }
}
</style>