<style>
    /* Leadership editorial presentation; scoped to this page. */
    .hoi-photo-card {
        background: #fff;
        border: 1px solid #dceef4;
        border-radius: 14px;
        box-shadow: 0 10px 32px rgba(39, 60, 102, .09);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .hoi-photo-header {
        background: linear-gradient(160deg, #112958 0%, #273c66 55%, #21a7d0 100%);
        padding: 2rem 1.4rem 1.6rem;
        text-align: center;
    }
    .hoi-avatar {
        width: 148px;
        height: 148px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,.9);
        box-shadow: 0 6px 24px rgba(0,0,0,.25);
    }
    .hoi-info {
        padding: 1.3rem 1.5rem 1.5rem;
        flex: 1;
    }
    .hoi-name {
        font-size: 1.18rem;
        font-weight: 800;
        color: #112958;
        margin-bottom: .18rem;
        line-height: 1.25;
    }
    .hoi-role {
        font-size: .95rem;
        color: #21a7d0;
        font-weight: 700;
        margin-bottom: .14rem;
    }
    .hoi-inst {
        font-size: .88rem;
        color: #6d7d8b;
        margin-bottom: .95rem;
    }
    .hoi-divider {
        border: none;
        border-top: 1px solid #dceef4;
        margin: 0 0 .95rem;
    }
    .hoi-attr-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .hoi-attr-list li {
        display: flex;
        align-items: flex-start;
        gap: .55rem;
        padding: .48rem 0;
        border-bottom: 1px dashed #dceef4;
        font-size: .88rem;
        color: #505050;
        line-height: 1.45;
    }
    .hoi-attr-list li:last-child { border-bottom: none; }
    .hoi-attr-list li i {
        color: #21a7d0;
        font-size: .8rem;
        margin-top: .2rem;
        flex-shrink: 0;
    }

    /* ── Message card (right) ──────────────────────────────────── */
    .hoi-message-card {
        background: #fff;
        border: 1px solid #dceef4;
        border-radius: 14px;
        box-shadow: 0 10px 32px rgba(39, 60, 102, .09);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .hoi-msg-header {
        background: linear-gradient(135deg, #21a7d0 0%, #1782a8 100%);
        padding: 1.35rem 1.8rem;
    }
    .hoi-msg-header h2 {
        color: #fff;
        font-size: 1.22rem;
        font-weight: 800;
        margin: 0 0 .2rem;
        line-height: 1.3;
        letter-spacing: .01em;
    }
    .hoi-msg-header p {
        color: rgba(255,255,255,.82);
        font-size: .84rem;
        margin: 0;
        font-weight: 500;
    }
    .hoi-msg-body {
        padding: 1.6rem 1.8rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .hoi-quote {
        position: relative;
        border-left: 4px solid #21a7d0;
        background: #e7f9fb;
        padding: .9rem 1.1rem .9rem 1.2rem;
        border-radius: 0 8px 8px 0;
        margin-bottom: 1.3rem;
        color: #112958;
        font-size: 1.06rem;
        font-weight: 700;
        font-style: italic;
        line-height: 1.55;
    }
    .hoi-quote .hoi-q-icon {
        font-size: 2.8rem;
        color: #21a7d0;
        line-height: .8;
        display: block;
        margin-bottom: .3rem;
        font-style: normal;
    }
    .hoi-body-text {
        color: #505050;
        line-height: 1.9;
        font-size: .96rem;
        text-align: justify;
        flex: 1;
    }
    .hoi-signature {
        margin-top: 1.6rem;
        padding-top: 1.1rem;
        border-top: 1px solid #dceef4;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }


    @media (max-width: 991.98px) {
        .hoi-avatar { width: 120px; height: 120px; }
        .hoi-msg-body { padding: 1.2rem 1.3rem; }
        .hoi-msg-header { padding: 1.1rem 1.3rem; }
    }
    @media (max-width: 575.98px) {
        .hoi-msg-header h2 { font-size: 1.1rem; }
        .hoi-quote { font-size: .97rem; }
    }
    .hoi-hero{position:relative;isolation:isolate;overflow:hidden;padding:32px 30px 36px;margin-bottom:22px;border-radius:18px;background:linear-gradient(110deg,#edf8ff 15%,#d6effc 75%,#e9f8ff);color:#132f55}
    .hoi-hero:after{content:"";position:absolute;z-index:-1;right:2%;bottom:-12px;width:44%;height:155px;opacity:.16;background:repeating-linear-gradient(90deg,transparent 0 24px,#317aa9 24px 39px,transparent 39px 62px),linear-gradient(#bedbec 18%,#eef8fc 18% 26%,#81b4d3 26% 30%,#e7f4fb 30%);border:10px solid #78afce;border-top:16px solid #598ba9;transform:skewY(-5deg);box-shadow:-32px 20px 0 #bddbea}
    .hoi-breadcrumb{font-size:13px;margin:0 0 20px;color:#496b88}.hoi-breadcrumb a{color:#087fb4}.hoi-hero h1{font-size:clamp(28px,3vw,40px);font-weight:800;margin:0 0 18px;max-width:75%}.hoi-hero-name{font-size:23px;font-weight:800;margin-bottom:8px}.hoi-hero-role{display:inline-block;background:#ccecfb;color:#087da9;padding:4px 12px;border-radius:6px;font-weight:700}.hoi-hero-institution{margin:8px 0 0;color:#516a84}
    .hoi-photo-card{height:auto;background:linear-gradient(145deg,#e6f6ff,#f6fbff);box-shadow:0 8px 30px #173c6010}.hoi-photo-header{background:transparent;padding:22px 20px 10px}.hoi-photo-header .people-photo-frame--profile{width:100%;height:auto;aspect-ratio:4/5;border:5px solid white;box-shadow:0 5px 20px #21486b1c}.hoi-info{padding:14px 20px 23px}.hoi-info .hoi-inst{margin-bottom:0}.hoi-message-card{height:auto;box-shadow:0 8px 30px #173c6010}.hoi-msg-header{display:flex;align-items:center;gap:16px;background:linear-gradient(110deg,#183b71,#0daed2);padding:20px 24px}.hoi-message-icon{display:grid;place-items:center;background:#ffffff20;border-radius:50%;width:48px;height:48px;flex-shrink:0;color:white;font-size:22px}.hoi-msg-header h2{font-size:25px}.hoi-msg-body{padding:26px}.hoi-quote{font-size:21px;line-height:1.8;font-style:normal;margin-bottom:22px}.hoi-body-text{font-size:19px;line-height:1.85;text-align:start;max-width:70ch;overflow-wrap:anywhere}.hoi-body-text p{margin:0 0 1.1em}.hoi-signature{display:block;text-align:right;margin-top:22px;padding-top:16px;break-inside:avoid;color:#314d6c;line-height:1.6}.hoi-signature img{display:block;max-width:180px;max-height:65px;object-fit:contain;margin:0 0 8px auto}.hoi-signature strong{display:block;color:#16375d}.hoi-message-layout{align-items:flex-start!important}.hoi-message-layout>div{min-width:0}
    @media(max-width:991px){.hoi-hero{padding:26px 22px}.hoi-hero:after{width:35%;opacity:.1}.hoi-hero h1{max-width:100%;padding-top:42px}.hoi-msg-body{padding:22px}.hoi-body-text{font-size:18px}.hoi-photo-header .people-photo-frame--profile{max-width:280px}}
    @media(max-width:575px){.hoi-hero{padding:22px 18px}.hoi-hero-name{font-size:21px}.hoi-msg-header{padding:18px;gap:12px}.hoi-msg-header h2{font-size:22px}.hoi-msg-body{padding:20px}.hoi-quote{font-size:19px}}
    /* Final editorial polish: decoration never competes with the real identity. */
    .hoi-hero{background:radial-gradient(ellipse at 12% 20%,#ffffffc9,transparent 58%),radial-gradient(ellipse at 86% 0%,#bde7fb8c,transparent 65%),linear-gradient(115deg,#edf8ff,#cee9f8 78%,#e8f7ff)}
    .hoi-hero:after{opacity:.22;box-shadow:-32px 20px 0 #bddbea,0 -9px 0 #ffffff80,0 15px 24px #568bac24}
    .hoi-hero:before{content:"";position:absolute;z-index:-1;inset:0;background:linear-gradient(90deg,#f4fbff96 0%,transparent 60%),radial-gradient(ellipse at 100% 100%,#5bacc221,transparent 52%);pointer-events:none}
    .hoi-body-text{font-size:19.5px;line-height:1.88;max-width:66ch;color:#43536a;letter-spacing:.005em}
    .hoi-body-text p{margin-bottom:1.15em}
    .hoi-quote{padding:16px 20px;border-left:5px solid #0aaed3;border-radius:0 10px 10px 0;background:linear-gradient(110deg,#e1f5fe,#effbff);color:#183c65;font-size:21px;font-weight:600;line-height:1.85;margin-bottom:24px;overflow-wrap:anywhere}
    .hoi-signature{margin-top:26px;padding-top:20px;padding-bottom:8px;line-height:1.65}
    .hoi-signature img{max-width:min(220px,100%);max-height:82px;margin-bottom:12px}
    .hoi-signature strong{font-size:17px;font-weight:800;margin-bottom:3px}
    .hoi-signature strong+div{font-size:15px;font-weight:600;color:#23718e}
    .hoi-signature strong+div+div{font-size:14px;color:#62758a;margin-top:3px}
    @media(max-width:991px){.hoi-body-text{font-size:18.5px}.hoi-hero:after{opacity:.13}}
    @media(max-width:575px){.hoi-quote{font-size:19px;padding:14px 16px}.hoi-signature img{max-height:74px}}
    .hoi-signature img[hidden]{display:none!important}
    .hoi-msg-header h2{color:#fff!important}
    /* Mockup geometry is page-scoped; shared navigation/footer are untouched. */
    @media screen{
        body:has(#hoi-message-page) .edu-content-wrap{padding:0 0 22px;min-height:0;background:linear-gradient(#e9f7ff,#f5fbfd)}
        body:has(#hoi-message-page) .edu-content-wrap>.container{max-width:none;padding:0}
        body:has(#hoi-message-page) .edu-main-card{border:0;border-radius:0;background:transparent;box-shadow:none;overflow:visible}
        body:has(#hoi-message-page) .edu-main-inner{padding:0}
        body:has(#hoi-message-page) .homepage-slider-wrap{display:none}
        body:has(#hoi-message-page) .edu-main-inner>.row{margin:0}
        #hoi-message-page{padding:0}
        .hoi-hero{margin:0;padding:48px max(7vw,calc((100vw - 1090px)/2)) 86px;border-radius:0;min-height:320px;background:radial-gradient(ellipse at 8% 40%,#f9fdffe6,transparent 70%),linear-gradient(115deg,#def4ff,#c6e9fb 80%,#effbff)}
        .hoi-hero:after{display:none}.hoi-hero:before{background:linear-gradient(90deg,#e8f7ffe0 20%,#e7f7ff44 65%,transparent)}
        .hoi-campus{position:absolute;z-index:-2;right:0;bottom:0;width:66%;height:100%;opacity:.55;pointer-events:none}
        .hoi-hero h1{font-size:42px;line-height:1.15;margin-bottom:28px;max-width:65%;letter-spacing:-.8px}
        .hoi-hero h1:after{content:"";display:block;width:40px;height:3px;background:#08acd1;margin-top:14px}
        .hoi-hero-name{font-size:27px;margin-bottom:8px}.hoi-hero-role{font-size:17px;border-radius:5px}.hoi-hero-institution{font-size:17px}
        .hoi-breadcrumb{margin-bottom:22px}
        .hoi-message-layout{position:relative;display:grid;grid-template-columns:minmax(0,29%) minmax(0,1fr);gap:18px;width:calc(100% - 10vw);max-width:1150px;margin:-35px auto 0!important;padding:22px;background:white;border-radius:12px;box-shadow:0 12px 35px #29668912;align-items:start}
        .hoi-message-layout>div{padding:0;width:auto;max-width:none}
        .hoi-photo-card{border-radius:10px;border:1px solid #bce8fa;background:linear-gradient(140deg,#e8f7ff 30%,#cdeefe 30% 43%,#f2faff 43%);box-shadow:none}
        .hoi-photo-header{padding:26px 25px 6px}
        .hoi-photo-header .people-photo-frame--profile{width:100%;max-width:260px;aspect-ratio:1;height:auto;border-radius:50%;border:7px solid white;box-shadow:0 5px 18px #315d7529}
        .hoi-photo-header img.people-portrait{object-position:center 28%;border-radius:50%}
        .hoi-info{padding:18px 18px 28px}.hoi-name{font-size:21px;line-height:1.3;margin-bottom:7px}.hoi-role{font-size:17px;margin-bottom:6px}.hoi-inst{font-size:16px;line-height:1.5}
        .hoi-message-card{border:1px solid #e0f0f7;border-radius:10px;box-shadow:none}
        .hoi-msg-header{position:relative;isolation:isolate;border-radius:9px;background:linear-gradient(110deg,#1c3e71,#15b3d7);padding:21px 24px;min-height:92px;gap:18px}
        .hoi-msg-header:after{content:"";position:absolute;z-index:-1;inset:0;border-radius:inherit;background:linear-gradient(40deg,transparent 60%,#ffffff0b 60% 82%,transparent 82%)}
        .home-style2 .edu-main-inner .hoi-msg-header h2:first-child{font-size:29px;line-height:1.35;margin:0 0 3px;color:white;letter-spacing:0}
        .hoi-msg-header p{font-size:16px;line-height:1.5;color:#d6f2ff}.hoi-message-icon{width:58px;height:58px;font-size:27px;background:#27bee44a}
        .hoi-msg-body{padding:26px 24px 22px}.hoi-quote{position:relative;padding:13px 18px 13px 68px;font-size:22px;line-height:1.65;font-weight:500;border-left:9px solid #0aaed4;border-radius:6px;margin:0 0 20px;background:#e8f9ff}
        .hoi-quote:before{content:"\201C";position:absolute;left:16px;top:12px;font:700 62px/1 Georgia,serif;color:#0da9cf}
        .hoi-body-text{font-size:20px;line-height:1.78;max-width:100%;color:#46516b;letter-spacing:0}.hoi-body-text p{margin-bottom:.8em}
        .hoi-signature{margin-top:18px;padding-top:14px;padding-bottom:0}.hoi-signature img{max-width:180px;max-height:75px;margin-bottom:7px}.hoi-signature strong{font-size:16px}.hoi-signature strong+div,.hoi-signature strong+div+div{font-size:14px}
    }
    @media screen and (max-width:991px){.hoi-hero{padding:32px 6vw 65px;min-height:310px}.hoi-hero h1{font-size:36px;max-width:85%;padding-top:28px}.hoi-campus{width:80%;opacity:.3}.hoi-message-layout{width:92%;grid-template-columns:minmax(0,30%) minmax(0,1fr);gap:14px;padding:16px}.hoi-photo-header{padding:18px 12px 4px}.hoi-name{font-size:18px}.hoi-role,.hoi-inst{font-size:14px}.hoi-info{padding:14px 12px 20px}.hoi-msg-header{padding:16px;gap:12px}.home-style2 .edu-main-inner .hoi-msg-header h2:first-child{font-size:24px}.hoi-message-icon{width:42px;height:42px;font-size:22px}.hoi-msg-header p{font-size:14px}.hoi-msg-body{padding:20px 18px}.hoi-body-text{font-size:18px}.hoi-quote{font-size:19px;padding-left:48px}.hoi-quote:before{left:9px;font-size:47px}}
    @media screen and (max-width:575px){.hoi-hero{padding:32px 6vw 64px}.hoi-hero h1{font-size:30px;max-width:100%}.hoi-hero-name{font-size:23px}.hoi-hero-role,.hoi-hero-institution{font-size:15px}.hoi-campus{opacity:.18}.hoi-message-layout{grid-template-columns:1fr;padding:14px;gap:18px}.hoi-photo-header .people-photo-frame--profile{max-width:230px}.hoi-name{font-size:21px}.hoi-role,.hoi-inst{font-size:16px}.hoi-body-text{font-size:18px}.hoi-msg-body{padding:20px 16px}.hoi-msg-header{min-height:82px}.hoi-quote{padding-right:12px}}
    .hoi-signature{width:fit-content;max-width:100%;min-width:min(190px,100%);margin-inline-start:auto;text-align:right;border-top:0;padding-top:18px}
    .hoi-signature-line{display:block;width:165px;max-width:100%;height:24px;border-bottom:1px solid #859eb4;margin:0 0 10px auto}
    .hoi-signature-line[hidden]{display:none!important}
    .hoi-signature strong{line-height:1.4;margin-bottom:4px}
    .hoi-signature strong+div,.hoi-signature strong+div+div{line-height:1.5}
    /* Compact reference-style leadership values, independent of profile data. */
    .hoi-sidebar-values{list-style:none;margin:0 26px 26px;padding:22px 0 0;border-top:1px solid #bce1f3;display:grid;gap:20px}
    .hoi-sidebar-values li{display:flex;align-items:center;gap:16px;color:#344d70;font-size:15px;line-height:1.55}
    .hoi-value-icon{display:grid;place-items:center;flex:0 0 50px;width:50px;height:50px;border:1px solid #c6eaff;border-radius:50%;background:linear-gradient(145deg,#fff,#effaff);box-shadow:0 3px 9px #2c8cbe0c;color:#087fb5;font-size:24px}
    @media screen{.hoi-photo-card{box-shadow:0 6px 20px #2b7b9b08}.hoi-info{padding-bottom:24px}}
    @media(max-width:991px){.hoi-sidebar-values{margin:0 14px 22px;padding-top:20px;gap:18px}.hoi-sidebar-values li{gap:10px;font-size:13px}.hoi-value-icon{flex-basis:40px;width:40px;height:40px;font-size:20px}}
    @media(max-width:575px){.hoi-sidebar-values{margin:0 22px 24px;gap:20px}.hoi-sidebar-values li{font-size:15px;gap:16px}.hoi-value-icon{flex-basis:48px;width:48px;height:48px;font-size:23px}}
    /* Final normal-flow composition: a compact hero and one message heading. */
    @media screen{
        .hoi-hero{padding-top:28px;padding-bottom:56px;min-height:0}
        .hoi-breadcrumb{margin-bottom:14px}
        .hoi-hero h1{font-size:36px;margin-bottom:12px;padding-top:0}
        .hoi-hero h1:after{margin-top:10px}
        .hoi-hero-name{font-size:24px;margin-bottom:6px}
        .hoi-hero-headline{font-size:21px;line-height:1.45;color:#245678;margin:0 0 12px;max-width:65%;overflow-wrap:anywhere}
        .hoi-message-layout{align-items:stretch!important}
        .hoi-leadership-column{display:flex}
        .hoi-photo-card{position:static;top:auto;width:100%;height:100%;background:radial-gradient(ellipse at 0 85%,#caedfc66,transparent 65%),linear-gradient(145deg,#e5f6ff,#f5fbff 55%,#e9f7fd)}
        .hoi-photo-card:after{content:"";flex:1;min-height:24px;background:repeating-linear-gradient(145deg,transparent 0 65px,#bedfee1f 65px 67px);pointer-events:none}
        .hoi-photo-header{padding:22px 22px 6px}
        .hoi-photo-header .people-photo-frame--profile{border:6px solid #fff;background:#e3edf3;box-shadow:0 3px 16px #315d7520}
        .hoi-photo-header img.people-portrait{object-position:center 22%}
        .hoi-photo-card .hoi-quote{margin:0 20px 22px;padding:38px 14px 16px;text-align:center;font-size:19px;line-height:1.8;border-left:0;border-top:1px solid #bce1f3;border-radius:0;background:transparent}
        .hoi-photo-card .hoi-info{flex:0 0 auto}
        .hoi-photo-card .hoi-quote:before{left:calc(50% - 13px);top:9px;font-size:42px;width:26px;height:26px;background:none;border:0}
        .hoi-msg-body{padding-top:24px}
        .hoi-body-text p{margin-bottom:1em}
    }
    @media screen and (max-width:991px){.hoi-hero{padding:24px 6vw 52px}.hoi-hero h1{font-size:32px;padding-top:0}.hoi-hero-headline{max-width:85%;font-size:20px}.hoi-photo-header{padding:18px 12px 4px}.hoi-photo-card .hoi-quote{margin-inline:12px;font-size:17px;padding-inline:8px}}
    @media screen and (max-width:575px){.hoi-hero h1{font-size:28px}.hoi-hero-headline{max-width:100%;font-size:19px}.hoi-photo-card{height:auto}.hoi-photo-card:after{display:none}.hoi-photo-card .hoi-quote{margin-inline:22px;font-size:19px}.hoi-hero-name{font-size:22px}}
    @media screen{
        .hoi-hero{padding-top:26px;padding-bottom:58px}
        .hoi-hero h1{font-size:clamp(28px,3vw,40px);line-height:1.35;margin-bottom:10px;letter-spacing:0}
        .hoi-hero-subtitle{color:#416882;font-size:18px;line-height:1.5;margin:0;max-width:65%}
        .hoi-info{padding-top:20px;padding-bottom:26px}
        .hoi-photo-header .people-photo-frame--profile{border:4px solid #fff;background:#eaf1f5;box-shadow:0 0 0 1px #c8e3ef,0 6px 20px #285a7326}
        .hoi-photo-header img.people-portrait{object-fit:cover;object-position:center 28%;transform:none;background:#eaf1f5;border-radius:inherit}
        .hoi-photo-header img.people-portrait.is-fallback,
        .hoi-photo-header img.people-portrait[data-portrait-fallback-active="true"]{object-position:center;transform:none}
        .hoi-photo-card .hoi-quote{background:#f5fcff80;border-bottom:1px solid #c6e5f3;padding-bottom:20px}
        .hoi-sidebar-values{border-top:0;padding-top:4px}
        .hoi-signature{min-width:0;padding-top:12px;margin-top:16px}
    }
    @media screen and (max-width:575px){.hoi-hero-subtitle{max-width:100%;font-size:16px}.hoi-hero{padding-top:22px;padding-bottom:54px}}
    /* Leadership portrait: natural 4:5 framing, never a circular crop. */
    @media screen{
        .hoi-photo-header{padding:22px 0 6px}
        .hoi-photo-header .people-photo-frame--profile{width:84%;max-width:none;height:auto;aspect-ratio:4/5;border-radius:12px;margin-inline:auto}
        .hoi-photo-card{height:auto;align-self:flex-start}
        .hoi-photo-card:after{display:none}
    }
    @media screen and (max-width:991px){.hoi-photo-header{padding-top:18px}}
    .hoi-message-layout.hoi-no-identity{grid-template-columns:1fr}
</style>

@php
    $configLocal = $config ?? null;

    if(isset($configLocal) && !empty($configLocal->instituteName)){
        $insName = $configLocal->instituteName;
    } elseif(isset($cultivation) && $cultivation && !empty($cultivation->institueName)) {
        $insName = $cultivation->institueName;
    } else {
        $insName = '';
    }

    $principalProfile = $leadershipProfile ?? $principalProfile ?? app(\App\Services\PrincipalProfile::class)->read();
    $avatarPath = $principalProfile['photoUrl'] ?: app(\App\Services\PublicAssetUrl::class)->url('avatar.png');
    $displayName = $principalProfile['name'] ?: (($isGoverningMessage ?? false) ? '' : 'Principal profile not added');
    $displayDesignation = $principalProfile['designation'] ?: (($isGoverningMessage ?? false) ? '' : 'Head of Institute');
    $generalSpeech = $principalProfile['message'] ?: ($messageNotice ?? 'A message has not been added yet.');
    $signatureUrl = ($isGoverningMessage ?? false) ? null : app(\App\Services\PublicMediaUrl::class)->institutionAboutImage($configLocal?->principalSign);
    $messageParagraphs = preg_split('/\R\s*\R/u', trim($generalSpeech)) ?: [];
    $introduction = isset($messageParagraphs[0]) && preg_match('/^["“]/u', $messageParagraphs[0]) ? array_shift($messageParagraphs) : null;
    // Role-specific presentation labels, not claims derived from person records.
    $sidebarValues = ($isGoverningMessage ?? false)
        ? [
            ['icon' => 'fa-compass', 'lines' => ['Visionary', 'Governance']],
            ['icon' => 'fa-university', 'lines' => ['Institutional', 'Development']],
            ['icon' => 'fa-users', 'lines' => ['Community', 'Partnership']],
        ]
        : [
            ['icon' => 'fa-graduation-cap', 'lines' => ['Quality Education', 'for a Brighter Future']],
            ['icon' => 'fa-users', 'lines' => ['Discipline, Character', 'and Leadership']],
            ['icon' => 'fa-book', 'lines' => ['Student-Centered', 'Learning']],
        ];
@endphp

<div id="hoi-message-page" class="col-12">
    <header class="hoi-hero">
        <svg class="hoi-campus" viewBox="0 0 900 360" preserveAspectRatio="xMidYMax slice" aria-hidden="true" focusable="false">
            <defs><linearGradient id="hoi-building" x2="0" y2="1"><stop stop-color="#f5fcff"/><stop offset="1" stop-color="#a8cede"/></linearGradient><pattern id="hoi-windows" width="85" height="75" patternUnits="userSpaceOnUse"><rect x="14" y="17" width="40" height="42" fill="#5a99bd"/><path d="M34 17v42M14 38h40" stroke="#d8eef8" stroke-width="3"/></pattern></defs>
            <path d="M120 360V155L710 95l190 47v218Z" fill="url(#hoi-building)" stroke="#90bdd4" stroke-width="4"/>
            <path d="M110 157L710 88l190 44v17L120 173Z" fill="#eefaff" stroke="#a2cbdd" stroke-width="4"/>
            <path d="M140 195L690 135v225H140Z" fill="url(#hoi-windows)"/>
            <path d="M125 244L710 185M125 321L710 260" stroke="#e4f5fb" stroke-width="12"/>
            <path d="M158 184v176m84-185v185m85-194v194m85-203v203m86-213v213m84-222v222m83-231v231" stroke="#dcedf5" stroke-width="12"/>
            <g fill="#79afbf" opacity=".75"><ellipse cx="104" cy="169" rx="58" ry="92"/><ellipse cx="60" cy="208" rx="48" ry="75"/><ellipse cx="805" cy="121" rx="64" ry="96"/><ellipse cx="864" cy="180" rx="63" ry="93"/></g>
            <path d="M102 228v132m705-172v172m52-106v106" stroke="#6999ac" stroke-width="9"/>
            <path d="M0 335Q180 300 390 341T900 310v50H0Z" fill="#d0ecf7"/>
        </svg>
        <nav class="hoi-breadcrumb" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a> &rsaquo; Institute &rsaquo; {{ $breadcrumbLabel ?? 'Head of Institute Message' }}</nav>
        <h1>{{ $messagePageTitle ?? 'প্রতিষ্ঠান প্রধানের বাণী' }}</h1>
        <p class="hoi-hero-subtitle">{{ $messagePageSubtitle ?? 'Message from the Head of Institution' }}</p>
    </header>
    <div class="row g-4 hoi-message-layout {{ ($isGoverningMessage ?? false) && !filled($displayName) ? 'hoi-no-identity' : '' }}">

        {{-- ── Left: Profile Card ──────────────────────────────── --}}
        @if(!($isGoverningMessage ?? false) || filled($displayName))
        <div class="col-12 col-lg-4 hoi-leadership-column">
            <div class="hoi-photo-card">
                <div class="hoi-photo-header">
                    <x-people-portrait :src="$avatarPath" :alt="'Photo of '.$displayName" variant="profile" />
                </div>
                <div class="hoi-info text-center">
                    <div class="hoi-name">{{ $displayName }}</div>
                    <div class="hoi-role">{{ $displayDesignation }}</div>
                    <div class="hoi-inst">{{ $insName }}</div>
                </div>
                @if($introduction)<blockquote class="hoi-quote">{{ $introduction }}</blockquote>@endif
                <ul class="hoi-sidebar-values" aria-label="Education values">
                    @foreach($sidebarValues as $value)
                        <li>
                            <span class="hoi-value-icon" aria-hidden="true"><i class="fa {{ $value['icon'] }}"></i></span>
                            <span>
                                @foreach($value['lines'] as $line)
                                    {{ $line }}
                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @endforeach
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @endif
        {{-- ── Right: Message Card ─────────────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="hoi-message-card">
                <div class="hoi-msg-body">
                    <div class="hoi-body-text">@foreach($messageParagraphs as $paragraph)<p>{!! nl2br(e($paragraph)) !!}</p>@endforeach</div>
                    <div class="hoi-signature">
                        @if($signatureUrl)<img src="{{ $signatureUrl }}" alt="Head of Institute signature" onerror="this.onerror=null;this.hidden=true;this.nextElementSibling.hidden=false;">@endif
                        <span class="hoi-signature-line" aria-hidden="true" @if($signatureUrl) hidden @endif></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
