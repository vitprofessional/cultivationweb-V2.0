    @php
        $config = \App\Models\ServerConfig::first();
        $principalAvatar = !empty($config?->avatar)
            ? config('app.url').'/public/upload/image/cultivation/'.rawurlencode(basename($config->avatar))
            : config('app.url').'/public/avatar.png';
    @endphp
    <div class="row mb-3 sidebar-stack-mobile">
        <div class="col-10 mx-auto">
            <div class="principal-standalone mb-3">
                <div class="plain-heading"><i class="fa-solid fa-user-graduate me-1"></i> <span>Head of Institute</span></div>
                <div class="text-center">
                    <img class="principal-photo" src="{{ $principalAvatar }}" alt="Principal portrait" loading="lazy">
                    <div class="principal-caption">
                        <div class="fw-semibold" style="font-size:12px">{{ $config->principalName ?? 'Engr. Abu Yousuf' }}</div>
                        <div class="text-muted small" style="font-size:11px">{{ $config->principalDesignation ?? 'Principal' }}</div>
                        <a class="btn btn-success btn-sm px-3" href="{{ route('principalSpeechPage') }}">Details</a>
                    </div>
                </div>
            </div>
        </div>
        @php
            $principalSpeech = \App\Models\PrincipalSpeech::first();
            $speechTitle = $frontendSpeechTitle ?? "Principal's Message";
            $principalSpeechLead = !empty($config->principalImportantSpeech)
                ? $config->principalImportantSpeech
                : (!empty($principalSpeech?->importantSpeech) ? $principalSpeech->importantSpeech : 'We want to make good students as well as good people.');
            $principalSpeechBody = !empty($config->principalGeneralSpeech)
                ? $config->principalGeneralSpeech
                : (!empty($principalSpeech?->generalSpeech) ? $principalSpeech->generalSpeech : '');
            $principalSpeechExcerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($principalSpeechBody))), 130, '...');
        @endphp
        <div class="col-10 mx-auto">
            <div class="sidebar-section mb-3 principal-message-box">
                <div class="section-heading"><i class="fa-solid fa-quote-left me-1"></i> <span>{{ $speechTitle }}</span></div>
                <div class="p-3 bg-white">
                    <p class="small fw-semibold text-secondary mb-2">“{{ $principalSpeechLead }}”</p>
                    <p class="small text-muted mb-3">{{ $principalSpeechExcerpt ?: 'A brief note from the head of institute.' }}</p>
                    <a class="btn btn-outline-success btn-sm w-100" href="{{ route('principalSpeechPage') }}">Read More</a>
                </div>
            </div>
        </div>
        @if(!empty($config->eduMinName))
        <div class="col-10 mx-auto">
            <div class="sidebar-section sidebar-resource-box mb-3">
                <div class="section-heading"><i class="fa-solid fa-toolbox me-1"></i> <span>Resources</span></div>
                <div class="px-3 py-2 small">
                    <div class="resource-sub mb-3 sidebar-links">
                        <div class="subheading"><i class="fa-solid fa-bolt me-1"></i> Quick Access</div>
                        <ul class="list-unstyled mb-0 sidebar-list link-list">
                            <li><a href="{{ route('newSyllabus') }}" class="sidebar-link">Syllabus</a></li>
                            <li><a href="{{ route('newClassSchedule') }}" class="sidebar-link">Class Routine</a></li>
                            <li><a href="{{ route('newExamSchedule') }}" class="sidebar-link">Exam Routine</a></li>
                            <li><a href="{{ route('internalResult') }}" class="sidebar-link">Result Archive</a></li>
                        </ul>
                    </div>
                    <div class="resource-sub mb-3 sidebar-links">
                        <div class="subheading"><i class="fa-solid fa-globe me-1"></i> Important Links</div>
                        <ul class="list-unstyled mb-0 sidebar-list link-list">
                            <li><a href="#" class="sidebar-link"> গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</a></li>
                            <li><a href="#" class="sidebar-link"> শিক্ষা মন্ত্রণালয়</a></li>
                            <li><a href="#" class="sidebar-link"> মাধ্যমিক ও উচ্চশিক্ষা অধিদপ্তর</a></li>
                            <li><a href="#" class="sidebar-link"> মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড</a></li>
                            <li><a href="#" class="sidebar-link"> মাধ্যমিক ও উচ্চ শিক্ষা বিভাগ</a></li>
                            <li><a href="#" class="sidebar-link"> ই-বুক</a></li>
                            <li><a href="#" class="sidebar-link"> আই-বুক</a></li>
                            <li><a href="#" class="sidebar-link"> মাউশি</a></li>
                        </ul>
                    </div>
                    <div class="resource-sub mb-3">
                        <div class="subheading"><i class="fa-solid fa-music me-1"></i> National Song</div>
                        <audio controls class="w-100 sidebar-audio">
                            <source src="{{ config('app.url') }}/public/music/bd_national_anthem.mp3" type="audio/mpeg" />
                        </audio>
                    </div>
                    <div class="resource-sub sidebar-links">
                        <div class="subheading"><i class="fa-solid fa-screwdriver-wrench me-1"></i> Internal eService</div>
                        <ul class="list-unstyled mb-0 sidebar-list">
                            <li><a href="#" class="sidebar-link"><i class="fa-solid fa-envelope"></i> Webmail</a></li>
                            <li><a href="#" class="sidebar-link"><i class="fa-solid fa-user"></i> Teacher Login</a></li>
                            <li><a href="#" class="sidebar-link"><i class="fa-solid fa-circle-question"></i> Complain/Suggestion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(!empty($config->boardChairmanName))
        @endif
    </div>
