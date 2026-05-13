@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Former Head of Institution List
@endsection
@php
$config = App\Models\ServerConfig::first();
$principals = $Datakey ?? collect();
@endphp
@section('frontcontent')
<style>
.exprincipal-shell {
    background: linear-gradient(180deg, #f7faf5 0%, #eef7f0 100%);
    border: 1px solid #d5e5d8;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 12px 28px rgba(27, 67, 49, 0.08);
}

.exprincipal-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 14px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #d5e5d8;
}

.exprincipal-head .title-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.exprincipal-head .kicker {
    display: inline-flex;
    width: fit-content;
    color: #21613f;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 5px 10px;
    border-radius: 999px;
    background: #e2f4e7;
    border: 1px solid #bfe5cb;
}

.exprincipal-head h2 {
    margin: 0;
    font-size: 22px;
    line-height: 1.2;
    color: #1f4932;
    font-weight: 800;
}

.exprincipal-head p {
    margin: 0;
    color: #5e7767;
    font-size: 14px;
    line-height: 1.6;
}

.exprincipal-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f8ec;
    color: #21613f;
    border: 1px solid #bfe5cb;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
    white-space: nowrap;
}

.exprincipal-card {
    height: 100%;
    background: #fff;
    border: 1px solid #d8e8dc;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(27, 67, 49, 0.08);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.exprincipal-card:hover {
    transform: translateY(-3px);
    border-color: #bdd8c5;
    box-shadow: 0 16px 30px rgba(27, 67, 49, 0.13);
}

.exprincipal-photo-wrap {
    background: #edf6ef;
    padding: 18px 18px 8px;
    display: flex;
    justify-content: center;
}

.exprincipal-photo {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    border: 4px solid #d8ebde;
    object-fit: cover;
    object-position: center top;
    background: #fff;
}

.exprincipal-body {
    padding: 12px 14px 14px;
    text-align: center;
}

.exprincipal-name {
    margin: 0;
    font-size: 17px;
    line-height: 1.25;
    font-weight: 800;
    color: #1f4932;
}

.exprincipal-role {
    margin: 6px 0 10px;
    color: #597565;
    font-size: 13px;
    font-weight: 600;
}

.exprincipal-period {
    margin: 0 auto;
    max-width: 280px;
    border: 1px dashed #bfd9c7;
    border-radius: 11px;
    background: #f7fbf8;
    padding: 10px;
}

.exprincipal-period .label {
    display: block;
    color: #597565;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
    margin-bottom: 4px;
}

.exprincipal-period .value {
    color: #1f4932;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.4;
}

.exprincipal-empty {
    padding: 20px;
    border-radius: 12px;
    border: 1px dashed #bcd8c4;
    background: #f6fbf7;
    text-align: center;
    color: #5e7767;
}

@media (max-width: 575.98px) {
    .exprincipal-shell {
        padding: 12px;
    }

    .exprincipal-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .exprincipal-head h2 {
        font-size: 18px;
    }
}

</style>
<div class="col-12 mt-4">
    <div class="exprincipal-shell">
        <div class="exprincipal-head">
            <div class="title-wrap">
                <span class="kicker">Institution Legacy</span>
                <h2>{{ !empty($config->instituteName) ? $config->instituteName : 'Jahanara Ayub Academy' }} Former Heads of Institution</h2>
                <p>Leadership history honoring the heads of institution who served over the years.</p>
            </div>
            <span class="exprincipal-count"><i class="fa fa-history"></i> {{ $principals->count() }} {{ $principals->count() === 1 ? 'Profile' : 'Profiles' }}</span>
        </div>

        @if($principals->count() > 0)
            <div class="row">
                @foreach($principals as $data)
                    @php
                        $name = !empty($data->fullName) ? $data->fullName : 'Former Head of Institution';
                        $start = !empty($data->startFrom) ? $data->startFrom : 'Unknown';
                        $end = !empty($data->endTo) ? $data->endTo : 'Present';
                        $photo = !empty($data->avatar)
                            ? config('app.url') . '/public/upload/image/exPrincipal/' . rawurlencode(basename($data->avatar))
                            : config('app.url') . '/public/avatar.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="exprincipal-card">
                            <div class="exprincipal-photo-wrap">
                                <img class="exprincipal-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';this.style.objectFit='contain';this.style.padding='12px';this.style.background='#fff';">
                            </div>
                            <div class="exprincipal-body">
                                <h3 class="exprincipal-name">{{ e($name) }}</h3>
                                <p class="exprincipal-role">Former Head of Institution</p>
                                <div class="exprincipal-period">
                                    <span class="label">Tenure</span>
                                    <span class="value">{{ e($start) }} - {{ e($end) }}</span>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="exprincipal-empty">No former head of institution records found.</div>
        @endif
    </div>
</div>
@endsection