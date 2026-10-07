@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Our Teachers')
@section('portal-layout', '1')
@php
    $teachers = $Datakey ?? collect();
@endphp
@section('frontcontent')
<style>
.edu-content-wrap .container:has(.teacher-directory-shell){width:100%;max-width:1320px;padding-inline:24px}
.edu-main-card:has(.teacher-directory-shell){border:0;box-shadow:none;background:transparent;padding:0}
.edu-main-inner:has(.teacher-directory-shell){padding:0}
.teacher-directory-shell{width:100%;min-width:0;padding:8px 0 24px}
.teacher-directory-head{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:30px;padding:22px 0 26px;border-bottom:1px solid #d9e6ef}
.faculty-eyebrow{display:flex;align-items:center;gap:10px;margin-bottom:10px;color:#087e9a;font-size:11px;font-weight:750;letter-spacing:.18em}
.faculty-eyebrow:before{content:'';width:28px;height:2px;background:#21a7d0}
.teacher-directory-head h1{margin:0 0 10px;color:#17334f;font-size:38px;font-weight:800;line-height:1.15;letter-spacing:-.025em}
.teacher-directory-head p{margin:0;color:#607286;font-size:15px;line-height:1.65}
.teacher-count{flex:none;font-size:12px;color:#42647e;padding:8px 12px;border:1px solid #dce6ee;border-radius:20px;background:#fff;white-space:nowrap}
.teacher-directory-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));grid-auto-rows:1fr;gap:24px;width:100%}
.teacher-directory-shell .teacher-card{display:flex;flex-direction:column;min-width:0;height:100%;background:#fff;border:1px solid #dce6ee;border-radius:16px;overflow:hidden;box-shadow:0 6px 22px #17334f09;transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease}
.teacher-directory-shell .teacher-card:hover,.teacher-directory-shell .teacher-card:focus-within{transform:translateY(-4px);border-color:#8ab8cf;box-shadow:0 14px 32px #17334f18}
.teacher-directory-shell .teacher-photo-wrap.people-photo-link{position:relative;display:block;padding:0;background:#eef3f7}
.teacher-directory-shell .teacher-photo-wrap:after{content:'';position:absolute;inset:55% 0 0;background:linear-gradient(transparent,#102d462b);pointer-events:none}
.teacher-directory-shell .people-photo-frame--directory{display:block;width:calc(100% - 12px);height:auto;aspect-ratio:4/5;margin:6px auto 0;border:0;border-radius:11px;box-shadow:none}
.teacher-directory-shell .people-photo-frame--directory img.people-portrait{position:absolute;inset:0;width:100%;height:100%}
.teacher-directory-shell .teacher-body{display:flex;flex-direction:column;flex:1;min-width:0;padding:20px}
.teacher-directory-shell .teacher-body h2.teacher-name{margin:0 0 10px;font-size:18px;font-weight:750;line-height:1.4;color:#17334f;overflow-wrap:anywhere}
.teacher-directory-shell .teacher-designation{align-self:flex-start;margin:0 0 18px;padding:4px 9px;border-radius:5px;background:#eaf5f8;color:#27647b;font-size:12px;line-height:1.5;overflow-wrap:anywhere;max-width:100%}
.teacher-directory-shell .teacher-actions{margin-top:auto;padding-top:12px;border-top:1px solid #edf2f6}
.teacher-directory-shell .teacher-action-btn{display:flex;align-items:center;justify-content:space-between;min-height:42px;width:100%;padding:10px 12px;border:1px solid #d5e2ed;border-radius:6px;background:#fff;color:#214866;font-size:13px;font-weight:650;line-height:1.4;text-decoration:none}
.teacher-directory-shell .teacher-action-btn:after{content:'\2192';font-size:18px;line-height:1}
.teacher-directory-shell .teacher-action-btn:hover{background:#173f70;border-color:#173f70;color:#fff}
.teacher-directory-shell .teacher-action-btn:focus-visible{outline:3px solid #21a7d0!important;outline-offset:3px}
.teacher-empty{padding:32px;border:1px dashed #d5e2ed;border-radius:12px;color:#607286;text-align:center}
.faculty-toolbar{display:flex;flex-wrap:wrap;align-items:end;gap:14px;padding:16px;margin-bottom:24px;border:1px solid #dce6ee;border-radius:12px;background:#fff}
.faculty-toolbar label{display:block;margin-bottom:6px;font-size:12px;font-weight:650;color:#42647e}
.faculty-search{flex:2;min-width:180px}.faculty-filter{flex:1;min-width:160px}
.faculty-toolbar input,.faculty-toolbar select{width:100%;height:44px;border:1px solid #cbdce7;border-radius:6px;padding:8px 12px;background:#fff;color:#17334f;font:inherit}
.faculty-toolbar button{height:44px;padding:8px 18px;border:1px solid #cbdce7;border-radius:6px;background:#edf5f9;color:#214866;font-weight:650;cursor:pointer}
.faculty-toolbar :focus-visible{outline:3px solid #21a7d0!important;outline-offset:2px}
.teacher-directory-shell [hidden]{display:none!important}
.teacher-directory-shell{background:linear-gradient(135deg,#edf8ff 0%,#f6fbff 65%,#f4f9fc 100%);padding-top:0}
.teacher-directory-head{position:relative;overflow:hidden;margin:0;padding:36px 28px 38px;border:0;background:radial-gradient(ellipse at 95% 15%,#8fcaf580,transparent 65%),linear-gradient(120deg,#f0f9ff,#e3f3ff);border-radius:14px 14px 0 0}
.teacher-directory-head>div{position:relative;z-index:1}
.teacher-directory-head:after{content:'';position:absolute;right:20px;bottom:0;width:300px;height:205px;opacity:.19;background:repeating-linear-gradient(90deg,transparent 0 28px,#37749a 28px 40px),repeating-linear-gradient(0deg,#c8e3f3 0 24px,#37749a 24px 32px);clip-path:polygon(0 28%,45% 28%,45% 12%,68% 0,100% 12%,100% 100%,0 100%);filter:drop-shadow(-12px 8px 12px #39779833)}
.teacher-directory-head h1{font-size:44px}.teacher-directory-head h1 span{color:#0878ca}
.faculty-lead{font-weight:650;font-size:17px!important;color:#5d7c9b!important;margin-bottom:5px!important}
.faculty-benefits{display:flex;flex-wrap:wrap;gap:28px;margin-top:20px;color:#183955;font-size:13px;font-weight:700}
.faculty-benefits span{display:flex;align-items:center;gap:9px}.faculty-benefits i{display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:#d8efff;color:#007bbd;font-style:normal}
.faculty-toolbar{position:relative;align-items:center;box-shadow:0 8px 28px #17334f0d;border-color:#e5eff6;border-radius:16px;padding:20px 22px;margin-bottom:22px;gap:18px}
.faculty-total{display:flex;align-items:center;gap:10px;min-width:215px;color:#607c98;font-size:12px}.faculty-total strong{font-size:28px;color:#17334f}.faculty-total small{display:block;font-size:11px}
.faculty-toolbar label{position:absolute;width:1px;height:1px;padding:0;overflow:hidden;clip-path:inset(50%)}
.faculty-toolbar input,.faculty-toolbar select{font-size:13px}
#faculty-results{position:absolute;width:1px;height:1px;overflow:hidden;clip-path:inset(50%)}
.teacher-directory-grid{gap:22px}
.teacher-directory-shell .teacher-body{padding:24px 18px}
.teacher-directory-shell .teacher-body h2.teacher-name{margin-bottom:16px;line-height:1.45}
.teacher-directory-shell .teacher-designation{margin-bottom:20px;padding:5px 9px;background:#e3eef7;color:#214866;border:1px solid #d5e6f3}
.teacher-directory-shell .teacher-actions{border:0;padding-top:0}
.teacher-directory-shell .teacher-action-btn{justify-content:center;gap:8px;background:linear-gradient(110deg,#224a83,#006ebc);border:0;color:#fff;min-height:46px;padding:12px 14px;transition:background .2s ease,box-shadow .2s ease}
.teacher-directory-shell .teacher-action-btn:hover{background:#07518a}
.faculty-head-badge{position:absolute;z-index:2;right:10px;top:10px;padding:5px 9px;border-radius:20px;background:#ffe082;color:#614900;font-size:10px;font-weight:750}
@media(max-width:899px){.teacher-directory-head:after{width:180px;opacity:.07}.teacher-directory-head h1{font-size:36px}.faculty-total{width:100%}}
@media(max-width:575px){.teacher-directory-head{padding:22px 16px}.teacher-directory-head h1{font-size:30px}.faculty-benefits{gap:12px;font-size:11px}.faculty-benefits i{width:26px;height:26px}.faculty-toolbar{padding:14px}.faculty-search,.faculty-filter{flex-basis:100%;min-width:0}}
@media(max-width:1199px){.teacher-directory-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}}
@media(max-width:899px){.teacher-directory-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:575px){.edu-content-wrap .container:has(.teacher-directory-shell){padding-inline:16px}.teacher-directory-grid{grid-template-columns:minmax(0,1fr);gap:20px}.teacher-directory-head{align-items:flex-start;flex-direction:column;gap:12px;margin-bottom:22px}.teacher-directory-head h1{font-size:25px}.teacher-directory-shell .teacher-name{min-height:0}.teacher-directory-shell .teacher-body{padding:22px 20px}}
@media(prefers-reduced-motion:reduce){.teacher-directory-shell .teacher-card{transition:none}.teacher-directory-shell .teacher-card:hover{transform:none}}
</style>
<section class="teacher-directory-shell" aria-labelledby="teachers-heading">
    <header class="teacher-directory-head">
        <div>
            <span class="faculty-eyebrow">OUR TEACHERS</span>
            <h1 id="teachers-heading">Meet Our <span>Teachers.</span></h1>
            <p class="faculty-lead">Dedicated educators. Brighter futures.</p>
            <p>Our teachers inspire, guide and support every student to achieve their full potential.</p>
            <div class="faculty-benefits"><span><i aria-hidden="true">◇</i>Quality Education</span><span><i aria-hidden="true">◎</i>Student Centred</span><span><i aria-hidden="true">✦</i>Brighter Future</span></div>
        </div>
    </header>
    @if($teachers->isNotEmpty())
        <form class="faculty-toolbar" id="faculty-toolbar" role="search" aria-label="Filter teachers">
            <div class="faculty-total"><strong>{{ $teachers->count() }}</strong><span>Teachers<small>in our academic team</small></span></div>
            <div class="faculty-search"><label for="faculty-search">Search by name or designation</label><input id="faculty-search" type="search" placeholder="Search by name or designation…" autocomplete="off"></div>
            <div class="faculty-filter"><label for="faculty-designation">Designation</label><select id="faculty-designation"><option value="">All designations</option></select></div>
            <button type="reset">Reset</button>
        </form>
        <p id="faculty-results" role="status" aria-live="polite" class="teacher-count" style="display:inline-block;margin-bottom:20px">Showing all teachers</p>
        <div class="teacher-directory-grid">
            @foreach($teachers as $data)
                @php
                    $name = trim((string) ($data->fullName ?? ''));
                    if ($name === '') {
                        $name = trim((string) ($data->firstName ?? ''));
                        $surname = trim((string) ($data->lastName ?? ''));
                        if ($surname !== '' && !preg_match('/(?:^|\s)'.preg_quote($surname, '/').'$/iu', $name)) {
                            $name = trim($name.' '.$surname);
                        }
                    }
                    $name = $name !== '' ? $name : 'Unknown';
                    $designation = \App\Models\TeacherManagement::getDesignationName($data->designation ?? null);
                    $photo = app(\App\Services\PublicMediaUrl::class)->teacherPortrait($data->avatar)
                        ?: app(\App\Services\PublicAssetUrl::class)->url('avatar.png');
                @endphp
                <article class="teacher-card" data-faculty-name="{{ $name }}" data-faculty-designation="{{ $designation }}">
                    <div class="teacher-photo-wrap people-photo-link">
                        <x-people-portrait :src="$photo" :alt="$name" image-class="teacher-photo" />
                        @if(strcasecmp(trim($designation), 'Head Master') === 0)
                            <span class="faculty-head-badge">Head Master</span>
                        @endif
                    </div>
                    <div class="teacher-body">
                        <h2 class="teacher-name">{{ $name }}</h2>
                        <p class="teacher-designation">{{ $designation }}</p>
                        <div class="teacher-actions">
                            <a class="teacher-action-btn" href="{{ route('teacher.show', ['id' => $data->id]) }}" aria-label="View {{ $name }} profile">View Profile</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div id="faculty-no-results" class="teacher-empty" hidden>No teachers match your search. Try another name or reset the filters.</div>
    @else
        <div class="teacher-empty">No teacher records found.</div>
    @endif
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('faculty-toolbar');
    if (!form) return;
    const search = document.getElementById('faculty-search');
    const designation = document.getElementById('faculty-designation');
    const cards = [...document.querySelectorAll('.teacher-directory-shell .teacher-card')];
    const grid = document.querySelector('.teacher-directory-grid');
    [...new Set(cards.map(card => card.dataset.facultyDesignation))].sort().forEach(value => {
        designation.add(new Option(value, value));
    });
    const balance = () => {
        cards.forEach(card => card.style.left = '');
        const visible = cards.filter(card => !card.hidden);
        const css = getComputedStyle(grid);
        const columns = css.gridTemplateColumns.split(' ').length;
        const remaining = visible.length % columns;
        if (!remaining || columns === 1) return;
        const offset = (columns - remaining) * (visible[0].getBoundingClientRect().width + parseFloat(css.columnGap)) / 2;
        visible.slice(-remaining).forEach(card => {card.style.position = 'relative';card.style.left = offset + 'px';});
    };
    const filter = () => {
        const query = search.value.trim().toLocaleLowerCase();
        cards.forEach(card => {card.hidden = !(card.dataset.facultyName + ' ' + card.dataset.facultyDesignation).toLocaleLowerCase().includes(query) || (designation.value !== '' && card.dataset.facultyDesignation !== designation.value);});
        const count = cards.filter(card => !card.hidden).length;
        document.getElementById('faculty-results').textContent = `Showing ${count} of ${cards.length} teachers`;
        document.getElementById('faculty-no-results').hidden = count !== 0;
        balance();
    };
    form.addEventListener('submit', event => event.preventDefault());
    search.addEventListener('input', filter);
    designation.addEventListener('change', filter);
    form.addEventListener('reset', () => {search.value = '';designation.value = '';filter();});
    window.addEventListener('resize', balance);
    filter();
});
</script>
@endsection
