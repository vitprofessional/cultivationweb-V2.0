{{-- Cross-links on the single-class routine page: other published classes of the same exam + the combined view (only when every class is published). --}}
@php
    $xSiblings = $siblingRoutines ?? collect();
    $xAll = $allClassAvailable ?? false;
@endphp
@if($xSiblings->count() > 0 || $xAll)
<style>
.routine-cross { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px 16px; margin: 0 0 12px; font-size: 13px; color: #4f6783; }
.routine-cross .also { font-weight: 600; }
.routine-cross .also a { color: #1b7096; font-weight: 700; text-decoration: none; }
.routine-cross .also a:hover { text-decoration: underline; }
.routine-cross .sep { margin: 0 2px; color: #a9b8c8; }
.routine-cross .all-link { background: #112958; color: #fff !important; border-radius: 8px; padding: 7px 14px; font-weight: 800; font-size: 12.5px; text-decoration: none; }
.routine-cross .all-link:hover { background: #0c7da7; }
</style>
<div class="routine-cross">
    <span class="also">
        @if($xSiblings->count() > 0)
            Also published:
            @foreach($xSiblings as $sib)
                <a href="{{ route('examRoutine.view', ['id' => $sib->id]) }}">{{ optional($sib->class)->className ?? 'N/A' }}@if($sib->department) ({{ $sib->department->departmentName }})@endif</a>@if(!$loop->last)<span class="sep">&nbsp;·&nbsp;</span>@endif
            @endforeach
        @endif
    </span>
    @if($xAll)
        <a href="{{ route('examRoutine.allClass.view', ['id' => $routine->id]) }}" class="all-link">View All Classes Together</a>
    @endif
</div>
@endif
