@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Needy Students
@endsection
@section('frontcontent')
<section>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 text-center con-title">
                <h2 class="wow fadeInLeft animated" data-wow-delay=".60s">Job Needy <span> Student</span></h2>
           </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @error('attachment')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                
                
                @error('avatar')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                                @if(session('success'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                                        <span>{{ session('success') }}</span>
                  </div>
                @endif
                
                                @if(session('error'))
                  <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                                        <span>{{ session('error') }}</span>
                  </div>
                @endif 
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><span class="text-muted">Job Seekers List</span></button>
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
                                    .needy-table { margin-bottom: 0; border-collapse: separate; border-spacing: 0; overflow: hidden; border-radius: 12px; table-layout: fixed; }
                                    .needy-table thead th { border-bottom: 1px solid #d7e6f3; color: #1f3f66; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; font-weight: 800; background: linear-gradient(180deg, #f8fcff 0%, #eef5fb 100%); padding: 14px 16px; }
                                    .needy-table tbody td { vertical-align: middle; color: #3f5772; font-size: 14px; padding: 16px; line-height: 1.6; border-color: #e0ebf4; }
                                    .needy-table tbody tr:nth-child(even) td { background: #fafcff; }
                                    .needy-table tbody tr:hover td { background: #f4faff; }
                                    .needy-table th:first-child, .needy-table td:first-child { width: 32%; }
                                    .needy-table th:nth-child(2), .needy-table td:nth-child(2) { width: auto; }
                                    .needy-table th:last-child, .needy-table td:last-child { width: 90px; text-align: center; white-space: nowrap; }
                                    .needy-info { display: flex; align-items: center; gap: 12px; }
                                    .needy-photo { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1.5px solid #dce9f4; flex-shrink: 0; }
                                    .needy-name { font-weight: 700; color: #1f3f66; font-size: 13px; display: block; }
                                    .needy-id { font-size: 12px; color: #6d7d8b; margin-top: 2px; }
                                    .needy-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px 12px; width: 100%; }
                                    .needy-meta-item { background: #fff; border: 1px solid #e8f0f7; border-radius: 8px; padding: 8px 10px; display: flex; flex-direction: column; gap: 2px; box-shadow: inset 0 1px 3px rgba(27, 112, 150, 0.04); }
                                    .needy-meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #0c7da7; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
                                    .needy-meta-label i { font-size: 12px; display: inline-block; min-width: 12px; }
                                    .needy-meta-value { font-size: 13px; font-weight: 600; color: #1f3f66; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                                    .needy-view-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(27, 112, 150, 0.2); background: rgba(27, 112, 150, 0.08); color: #1b7096; transition: all .2s ease; text-decoration: none; }
                                    .needy-view-btn:hover { background: rgba(27, 112, 150, 0.15); border-color: rgba(27, 112, 150, 0.3); color: #0f4968; cursor: pointer; }
                                </style>
                                <table id="myTable" data-order='[[ 0, "desc" ]]' class="needy-table table table-border table-hover align-middle" >
                                    <thead>
                                        <tr>
                                            <th class="d-none">Id</th>
                                            <th>Seeker Info</th>
                                            <th>Seeker Details</th>
                                            <th>View CV</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Datakey))
                                        @foreach($Datakey as $data)
                                        <tr>
                                            <td class="d-none">{{ $data->id }}</td>
                                            <td>
                                                <div class="needy-info">
                                                    <img class="needy-photo" src="{{ asset('upload/image/neddyStudent/' . rawurlencode(basename($data->attachment))) }}" alt="{{ $data->fullName }}" />
                                                    <div>
                                                        <span class="needy-name">{{ $data->fullName }}</span>
                                                        <span class="needy-id">Roll: {{ $data->rollNumber }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="needy-meta-grid">
                                                    <div class="needy-meta-item needy-meta-session">
                                                        <span class="needy-meta-label"><i class="fa fa-calendar"></i> Session</span>
                                                        <span class="needy-meta-value">{{ $data->sessionYear }}</span>
                                                    </div>
                                                    <div class="needy-meta-item needy-meta-email">
                                                        <span class="needy-meta-label"><i class="fa fa-envelope"></i> Email</span>
                                                        <span class="needy-meta-value">{{ $data->email }}</span>
                                                    </div>
                                                    <div class="needy-meta-item needy-meta-roll">
                                                        <span class="needy-meta-label"><i class="fa fa-id-card"></i> Roll</span>
                                                        <span class="needy-meta-value">{{ $data->rollNumber }}</span>
                                                    </div>
                                                    <div class="needy-meta-item needy-meta-mobile">
                                                        <span class="needy-meta-label"><i class="fa fa-mobile"></i> Mobile</span>
                                                        <span class="needy-meta-value">{{ $data->mobile }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="needy-view-btn" data-bs-toggle="modal" data-bs-target="#getData{{ $data->id }}" title="View CV">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <div class="modal fade" id="getData{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <p class="modal-title fs-5" id="staticBackdropLabel">CV of {{ $data->fullName }}</p>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <embed class="w-100" height="550px" src="{{ asset('upload/image/neddyStudent/' . rawurlencode(basename($data->attachment))) }}" title="CV of {{ $data->fullName }}"></embed>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach         
                                       @endif
                                    </tbody>
                                 </table>                         
                            </div>
                        </div>    
                    </div>
                    <div class="tab-pane fade card rounded-0" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <div class="card-body">
                            <form class="form row" method="POST" action="{{ route('saveNeedyStdPanel') }}" enctype="multipart/form-data" novalidate>
                                @csrf
                                <!-- Honeypot fields to catch bots -->
                                <div class="d-none" aria-hidden="true">
                                    <label for="website">Website</label>
                                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                                </div>
                                <input type="hidden" name="form_ts" value="{{ time() }}">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" name="fullName" class="form-control" placeholder="Enter your full name(*)" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rollNumber">Roll Number</label>
                                        <input type="text" name="rollNumber" class="form-control" placeholder="Enter roll number(*)" inputmode="numeric" pattern="\d{6}" maxlength="6" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sessionYear">Session</label>
                                        <input type="text" name="sessionYear" class="form-control" placeholder="Enter session year(*)" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter a valid email(*)" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number(*)" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="attachment">CV</label>
                                        <input type="file" class="form-control" name="attachment" accept="application/pdf,.pdf" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="avatar">Photo</label>
                                        <input type="file" class="form-control" name="avatar" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-primary" value="Save Profile">
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