@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Governing Body')
@section('portal-layout', '1')
@php
    $members = ($Datakey ?? collect())->map(function ($m) {
        $name = trim((string) ($m->fullName ?? ''));
        if ($name === '') {
            $name = trim((string) ($m->firstName ?? ''));
            $last = trim((string) ($m->lastName ?? ''));
            if ($last !== '' && !preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $name)) $name = trim($name.' '.$last);
        }
        $m->display_name = $name !== '' ? $name : 'Governing Member';
        $m->display_role = trim((string) ($m->designation ?? '')) ?: 'Member';
        $parts = preg_split('/\s+/u', $m->display_name, -1, PREG_SPLIT_NO_EMPTY);
        $m->initials = mb_strtoupper(mb_substr($parts[0] ?? '?', 0, 1).(count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
        $m->photoUrl = app(\App\Services\PublicMediaUrl::class)->institutionAboutImage($m->avatar ?? null);
        return $m;
    })->sortBy(fn ($m) => in_array(strtolower($m->display_role), ['chairman', 'president'], true) ? 0 : 1)->values();
@endphp
@section('frontcontent')
<style>
.cmt-wrap{display:flex;flex-wrap:wrap;justify-content:center;gap:24px}
.cmt-card{display:flex;flex-direction:column;align-items:center;text-align:center;width:min(100%,320px);padding:36px 28px 30px;background:#fff;border:1px solid #f0e2c4;border-radius:22px;box-shadow:0 8px 26px rgba(27,58,92,.08);transition:transform .25s,box-shadow .25s}
.cmt-card:hover,.cmt-card:focus-within{transform:translateY(-4px);box-shadow:0 16px 34px rgba(27,58,92,.14)}
.cmt-avatar{width:104px;height:104px;border-radius:50%;margin-bottom:18px;font-family:Georgia,serif;font-size:34px}
img.cmt-avatar{display:block;flex:none;object-fit:cover;object-position:center 25%;border:3px solid #f5deb0;background:#eef2f6}
.cmt-card h2{margin:0 0 10px;font-family:Georgia,serif;font-size:22px;font-weight:700;color:var(--navy);overflow-wrap:anywhere}
.cmt-role{margin:0 0 22px;padding:5px 14px;border-radius:999px;background:#fdf0d5;color:var(--gold);font-size:13px;font-weight:600}
</style>
<section class="ppl-shell" aria-labelledby="ppl-heading">
    @include('frontend.institute.partials.people-hero', [
        'accent' => '#1B3A5C',
        'eyebrow' => 'Governing Body',
        'title' => 'Meet Our Leadership',
        'subtitle' => 'The people guiding our institution with care and commitment.',
        'stats' => [['fa-user-tie', $members->count(), $members->count() === 1 ? 'Member' : 'Members']],
    ])
    @if($members->isEmpty())
        <div class="ppl-empty">No records available.</div>
    @else
        <div class="cmt-wrap">
            @foreach($members as $m)
                <article class="cmt-card">
                    @if($m->photoUrl)
                        <img src="{{ $m->photoUrl }}" alt="{{ $m->display_name }}" class="cmt-avatar" loading="lazy" onerror="this.outerHTML='<span class=&quot;ppl-initials cmt-avatar&quot; aria-hidden=&quot;true&quot;>{{ $m->initials }}</span>'">
                    @else
                        <span class="ppl-initials cmt-avatar" aria-hidden="true">{{ $m->initials }}</span>
                    @endif
                    <h2>{{ $m->display_name }}</h2>
                    <span class="cmt-role">{{ $m->display_role }}</span>
                    <a class="ppl-btn ppl-btn--solid" href="{{ route('committee.show', ['id' => $m->id]) }}" aria-label="View {{ $m->display_name }} profile">View Profile</a>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
