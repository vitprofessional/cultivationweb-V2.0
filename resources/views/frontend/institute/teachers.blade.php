@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Our Teachers')
@section('portal-layout', '1')
@php
    $teachers = ($Datakey ?? collect())->map(function ($t) {
        $name = trim((string) ($t->fullName ?? ''));
        if ($name === '') {
            $name = trim((string) ($t->firstName ?? ''));
            $surname = trim((string) ($t->lastName ?? ''));
            if ($surname !== '' && !preg_match('/(?:^|\s)'.preg_quote($surname, '/').'$/iu', $name)) $name = trim($name.' '.$surname);
        }
        $t->display_name = $name !== '' ? $name : 'Unknown';
        $t->display_role = \App\Models\TeacherManagement::getDesignationName($t->designation ?? null);
        $t->display_photo = app(\App\Services\PublicMediaUrl::class)->teacherPortrait($t->avatar)
            ?: app(\App\Services\PublicAssetUrl::class)->url('avatar.png');
        return $t;
    });
    $leaderRoles = ['head master', 'assistant head master'];
    $isLeader = fn ($t) => in_array(strtolower(trim($t->display_role)), $leaderRoles, true);
    $leaders = $teachers->filter($isLeader)->sortBy(fn ($t) => strtolower(trim($t->display_role)) === 'head master' ? 0 : 1)->values();
    $others = $teachers->reject($isLeader)->values();
@endphp
@section('frontcontent')
<style>
.tch-leaders{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:20px;margin-bottom:36px}
.tch-leader{display:flex;align-items:center;gap:22px;padding:24px 26px;background:#fff;border:1px solid #f0e2c4;border-radius:20px;box-shadow:0 6px 22px rgba(27,58,92,.06);transition:transform .25s,box-shadow .25s}
.tch-leader:hover,.tch-leader:focus-within{transform:translateY(-3px);box-shadow:0 14px 30px rgba(27,58,92,.12)}
.tch-leader .people-photo-frame{position:relative;display:block;flex:none;width:112px;height:112px;aspect-ratio:1;margin:0;border:3px solid #f5deb0;border-radius:50%;overflow:hidden}
.tch-leader .people-portrait,.tch-card .people-portrait{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 22%;transform:none}
.tch-leader h2{margin:0 0 4px;font-family:Georgia,serif;font-size:22px;font-weight:700;color:var(--navy);overflow-wrap:anywhere}
.tch-leader p{margin:0 0 14px;font-size:13px;font-weight:600;color:var(--gold)}
.tch-chips{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px}
.tch-chip{padding:8px 18px;border:1px solid #d5dde6;border-radius:999px;background:#fff;color:var(--navy);font:600 13px/1.4 inherit;cursor:pointer;transition:background .2s,color .2s,border-color .2s}
.tch-chip:hover{border-color:var(--navy)}
.tch-chip[aria-pressed=true]{background:var(--navy);border-color:var(--navy);color:#fff}
.tch-chip:focus-visible{outline:3px solid rgba(146,64,14,.4);outline-offset:2px}
.tch-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
.tch-card{display:flex;align-items:center;gap:22px;min-width:0;padding:24px 26px;background:#fff;border:1px solid #f0e2c4;border-radius:20px;box-shadow:0 6px 22px rgba(27,58,92,.06);transition:transform .25s,box-shadow .25s}
.tch-card:hover,.tch-card:focus-within{transform:translateY(-3px);box-shadow:0 14px 30px rgba(27,58,92,.12)}
.tch-card .people-photo-frame{position:relative;display:block;flex:none;width:120px;height:120px;aspect-ratio:1;margin:0;border:2px solid #f5deb0;border-radius:14px;overflow:hidden}
.tch-card-body{display:flex;flex-direction:column;align-items:flex-start;min-width:0}
.edu-main-inner .ppl-shell .tch-card h3{margin:0 0 8px;font-family:Georgia,serif;font-size:15px;font-weight:700;line-height:1.3;color:var(--navy);overflow-wrap:anywhere}
.tch-role{margin:0 0 6px;padding:4px 12px;border-radius:999px;background:#fdf0d5;color:var(--gold);font-size:12px;font-weight:600}
.tch-meta{margin:0 0 14px;font-size:12px;color:#6b7a8c}
.tch-role+.ppl-btn{margin-top:8px}
@media(max-width:575px){.tch-leaders,.tch-grid{grid-template-columns:1fr}.tch-leader,.tch-card{flex-direction:column;text-align:center}.tch-card-body{align-items:center}}
</style>
<section class="ppl-shell" aria-labelledby="ppl-heading">
    @include('frontend.institute.partials.people-hero', [
        'accent' => '#1B3A5C',
        'eyebrow' => 'Our Teachers',
        'title' => 'Meet Our Teaching Faculty',
        'subtitle' => 'Dedicated educators who inspire, guide and support every student to reach their full potential.',
        'stats' => [['fa-chalkboard-user', $teachers->count(), 'Faculty Members']],
    ])

    @if($teachers->isEmpty())
        <div class="ppl-empty">No teacher records found.</div>
    @else
        @if($leaders->isNotEmpty())
            <h2 class="ppl-section-title"><span>Leadership</span></h2>
            <div class="tch-leaders">
                @foreach($leaders as $t)
                    <article class="tch-leader">
                        <x-people-portrait :src="$t->display_photo" :alt="$t->display_name" />
                        <div>
                            <h2>{{ $t->display_name }}</h2>
                            <p>{{ $t->display_role }}</p>
                            <a class="ppl-btn" style="--btn:#92400E" href="{{ route('teacher.show', ['id' => $t->id]) }}" aria-label="View {{ $t->display_name }} profile">View Profile</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if($others->isNotEmpty())
            <h2 class="ppl-section-title"><span>Faculty</span></h2>
            <div class="tch-chips" role="group" aria-label="Filter faculty by designation">
                <button type="button" class="tch-chip" data-filter="" aria-pressed="true">All Faculty</button>
                <button type="button" class="tch-chip" data-filter="senior teacher" aria-pressed="false">Senior Teacher</button>
                <button type="button" class="tch-chip" data-filter="assistant teacher" aria-pressed="false">Assistant Teacher</button>
            </div>
            <div class="tch-grid">
                @foreach($others as $t)
                    <article class="tch-card" data-role="{{ strtolower(trim($t->display_role)) }}">
                        <x-people-portrait :src="$t->display_photo" :alt="$t->display_name" />
                        @php
                            $joined = null;
                            if (!empty($t->joinDate)) { try { $joined = \Carbon\Carbon::parse($t->joinDate)->format('Y'); } catch (\Throwable $e) {} }
                        @endphp
                        <div class="tch-card-body">
                            <h3>{{ $t->display_name }}</h3>
                            <span class="tch-role">{{ $t->display_role }}</span>
                            @if($joined)<p class="tch-meta">Joined {{ $joined }}</p>@endif
                            <a class="ppl-btn" href="{{ route('teacher.show', ['id' => $t->id]) }}" aria-label="View {{ $t->display_name }} profile">View Profile</a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div id="tch-none" class="ppl-empty" hidden>No teachers in this category.</div>
        @endif
    @endif
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chips = [...document.querySelectorAll('.tch-chip')];
    const cards = [...document.querySelectorAll('.tch-card')];
    chips.forEach(chip => chip.addEventListener('click', () => {
        chips.forEach(c => c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'));
        cards.forEach(card => card.hidden = chip.dataset.filter !== '' && card.dataset.role !== chip.dataset.filter);
        document.getElementById('tch-none').hidden = cards.some(c => !c.hidden);
    }));
});
</script>
@endsection
