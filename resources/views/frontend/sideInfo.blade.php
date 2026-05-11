@php
    $config = \App\Models\ServerConfig::first();
    $principalAvatar = !empty($config?->avatar)
        ? config('app.url').'/public/upload/image/cultivation/'.rawurlencode(basename($config->avatar))
        : config('app.url').'/public/avatar.png';
@endphp

@if(!empty($config->eduMinName))
<div class="sidebar-section mb-3">
    <div class="section-heading"><i class="fa-solid fa-user-tie me-1"></i> <span>Education Minister</span></div>
    <div class="text-center p-3">
        @php($eduImg = !empty($config->eduMinImg) ? config('app.url').'/public/upload/image/cultivation/'.rawurlencode(basename($config->eduMinImg)) : config('app.url').'/public/avatar.png')
        <img class="avatar-circle mb-2" src="{{ $eduImg }}" alt="Education Minister portrait">
        <p class="fw-semibold small mb-0">{{ $config->eduMinName }}</p>
    </div>
</div>
@endif

@if(!empty($config->boardChairmanName))
<div class="sidebar-section mb-3">
    <div class="section-heading"><i class="fa-solid fa-user-tie me-1"></i> <span>Board Chairman</span></div>
    <div class="text-center p-3">
        @php($bcImg = !empty($config->boardChairmanImg) ? config('app.url').'/public/upload/image/cultivation/'.rawurlencode(basename($config->boardChairmanImg)) : config('app.url').'/public/avatar.png')
        <img class="avatar-circle mb-2" src="{{ $bcImg }}" alt="Board Chairman portrait">
        <p class="fw-semibold small mb-0">{{ $config->boardChairmanName }}</p>
    </div>
</div>
@endif

<div class="sidebar-section sidebar-resource-box mb-3" aria-labelledby="resourcesHeading">
    <div class="section-heading" id="resourcesHeading"><i class="fa-solid fa-toolbox me-1"></i> <span>Resources</span></div>
    <div class="px-3 py-2 small">
        <div class="sidebar-section mb-3 sidebar-links small" aria-labelledby="quickAccessHeading">
            <div class="section-heading" id="quickAccessHeading"><i class="fa-solid fa-bolt me-1"></i> <span>Quick Access</span></div>
            <div class="px-3 pb-2">
                <ul class="list-unstyled mb-0 sidebar-list link-list">
                    <li><a href="{{ route('newSyllabus') }}" class="sidebar-link">Syllabus</a></li>
                    <li><a href="{{ route('newClassSchedule') }}" class="sidebar-link">Class Routine</a></li>
                    <li><a href="{{ route('newExamSchedule') }}" class="sidebar-link">Exam Routine</a></li>
                    <li><a href="{{ route('internalResult') }}" class="sidebar-link">Result Archive</a></li>
                </ul>
            </div>
        </div>
        <div class="sidebar-section mb-3 sidebar-links small" aria-labelledby="linksHeading">
            <div class="section-heading" id="linksHeading"><i class="fa-solid fa-globe me-1"></i> <span>Important Links</span></div>
            <div class="px-3 pb-2">
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
        </div>
        <div class="sidebar-section mb-3" aria-labelledby="songHeading">
            <div class="section-heading" id="songHeading"><i class="fa-solid fa-music me-1"></i> <span>National Song</span></div>
            <div class="px-3 pb-2">
                <audio controls class="w-100 sidebar-audio">
                    <source src="{{ config('app.url') }}/public/music/bd_national_anthem.mp3" type="audio/mpeg" />
                </audio>
            </div>
        </div>
        <div class="sidebar-section mb-3 sidebar-links small" aria-labelledby="serviceHeading">
            <div class="section-heading" id="serviceHeading"><i class="fa-solid fa-screwdriver-wrench me-1"></i> <span>Internal eService</span></div>
            <div class="px-3 pb-2">
                <ul class="list-unstyled mb-0 sidebar-list link-list">
                    <li><a href="#" class="sidebar-link"> Webmail</a></li>
                    <li><a href="#" class="sidebar-link"> Teacher Login</a></li>
                    <li><a href="#" class="sidebar-link"> Complain/Suggestion</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
