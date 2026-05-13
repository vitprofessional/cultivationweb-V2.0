@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Teacher List
@endsection
@php
$config = App\Models\ServerConfig::first();
$teachers = $Datakey ?? collect();
@endphp
@section('frontcontent')
<style>
    .teacher-directory-shell {
        background: linear-gradient(180deg, #f8fbff 0%, #f2f8fd 100%);
        border: 1px solid #dce9f4;
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 12px 28px rgba(19, 54, 102, 0.08);
    }

    .teacher-directory-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 14px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #dce9f4;
    }

    .teacher-directory-head .title-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .teacher-directory-head .kicker {
        display: inline-flex;
        width: fit-content;
        color: #0c7da7;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 5px 10px;
        border-radius: 999px;
        background: #dff4fb;
        border: 1px solid #bce7f4;
    }

    .teacher-directory-head h2 {
        margin: 0;
        font-size: 22px;
        line-height: 1.2;
        color: #112958;
        font-weight: 800;
    }

    .teacher-directory-head p {
        margin: 0;
        color: #5a708a;
        font-size: 14px;
        line-height: 1.6;
    }

    .teacher-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e7f9fb;
        color: #1285af;
        border: 1px solid #b8e8f5;
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .teacher-card {
        height: 100%;
        background: #fff;
        border: 1px solid #dce9f4;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(19, 54, 102, 0.08);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .teacher-card:hover {
        transform: translateY(-3px);
        border-color: #bdd9ed;
        box-shadow: 0 16px 30px rgba(19, 54, 102, 0.14);
    }

    .teacher-photo-wrap {
        display: block;
        position: relative;
        background: #eef5fb;
    }

    .teacher-photo {
        width: 100%;
        height: 252px;
        object-fit: cover;
        object-position: center top;
        display: block;
        transition: transform .35s ease;
    }

    .teacher-card:hover .teacher-photo {
        transform: scale(1.04);
    }

    .teacher-body {
        padding: 14px;
    }

    .teacher-name {
        margin: 0 0 5px;
        font-size: 17px;
        line-height: 1.25;
        font-weight: 800;
    }

    .teacher-name a {
        color: #112958;
    }

    .teacher-name a:hover {
        color: #21a7d0;
    }

    .teacher-designation {
        margin: 0 0 10px;
        color: #4f6783;
        font-size: 14px;
        font-weight: 600;
    }

    .teacher-meta-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin: 0 0 10px;
    }

    .teacher-meta-chips span {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 999px;
        border: 1px solid #d9e8f5;
        background: #f4f9ff;
        color: #355474;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
    }

    .teacher-contact-line {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 7px;
        font-size: 13px;
        line-height: 1.4;
        color: #5d748e;
        min-height: 20px;
    }

    .teacher-contact-line i {
        color: #21a7d0;
        width: 14px;
        text-align: center;
        flex-shrink: 0;
    }

    .teacher-contact-line a {
        color: #4f6783;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .teacher-contact-line a:hover {
        color: #21a7d0;
    }

    .teacher-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .teacher-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 7px 10px;
        border-radius: 8px;
        border: 1px solid #cde3f1;
        background: #fff;
        color: #1f5078;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
    }

    .teacher-action-btn:hover {
        border-color: #21a7d0;
        color: #fff;
        background: #21a7d0;
    }

    .teacher-empty {
        padding: 20px;
        border-radius: 12px;
        border: 1px dashed #cfe3ec;
        background: #f8fbfc;
        text-align: center;
        color: #6d7d8b;
    }

    @media (max-width: 991.98px) {
        .teacher-photo {
            height: 230px;
        }
    }

    @media (max-width: 575.98px) {
        .teacher-directory-shell {
            padding: 12px;
        }

        .teacher-directory-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .teacher-directory-head h2 {
            font-size: 18px;
        }

        .teacher-photo {
            height: 210px;
        }
    }
