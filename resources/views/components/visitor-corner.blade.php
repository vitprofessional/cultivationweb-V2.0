{{-- resources/views/components/visitor-corner.blade.php --}}
<div class="rounded shadow-sm border overflow-hidden">
    <div class="p-3">
        <div class="py-2 d-flex justify-content-between align-items-center border-bottom">
            <span>Today Visitor:</span>
            <span class="badge bg-success">{{ $todayVisitors }}</span>
        </div>

        <div class="py-2 d-flex justify-content-between align-items-center border-bottom">
            <span>Total Visitor:</span>
            <span class="badge bg-success">{{ $totalVisitors }}</span>
        </div>

        <div class="pt-2 d-flex justify-content-between align-items-center">
            <span>Your IP Address:</span>
            <span class="badge bg-info text-dark">{{ $ip }}</span>
        </div>
    </div>
</div>
