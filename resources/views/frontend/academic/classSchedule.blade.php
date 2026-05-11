@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Class Schedule
@endsection

@section('frontcontent')
<section class="container mt-4">
    <div class="row">
        <div class="col-md-12 text-center con-title mt-4">
            <h2 class="hedingAbout wow fadeInLeft animated my-4" data-wow-delay=".60s">Class <span> Schedule</span> </h2>
        </div>
    </div>
    <div calss="row">
        <div class="col-10 mx-auto my-4">
            <div class="card">
                <div class="card-header">
                    Class Schedule List 
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-12 col-md-3">
                            <select name="class" class="form-select form-select-sm">
                                <option value="">All Classes</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ request('class') == $c->id ? 'selected' : '' }}>{{ $c->className }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="department" class="form-select form-select-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ request('department') == $d->id ? 'selected' : '' }}>{{ $d->departmentName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="session" class="form-select form-select-sm">
                                <option value="">All Sessions</option>
                                @foreach($sessions as $s)
                                    <option value="{{ $s->id }}" {{ request('session') == $s->id ? 'selected' : '' }}>{{ $s->session }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-sm">Filter</button>
                            <a href="{{ route('newClassSchedule') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        </div>
                    </form>
                    <!-- On tables -->
                    <table id="myTable" class="display border table table-hover align-middle" >
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Department</th>
                                <th>Session</th> 
                                <th style="width:170px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($Datakey as $data)
                            @php
                                // Resolve section field from possible column names used in older schema
                                $sectionVal = $data->assignSection ?? $data->section ?? $data->sectionName ?? 'All';
                                $rowsVal = $data->rows ?? $data->rows_count ?? $data->row_count ?? '-';
                                $attachment = $data->attachment ?? '';
                                $attachmentUrl = $attachment ? url('/public/upload/image/cultivation/classRoutine/'.rawurlencode(basename($attachment))) : null;
                                $ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                            @endphp
                            <tr>
                                <td>{{ $data->title }}</td>
                                <td>{{ optional($data->class)->className ?? 'N/A' }}</td>
                                <td>{{ $sectionVal }}</td>
                                <td>{{ optional($data->department)->departmentName ?? 'N/A' }}</td>
                                <td>{{ optional($data->session)->session ?? 'N/A' }}</td>
                                <td>

                                    {{-- V2 View & Print (grid and print-friendly) --}}
                                    <a href="{{ route('classRoutine.view', ['id' => $data->id]) }}" class="btn btn-sm btn-info me-1" title="View Routine">
                                        View
                                    </a>
                                    <a href="{{ route('classRoutine.print', ['id' => $data->id]) }}" target="_blank" class="btn btn-sm btn-primary me-1" title="Print Routine">
                                        Print
                                    </a>
                                    <a href="{{ route('classRoutine.download', ['id' => $data->id]) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <!-- Routine Preview Modal -->
        <div class="modal fade" id="routineModal" tabindex="-1" aria-labelledby="routineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="routineModalLabel">Class Routine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="routinePreview" style="min-height:300px">
                            <!-- Content injected by JS: image or iframe for PDFs -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('routineModal'));

        // Delegate handler for preview buttons (works with DataTables pagination/reflow)
        document.addEventListener('click', function(evt){
            const btn = evt.target.closest('.view-routine');
            if(!btn) return;
            evt.preventDefault();
            const file = btn.dataset.file;
            const filename = btn.dataset.filename || '';
            const preview = document.getElementById('routinePreview');
            preview.innerHTML = '';
            const ext = String(filename).split('.').pop().toLowerCase();
            if(['jpg','jpeg','png','gif','webp','avif'].includes(ext)){
                const img = document.createElement('img');
                img.src = file;
                img.alt = filename;
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                preview.appendChild(img);
            } else if(ext === 'pdf'){
                const iframe = document.createElement('iframe');
                iframe.src = file;
                iframe.style.width = '100%';
                iframe.style.minHeight = '500px';
                iframe.frameBorder = 0;
                preview.appendChild(iframe);
            } else {
                const link = document.createElement('a');
                link.href = file;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.textContent = 'Open routine in new tab';
                preview.appendChild(link);
            }
            modal.show();
        });

        // Delegate handler for print buttons
        document.addEventListener('click', function(evt){
            const btn = evt.target.closest('.print-file');
            if(!btn) return;
            evt.preventDefault();
            const file = btn.dataset.file;
            if(!file) return;
            const w = window.open(file, '_blank');
            if(!w) return;
            try{
                w.addEventListener ? w.addEventListener('load', ()=>{ try{ w.print(); }catch(e){} }) : setTimeout(()=>{ try{ w.print(); }catch(e){} }, 700);
            }catch(_){ /* ignore */ }
        });
});
</script>
@endpush
@endsection