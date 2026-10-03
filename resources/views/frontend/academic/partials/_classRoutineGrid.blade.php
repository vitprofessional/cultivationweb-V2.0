@php
    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
    $slots = [];
    $cells = [];
    foreach (($entries ?? collect()) as $entry) {
        $day = ucfirst(strtolower((string) $entry->class_day));
        $start = (string) ($entry->start_time ?? '');
        $end = (string) ($entry->end_time ?? '');
        if (!in_array($day, $days, true) || $start === '' || $end === '') continue;
        $key = $start.'|'.$end;
        $slots[$key] = ['key' => $key, 'start' => $start, 'label' => substr($start, 0, 5).' – '.substr($end, 0, 5)];
        $cells[$key][$day][] = $entry;
    }
    $slots = collect(array_values($slots))->sortBy('start')->values();
    $className = optional($routine->class)->className ?? '-';
    $sessionName = optional($routine->session)->session ?? '-';
    $sectionName = (int) ($routine->assignSection ?? 0) > 0 ? (optional($routine->section)->section ?? '-') : 'All sections';
    $groupName = optional($routine->department)->departmentName ?? '-';
@endphp

<header class="public-routine-heading">
    <p class="public-routine-institution">{{ $institutionName ?: '-' }}</p>
    <h1>Class Routine</h1>
    <p class="public-routine-subtitle">{{ $routine->title ?: 'Weekly timetable' }}</p>
    <div class="public-routine-context" aria-label="Routine details">
        <span><b>Session</b>{{ $sessionName }}</span>
        <span><b>Class</b>{{ $className }}</span>
        <span><b>Section</b>{{ $sectionName }}</span>
        <span><b>Department / Group</b>{{ $groupName }}</span>
    </div>
</header>

<div class="routine-desktop-table">
    <table class="public-routine-table">
        <thead><tr><th scope="col">Period / Time</th>@foreach($days as $day)<th scope="col">{{ $day }}</th>@endforeach</tr></thead>
        <tbody>
        @forelse($slots as $slot)
            <tr>
                <th scope="row" class="routine-time">{{ $slot['label'] }}</th>
                @foreach($days as $day)
                    <td>
                        @forelse($cells[$slot['key']][$day] ?? [] as $entry)
                            @php
                                $subject = trim((string) ($entry->subject_name ?? ''));
                                $teacher = trim((string) ($entry->teacher->adminName ?? ''));
                                $room = trim((string) (($entry->room ?? $routine->defaultRoom)->name ?? ''));
                                $activity = $entry->subject_id === null && $subject !== '';
                            @endphp
                            <div class="routine-cell {{ $activity ? 'routine-activity' : '' }}">
                                <strong class="routine-subject">{{ $subject !== '' ? $subject : '-' }}</strong>
                                <span><b>Teacher</b>{{ $activity ? '-' : ($teacher !== '' ? $teacher : '-') }}</span>
                                <span><b>Room</b>{{ $activity ? '-' : ($room !== '' ? $room : '-') }}</span>
                            </div>
                        @empty
                            <div class="routine-cell routine-empty"><strong class="routine-subject">-</strong><span><b>Teacher</b>-</span><span><b>Room</b>-</span></div>
                        @endforelse
                    </td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="6" class="routine-no-rows">No routine periods are available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="routine-mobile-days">
    @foreach($days as $day)
        <section class="routine-day-card">
            <h2>{{ $day }}</h2>
            @php $dayEntries = $entries->filter(fn ($entry) => ucfirst(strtolower((string) $entry->class_day)) === $day && filled($entry->start_time) && filled($entry->end_time))->sortBy('start_time'); @endphp
            @forelse($dayEntries as $entry)
                @php
                    $subject = trim((string) ($entry->subject_name ?? ''));
                    $teacher = trim((string) ($entry->teacher->adminName ?? ''));
                    $room = trim((string) (($entry->room ?? $routine->defaultRoom)->name ?? ''));
                    $activity = $entry->subject_id === null && $subject !== '';
                @endphp
                <article class="routine-mobile-period {{ $activity ? 'routine-activity' : '' }}">
                    <p class="routine-mobile-time">{{ substr((string) $entry->start_time, 0, 5) }} – {{ substr((string) $entry->end_time, 0, 5) }}</p>
                    <strong class="routine-subject">{{ $subject !== '' ? $subject : '-' }}</strong>
                    <span><b>Teacher</b>{{ $activity ? '-' : ($teacher !== '' ? $teacher : '-') }}</span>
                    <span><b>Room</b>{{ $activity ? '-' : ($room !== '' ? $room : '-') }}</span>
                </article>
            @empty
                <p class="routine-no-rows">No periods scheduled.</p>
            @endforelse
        </section>
    @endforeach
</div>
