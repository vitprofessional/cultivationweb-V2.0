@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Student List
@endsection
@php
$config =App\Models\ServerConfig::first()
@endphp
@section('frontcontent')
<style>
    #studentTable_wrapper .dataTables_length select{min-width:70px}
    #studentTable_wrapper .dataTables_filter input{min-width:180px}
    table.dataTable tbody tr:hover{background:#f8fafc}
    .profile-btn{padding:.35rem .6rem;font-size:.7rem;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;border:1px solid #0ea5e9;color:#0ea5e9;background:#fff;transition:.25s}
    .profile-btn:hover{background:#0ea5e9;color:#fff}
</style>
<section class="mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center con-title mt-4">
                <h2 class="wow fadeInLeft animated my-4" data-wow-delay=".60s"> Student Details of <span>@if(!empty($config->instituteName)){{ $config->instituteName }}@else Jahanara Ayub Academy @endif</span></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Student List (Ordered by Class)</span>
                        <small class="text-muted">Total: {{ count($Datakey ?? []) }}</small>
                    </div>
                    <div class="card-body table-responsive">
                        @php
                            $sessions = \App\Models\sessionManage::all()->keyBy('id');
                            $classes = \App\Models\classManage::all()->keyBy('id');
                            $sections = \App\Models\sectionManage::all()->keyBy('id');
                            $departments = \App\Models\Department::all()->keyBy('id');
                        @endphp
                        <table id="studentTable" data-order='[[0,"asc"]]' class="display table table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Session</th>
                                    <th>Department</th>
                                    <th>Section</th>
                                    <th>Photo</th>
                                    <th>Profile</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($Datakey as $std)
                                    @php
                                        $fullName = trim(($std->fullName ?? '').' '.($std->sureName ?? '')) ?: 'Unknown';
                                        $sessionName = optional($sessions[$std->sessName] ?? null)->session ?? '-';
                                        $className = optional($classes[$std->className] ?? null)->className ?? '-';
                                        $sectionName = optional($sections[$std->sectionName] ?? null)->section ?? '-';
                                        $deptName = optional($departments[$std->departmentName] ?? null)->departmentName ?? '-';
                                        $photo = !empty($std->avatar)
                                                ? config('app.url').'/public/upload/image/student/'.rawurlencode(basename($std->avatar))
                                                : config('app.url').'/public/avatar.png';
                                    @endphp
                                    <tr>
                                        <td data-order="{{ (int)($std->className ?? 0) }}">{{ e($className) }}</td>
                                        <td>{{ e($std->stdId) }}</td>
                                        <td>{{ e($fullName) }}</td>
                                        <td>{{ e($sessionName) }}</td>
                                        <td>{{ e($deptName) }}</td>
                                        <td>{{ e($sectionName) }}</td>
                                        <td style="width:60px"><img class="img-fluid rounded-circle" style="width:48px;height:48px;object-fit:cover" src="{{ $photo }}" alt="{{ e($fullName) }}" loading="lazy"></td>
                                        <td><a href="{{ route('student.show',['id'=>$std->id]) }}" class="profile-btn"><i class="fa-regular fa-eye"></i> View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-4">No student data found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded',function(){
    if(window.jQuery && $('#studentTable').length){
        let orderData = $('#studentTable').data('order');
        $('#studentTable').DataTable({
            order: orderData || [[0,'asc']],
            pageLength:25,
            responsive:true,
            columnDefs:[{targets:[6,7],orderable:false}],
        });
    }
});
</script>
@endsection