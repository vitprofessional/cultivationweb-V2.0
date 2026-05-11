@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Internal Result
@endsection
@section('frontcontent')
 <section class="container mt-4">
    <div class="row">
        <div class="col-10 mx-auto text-center con-title mt-4">
            <h2 class="hedingAbout wow fadeInLeft animated my-4" data-wow-delay=".60s">Internal <span> Result</span> </h2>
        </div>
    </div>
    <div calss="row">
        <div class="col-10 mx-auto my-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>Internal Result List</div>
                    <div class="small text-muted">At a glance</div>
                </div>
                <div class="card-body">
                    @php
                        $totalCount = isset($Datakey) ? $Datakey->count() : 0;
                        $withAttachment = isset($Datakey) ? $Datakey->filter(fn($d)=>!empty($d->attachment))->count() : 0;
                        $latest = isset($Datakey) ? $Datakey->sortByDesc(fn($d)=> $d->published_at ?? $d->created_at)->first() : null;
                    @endphp
                    <div class="row mb-3">
                        <div class="col-12 col-md-4">
                            <div class="border p-2 rounded text-center">
                                <div class="h5 mb-0">{{ $totalCount }}</div>
                                <div class="small text-muted">Total results</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border p-2 rounded text-center">
                                <div class="h5 mb-0">{{ $withAttachment }}</div>
                                <div class="small text-muted">With attachment</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border p-2 rounded text-center">
                                <div class="h6 mb-0">{{ $latest ? (\Carbon\Carbon::parse($latest->published_at ?? $latest->created_at)->format('d M Y')) : '—' }}</div>
                                <div class="small text-muted">Latest published</div>
                            </div>
                        </div>
                    </div>
                    <!-- On tables -->
                        <form method="GET" action="{{ route('internalResult') }}" class="row g-3 mb-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Class</label>
                                <select name="class" class="form-select">
                                    <option value="">All</option>
                                    @isset($classes)
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}" @if(!empty($filters['class']) && (int)$filters['class']===(int)$c->id) selected @endif>
                                                {{ $c->className }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Section</label>
                                <select name="section" class="form-select">
                                    <option value="">All</option>
                                    @isset($sections)
                                        @foreach($sections as $s)
                                            <option value="{{ $s->id }}" @if(!empty($filters['section']) && (int)$filters['section']===(int)$s->id) selected @endif>
                                                {{ $s->section }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Department</label>
                                <select name="department" class="form-select">
                                    <option value="">All</option>
                                    @isset($depts)
                                        @foreach($depts as $d)
                                            <option value="{{ $d->id }}" @if(!empty($filters['department']) && (int)$filters['department']===(int)$d->id) selected @endif>
                                                {{ $d->departmentName }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Session</label>
                                <select name="session" class="form-select">
                                    <option value="">All</option>
                                    @isset($sessions)
                                        @foreach($sessions as $ss)
                                            <option value="{{ $ss->id }}" @if(!empty($filters['session']) && (int)$filters['session']===(int)$ss->id) selected @endif>
                                                {{ $ss->session }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-success">Filter</button>
                                <a href="{{ route('internalResult') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </form>
                    <div class="row g-3">
                        @if(isset($Datakey) && $Datakey->count() > 0)
                            @foreach($Datakey as $data)
                                @php
                                    $itemClass      = !empty($data->assignClass) ? \App\Models\classManage::find($data->assignClass) : null;
                                    $itemSection    = !empty($data->assignSection) ? \App\Models\sectionManage::find($data->assignSection) : null;
                                    $itemDepartment = !empty($data->assignDepartment) ? \App\Models\Department::find($data->assignDepartment) : null;
                                    $itemSession    = !empty($data->assignSession) ? \App\Models\sessionManage::find($data->assignSession) : null;
                                    $pubDate = $data->published_at ?? $data->created_at ?? null;
                                @endphp
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="row g-0 h-100">
                                            <div class="col-4">
                                                @php
                                                    $attachment = $data->attachment ?? null;
                                                    $isImage = $attachment && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $attachment);
                                                    $isPdf = $attachment && preg_match('/\.(pdf)$/i', $attachment);
                                                @endphp
                                                <div class="h-100 d-flex align-items-center justify-content-center bg-light">
                                                    @if($isImage)
                                                        <img src="{{ asset('upload/image/cultivation/internalResult/'.$attachment) }}" class="img-fluid" style="max-height:120px; object-fit:cover;" alt="result">
                                                    @elseif($isPdf)
                                                        <div class="text-center p-3">
                                                            <i class="fa fa-file-pdf fa-3x text-danger"></i>
                                                            <div class="small mt-2">PDF</div>
                                                        </div>
                                                    @else
                                                        <div class="text-center p-3 small text-muted">No preview</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body d-flex flex-column">
                                                    @php
                                                        $isPublished = !empty($data->is_published) || (!empty($data->status) && strtolower($data->status)=='published') || !empty($data->published_at);
                                                    @endphp
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h5 class="card-title mb-1">{{ $data->title ?? 'Untitled' }}</h5>
                                                        @if($isPublished)
                                                            <span class="badge bg-success">Published</span>
                                                        @else
                                                            <span class="badge bg-secondary">Unpublished</span>
                                                        @endif
                                                    </div>
                                                    <p class="mb-1 small text-muted">Class: {{ $itemClass->className ?? '—' }} · Section: {{ $itemSection->section ?? '—' }}</p>
                                                    <p class="mb-2 small text-muted">Department: {{ $itemDepartment->departmentName ?? '—' }} · Session: {{ $itemSession->session ?? '—' }}</p>
                                                    <p class="mb-2"><strong>Published:</strong> {{ $pubDate ? \Carbon\Carbon::parse($pubDate)->format('d M Y') : '—' }}</p>
                                                    <div class="mt-auto d-flex gap-2">
                                                        @if(!empty($data->attachment))
                                                            <a data-fancybox data-type="iframe" href="{{ asset('upload/image/cultivation/internalResult/'.$data->attachment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                                <i class="fa fa-eye me-1"></i> Preview
                                                            </a>
                                                            <a href="{{ asset('upload/image/cultivation/internalResult/'.$data->attachment) }}" download class="btn btn-sm btn-outline-success">
                                                                <i class="fa fa-download me-1"></i> Download
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No file attached</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="p-4 mb-0">No results found.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection