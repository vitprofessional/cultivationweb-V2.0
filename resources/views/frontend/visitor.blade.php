<div class="card">
    <div class="card-header bg-success text-white">
        <i class="fas fa-users"></i> Visitor Corner
    </div>
    <div class="card-body">
        <p>Today Visitor: <span class="badge bg-success">{{ $todayVisitors }}</span></p>
        <p>Total Visitor: <span class="badge bg-success">{{ $totalVisitors }}</span></p>
        <p>Your IP Address: <span class="badge bg-info">{{ $ip }}</span></p>
    </div>
</div>
