@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', ucfirst($profileKind ?? 'teacher').' Profile')
@section('portal-layout', '1')
@section('frontcontent')
@php
    $config = \App\Models\ServerConfig::orderByDesc('id')->first();
    $profileKind = $profileKind ?? 'teacher';
    $isCommittee = $profileKind === 'committee';
    $isStaff = $profileKind === 'staff';
    $directoryRoute = $isCommittee ? 'comitteePage' : ($isStaff ? 'staffPage' : 'teacherPage');
    $directoryLabel = $isCommittee ? 'Governing Body' : ($isStaff ? 'Staff' : 'Teachers');
    $name = trim($teacher->fullName ?? '');
    if ($name === '') {
        $name = trim($teacher->firstName ?? '');
        $last = trim($teacher->lastName ?? '');
        if ($last !== '' && !preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $name)) $name = trim($name.' '.$last);
    }
    $name = $name ?: '-';
    $designation = $isCommittee ? (trim($teacher->designation ?? '') ?: '-') : ($isStaff ? (!empty($teacher->designation) ? \App\Models\StaffManagement::getDesignationName($teacher->designation) : '-') : \App\Models\TeacherManagement::getDesignationName($teacher->designation ?? null));
    $media = app(\App\Services\PublicMediaUrl::class);
    $photo = $isCommittee ? $media->institutionAboutImage($teacher->avatar ?? null) : ($isStaff ? $media->staffPortrait($teacher->avatar ?? null) : $media->teacherPortrait($teacher->avatar ?? null));
    $pick = function (array $keys) use ($teacher) {
        foreach ($keys as $key) {
            $value = $teacher->{$key} ?? null;
            if ($value !== null && trim((string) $value) !== '') return $value;
        }
        return null;
    };
    $gender = $pick(['gender']);
    $gender = ['1'=>'Male','2'=>'Female','3'=>'Other'][$gender ?? ''] ?? $gender;
    $religion = $pick(['religion','relegion']);
    $religion = ['1'=>'Islam','2'=>'Hindu','3'=>'Christian','4'=>'Buddhist','5'=>'Other'][$religion ?? ''] ?? $religion;
    $blood = $pick(['bloodGroup','blood_group','blGroup','blgroup']);
    $blood = ['1'=>'A+','2'=>'A-','3'=>'B+','4'=>'B-','5'=>'O+','6'=>'O-','7'=>'AB+','8'=>'AB-'][$blood ?? ''] ?? $blood;
    $overview = [
        'Designation'=>$designation,
        'Joining Date'=>$pick(['joinDate','joining_date','joiningDate','join_date']),
    ];
    if ($isCommittee) $overview = ['Designation'=>$designation];
    if ($isStaff && !$overview['Joining Date']) unset($overview['Joining Date']);
    $contact = ['Email'=>$pick(['email']), 'Phone'=>$pick(['mobile','phone','phoneNumber','phone_number']), 'Address'=>$pick(['address'])];
    $personal = ['Blood Group'=>$blood,'Gender'=>$gender,'Religion'=>$religion];
    if ($profileKind !== 'teacher') {
        $contact = array_filter($contact, fn ($value) => $value !== null);
        $personal = array_filter($personal, fn ($value) => $value !== null);
    }
    $bio = $pick(['description']);
    $message = $isCommittee && in_array(strtolower($designation), ['chairman','president'], true) ? $pick(['message']) : null;
