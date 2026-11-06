@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle', 'Our Staff')
@section('portal-layout', '1')
@php
    $staff = ($Datakey ?? collect())->map(function ($s) {
        $name = trim((string) ($s->fullName ?? ''));
        if ($name === '') {
            $name = trim((string) ($s->firstName ?? ''));
            $last = trim((string) ($s->lastName ?? ''));
            if ($last !== '' && !preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $name)) $name = trim($name.' '.$last);
        }
        $s->display_name = $name !== '' ? $name : 'Staff Member';
        $s->display_role = \App\Models\StaffManagement::getDesignationName($s->designation ?? null);
        $parts = preg_split('/\s+/u', $s->display_name, -1, PREG_SPLIT_NO_EMPTY);
        $s->initials = mb_strtoupper(mb_substr($parts[0] ?? '?', 0, 1).(count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
        $s->photoUrl = app(\App\Services\PublicMediaUrl::class)->staffPortrait($s->avatar);
        $r = mb_strtolower((string) $s->display_role);
        if (preg_match('/guard|security/u', $r)) $s->cat = ['Security', '#9B3A3A', '#7A2C2C', '#F6E6E6', 'shield'];
        elseif (preg_match('/clean|ayah|sohayok|soyhaok/u', $r)) $s->cat = ['Housekeeping', '#92400E', '#6B2F0A', '#FDF0D5', 'sparkles'];
        elseif (preg_match('/computer|\blab|operator|\bict\b/u', $r)) $s->cat = ['Technical', '#115E59', '#0C4440', '#E1EEEB', 'monitor'];
        else $s->cat = ['Administrative', '#1B3A5C', '#12263D', '#E7EDF2', 'briefcase'];
        return $s;
    });
@endphp
@section('frontcontent')
<style>
.ppl-shell--staff{--accent:#115E59}
.stf-list{display:flex;flex-direction:column;gap:12px;margin:0;padding:0;list-style:none}
.stf-row{display:flex;align-items:center;gap:20px;padding:18px 24px 18px 20px;background:#fff;border:1px solid #e3e9f0;border-left:4px solid var(--c);border-radius:14px;box-shadow:0 2px 8px rgba(27,58,92,.05);transition:transform .2s,box-shadow .2s}
.stf-row:hover,.stf-row:focus-within{transform:translateY(-2px);box-shadow:0 10px 24px rgba(27,58,92,.13)}
.stf-row .stf-avatar{width:115px;height:120px;border-radius:16px;border:0;font-size:28px;color:#fff;background:linear-gradient(135deg,var(--c),var(--cd));box-shadow:0 4px 10px color-mix(in srgb,var(--c) 35%,transparent)}
.stf-row img.stf-avatar{flex:none;object-fit:cover;background:#eef2f6}
.stf-main{display:flex;flex-direction:column;gap:5px;flex:1;min-width:0}
.stf-row .stf-name{margin:0;font-size:16px;font-weight:700;line-height:1.3;color:var(--navy);overflow-wrap:anywhere}
.stf-meta{display:flex;flex-wrap:wrap;align-items:center;gap:6px 8px}
.stf-meta svg{flex:none;width:18px;height:18px;margin-right:-1px;color:var(--c)}
.stf-role{font-size:13px;color:#5b6b7d}
.stf-badge{padding:2px 9px;border-radius:999px;background:var(--cb);color:var(--cd);font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase}
.stf-row .ppl-btn{--btn:var(--c);flex:none}
.stf-row:hover .ppl-btn,.stf-row:focus-within .ppl-btn{background:var(--c);color:#fff!important}
@media(max-width:575px){.stf-row{flex-wrap:wrap;padding:14px;gap:12px}.stf-row .stf-avatar{width:72px;height:76px;border-radius:12px;font-size:20px}.stf-main{flex-basis:calc(100% - 84px)}.stf-row .ppl-btn{margin-left:84px}}
</style>
<section class="ppl-shell ppl-shell--staff" aria-labelledby="ppl-heading">
    @include('frontend.institute.partials.people-hero', [
        'accent' => '#115E59',
        'eyebrow' => 'Our Staff',
        'title' => 'Meet Our Support Team',
        'subtitle' => 'The people who support our students and keep our institution running smoothly every day.',
        'stats' => [['fa-users', $staff->count(), 'Staff Members']],
    ])
    @if($staff->isEmpty())
        <div class="ppl-empty">No records available.</div>
    @else
        <ul class="stf-list">
            @foreach($staff as $s)
                @php [$catName, $c, $cd, $cb, $icon] = $s->cat; @endphp
                <li class="stf-row" style="--c:{{ $c }};--cd:{{ $cd }};--cb:{{ $cb }}">
                    @if($s->photoUrl)
                        <img src="{{ $s->photoUrl }}" alt="" class="stf-avatar" loading="lazy" onerror="this.outerHTML='<span class=&quot;ppl-initials stf-avatar&quot; aria-hidden=&quot;true&quot;>{{ $s->initials }}</span>'">
                    @else
                        <span class="ppl-initials stf-avatar" aria-hidden="true">{{ $s->initials }}</span>
                    @endif
                    <div class="stf-main">
                        <span class="stf-name">{{ $s->display_name }}</span>
                        <span class="stf-meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @if($icon === 'shield')<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>
                                @elseif($icon === 'sparkles')<path fill="currentColor" stroke="none" d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/>
                                @elseif($icon === 'monitor')<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>
                                @else<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 13h18"/>@endif
                            </svg>
                            <span class="stf-role">{{ $s->display_role }}</span>
                            <span class="stf-badge">{{ $catName }}</span>
                        </span>
                    </div>
                    <a class="ppl-btn" href="{{ route('staff.show', ['id' => $s->id]) }}" aria-label="View {{ $s->display_name }} profile">View Profile</a>
                </li>
            @endforeach
        </ul>
    @endif
</section>
@endsection
