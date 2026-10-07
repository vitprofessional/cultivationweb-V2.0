@php
    $committee = $kind === 'committee';
    $records = $records->sortBy(fn ($record) => $committee && in_array(strtolower(trim($record->designation ?? '')), ['chairman', 'president'], true) ? 0 : 1)->values();
@endphp
<style>
.edu-content-wrap .container:has(.team-directory){width:100%;max-width:1320px;padding-inline:24px}
.edu-main-card:has(.team-directory),.edu-main-inner:has(.team-directory){border:0;box-shadow:none;background:transparent;padding:0}
.team-directory{min-width:0;background:linear-gradient(135deg,#edf8ff,#f6fbff)}
.team-hero{position:relative;padding:36px 28px;background:radial-gradient(ellipse at 95% 15%,#8fcaf580,transparent 65%),linear-gradient(120deg,#f0f9ff,#e3f3ff);border-radius:14px;margin-bottom:24px;overflow:hidden}
.team-hero:after{content:'';position:absolute;right:20px;bottom:0;width:200px;height:150px;opacity:.1;background:repeating-linear-gradient(90deg,transparent 0 28px,#37749a 28px 40px),repeating-linear-gradient(0deg,#c8e3f3 0 24px,#37749a 24px 32px);clip-path:polygon(0 28%,45% 28%,45% 12%,68% 0,100% 12%,100% 100%,0 100%);pointer-events:none}
.team-eyebrow{display:block;color:#087e9a;font-size:11px;font-weight:750;letter-spacing:.18em;margin-bottom:12px}
.team-hero h1{position:relative;z-index:1;margin:0 0 12px;font-size:40px;line-height:1.2;letter-spacing:-.025em;color:#17334f;font-weight:800}
.team-hero p{position:relative;z-index:1;margin:0 0 16px;color:#607286;line-height:1.6}
.team-count{display:inline-block;background:#fff;border:1px solid #dce6ee;border-radius:20px;padding:7px 12px;font-size:12px;color:#42647e}
.team-grid{display:flex;flex-wrap:wrap;justify-content:center;align-items:stretch;gap:22px}
.team-card{display:flex;flex-direction:column;width:calc((100% - 66px)/4);min-width:0;background:#fff;border:1px solid #dce6ee;border-radius:16px;overflow:hidden;box-shadow:0 6px 22px #17334f09;transition:transform .25s,box-shadow .25s}
.team-card:hover,.team-card:focus-within{transform:translateY(-4px);box-shadow:0 14px 32px #17334f18}
.team-photo{position:relative;background:#eef3f7}
.team-directory .people-photo-frame{display:block;position:relative;width:calc(100% - 12px);height:auto;aspect-ratio:4/5;margin:6px auto 0;border:0;border-radius:11px;overflow:hidden;background:#eef3f7}
.team-directory .people-portrait{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 28%;transform:none}
.team-featured{position:absolute;z-index:2;right:10px;top:10px;padding:5px 9px;border-radius:20px;background:#ffe082;color:#614900;font-size:10px;font-weight:750}
.team-body{display:flex;flex-direction:column;flex:1;padding:24px 18px;min-width:0}
.team-directory .team-body h2.team-name{margin:0 0 16px;color:#17334f;font-size:18px;font-weight:750;line-height:1.45;overflow-wrap:anywhere}
.team-role{align-self:flex-start;max-width:100%;margin:0 0 20px;padding:5px 9px;border:1px solid #d5e6f3;border-radius:5px;background:#e3eef7;color:#214866;font-size:12px;overflow-wrap:anywhere}
.team-details{margin-top:auto}.team-details summary{display:flex;align-items:center;justify-content:center;gap:8px;min-height:46px;padding:12px 14px;list-style:none;border-radius:6px;background:linear-gradient(110deg,#224a83,#006ebc);color:#fff;font-size:13px;font-weight:650;cursor:pointer}.team-details summary::-webkit-details-marker{display:none}.team-details summary:hover{background:#07518a}.team-details summary:focus-visible{outline:3px solid #21a7d0!important;outline-offset:3px}
.team-contact{padding-top:16px;font-size:13px;line-height:1.7;overflow-wrap:anywhere}.team-contact p{margin:0 0 8px}.team-empty{padding:30px;text-align:center;color:#607286}
.team-profile-link{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:auto;min-height:46px;padding:12px 14px;border-radius:6px;background:linear-gradient(110deg,#224a83,#006ebc);color:#fff!important;font-size:13px;font-weight:650;text-decoration:none}.team-profile-link:hover{background:#07518a}.team-profile-link:focus-visible{outline:3px solid #21a7d0!important;outline-offset:3px}
@media(max-width:1199px){.team-card{width:calc((100% - 44px)/3)}}
@media(max-width:899px){.team-card{width:calc((100% - 22px)/2)}.team-hero h1{font-size:34px}}
@media(max-width:575px){.edu-content-wrap .container:has(.team-directory){padding-inline:16px}.team-card{width:100%}.team-hero{padding:24px 16px}.team-hero h1{font-size:28px}.team-body{padding:22px 20px}.team-hero:after{opacity:.05}}
@media(prefers-reduced-motion:reduce){.team-card{transition:none}.team-card:hover,.team-card:focus-within{transform:none}}
</style>
<section class="team-directory" aria-labelledby="team-heading">
    <header class="team-hero">
        <span class="team-eyebrow">{{ $committee ? 'GOVERNING BODY' : 'OUR STAFF' }}</span>
        <h1 id="team-heading">{{ $committee ? 'Meet Our Leadership' : 'Meet Our Support Team' }}</h1>
        <p>{{ $committee ? 'Meet the people guiding our institution with care and commitment.' : 'Meet the people who support our students and keep our institution running smoothly.' }}</p>
        <span class="team-count">{{ $records->count() }} {{ $committee ? 'Members' : 'Staff Members' }}</span>
    </header>
    <div class="team-grid">
        @foreach($records as $record)
            @php
                $name = trim($record->fullName ?? '');
                if ($name === '') {
                    $name = trim($record->firstName ?? '');
                    $last = trim($record->lastName ?? '');
                    if ($last !== '' && !preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $name)) $name = trim($name.' '.$last);
                }
                $name = $name ?: ($committee ? 'Governing Member' : 'Staff Member');
                $role = $committee ? ($record->designation ?: 'Member') : \App\Models\StaffManagement::getDesignationName($record->designation ?? null);
                $featured = $committee && in_array(strtolower(trim($role)), ['chairman', 'president'], true);
                $photo = $committee ? app(\App\Services\PublicMediaUrl::class)->institutionAboutImage($record->avatar ?? null) : app(\App\Services\PublicMediaUrl::class)->staffPortrait($record->avatar ?? null);
            @endphp
            <article class="team-card" data-featured="{{ $featured ? 'true' : 'false' }}">
                <div class="team-photo">
                    <x-people-portrait :src="$photo" :alt="$name" />
                    @if($featured)<span class="team-featured">{{ $role }}</span>@endif
                </div>
                <div class="team-body">
                    <h2 class="team-name">{{ $name }}</h2>
                    <p class="team-role">{{ $role }}</p>
                    <a class="team-profile-link" href="{{ route($committee ? 'committee.show' : 'staff.show', ['id'=>$record->id]) }}" aria-label="View {{ $name }} profile">View Profile <span aria-hidden="true">→</span></a>
                </div>
            </article>
        @endforeach
    </div>
    @if($records->isEmpty())<p class="team-empty">No records available.</p>@endif
</section>