</style>
<div class="col-12">
    <div class="teacher-directory-shell">
        <div class="teacher-directory-head">
            <div class="title-wrap">
                <span class="kicker">Academic Directory</span>
                <h2>{{ !empty($config->instituteName) ? $config->instituteName : 'Jahanara Ayub Academy' }} Teacher Directory</h2>
                <p>Meet our faculty members and view their academic profiles and official contact information.</p>
            </div>
            <span class="teacher-count"><i class="fa fa-users"></i> {{ $teachers->count() }} {{ $teachers->count() === 1 ? 'Teacher' : 'Teachers' }}</span>
        </div>

        @if($teachers->count() > 0)
            <div class="row">
                @foreach($teachers as $data)
                    @php
                        $pickValue = function ($item, array $keys) {
                            foreach ($keys as $key) {
                                $value = $item->{$key} ?? null;
                                if (is_string($value) && trim($value) === '') {
                                    continue;
                                }
                                if (!empty($value) || $value === '0' || $value === 0) {
                                    return $value;
                                }
                            }
                            return null;
                        };

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

                        $name = trim(($data->firstName ?? '') . ' ' . ($data->lastName ?? ''));
                        $name = $name !== '' ? $name : 'Unknown';
                        $designation = \App\Models\TeacherManagement::getDesignationName($data->designation ?? null);
                        $designation = !empty($designation) ? $designation : 'Faculty Member';
                        $genderText = $resolveOption($pickValue($data, ['gender']), [
                            '1' => 'Male',
                            '2' => 'Female',
                            '3' => 'Other',
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                            'others' => 'Other',
                        ]);
                        $religionText = $resolveOption($pickValue($data, ['religion', 'relegion']), [
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
                            'other' => 'Other',
                            'others' => 'Other',
                        ]);
                        $bloodGroupText = $resolveOption($pickValue($data, ['bloodGroup', 'blood_group', 'blGroup', 'blgroup']), [
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
                        $photo = !empty($data->avatar)
                            ? config('app.url') . '/public/upload/image/teacher/' . rawurlencode(basename($data->avatar))
                            : config('app.url') . '/public/avatar.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="teacher-card">
                            <a class="teacher-photo-wrap" href="{{ route('teacher.show', ['id' => $data->id]) }}" aria-label="View {{ e($name) }} profile">
                                <img class="teacher-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';this.style.objectFit='contain';this.style.padding='20px';this.style.background='#eef5fb';">
                            </a>
                            <div class="teacher-body">
                                <h3 class="teacher-name">
                                    <a href="{{ route('teacher.show', ['id' => $data->id]) }}">{{ e($name) }}</a>
                                </h3>
                                <p class="teacher-designation">{{ e($designation) }}</p>

                                @if(!empty($genderText) || !empty($religionText) || !empty($bloodGroupText))
                                    <div class="teacher-meta-chips">
                                        @if(!empty($genderText))
                                            <span>{{ e($genderText) }}</span>
                                        @endif
                                        @if(!empty($religionText))
                                            <span>{{ e($religionText) }}</span>
                                        @endif
                                        @if(!empty($bloodGroupText))
                                            <span>{{ e($bloodGroupText) }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="teacher-contact-line">
                                    <i class="fa fa-phone"></i>
                                    @if(!empty($data->mobile))
                                        <a href="tel:{{ preg_replace('/\s+/', '', $data->mobile) }}" title="Call {{ e($name) }}">{{ e($data->mobile) }}</a>
                                    @else
                                        <span>Phone not available</span>
                                    @endif
                                </div>
                                <div class="teacher-contact-line">
                                    <i class="fa fa-envelope"></i>
                                    @if(!empty($data->email))
                                        <a href="mailto:{{ e($data->email) }}" title="Email {{ e($name) }}">{{ e($data->email) }}</a>
                                    @else
                                        <span>Email not available</span>
                                    @endif
                                </div>

                                <div class="teacher-actions">
                                    <a class="teacher-action-btn" href="{{ route('teacher.show', ['id' => $data->id]) }}"><i class="fa fa-eye"></i> Profile</a>
                                    @if(!empty($data->mobile))
                                        <a class="teacher-action-btn" href="tel:{{ preg_replace('/\s+/', '', $data->mobile) }}"><i class="fa fa-phone"></i> Call</a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="teacher-empty">No teacher records found.</div>
        @endif
    </div>
</div>

@endsection