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
.teacher-profile{display:grid;grid-template-columns:minmax(0,300px) minmax(0,1fr);gap:20px;align-items:start;color:#17334f}
.tp-identity,.tp-section{background:linear-gradient(145deg,#fff,#fcfeff);border:1px solid #d9e7f2;border-radius:16px;box-shadow:0 8px 26px #17334f0c;overflow:hidden}
.teacher-profile .ts-photo-wrap{padding:18px 18px 0;background:#fff}
.teacher-profile .people-photo-frame--profile{display:block;position:relative;width:100%;height:auto;aspect-ratio:4/5;margin:0;border:0;border-radius:11px;overflow:hidden}
.teacher-profile .people-portrait{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 28%;transform:none}
.tp-identity-body{padding:18px 18px 24px}
.teacher-profile h1.ts-name{font-size:26px;line-height:1.35;font-weight:800;margin:0 0 12px;overflow-wrap:anywhere;color:#17334f}
.tp-role{display:inline-block;font-size:13px;line-height:1.5;padding:5px 9px;border:1px solid #d5e6f3;border-radius:5px;background:#e3eef7;color:#214866;margin:0 0 14px}
.tp-institution{font-size:13px;line-height:1.6;color:#607286;margin:0 0 20px;overflow-wrap:anywhere}
.tp-actions{display:grid;gap:10px}.tp-actions a{display:flex;align-items:center;justify-content:center;min-height:44px;border-radius:6px;padding:10px 14px;background:linear-gradient(110deg,#224a83,#006ebc);color:#fff;font-size:13px;font-weight:650;text-decoration:none}
.teacher-profile .tp-actions a:is(:hover,:focus-visible){background:#07518a;color:#fff!important;border-color:#07518a}
.teacher-profile .tp-actions a:is(:hover,:focus-visible) *{color:inherit!important}
.tp-actions a:focus-visible,.tp-section summary:focus-visible{outline:3px solid #21a7d0!important;outline-offset:3px}
.tp-actions a.tp-back{background:#edf5f9;color:#214866;border:1px solid #d5e2ed}
.tp-details{display:grid;gap:20px;min-width:0}.tp-section{padding:24px}
.teacher-profile .tp-section h2{font-size:19px!important;font-weight:750;line-height:1.4;margin:0 0 20px!important;color:#17334f}
.tp-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 24px;margin:0}
.tp-field{min-width:0}.tp-field dt{font-size:12px;color:#6a8095;font-weight:500;margin-bottom:6px}.tp-field dd{font-size:15px;font-weight:650;color:#17334f;line-height:1.65;margin:0;overflow-wrap:anywhere}.tp-field.tp-wide{grid-column:1/-1}
.tp-section summary{font-size:17px;font-weight:750;cursor:pointer;color:#42647e}.tp-section[open] summary{margin-bottom:22px}
.tp-bio{font-size:15px;color:#526b80;line-height:1.8;white-space:pre-line;overflow-wrap:anywhere;margin:0}
.tp-hero{position:relative;overflow:hidden;padding:24px 30px;margin-bottom:16px;border-radius:14px;background:radial-gradient(ellipse at 85% 10%,#a9d5f5aa,transparent 70%),linear-gradient(120deg,#eff9ff,#dcefff)}
.tp-hero:after{content:none}
.tp-hero .tp-campus{position:absolute;right:0;bottom:0;width:54%;height:100%;opacity:.3;pointer-events:none;mask-image:linear-gradient(90deg,transparent,#000 28%)}
.tp-campus svg{width:100%;height:100%;display:block}
@media(max-width:575px){.tp-hero .tp-campus{width:70%;opacity:.12}}
.tp-hero>*{position:relative;z-index:1}.tp-breadcrumb{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;font-size:12px;color:#6886a0}.tp-breadcrumb a{color:#416b93;text-decoration:none}
.tp-hero h1.ts-name{font-size:32px;line-height:1.25;font-weight:800;margin:0 0 12px;color:#17334f;overflow-wrap:anywhere}.tp-hero .tp-institution{margin-bottom:0}.tp-heading{display:flex;align-items:center;gap:14px;margin-bottom:18px}.tp-heading-icon{display:flex;align-items:center;justify-content:center;flex:none;width:46px;height:46px;border-radius:12px;background:#e0f1ff;color:#0087cd;font-size:21px;font-weight:750}
.teacher-profile .tp-heading h2{margin:0 0 3px!important}.tp-heading p{margin:0;color:#71859b;font-size:12px;line-height:1.5}
.tp-grid{gap:10px}.tp-field{display:flex;align-items:center;gap:12px;background:#f7fbff;border:1px solid #e1edf7;border-radius:10px;padding:12px;min-width:0}.tp-field-icon{display:flex;align-items:center;justify-content:center;flex:none;width:36px;height:36px;border-radius:9px;background:#e3f2ff;color:#0087cd;font-size:18px}.tp-field-content{min-width:0}.tp-field dt{margin-bottom:3px}.tp-field dd{font-size:14px}.tp-section{padding:20px}.tp-actions a{gap:10px}
.tp-personal summary{display:flex;align-items:center;gap:12px;list-style:none}.tp-personal summary:after{content:'⌄';margin-left:auto;color:#214866}.tp-personal[open] summary:after{content:'⌃'}
.tp-heading-icon{background:linear-gradient(135deg,#d8edff,#e7f6ff);border:1px solid #d1e8fa;color:#0079bc;box-shadow:inset 0 1px 0 #ffffffaa}
.tp-field-icon{background:#dcefff;color:#0079bc;border:1px solid #d3e9f9}
.tp-field{background:linear-gradient(120deg,#f4f9ff,#f9fcff);padding:14px 12px;align-items:center}
.tp-section{padding:22px}.tp-details{gap:18px}
@media(max-width:575px){.tp-hero{padding:24px 18px}.tp-hero h1.ts-name{font-size:25px}.tp-hero:after{opacity:.06;width:180px}.tp-heading{gap:10px}.tp-field{padding:10px}.tp-heading-icon{width:38px;height:38px}}
@media(max-width:991px){.teacher-profile{grid-template-columns:minmax(0,1fr);gap:22px}.tp-identity{width:100%;max-width:480px;justify-self:center}}
@media(max-width:575px){.edu-content-wrap .container:has(.teacher-profile){padding-inline:16px}.tp-section,.tp-identity-body{padding:20px}.tp-grid{grid-template-columns:1fr;gap:18px}.teacher-profile h1.ts-name{font-size:24px}}
</style>
<header class="tp-hero">
    <div class="tp-campus" aria-hidden="true">
        <svg viewBox="0 0 600 220" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax slice">
            <defs><linearGradient id="tp-campus-wall" x2="0" y2="1"><stop stop-color="#f9fdff"/><stop offset="1" stop-color="#97bed3"/></linearGradient><pattern id="tp-campus-windows" width="44" height="40" patternUnits="userSpaceOnUse"><rect x="9" y="8" width="25" height="25" fill="#497b98"/><path d="M21 8v25M9 20h25" stroke="#c4dce7" stroke-width="2"/></pattern></defs>
            <path d="M0 210Q140 185 300 205T600 198V220H0Z" fill="#9cbdc5"/>
            <g fill="#79aeb9"><path d="M65 210l5-118h9l5 118Z"/><ellipse cx="73" cy="80" rx="40" ry="46"/><ellipse cx="49" cy="101" rx="29" ry="28"/><path d="M507 210l5-124h10l6 124Z"/><ellipse cx="516" cy="69" rx="47" ry="49"/><ellipse cx="551" cy="97" rx="34" ry="35"/></g>
            <path d="M125 90L421 51l50 27v137H125Z" fill="url(#tp-campus-wall)"/>
            <path d="M125 90L421 51v164H125Z" fill="#e3f1f7"/>
            <path d="M139 106L405 72v124H139Z" fill="url(#tp-campus-windows)"/>
            <g stroke="#edf7fb" stroke-width="9"><path d="M137 92v118M194 85v125M251 77v133M308 70v140M365 63v147M421 55v155"/><path d="M128 132l292-27M128 172l292-14"/></g>
            <path d="M118 86L425 44l53 28-7 8-50-20-297 38Z" fill="#8aaebf"/><path d="M252 60V36l72-8v23" fill="#eaf4f8"/><path d="M247 36l80-10" stroke="#87a9ba" stroke-width="6"/>
            <path d="M269 214v-43h42v43" fill="#487793"/><path d="M252 215h74l16 5h-105Z" fill="#a7c5d4"/>
        </svg>
    </div>
    <nav class="tp-breadcrumb" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a><span aria-hidden="true">›</span><a href="{{ route($directoryRoute) }}">{{ $directoryLabel }}</a><span aria-hidden="true">›</span><span>{{ $name }}</span></nav>
    <h1 class="ts-name">{{ $name }}</h1>
    <p class="tp-role">{{ $designation }}</p>
    @if(!empty($config->instituteName))<p class="tp-institution">{{ $config->instituteName }}</p>@endif
</header>
<article class="teacher-profile teacher-single-page">
    <aside class="tp-identity">
        <div class="ts-photo-wrap"><x-people-portrait :src="$photo" :alt="$name" variant="profile" image-class="ts-photo" /></div>
        <div class="tp-identity-body">
            <nav class="tp-actions" aria-label="Teacher actions">
                @if(!empty($contact['Email']))<a href="mailto:{{ $contact['Email'] }}"><span aria-hidden="true">✉</span>Send Email</a>@endif
                @if(!empty($contact['Phone']))<a href="tel:{{ preg_replace('/\s+/', '', $contact['Phone']) }}"><span aria-hidden="true">☎</span>{{ $profileKind === 'teacher' ? 'Call Teacher' : 'Call' }}</a>@endif
                <a class="tp-back" href="{{ route($directoryRoute) }}">← Back to {{ $directoryLabel }}</a>
            </nav>
        </div>
    </aside>
    <div class="tp-details">
        <section class="tp-section" aria-labelledby="tp-overview"><div class="tp-heading"><span class="tp-heading-icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/></svg></span><div><h2 id="tp-overview">{{ $isCommittee ? 'Leadership Information' : 'Professional Information' }}</h2></div></div><dl class="tp-grid">
            @foreach($overview as $label=>$value)<div class="tp-field"><span class="tp-field-icon" aria-hidden="true">{{ ['Designation'=>'▣','Joining Date'=>'▦'][$label] }}</span><div class="tp-field-content"><dt>{{ $label }}</dt><dd>{{ $value ?? '-' }}</dd></div></div>@endforeach
        </dl></section>
        @if(count($contact))<section class="tp-section" aria-labelledby="tp-contact"><div class="tp-heading"><span class="tp-heading-icon" aria-hidden="true">☎</span><div><h2 id="tp-contact">Contact Information</h2></div></div><dl class="tp-grid">
            @foreach($contact as $label=>$value)<div class="tp-field {{ $label === 'Address' ? 'tp-wide' : '' }}"><span class="tp-field-icon" aria-hidden="true">{{ ['Email'=>'✉','Phone'=>'☎','Address'=>'⌖'][$label] }}</span><div class="tp-field-content"><dt>{{ $label }}</dt><dd>{{ $value ?? '-' }}</dd></div></div>@endforeach
        </dl></section>@endif
        @if(count($personal))<details class="tp-section tp-personal"><summary><span class="tp-heading-icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/></svg></span>Personal Information</summary><dl class="tp-grid">
            @foreach($personal as $label=>$value)<div class="tp-field"><dt>{{ $label }}</dt><dd>{{ $value ?? '-' }}</dd></div>@endforeach
        </dl></details>@endif
        @if($bio)<section class="tp-section" aria-labelledby="tp-about"><div class="tp-heading"><span class="tp-heading-icon" aria-hidden="true">▤</span><div><h2 id="tp-about">{{ $profileKind === 'teacher' ? 'About Teacher' : 'About' }}</h2></div></div><p class="tp-bio">{{ $bio }}</p></section>@endif
        @if($message)<section class="tp-section" aria-labelledby="tp-message"><h2 id="tp-message">Official Message</h2><p class="tp-bio">{{ $message }}</p></section>@endif
    </div>
</article>
@endsection
