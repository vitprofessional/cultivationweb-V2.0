@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Teacher Profile
@endsection
@php $config = App\Models\ServerConfig::first(); @endphp
@section('frontcontent')
<style>
.teacher-single-page {
    --ts-primary: #f4a024;
    --ts-primary-deep: #d97e00;
    --ts-title: #102542;
    --ts-text: #4f5f75;
    --ts-soft: #f4f8fd;
    --ts-border: #dbe7f5;
    --ts-card-shadow: 0 14px 34px -20px rgba(16, 37, 66, .45);
    margin: 28px auto 36px;
}

.teacher-single-page .ts-wrap {
    background: #fff;
    border: 1px solid var(--ts-border);
    border-radius: 22px;
    box-shadow: var(--ts-card-shadow);
    padding: 22px;
}

.teacher-single-page .ts-left,
.teacher-single-page .ts-right {
    height: 100%;
}

.teacher-single-page .ts-left {
    background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
    border: 1px solid var(--ts-border);
    border-radius: 16px;
    overflow: hidden;
}

.teacher-single-page .ts-photo-wrap {
    background: #edf4fe;
    aspect-ratio: 4 / 4.2;
}

.teacher-single-page .ts-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

.teacher-single-page .ts-left-content {
    padding: 18px 16px 16px;
    text-align: center;
}

.teacher-single-page .ts-name {
    margin: 0;
    font-size: 24px;
    line-height: 1.2;
    color: var(--ts-title);
    font-weight: 800;
}

.teacher-single-page .ts-designation {
    margin: 8px 0 0;
    color: var(--ts-primary-deep);
    font-size: 14px;
    font-weight: 700;
    letter-spacing: .02em;
}

.teacher-single-page .ts-contact-list {
    margin: 14px 0 0;
    padding: 0;
    list-style: none;
    display: grid;
    gap: 8px;
    text-align: left;
}

