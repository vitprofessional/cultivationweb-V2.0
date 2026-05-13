@extends($frontendLayout ?? config('frontend.layout')) @section('fronttitle') Placement Cell @endsection @section('frontcontent')
<section>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 text-center con-title">
                <h2 class="wow fadeInLeft animated" data-wow-delay=".60s">Placement<span> Cell</span></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @error('avatar')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @if(Session::get('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>{!! Session::get('success') !!}</span>
                </div>
                @endif @if(Session::get('error'))
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>{!! Session::get('error') !!}</span>
                </div>
                @endif
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><span class="text-muted">Placement Cell</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><span class="text-muted">Create Profile</span></button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="card rounded-0 border-top-0">
                            <div class="card-body table-responsive">
                                <!-- On tables -->
                                <style>
                                    .placement-table { margin-bottom: 0; border-collapse: separate; border-spacing: 0; overflow: hidden; border-radius: 12px; table-layout: fixed; }
                                    .placement-table thead th { border-bottom: 1px solid #d7e6f3; color: #1f3f66; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; font-weight: 800; background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%); padding: 14px 16px; }
                                    .placement-table tbody td { vertical-align: middle; color: #3f5772; font-size: 14px; padding: 16px; line-height: 1.6; border-color: #e0ebf4; }
                                    .placement-table tbody tr:nth-child(even) td { background: #fafcff; }
                                    .placement-table tbody tr:hover td { background: #f4faff; }
                                    .placement-table th:first-child, .placement-table td:first-child { width: 32%; }
                                    .placement-table th:nth-child(2), .placement-table td:nth-child(2) { width: auto; }
                                    .placement-table th:last-child, .placement-table td:last-child { width: 90px; text-align: center; white-space: nowrap; }
                                    .placement-info { display: flex; align-items: center; gap: 12px; }
                                    .placement-photo { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1.5px solid #dce9f4; flex-shrink: 0; }
                                    .placement-name { font-weight: 700; color: #1f3f66; font-size: 13px; display: block; }
                                    .placement-id { font-size: 12px; color: #6d7d8b; margin-top: 2px; }
                                    .placement-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px 12px; width: 100%; }
                                    .placement-meta-item { background: #fff; border: 1px solid #e8f0f7; border-radius: 8px; padding: 8px 10px; display: flex; flex-direction: column; gap: 2px; box-shadow: inset 0 1px 3px rgba(27, 112, 150, 0.04); }
                                    .placement-meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #0c7da7; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
                                    .placement-meta-label i { font-size: 12px; display: inline-block; min-width: 12px; }
                                    .placement-meta-value { font-size: 13px; font-weight: 600; color: #1f3f66; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                                    .placement-view-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(27, 112, 150, 0.2); background: rgba(27, 112, 150, 0.08); color: #1b7096; transition: all .2s ease; text-decoration: none; }
                                    .placement-view-btn:hover { background: rgba(27, 112, 150, 0.15); border-color: rgba(27, 112, 150, 0.3); color: #0f4968; cursor: pointer; }
                                </style>
                                <table id="myTable" data-order='[[ 0, "desc" ]]' class="placement-table table table-border table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Id</th>
                                            <th>Placement Info</th>
                                            <th>Placement Details</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Datakey)) @foreach($Datakey as $data)
                                        <tr>
                                            <td class="d-none">{!!$data->id!!}</td>
                                            <td>
                                                <div class="placement-info">
                                                    <img class="placement-photo" src="{{ config('app.url') }}/public/upload/image/placementCell/{{ $data->attachment }}" alt="{!! $data->fullName !!}" />
                                                    <div>
                                                        <span class="placement-name">{!!$data->fullName!!}</span>
                                                        <span class="placement-id">Roll: {!!$data->rollNumber!!}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="placement-meta-grid">
                                                    <div class="placement-meta-item placement-meta-session">
                                                        <span class="placement-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                                        <span class="placement-meta-value">{!!$data->sessionYear!!}</span>
                                                    </div>
                                                    <div class="placement-meta-item placement-meta-company">
                                                        <span class="placement-meta-label"><i class="fa fa-building"></i> Company</span>
                                                        <span class="placement-meta-value">{!!$data->companyName!!}</span>
                                                    </div>
                                                    <div class="placement-meta-item placement-meta-position">
                                                        <span class="placement-meta-label"><i class="fa fa-user"></i> Position</span>
                                                        <span class="placement-meta-value">{!!$data->designation!!}</span>
                                                    </div>
                                                    <div class="placement-meta-item placement-meta-roll">
                                                        <span class="placement-meta-label"><i class="fa fa-envelope"></i> Email</span>
                                                        <span class="placement-meta-value">{!!$data->email!!}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="placement-view-btn" data-bs-toggle="modal" data-bs-target="#getData{{ $data->id }}" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="getData{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <p class="modal-title fs-5" id="staticBackdropLabel">Details About {{ $data->fullName }}</p>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center mb-3">
                                                                    <img class="w-50 border" src="{{ asset('/public/upload/image/placementCell/').'/'.$data->avatar}}" alt="{!! $data->fullName !!}" />
                                                                </div>
                                                                <table class="table table-bordered">
                                                                    <tr>
                                                                        <th class="fw-bold">Session</th>
                                                                        <td class="text-start">: {!! $data->sessionYear !!}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="fw-bold">Roll Number</th>
                                                                        <td class="text-start">: {!! $data->rollNumber !!}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="fw-bold">Company</th>
                                                                        <td class="text-start">: {!! $data->companyName !!}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="fw-bold">Position</th>
                                                                        <td class="text-start">: {!! $data->designation !!}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="fw-bold">Mobile</th>
                                                                        <td class="text-start">: {!! $data->mobile!!}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="fw-bold">Email</th>
                                                                        <td class="text-start">: {!! $data->email !!}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card rounded-0" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <div class="card-body">
                            <form class="form row" method="POST" action="{{route('savePlacementCell')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <input type="text" name="fullName" class="form-control" placeholder="Enter your full name(*)" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" name="rollNumber" class="form-control" placeholder="Enter roll number(*)" minlength="6" maxlength="6" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="sessionYear" class="form-control" placeholder="Enter session year(*)" required />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Enter a valid email(*)" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number(*)" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="designation" class="form-control" placeholder="Enter current designation/title(*)" required />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <input type="text" name="companyName" class="form-control" placeholder="Enter current company(*)" required />
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="jobDetails" placeholder="Details about your profession" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="avatar" required />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-primary" value="Save Profile" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
