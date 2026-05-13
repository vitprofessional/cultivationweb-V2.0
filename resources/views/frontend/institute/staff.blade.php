@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Our Staff Panel
@endsection
@php
$config = App\Models\ServerConfig::first();
$staffMembers = $Datakey ?? collect();
@endphp
@section('frontcontent')
<style>
.staff-directory-shell {
    background: linear-gradient(180deg, #f8fbff 0%, #f2f8fd 100%);
    border: 1px solid #dce9f4;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 12px 28px rgba(19, 54, 102, 0.08);
}

.staff-directory-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 14px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #dce9f4;
}

.staff-directory-head .title-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.staff-directory-head .kicker {
    display: inline-flex;
    width: fit-content;
    color: #08688e;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 5px 10px;
    border-radius: 999px;
    background: #def2ff;
    border: 1px solid #b7def6;
}

.staff-directory-head h2 {
    margin: 0;
    font-size: 22px;
    line-height: 1.2;
    color: #102b56;
    font-weight: 800;
}

.staff-directory-head p {
    margin: 0;
    color: #5a708a;
    font-size: 14px;
    line-height: 1.6;
}

.staff-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e6f7ff;
    color: #0f83b0;
    border: 1px solid #b8e3f5;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
    white-space: nowrap;
}

.staff-card {
    height: 100%;
    background: #fff;
    border: 1px solid #dce9f4;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(19, 54, 102, 0.08);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.staff-card:hover {
    transform: translateY(-3px);
    border-color: #bdd9ed;
    box-shadow: 0 16px 30px rgba(19, 54, 102, 0.14);
}

.staff-photo-wrap {
    display: block;
    position: relative;
    background: #eef5fb;
}

.staff-photo {
    width: 100%;
    height: 252px;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform .35s ease;
}

.staff-card:hover .staff-photo {
    transform: scale(1.04);
}

.staff-body {
    padding: 14px;
}

.staff-name {
    margin: 0 0 5px;
    font-size: 17px;
    line-height: 1.25;
    font-weight: 800;
    color: #112958;
}

.staff-designation {
    margin: 0 0 10px;
    color: #4f6783;
    font-size: 14px;
    font-weight: 600;
}

.staff-contact-line {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 7px;
    font-size: 13px;
    line-height: 1.4;
    color: #5d748e;
    min-height: 20px;
}

.staff-contact-line i {
    color: #21a7d0;
    width: 14px;
    text-align: center;
    flex-shrink: 0;
}

.staff-contact-line a {
    color: #4f6783;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.staff-contact-line a:hover {
    color: #21a7d0;
}

.staff-empty {
    padding: 20px;
    border-radius: 12px;
    border: 1px dashed #cfe3ec;
    background: #f8fbfc;
    text-align: center;
    color: #6d7d8b;
}

@media (max-width: 991.98px) {
    .staff-photo {
        height: 230px;
    }
}

@media (max-width: 575.98px) {
    .staff-directory-shell {
        padding: 12px;
    }

    .staff-directory-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .staff-directory-head h2 {
        font-size: 18px;
    }

    .staff-photo {
        height: 210px;
    }
}
</style>
<div class="col-12 mt-4">
    <div class="staff-directory-shell">
        <div class="staff-directory-head">
            <div class="title-wrap">
                <span class="kicker">Institutional Team</span>
                <h2>{{ !empty($config->instituteName) ? $config->instituteName : 'Jahanara Ayub Academy' }} Staff Panel</h2>
                <p>Meet our office and support staff with verified role and contact details.</p>
            </div>
            <span class="staff-count"><i class="fa fa-users"></i> {{ $staffMembers->count() }} {{ $staffMembers->count() === 1 ? 'Member' : 'Members' }}</span>
        </div>

        @if($staffMembers->count() > 0)
            <div class="row">
                @foreach($staffMembers as $data)
                    @php
                        $name = trim(($data->firstName ?? '') . ' ' . ($data->lastName ?? ''));
                        $name = $name !== '' ? $name : ($data->fullName ?? 'Staff Member');
                        $designation = \App\Models\StaffManagement::getDesignationName($data->designation ?? null);
                        $designation = !empty($designation) ? $designation : 'Support Staff';
                        $photo = !empty($data->avatar)
                            ? config('app.url') . '/public/upload/image/staff/' . rawurlencode(basename($data->avatar))
                            : config('app.url') . '/public/avatar.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="staff-card">
                            <div class="staff-photo-wrap">
                                <img class="staff-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';this.style.objectFit='contain';this.style.padding='20px';this.style.background='#eef5fb';">
                            </div>
                            <div class="staff-body">
                                <h3 class="staff-name">{{ e($name) }}</h3>
                                <p class="staff-designation">{{ e($designation) }}</p>

                                <div class="staff-contact-line">
                                    <i class="fa fa-phone"></i>
                                    @if(!empty($data->mobile))
                                        <a href="tel:{{ preg_replace('/\s+/', '', $data->mobile) }}">{{ e($data->mobile) }}</a>
                                    @else
                                        <span>Phone not available</span>
                                    @endif
                                </div>

                                <div class="staff-contact-line">
                                    <i class="fa fa-envelope"></i>
                                    @if(!empty($data->email))
                                        <a href="mailto:{{ e($data->email) }}">{{ e($data->email) }}</a>
                                    @else
                                        <span>Email not available</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="staff-empty">No staff records found.</div>
        @endif
    </div>
</div>
@endsection