.teacher-single-page .ts-contact-list li {
    font-size: 13px;
    line-height: 1.45;
    color: #30445f;
    background: #fff;
    border: 1px solid var(--ts-border);
    border-radius: 10px;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.teacher-single-page .ts-contact-list i {
    width: 16px;
    text-align: center;
    color: var(--ts-primary-deep);
}

.teacher-single-page .ts-contact-list a {
    color: #1d3f73;
    text-decoration: none;
    word-break: break-word;
}

.teacher-single-page .ts-contact-list a:hover {
    color: var(--ts-primary-deep);
}

.teacher-single-page .ts-social {
    margin-top: 14px;
    display: flex;
    justify-content: center;
    gap: 8px;
}

.teacher-single-page .ts-social a {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid #d5e4f7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #264b7f;
    background: #fff;
    transition: .2s ease;
}

.teacher-single-page .ts-social a:hover {
    background: var(--ts-primary);
    border-color: var(--ts-primary);
    color: #fff;
    transform: translateY(-2px);
}

.teacher-single-page .ts-photo-intro {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px dashed #d7e5f6;
    text-align: left;
}

.teacher-single-page .ts-photo-intro .ts-subtitle {
    font-size: 13px;
    line-height: 1.6;
}

.teacher-single-page .ts-photo-intro .ts-summary {
    margin-top: 10px;
}

.teacher-single-page .ts-photo-intro .ts-section {
    margin-top: 14px;
}

.teacher-single-page .ts-photo-intro .ts-section h5 {
    font-size: 17px;
    margin-bottom: 8px;
}

.teacher-single-page .ts-photo-intro .ts-description {
    font-size: 13px;
    line-height: 1.7;
}

.teacher-single-page .ts-right {
    border: 1px solid var(--ts-border);
    border-radius: 16px;
    padding: 22px;
    background: #fff;
}

.teacher-single-page .ts-kicker {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #8a5a08;
    font-weight: 800;
    background: #fff3df;
    border: 1px solid #ffd79e;
    border-radius: 999px;
    padding: 5px 10px;
}

.teacher-single-page .ts-main-title {
    margin: 12px 0 6px;
    color: var(--ts-title);
    font-size: 30px;
    line-height: 1.2;
    font-weight: 800;
}

.teacher-single-page .ts-subtitle {
    margin: 0;
    color: #556a85;
    font-size: 15px;
    line-height: 1.6;
}

.teacher-single-page .ts-summary {
    margin: 16px 0 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.teacher-single-page .ts-summary li {
    font-size: 12px;
    color: #2f4664;
    background: var(--ts-soft);
    border: 1px solid #d8e8fa;
    border-radius: 999px;
    padding: 6px 11px;
    font-weight: 700;
}

.teacher-single-page .ts-section {
    margin-top: 22px;
}

.teacher-single-page .ts-right .ts-section:first-child {
    margin-top: 0;
}

.teacher-single-page .ts-section h5 {
    margin: 0 0 10px;
    color: var(--ts-title);
    font-size: 18px;
    font-weight: 800;
}

.teacher-single-page .ts-description {
    margin: 0;
    color: var(--ts-text);
    font-size: 14px;
    line-height: 1.78;
}

.teacher-single-page .ts-info-grid {
    margin-top: 10px;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.teacher-single-page .ts-info-item {
    border: 1px solid var(--ts-border);
    background: #fafdff;
    border-radius: 12px;
    padding: 10px 11px;
}

.teacher-single-page .ts-info-item .label {
    display: block;
    color: #6a7f9b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 2px;
    font-weight: 700;
}

.teacher-single-page .ts-info-item .value {
    color: #19385f;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.45;
    word-break: break-word;
}

.teacher-single-page .ts-footer-actions {
    margin-top: 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.teacher-single-page .ts-btn {
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 15px;
    text-decoration: none;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.teacher-single-page .ts-btn-primary {
    background: var(--ts-primary);
    color: #fff;
}

.teacher-single-page .ts-btn-primary:hover {
    color: #fff;
    background: var(--ts-primary-deep);
}

.teacher-single-page .ts-btn-light {
    background: #fff;
    color: #294c7a;
    border-color: #cadcf3;
}

.teacher-single-page .ts-btn-light:hover {
    background: #f1f7ff;
    color: #1b3c6c;
}

@media (max-width: 991.98px) {
    .teacher-single-page .ts-right {
        margin-top: 16px;
    }

    .teacher-single-page .ts-main-title {
        font-size: 24px;
    }
}

@media (max-width: 767.98px) {
    .teacher-single-page .ts-wrap {
        padding: 14px;
        border-radius: 16px;
    }

    .teacher-single-page .ts-right {
        padding: 15px;
    }

    .teacher-single-page .ts-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@php
    $name = trim(($teacher->firstName ?? '').' '.($teacher->lastName ?? ''));
    $name = $name !== '' ? $name : 'Unknown';
    $designation = \App\Models\TeacherManagement::getDesignationName($teacher->designation ?? null);
    $photo = !empty($teacher->avatar)
        ? config('app.url').'/public/upload/image/teacher/'.rawurlencode(basename($teacher->avatar))
        : config('app.url').'/public/avatar.png';

    $pickValue = function(array $keys) use ($teacher) {
        foreach ($keys as $key) {
            $value = $teacher->{$key} ?? null;
            if (is_string($value) && trim($value) === '') {
                continue;
            }
            if (!empty($value) || $value === '0' || $value === 0) {
                return $value;
            }
        }
        return null;
    };

    $joinDate = $pickValue(['joinDate', 'joining_date', 'joiningDate', 'join_date']);
    $subjectName = $pickValue(['subject', 'subjectName', 'subject_name']);
    $teacherId = $pickValue(['teacherId', 'teacher_id', 'empId', 'employee_id', 'idNo', 'id_no']);
    $gender = $pickValue(['gender']);
    $bloodGroup = $pickValue(['bloodGroup', 'blood_group', 'blGroup', 'blgroup']);
    $religion = $pickValue(['religion', 'relegion']);
    $phone = $pickValue(['mobile', 'phone', 'phoneNumber', 'phone_number']);
    $mpoIndex = $pickValue(['mpoIndex', 'mpo_index', 'mpoindex']);
    $pdsId = $pickValue(['pdsId', 'pds_id', 'pdsid']);

    $resolveOption = function ($value, array $map) {
        if ($value === null) {
            return null;
        }

        $raw = is_string($value) ? trim($value) : (string) $value;
        if ($raw === '') {
            return null;
        }

        if (array_key_exists($raw, $map)) {
            return $map[$raw];
        }

        $normalized = strtolower($raw);
        foreach ($map as $key => $label) {
            if (strtolower((string) $key) === $normalized) {
                return $label;
            }
        }

        return $raw;
    };

    $gender = $resolveOption($gender, [
        '1' => 'Male',
        '2' => 'Female',
        '3' => 'Other',
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'others' => 'Other',
    ]);

    $religion = $resolveOption($religion, [
        '1' => 'Islam',
        '2' => 'Hindu',
        '3' => 'Christian',
        '4' => 'Buddhist',
        '5' => 'Other',
        'islam' => 'Islam',
        'hindu' => 'Hindu',
        'christian' => 'Christian',
        'buddish' => 'Buddhist',
        'buddhist' => 'Buddhist',
        'others' => 'Other',
    ]);

    $bloodGroup = $resolveOption($bloodGroup, [
        '1' => 'A+',
        '2' => 'A-',
        '3' => 'B+',
        '4' => 'B-',
        '5' => 'O+',
        '6' => 'O-',
        '7' => 'AB+',
        '8' => 'AB-',
        'a+' => 'A+',
        'a-' => 'A-',
        'b+' => 'B+',
        'b-' => 'B-',
        'o+' => 'O+',
        'o-' => 'O-',
        'ab+' => 'AB+',
        'ab-' => 'AB-',
    ]);

    $socialLinks = [
        ['label' => 'Facebook', 'icon' => 'fa-facebook-f', 'url' => $pickValue(['facebook', 'facebook_url', 'fb', 'fb_link'])],
        ['label' => 'Twitter', 'icon' => 'fa-twitter', 'url' => $pickValue(['twitter', 'twitter_url', 'x', 'x_url'])],
        ['label' => 'LinkedIn', 'icon' => 'fa-linkedin-in', 'url' => $pickValue(['linkedin', 'linkedin_url'])],
        ['label' => 'YouTube', 'icon' => 'fa-youtube', 'url' => $pickValue(['youtube', 'youtube_url'])],
        ['label' => 'Website', 'icon' => 'fa-globe', 'url' => $pickValue(['website', 'web', 'site'])],
    ];

    $summaryItems = [];
    if (!empty($designation)) {
        $summaryItems[] = $designation;
    }
    if (!empty($subjectName)) {
        $summaryItems[] = 'Subject: '.$subjectName;
    }
    if (!empty($joinDate)) {
        $summaryItems[] = 'Joined: '.$joinDate;
    }
    if (!empty($teacherId)) {
        $summaryItems[] = 'ID: '.$teacherId;
    }

@endphp
<div class="container teacher-single-page">
    <div class="ts-wrap">
        <div class="row">
            <div class="col-lg-5">
                <aside class="ts-left">
                    <div class="ts-photo-wrap">
                        <img class="ts-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';this.style.objectFit='contain';this.style.padding='18px';this.style.background='#edf4fe';">
                    </div>
                    <div class="ts-left-content">
                        <h2 class="ts-name">{{ e($name) }}</h2>

                        <div class="ts-photo-intro">

                            @if(count($summaryItems) > 0)
                                <ul class="ts-summary">
                                    @foreach($summaryItems as $item)
                                        <li>{{ e($item) }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="ts-section">
                                <h5>About Teacher</h5>
                                <p class="ts-description">
                                    @if(!empty($teacher->description))
                                        {!! nl2br(e($teacher->description)) !!}
                                    @else
                                        Professional faculty member dedicated to student progress, classroom excellence, and institutional values.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <ul class="ts-contact-list">
                            @if(!empty($teacher->email))
                                <li>
                                    <i class="fa fa-envelope"></i>
                                    <a href="mailto:{{ e($teacher->email) }}">{{ e($teacher->email) }}</a>
                                </li>
                            @endif
                            @if(!empty($teacher->mobile))
                                <li>
                                    <i class="fa fa-phone"></i>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $teacher->mobile) }}">{{ e($teacher->mobile) }}</a>
                                </li>
                            @endif
                            @if(!empty($teacher->address))
                                <li>
                                    <i class="fa fa-map-marker"></i>
                                    <span>{{ e($teacher->address) }}</span>
                                </li>
                            @endif
                        </ul>

                        @php
                            $hasSocial = false;
                        @endphp
                        @foreach($socialLinks as $social)
                            @if(!empty($social['url']))
                                @php $hasSocial = true; @endphp
                            @endif
                        @endforeach

                        @if($hasSocial)
                            <div class="ts-social" aria-label="Social links">
                                @foreach($socialLinks as $social)
                                    @if(!empty($social['url']))
                                        <a href="{{ e($social['url']) }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                                            <i class="fa {{ $social['icon'] }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </aside>
            </div>

            <div class="col-lg-7">
                <section class="ts-right">
                    <div class="ts-section">
                        <h5>Professional Details</h5>
                        <div class="ts-info-grid">
                            <div class="ts-info-item">
                                <span class="label">Email</span>
                                <span class="value">{{ !empty($teacher->email) ? e($teacher->email) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">Phone</span>
                                <span class="value">{{ !empty($phone) ? e($phone) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">Blood Group</span>
                                <span class="value">{{ !empty($bloodGroup) ? e($bloodGroup) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">Relegion</span>
                                <span class="value">{{ !empty($religion) ? e($religion) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">Join Date</span>
                                <span class="value">{{ !empty($joinDate) ? e($joinDate) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">Gender</span>
                                <span class="value">{{ !empty($gender) ? e($gender) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">MPO INDEX</span>
                                <span class="value">{{ !empty($mpoIndex) ? e($mpoIndex) : 'Not available' }}</span>
                            </div>
                            <div class="ts-info-item">
                                <span class="label">PDS ID</span>
                                <span class="value">{{ !empty($pdsId) ? e($pdsId) : 'Not available' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="ts-footer-actions">
                        <a href="{{ route('teacherPage') }}" class="ts-btn ts-btn-light"><i class="fa fa-arrow-left"></i> Back to Teachers</a>
                        @if(!empty($teacher->email))
                            <a href="mailto:{{ e($teacher->email) }}" class="ts-btn ts-btn-primary"><i class="fa fa-paper-plane"></i> Send Email</a>
                        @endif
                        @if(!empty($teacher->mobile))
                            <a href="tel:{{ preg_replace('/\s+/', '', $teacher->mobile) }}" class="ts-btn ts-btn-primary"><i class="fa fa-phone"></i> Call Now</a>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection