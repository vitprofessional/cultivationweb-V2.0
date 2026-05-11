<?php

// app/Http/Middleware/TrackVisitors.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class TrackVisitors {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $today = now()->toDateString();

        // Check if this IP already logged today
        if (!Visitor::where('ip_address', $ip)->where('visit_date', $today)->exists()) {
            Visitor::create([
                'ip_address' => $ip,
                'visit_date' => $today
            ]);
        }

        return $next($request);
    }
}
