@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Governing Body
@endsection
@php
$config = App\Models\ServerConfig::first();
$members = $Datakey ?? collect();
@endphp
@section('frontcontent')
<style>
.committee-directory-shell {
    background: linear-gradient(180deg, #fbf8f3 0%, #f8f4ee 100%);
    border: 1px solid #e7dccb;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 12px 28px rgba(88, 58, 24, 0.09);
}

.committee-directory-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 14px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e7dccb;
}

.committee-directory-head .title-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.committee-directory-head .kicker {
    display: inline-flex;
    width: fit-content;
    color: #7e5600;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 5px 10px;
    border-radius: 999px;
    background: #fff2d9;
    border: 1px solid #f1d7a8;
}

.committee-directory-head h2 {
    margin: 0;
    font-size: 22px;
    line-height: 1.2;
    color: #4f3513;
    font-weight: 800;
}

.committee-directory-head p {
    margin: 0;
    color: #7c684f;
    font-size: 14px;
    line-height: 1.6;
}

.committee-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff3dc;
    color: #976100;
    border: 1px solid #f0d8ab;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
    white-space: nowrap;
}

.committee-card {
    height: 100%;
    background: #fff;
    border: 1px solid #eadfce;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(88, 58, 24, 0.08);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.committee-card:hover {
    transform: translateY(-3px);
    border-color: #dec7a1;
    box-shadow: 0 16px 30px rgba(88, 58, 24, 0.14);
}

.committee-photo-wrap {
    display: block;
    background: #faf3e8;
}

.committee-photo {
    width: 100%;
    height: 252px;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform .35s ease;
}

.committee-card:hover .committee-photo {
    transform: scale(1.04);
}

.committee-body {
    padding: 14px;
}

.committee-name {
    margin: 0 0 5px;
    font-size: 17px;
    line-height: 1.25;
    font-weight: 800;
    color: #4f3513;
}

.committee-designation {
    margin: 0 0 10px;
    color: #7d684e;
    font-size: 14px;
    font-weight: 600;
}

.committee-contact-line {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 7px;
    font-size: 13px;
    line-height: 1.4;
    color: #7d684e;
    min-height: 20px;
}

.committee-contact-line i {
    color: #b77a07;
    width: 14px;
    text-align: center;
    flex-shrink: 0;
}

.committee-contact-line a {
    color: #6d583f;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.committee-contact-line a:hover {
    color: #a66700;
}

.committee-empty {
    padding: 20px;
    border-radius: 12px;
    border: 1px dashed #e6d7be;
    background: #fcf8f2;
    text-align: center;
    color: #866e4e;
}

@media (max-width: 991.98px) {
    .committee-photo {
        height: 230px;
    }
}

@media (max-width: 575.98px) {
    .committee-directory-shell {
        padding: 12px;
    }

    .committee-directory-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .committee-directory-head h2 {
        font-size: 18px;
    }

    .committee-photo {
        height: 210px;
    }
}
</style>
<div class="col-12 mt-4">
    <div class="committee-directory-shell">
        <div class="committee-directory-head">
            <div class="title-wrap">
                <span class="kicker">Governance</span>
                <h2>{{ !empty($config->instituteName) ? $config->instituteName : 'Jahanara Ayub Academy' }} Governing Body</h2>
                <p>Professional profile directory of governing members with official designation and contacts.</p>
            </div>
            <span class="committee-count"><i class="fa fa-sitemap"></i> {{ $members->count() }} {{ $members->count() === 1 ? 'Member' : 'Members' }}</span>
        </div>

        @if($members->count() > 0)
            <div class="row">
                @foreach($members as $data)
                    @php
                        $name = !empty($data->fullName)
                            ? $data->fullName
                            : trim(($data->firstName ?? '') . ' ' . ($data->lastName ?? ''));
                        $name = !empty(trim($name)) ? $name : 'Governing Member';
                        $designation = !empty($data->designation) ? $data->designation : 'Member';
                        $photo = !empty($data->avatar)
                            ? config('app.url') . '/public/upload/image/cultivation/' . rawurlencode(basename($data->avatar))
                            : config('app.url') . '/public/avatar.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="committee-card">
                            <div class="committee-photo-wrap">
                                <img class="committee-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';this.style.objectFit='contain';this.style.padding='20px';this.style.background='#faf3e8';">
                            </div>
                            <div class="committee-body">
                                <h3 class="committee-name">{{ e($name) }}</h3>
                                <p class="committee-designation">{{ e($designation) }}</p>

                                <div class="committee-contact-line">
                                    <i class="fa fa-phone"></i>
                                    @if(!empty($data->mobile))
                                        <a href="tel:{{ preg_replace('/\s+/', '', $data->mobile) }}">{{ e($data->mobile) }}</a>
                                    @else
                                        <span>Phone not available</span>
                                    @endif
                                </div>

                                <div class="committee-contact-line">
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
            <div class="committee-empty">No governing member records found.</div>
        @endif
    </div>
</div>
@endsection