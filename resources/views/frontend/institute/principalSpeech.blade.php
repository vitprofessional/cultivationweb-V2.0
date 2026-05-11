@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Head of Institute Message
@endsection
@section('frontcontent')
<style>
    /* Page-scoped styles (keep minimal) */
    .principal-hero {
        text-align: center;
        margin-top: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .principal-hero h1 {
        font-weight: 700;
        letter-spacing: .3px;
    }
    .principal-hero small {
        color: #6c757d;
    }
    .principal-card img.principal-avatar {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #f8f9fa;
        box-shadow: 0 6px 20px rgba(0,0,0,.12);
    }
    .principal-meta {
        font-size: .95rem;
        color: #6c757d;
        margin-top: .25rem;
    }
    .speech-lead {
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.5;
    }
    .speech-body {
        font-size: 1rem;
        line-height: 1.8;
        text-align: justify;
    }
    @media (max-width: 576px) {
        .principal-card img.principal-avatar { width: 140px; height: 140px; }
    }

    /* Print only the speech content */
    @media print {
        body * { visibility: hidden; }
        #speechPrintArea, #speechPrintArea * { visibility: visible; }
        #speechPrintArea { position: absolute; left: 0; top: 0; width: 100%; }
    }
</style>

 <section>
    <div class="container">
        @php
            $configLocal = isset($config) ? $config : \App\Models\ServerConfig::first();
            $speechTitle = $frontendSpeechTitle ?? "Principal's Message";
        @endphp
        <div class="principal-hero">
            <h1 class="my-3">{{ $speechTitle }}</h1>
            <small>Guidance, values, and inspiration for our students and community</small>
        </div>

        @php
            // Prefer ServerConfig for institute and principal details
            if(isset($configLocal) && !empty($configLocal->instituteName)){
                $insName = $configLocal->instituteName;
            } elseif(isset($cultivation) && $cultivation && !empty($cultivation->institueName)) {
                $insName = $cultivation->institueName;
            } else {
                $insName = 'Jahanara-Ayub Academy';
            }
        @endphp

        <div id="speechPrintArea" class="row g-4 align-items-start my-2">
            <div class="col-12 col-md-4">
                <div class="card principal-card shadow-sm h-100 text-center p-3">
                    <div class="d-flex flex-column align-items-center">
                        @php
                            $avatarPath = null;
                            if(isset($configLocal) && !empty($configLocal->avatar)){
                                $avatarPath = config('app.url') . '/public/upload/image/cultivation/' . rawurlencode(basename($configLocal->avatar));
                            } elseif(isset($principal) && $principal && !empty($principal->avatar)) {
                                $avatarPath = config('app.url') . '/public/upload/image/teacher/' . rawurlencode(basename($principal->avatar));
                            } else {
                                $avatarPath = config('app.url') . '/public/avatar.png';
                            }
                            $displayName = isset($configLocal) && !empty($configLocal->principalName)
                                ? $configLocal->principalName
                                : (isset($principal) && $principal ? ($principal->firstName . ' ' . $principal->lastName) : 'Engr. Abu Yousuf');
                            $displayDesignation = isset($configLocal) && !empty($configLocal->principalDesignation)
                                ? $configLocal->principalDesignation
                                : ((isset($principal) && $principal)
                                    ? (($principal->designation==1) ? 'Principal' : (($principal->designation==2) ? 'Principal (In-charge)' : 'Principal'))
                                    : 'Principal');
                        @endphp
                        <img class="principal-avatar" src="{{ $avatarPath }}" alt="Principal photo of {{ $displayName }}">
                        <div class="mt-3">
                            <div class="h5 mb-1">{{ $displayName }}</div>
                            <div class="principal-meta">{{ $displayDesignation }}<br>{{ $insName }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        @php
                            $importantSpeech = null;
                            $generalSpeech = null;
                            // Priority: ServerConfig fields if exist
                            if(isset($configLocal) && (!empty($configLocal->principalImportantSpeech) || !empty($configLocal->principalGeneralSpeech))){
                                $importantSpeech = $configLocal->principalImportantSpeech ?? null;
                                $generalSpeech = $configLocal->principalGeneralSpeech ?? null;
                            } elseif(isset($pSpeech) && $pSpeech) { // fallback to legacy model
                                $importantSpeech = $pSpeech->importantSpeech ?? null;
                                $generalSpeech = $pSpeech->generalSpeech ?? null;
                            }
                            if(empty($importantSpeech)){
                                $importantSpeech = 'We want to make good students as well as good people.';
                            }
                            if(empty($generalSpeech)){
                                $generalSpeech = "Life is not always smooth sailing; it’s more like a roller coaster with its ups and downs. But remember, it’s the bumps and twists that make the ride exciting and memorable. When you face challenges or setbacks, it’s easy to feel discouraged. However, it’s during these tough times that your true strength shines through. It’s the moments when you refuse to give up that define your character and set the stage for your success.";
                            }
                        @endphp
                        <p class="speech-lead mb-3">“{{ $importantSpeech }}”</p>
                        <div class="speech-body">{!! nl2br(e($generalSpeech)) !!}</div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            <i class="fa fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

   


@endsection