@endphp
@php
    // View-model for the dossier layout (derived from the variables above; nothing above is modified).
    if ($isCommittee) { $accent = '#12263D'; $accentTint = '#E7EDF2'; $eyebrow = 'GOVERNING BODY MEMBER'; }
    elseif ($isStaff) { $accent = '#92400E'; $accentTint = '#FDF0D5'; $eyebrow = 'STAFF MEMBER'; }
    else { $accent = '#1B3A5C'; $accentTint = '#E7EDF2'; $eyebrow = 'FACULTY MEMBER'; }
    $nameParts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY);
    $initials = $name === '-' ? '?' : mb_strtoupper(mb_substr($nameParts[0] ?? '?', 0, 1).(count($nameParts) > 1 ? mb_substr(end($nameParts), 0, 1) : ''));
    $hasPhoto = !empty($photo) && $photo !== app(\App\Services\PublicAssetUrl::class)->url('avatar.png');
    $facts = [];
    foreach (array_merge($overview, $contact) as $label => $value) {
        if ($value === null || trim((string) $value) === '' || trim((string) $value) === '-') continue;
        $facts[] = ['label' => $label, 'value' => $value];
    }
    $personalFacts = array_filter($personal, fn ($v) => $v !== null && trim((string) $v) !== '');
@endphp
<style>
html:has(.teacher-profile),body:has(.teacher-profile){height:auto;overflow-x:clip;overflow-y:visible}
.edu-content-wrap:has(.teacher-profile){min-height:0;padding-top:20px;padding-bottom:28px}
body:has(.teacher-profile) #rs-footer .footer-main-row{display:grid;grid-template-columns:1.35fr 1fr 1fr 1fr;gap:24px;margin:0}
body:has(.teacher-profile) #rs-footer .footer-main-row>.footer-widget{width:auto;max-width:none;padding:0;margin-bottom:0}
body:has(.teacher-profile) #rs-footer .container{width:100%;max-width:1120px;margin-inline:auto;padding-inline:24px}
@media(max-width:991px){body:has(.teacher-profile) #rs-footer .footer-main-row{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:575px){body:has(.teacher-profile) #rs-footer .footer-main-row{grid-template-columns:minmax(0,1fr)}}
.edu-content-wrap .container:has(.teacher-profile){max-width:1120px;width:100%;margin-inline:auto;padding-inline:24px}
.edu-main-card:has(.teacher-profile),.edu-main-inner:has(.teacher-profile){border:0;box-shadow:none;background:transparent;padding:0}
.edu-main-inner:has(.teacher-profile)>.row{margin-left:0;margin-right:0;--bs-gutter-x:0}

.teacher-profile{--ac:#1B3A5C;--tint:#E7EDF2;--line:#ECE4D1;--muted:#8A8276;--ink:#262A30;box-sizing:border-box;flex:0 0 100%;width:100%;max-width:100%;padding:0;font-family:'Inter',system-ui,-apple-system,'Segoe UI',sans-serif;color:var(--ink)}
.teacher-profile *{box-sizing:border-box}
.tpd-back{display:block;width:fit-content;margin:0 0 12px;font-size:13px;color:var(--muted)!important;text-decoration:none!important}
.tpd-back:hover{color:var(--ac)!important}
.tpd-back:focus-visible{outline:3px solid rgba(146,64,14,.4);outline-offset:3px;border-radius:4px}
.tpd-eyebrow{display:inline-block;margin:0 0 18px;padding:4px 12px;border-radius:999px;background:var(--tint);color:var(--ac);font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase}
.tpd-grid{display:grid;grid-template-columns:300px minmax(0,1fr);gap:24px;align-items:start}
.tpd-side{overflow:hidden;background:#fff;border:1px solid var(--line);border-top:6px solid var(--ac);border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.06)}
.tpd-side-body{padding:24px;text-align:center}
.tpd-photo{position:relative;display:flex;align-items:center;justify-content:center;width:140px;height:170px;margin:0 auto;overflow:hidden;border:1px solid var(--line);border-radius:10px;background:var(--ac);color:#fff;font:700 44px/1 'Source Serif 4',Georgia,serif}
.tpd-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 25%;background:#eef2f6}
.edu-main-inner .teacher-profile h1.tpd-name{margin:14px 0 0;font-family:'Source Serif 4',Georgia,serif;font-size:20px;font-weight:700;line-height:1.3;color:var(--ac);overflow-wrap:anywhere}
.tpd-role{margin:6px 0 0;font-size:13px;color:#5C564A}
.tpd-inst{margin:4px 0 0;font-size:12px;color:var(--muted)}
.tpd-rule{height:0;margin:16px 0;border:0;border-top:1px solid var(--line)}
.tpd-actions{display:flex;flex-direction:column;gap:8px}
.tpd-btn{display:flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:8px;border:1.5px solid var(--ac);font-size:13px;font-weight:600;line-height:1.4;text-decoration:none!important;transition:background .2s,color .2s}
.tpd-btn{transition:background-color .2s ease,color .2s ease,box-shadow .2s ease,transform .15s ease,filter .2s ease}
.tpd-btn--solid{background:var(--ac);color:#fff!important;box-shadow:0 2px 6px color-mix(in srgb,var(--ac) 35%,transparent)}
.tpd-btn--solid:hover,.tpd-btn--solid:focus-visible{filter:brightness(1.18);box-shadow:0 5px 12px color-mix(in srgb,var(--ac) 40%,transparent);transform:translateY(-1px)}
.tpd-btn--outline{background:transparent;color:var(--ac)!important}
.tpd-btn--outline:hover,.tpd-btn--outline:focus-visible{background:var(--ac);color:#fff!important;box-shadow:0 5px 12px color-mix(in srgb,var(--ac) 30%,transparent);transform:translateY(-1px)}
.tpd-btn:focus-visible{outline:3px solid rgba(146,64,14,.4);outline-offset:3px}
.tpd-facts{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));column-gap:28px;margin:0;text-align:left}
.tpd-fact{min-width:0;padding:12px 0;border-bottom:1px solid #F2EEE3}
.tpd-fact dt{margin:0 0 3px;font-size:11px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)}
.tpd-fact dd{margin:0;font-size:14px;line-height:1.5;color:var(--ink);overflow-wrap:anywhere}
.tpd-main{display:flex;flex-direction:column;gap:16px;min-width:0}
.tpd-card{padding:22px;background:#fff;border:1px solid var(--line);border-radius:12px}
.tpd-card--raised{box-shadow:0 4px 16px rgba(0,0,0,.06)}
.edu-main-inner .teacher-profile .tpd-card h2{position:relative;margin:0 0 16px!important;padding-bottom:10px;font-family:'Inter',system-ui,sans-serif;font-size:15px;font-weight:700;line-height:1.3;color:var(--ac)}
.edu-main-inner .teacher-profile .tpd-card h2:after{content:'';position:absolute;left:0;bottom:0;width:28px;height:2px;background:var(--ac)}
.tpd-bio{margin:0;font-size:15px;line-height:1.75;color:#4a4f57;white-space:pre-line;overflow-wrap:anywhere}
.tpd-pairs{display:flex;flex-wrap:wrap;row-gap:8px;margin:0;padding:0;list-style:none;font-size:14px}
.tpd-pairs li{padding:0 16px;border-left:1px solid var(--line)}
.tpd-pairs li:first-child{padding-left:0;border-left:0}
.tpd-pairs b{font-weight:600;color:var(--muted)}
.tpd-message{position:relative;padding:24px 24px 22px;border:1px solid var(--line);border-left:4px solid var(--ac);border-radius:12px;background:color-mix(in srgb,var(--tint) 30%,#fff)}
.edu-main-inner .teacher-profile .tpd-message .tpd-q{display:block!important;height:22px;font-family:'Source Serif 4',Georgia,serif!important;font-size:44px!important;font-weight:400;font-style:normal;line-height:.5!important;color:var(--ac)!important;opacity:.5}
.tpd-message blockquote{margin:0;padding:0;border:0;background:none;box-shadow:none;font-family:'Source Serif 4',Georgia,serif;font-size:16px;font-style:italic;line-height:1.8;color:var(--ink);white-space:pre-line;overflow-wrap:anywhere}
.tpd-message blockquote:before,.tpd-message blockquote:after{content:none!important;display:none!important}
.tpd-message .tpd-mrule{width:32px;height:2px;margin:18px 0 12px;background:var(--ac)}
.tpd-attr{margin:0;font-size:11px;color:var(--muted)}
.tpd-attr strong{display:block;margin-bottom:2px;font-size:12px;letter-spacing:.05em;text-transform:uppercase;color:var(--ac)}
@media(max-width:767px){.tpd-grid{grid-template-columns:minmax(0,1fr)}}
@media(max-width:575px){.edu-content-wrap .container:has(.teacher-profile){padding-inline:16px}.tpd-pairs li{padding:0 12px}}
@media(prefers-reduced-motion:reduce){.tpd-btn{transition:none;transform:none!important}}
</style>
<article class="teacher-profile teacher-single-page" style="--ac:{{ $accent }};--tint:{{ $accentTint }}">
    <a class="tpd-back" href="{{ route($directoryRoute) }}">← Back to {{ $directoryLabel }}</a>
    <span class="tpd-eyebrow">{{ $eyebrow }}</span>
    <div class="tpd-grid">
        <aside class="tpd-side" aria-label="{{ $name }} summary">
            <div class="tpd-side-body">
                <div class="tpd-photo" aria-hidden="{{ $hasPhoto ? 'false' : 'true' }}">
                    @if($hasPhoto)<img src="{{ $photo }}" alt="{{ $name }}" decoding="async" onerror="this.remove()">@endif
                    <span>{{ $initials }}</span>
                </div>
                <h1 class="tpd-name">{{ $name }}</h1>
                <p class="tpd-role">{{ $designation }}</p>
                @if(!empty($config->instituteName))<p class="tpd-inst">{{ $config->instituteName }}</p>@endif
                @if(!empty($contact['Email']) || !empty($contact['Phone']))
                    <hr class="tpd-rule">
                    <nav class="tpd-actions" aria-label="{{ ucfirst($profileKind) }} actions">
                        @if(!empty($contact['Email']))<a class="tpd-btn tpd-btn--solid" href="mailto:{{ $contact['Email'] }}">Send Email</a>@endif
                        @if(!empty($contact['Phone']))<a class="tpd-btn tpd-btn--outline" href="tel:{{ preg_replace('/\s+/', '', $contact['Phone']) }}">Call</a>@endif
                    </nav>
                @endif
            </div>
        </aside>
        <div class="tpd-main">
            <section class="tpd-card tpd-card--raised" aria-labelledby="tp-facts"><h2 id="tp-facts">Quick Facts</h2>
                <dl class="tpd-facts">
                    @forelse($facts as $fact)<div class="tpd-fact"><dt>{{ $fact['label'] }}</dt><dd>{{ $fact['value'] }}</dd></div>
                    @empty<div class="tpd-fact"><dt>Designation</dt><dd>—</dd></div>@endforelse
                </dl>
            </section>
            @if($bio)
                <section class="tpd-card" aria-labelledby="tp-about"><h2 id="tp-about">About</h2><p class="tpd-bio">{{ $bio }}</p></section>
            @endif
            @if(count($personalFacts))
                <section class="tpd-card" aria-labelledby="tp-personal"><h2 id="tp-personal">Personal Information</h2>
                    <ul class="tpd-pairs">@foreach($personalFacts as $label => $value)<li><b>{{ $label }}:</b> {{ $value }}</li>@endforeach</ul>
                </section>
            @endif
            @if($isCommittee && $message)
                <section class="tpd-message" aria-label="Official Message">
                    <span class="tpd-q" aria-hidden="true">&ldquo;</span>
                    <blockquote>{{ $message }}</blockquote>
                    <div class="tpd-mrule" aria-hidden="true"></div>
                    <p class="tpd-attr"><strong>{{ $name }}</strong>{{ $designation }}</p>
                </section>
            @endif
        </div>
    </div>
</article>
@endsection
