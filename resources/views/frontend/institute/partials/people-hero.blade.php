{{-- Shared hero panel + base styles for Teachers / Staff / Committee pages.
     Props: $accent (hex), $eyebrow, $title, $subtitle, $stats = [[icon,value,label],...] --}}
@php
    $pplClassCount = '—'; $pplEstablished = '—'; $pplInstitute = null;
    try { $pplInstitute = \App\Models\InstituteDetails::first(); } catch (\Throwable $e) {}
    try { $pplClassCount = \App\Models\classManage::count() ?: '—'; } catch (\Throwable $e) {}
    if ($pplInstitute && !empty($pplInstitute->establishDate)) {
        try { $pplEstablished = \Carbon\Carbon::parse($pplInstitute->establishDate)->format('Y'); }
        catch (\Throwable $e) { if (preg_match('/(19|20)\d{2}/', $pplInstitute->establishDate, $m)) $pplEstablished = $m[0]; }
    }
    $stats = array_merge($stats, [['fa-school', $pplClassCount, 'Classes'], ['fa-calendar-days', $pplEstablished, 'Established']]);
@endphp
<style>
.edu-content-wrap .container:has(.ppl-shell){width:100%;max-width:1200px;padding-inline:24px}
.edu-main-card:has(.ppl-shell),.edu-main-inner:has(.ppl-shell){border:0;box-shadow:none;background:transparent;padding:0}
.edu-main-inner:has(.ppl-shell)>.row{margin-left:0;margin-right:0;--bs-gutter-x:0}
.ppl-shell{box-sizing:border-box;flex:0 0 100%;width:100%;max-width:100%;padding-inline:0}
.ppl-shell{--navy:#1B3A5C;--gold:#92400E;--accent:#1B3A5C;min-width:0;padding:8px 0 32px;color:var(--navy)}
.ppl-shell [hidden]{display:none!important}
.ppl-hero{position:relative;overflow:hidden;margin-bottom:32px;padding:44px 40px 36px;border-radius:24px;border:1px solid #f0e2c4;background:linear-gradient(135deg,#fffaf0 0%,#fdf0d5 100%)}
.ppl-hero:before{content:'';position:absolute;top:-90px;right:-70px;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(146,64,14,.16),rgba(146,64,14,.04) 70%);pointer-events:none}
.ppl-hero>*{position:relative}
.ppl-eyebrow{display:flex;align-items:center;gap:10px;margin-bottom:14px;color:var(--gold);font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase}
.ppl-eyebrow:before{content:'';width:28px;height:2px;background:var(--gold)}
.ppl-hero h1{margin:0 0 12px;font-family:Georgia,'Times New Roman',serif;font-size:44px;line-height:1.15;font-weight:700;color:var(--navy);letter-spacing:-.01em}
.ppl-hero .ppl-sub{max-width:620px;margin:0 0 28px;font-size:16px;line-height:1.65;color:#5b6b7d}
.ppl-stats{display:flex;flex-wrap:wrap;align-items:center}
.ppl-stat{display:flex;align-items:center;gap:12px;padding:0 28px}
.ppl-stat:first-child{padding-left:0}
.ppl-stat+.ppl-stat{border-left:1px solid rgba(27,58,92,.18)}
.ppl-stat i{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;background:#fff;color:var(--accent);font-size:16px;box-shadow:0 2px 8px rgba(27,58,92,.08)}
.ppl-stat b{display:block;font-size:22px;line-height:1.1;color:var(--navy)}
.ppl-stat span{display:block;margin-top:2px;font-size:12px;color:#6b7a8c}
.ppl-section-title{display:flex;align-items:center;gap:10px;margin:0 0 16px;font-size:13px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold)}
.ppl-section-title>span{flex:none;display:block;padding:0;text-indent:0;overflow:visible;line-height:1.4}
.ppl-section-title:before{content:none!important;display:none!important}
.ppl-section-title:after{content:'';flex:1;height:1px;background:#ecdcc0}
.ppl-empty{padding:32px;border:1px dashed #d5dde6;border-radius:14px;text-align:center;color:#607286}
.ppl-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:9px 22px;border-radius:999px;border:1.5px solid var(--btn,var(--navy));background:transparent;color:var(--btn,var(--navy))!important;font-size:13px;font-weight:600;line-height:1.4;text-decoration:none!important;transition:background .2s,color .2s}
.ppl-btn:hover,.ppl-btn:focus-visible{background:var(--btn,var(--navy));color:#fff!important}
.ppl-btn:focus-visible{outline:3px solid rgba(146,64,14,.4);outline-offset:3px}
.ppl-btn--solid{background:var(--btn,var(--navy));color:#fff!important}
.ppl-btn--solid:hover{filter:brightness(1.12)}
.ppl-initials{display:flex;align-items:center;justify-content:center;flex:none;font-weight:700;color:var(--accent);background:linear-gradient(135deg,#fff5e0,#f5deb0);border:1px solid #ecd6a6}
@media(max-width:767px){.ppl-hero{padding:30px 22px 26px;border-radius:18px}.ppl-hero h1{font-size:32px}.ppl-stats{gap:16px 0}.ppl-stat{padding:0 18px}}
@media(max-width:575px){.edu-content-wrap .container:has(.ppl-shell){padding-inline:16px}.ppl-hero h1{font-size:27px}.ppl-stat{padding:0 14px 0 0;border-left:0!important}}
@media(prefers-reduced-motion:reduce){.ppl-shell *{transition:none!important;transform:none!important}}
</style>
<header class="ppl-hero" style="--accent:{{ $accent }}">
    <div class="ppl-eyebrow">{{ $eyebrow }}</div>
    <h1 id="ppl-heading">{{ $title }}</h1>
    <p class="ppl-sub">{{ $subtitle }}</p>
    <div class="ppl-stats">
        @foreach($stats as [$icon, $value, $label])
            <div class="ppl-stat"><i class="fa-solid {{ $icon }}" aria-hidden="true"></i><div><b>{{ $value }}</b><span>{{ $label }}</span></div></div>
        @endforeach
    </div>
</header>
