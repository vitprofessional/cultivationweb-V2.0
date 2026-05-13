@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Head of Institute Message
@endsection
@section('frontcontent')
<style>
    /* ── Profile card (left) ───────────────────────────────────── */
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

    @media print {
        body * { visibility: hidden; }
        #hoi-print-area, #hoi-print-area * { visibility: visible; }
        #hoi-print-area { position: absolute; left: 0; top: 0; width: 100%; }
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
</style>

@php
    $configLocal = isset($config) ? $config : \App\Models\ServerConfig::first();

    if(isset($configLocal) && !empty($configLocal->instituteName)){
        $insName = $configLocal->instituteName;
    } elseif(isset($cultivation) && $cultivation && !empty($cultivation->institueName)) {
        $insName = $cultivation->institueName;
    } else {
        $insName = 'Jahanara-Ayub Academy';
    }

    if(isset($configLocal) && !empty($configLocal->avatar)){
        $avatarPath = config('app.url') . '/public/upload/image/cultivation/' . rawurlencode(basename($configLocal->avatar));
    } elseif(isset($principal) && $principal && !empty($principal->avatar)) {
        $avatarPath = config('app.url') . '/public/upload/image/teacher/' . rawurlencode(basename($principal->avatar));
    } else {
        $avatarPath = config('app.url') . '/public/avatar.png';
    }

    $displayName = isset($configLocal) && !empty($configLocal->principalName)
        ? $configLocal->principalName
        : (isset($principal) && $principal ? trim(($principal->firstName ?? '') . ' ' . ($principal->lastName ?? '')) : 'Engr. Abu Yousuf');

    $displayDesignation = isset($configLocal) && !empty($configLocal->principalDesignation)
        ? $configLocal->principalDesignation
        : ((isset($principal) && $principal)
            ? (($principal->designation == 1) ? 'Head of Institute' : (($principal->designation == 2) ? 'Head of Institute (In-charge)' : 'Head of Institute'))
            : 'Head of Institute');

    $importantSpeech = null;
    $generalSpeech   = null;
    if(isset($configLocal) && (!empty($configLocal->principalImportantSpeech) || !empty($configLocal->principalGeneralSpeech))){
        $importantSpeech = $configLocal->principalImportantSpeech ?? null;
        $generalSpeech   = $configLocal->principalGeneralSpeech   ?? null;
    } elseif(isset($pSpeech) && $pSpeech) {
        $importantSpeech = $pSpeech->importantSpeech ?? null;
        $generalSpeech   = $pSpeech->generalSpeech   ?? null;
    }

    if(empty($importantSpeech)){
        $importantSpeech = 'We want to make good students as well as good people.';
    }
    if(empty($generalSpeech)){
        $generalSpeech = 'Excellence is not an accident — it is the result of consistent effort, strong values, and an unwavering commitment to growth. Our institution has always believed that true education goes beyond textbooks. It shapes character, builds confidence, and prepares students to serve their community with integrity. I encourage every student, teacher, and parent to remain committed to learning and to uphold the values that define our institution.';
    }
@endphp

<div id="hoi-print-area" class="col-12">
    <div class="row g-4 align-items-stretch">

        {{-- ── Left: Profile Card ──────────────────────────────── --}}
        <div class="col-12 col-lg-4">
            <div class="hoi-photo-card">
                <div class="hoi-photo-header">
                    <img class="hoi-avatar"
                         src="{{ $avatarPath }}"
                         alt="Photo of {{ e($displayName) }}"
                         onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';">
                </div>
                <div class="hoi-info text-center">
                    <div class="hoi-name">{{ $displayName }}</div>
                    <div class="hoi-role">{{ $displayDesignation }}</div>
                    <div class="hoi-inst">{{ $insName }}</div>
                    <hr class="hoi-divider">
                    <ul class="hoi-attr-list text-start">
                        <li><i class="fa fa-check-circle"></i> Student-centered leadership</li>
                        <li><i class="fa fa-check-circle"></i> Academic discipline and values</li>
                        <li><i class="fa fa-check-circle"></i> Future-ready institutional vision</li>
                        <li><i class="fa fa-check-circle"></i> Community and service commitment</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ── Right: Message Card ─────────────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="hoi-message-card">
                <div class="hoi-msg-header">
                    <h2>{{ $displayName }}</h2>
                    <p>{{ $displayDesignation }}&ensp;&bull;&ensp;{{ $insName }}</p>
                </div>
                <div class="hoi-msg-body">
                    <blockquote class="hoi-quote">
                        <span class="hoi-q-icon">&ldquo;</span>{{ $importantSpeech }}
                    </blockquote>
                    <div class="hoi-body-text">{!! nl2br(e($generalSpeech)) !!}</div>
                    <div class="hoi-signature">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            <i class="fa fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection