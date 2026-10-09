{{-- Fixed decorative artwork: independent of institution records and uploaded media. --}}
@if($variant === 'campus')
<svg class="about-hero-visual" viewBox="0 0 1000 360" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="about-campus-sky" x2="0" y2="1"><stop stop-color="#b8e1f7"/><stop offset="1" stop-color="#f0fbff"/></linearGradient>
        <linearGradient id="about-campus-wall" x2="0" y2="1"><stop stop-color="#f8fdff"/><stop offset="1" stop-color="#a9cee3"/></linearGradient>
        <pattern id="about-campus-windows" width="72" height="67" patternUnits="userSpaceOnUse"><rect x="15" y="12" width="36" height="40" rx="2" fill="#6dacca"/><path d="M33 12v40M15 32h36" stroke="#e5f5fc" stroke-width="3"/></pattern>
    </defs>
    <rect width="1000" height="360" fill="url(#about-campus-sky)"/>
    <path d="M0 340Q220 270 470 331T1000 299V360H0Z" fill="#a8d0df"/>
    <path d="M340 160h520v178H340Z" fill="url(#about-campus-wall)" stroke="#a7cddd" stroke-width="3"/>
    <path d="M319 163l281-90 281 90Z" fill="#81b6d0"/><path d="M350 157l250-74 250 74Z" fill="#edf8ff"/>
    <rect x="364" y="174" width="473" height="152" fill="url(#about-campus-windows)"/>
    <path d="M350 235h499M350 303h499" stroke="#e7f6fb" stroke-width="10"/>
    <rect x="548" y="178" width="104" height="160" fill="#dceff8"/><rect x="570" y="266" width="60" height="72" rx="26" fill="#5f93ad"/>
    <path d="M535 338h130l25 19H510Z" fill="#e8f4f9"/>
    <g fill="#89b8c6"><ellipse cx="299" cy="223" rx="47" ry="72"/><ellipse cx="890" cy="204" rx="56" ry="83"/></g>
    <path d="M299 272v69m590-82v82" stroke="#729aac" stroke-width="7"/>
    <path d="M186 88q12-12 24 0m29 9q12-12 24 0" fill="none" stroke="#80aec7" stroke-width="3"/>
    <circle cx="850" cy="52" r="29" fill="#ecfaff" opacity=".65"/>
</svg>
@else
<svg class="about-journey-visual" viewBox="0 0 220 200" aria-hidden="true" focusable="false">
    <defs><linearGradient id="about-book-page" x2="1" y2="1"><stop stop-color="#fff"/><stop offset="1" stop-color="#d6edf8"/></linearGradient></defs>
    <ellipse cx="110" cy="175" rx="95" ry="12" fill="#86bbd2" opacity=".2"/>
    <path d="M16 69q48-24 94 2 47-26 94-2v99q-46-22-94 1-48-22-94-1Z" fill="#216596"/>
    <path d="M21 58q44-20 89 7v95q-43-27-89-7Zm89 7q43-27 89-7v95q-43-24-89 7Z" fill="url(#about-book-page)" stroke="#b5d8e8" stroke-width="2"/>
    <path d="M110 68v89M33 80q29-8 61 9M33 101q29-8 61 9M33 122q29-8 61 9M126 90q28-18 60-12M126 111q28-18 60-12M126 132q28-18 60-12" fill="none" stroke="#8fbacf" stroke-width="3"/>
    <path d="m153 26 10-15 10 15-10 15Z" fill="#19b4d0"/><circle cx="62" cy="32" r="7" fill="#81c5e1"/><path d="M99 22v14m-7-7h14" stroke="#448fac" stroke-width="3"/>
</svg>
@endif
