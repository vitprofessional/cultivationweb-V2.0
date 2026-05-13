@extends($frontendLayout ?? config('frontend.layout'))
@section('fronttitle')
Student Profile
@endsection
@php $config = App\Models\ServerConfig::first(); @endphp
@section('frontcontent')
<style>
  .student-profile-shell {
    max-width: 1120px;
    margin: 1.5rem auto 0;
    padding: 24px;
    border-radius: 24px;
    background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
    border: 1px solid #dfeaf3;
    box-shadow: 0 22px 50px rgba(17, 41, 88, 0.08);
  }

  .student-profile-card {
    position: relative;
    overflow: hidden;
    display: grid;
    grid-template-columns: 240px minmax(0, 1fr);
    gap: 24px;
    padding: 30px;
    border-radius: 22px;
    background: #ffffff;
    border: 1px solid #dfe8f1;
    box-shadow: 0 16px 34px rgba(17, 41, 88, 0.08);
  }

  .student-profile-card::before {
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 132px;
    background: linear-gradient(135deg, #112958, #1a74b8);
  }

  .student-profile-card > * {
    position: relative;
    z-index: 1;
  }

  .student-profile-media {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    padding-top: 18px;
  }

  .student-profile-photo-ring {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 196px;
    height: 196px;
    padding: 8px;
    border-radius: 50%;
    background: linear-gradient(135deg, #20a8df, #ffffff);
    box-shadow: 0 20px 36px rgba(17, 41, 88, 0.16);
  }

  .student-profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    background: #fff;
    border: 6px solid #fff;
  }

  .student-profile-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #e9f7fd;
    border: 1px solid #cbeefe;
    color: #13739b;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
  }

  .student-profile-body {
    padding-top: 6px;
  }

  .student-profile-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.14);
    color: #dff4ff;
    border: 1px solid rgba(255, 255, 255, 0.14);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .09em;
    text-transform: uppercase;
  }

  .student-profile-name {
    margin: 12px;
    color: #ffffff !important;
    font-size: 22px;
    line-height: 1.15 !important;
    font-weight: 800 !important;
    text-transform: uppercase;
    letter-spacing: .01em;
  }

  .student-profile-summary {
    margin: 0 0 16px;
    color: #5a708a;
    font-size: 15px;
    line-height: 1.7;
    max-width: 720px;
  }

  .student-profile-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0 0 18px;
  }

  .student-profile-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 12px;
    background: #f2f7fb;
    border: 1px solid #dceaf3;
    color: #294565;
    font-size: 14px;
    font-weight: 700;
    line-height: 1;
  }

  .student-profile-chip i {
    color: #1fa0d1;
  }

  .student-profile-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
  }

  .student-profile-item {
    min-height: 76px;
    padding: 14px 16px;
    border-radius: 14px;
    background: linear-gradient(180deg, #f8fafc 0%, #f2f6fb 100%);
    border: 1px solid #dfe8f1;
  }

  .student-profile-item .label {
    display: block;
    margin-bottom: 6px;
    color: #647992;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
  }

  .student-profile-item .value {
    display: block;
    color: #213a5b;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.4;
  }

  .student-profile-actions {
    display: flex;
    justify-content: center;
    margin-top: 18px;
  }

  .student-profile-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 46px;
    padding: 11px 18px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid #20a8df;
    color: #138ac0;
    font-weight: 800;
    box-shadow: 0 10px 20px rgba(32, 168, 223, 0.12);
  }

  .student-profile-back:hover,
  .student-profile-back:focus {
    color: #fff;
    background: #20a8df;
    border-color: #20a8df;
  }

  @media (max-width: 991.98px) {
    .student-profile-card {
      grid-template-columns: 1fr;
    }

    .student-profile-media {
      padding-top: 26px;
    }

    .student-profile-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 575.98px) {
    .student-profile-shell {
      padding: 14px;
      border-radius: 18px;
    }

    .student-profile-card {
      padding: 18px;
      gap: 18px;
    }

    .student-profile-photo-ring {
      width: 164px;
      height: 164px;
    }

    .student-profile-name {
      font-size: 19px;
    }

    .student-profile-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@php
  $name = trim(($student->fullName ?? '') . ' ' . ($student->sureName ?? '')) ?: 'Unknown';
  $photo = !empty($student->avatar)
    ? config('app.url') . '/public/upload/image/student/' . rawurlencode(basename($student->avatar))
    : config('app.url') . '/public/avatar.png';
  $studentId = $student->stdId ?? '-';
  $className = optional($class)->className ?? '-';
  $departmentName = optional($dept)->departmentName ?? '-';
  $sectionName = optional($section)->section ?? '-';
  $sessionName = optional($session)->session ?? '-';
@endphp
<div class="container">
  <div class="student-profile-shell">
    <div class="student-profile-card">
      <div class="student-profile-media">
        <div class="student-profile-photo-ring">
          <img class="student-profile-photo" src="{{ $photo }}" alt="{{ e($name) }}" loading="lazy"
             onerror="this.onerror=null;this.src='{{ asset('public/avatar.png') }}';">
        </div>
        <div class="student-profile-tag"><i class="fa fa-user"></i> Student Record</div>
      </div>
      <div class="student-profile-body">
        <div class="student-profile-kicker"><i class="fa fa-shield"></i> Verified Student Profile</div>
        <h1 class="student-profile-name">{{ e($name) }}</h1>
        <p class="student-profile-summary">A concise overview of the student's academic placement and current enrollment information.</p>

        <div class="student-profile-chips">
          <span class="student-profile-chip"><i class="fa fa-id-badge"></i> {{ e($studentId) }}</span>
          <span class="student-profile-chip"><i class="fa fa-graduation-cap"></i> {{ e($className) }}</span>
          <span class="student-profile-chip"><i class="fa fa-users"></i> {{ e($sectionName) }}</span>
          <span class="student-profile-chip"><i class="fa fa-calendar"></i> {{ e($sessionName) }}</span>
        </div>

        <div class="student-profile-grid">
          <div class="student-profile-item">
            <span class="label">Roll / Student ID</span>
            <span class="value">{{ e($studentId) }}</span>
          </div>
          <div class="student-profile-item">
            <span class="label">Class</span>
            <span class="value">{{ e($className) }}</span>
          </div>
          <div class="student-profile-item">
            <span class="label">Department</span>
            <span class="value">{{ e($departmentName) }}</span>
          </div>
          <div class="student-profile-item">
            <span class="label">Section</span>
            <span class="value">{{ e($sectionName) }}</span>
          </div>
          <div class="student-profile-item">
            <span class="label">Session</span>
            <span class="value">{{ e($sessionName) }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="student-profile-actions">
      <a href="{{ route('student') }}" class="student-profile-back"><i class="fa fa-arrow-left"></i> Back to student list</a>
    </div>
  </div>
</div>
@endsection