<?php

// app/View/Components/VisitorCorner.php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Visitor;

class VisitorCorner extends Component
{
    public function __construct(private Request $request) {}

    public function render(): View|Closure|string
    {
        $today = now()->toDateString();

        // Cache to avoid counting on every render
        $todayVisitors = Cache::remember("visitors.today.$today", 60, function () use ($today) {
            return Visitor::where('visit_date', $today)->count();
        });

        $totalVisitors = Cache::remember('visitors.total', 60, function () {
            return Visitor::count();
        });

        return view('components.visitor-corner', [
            'todayVisitors' => $todayVisitors,
            'totalVisitors' => $totalVisitors,
            'ip'            => $this->request->ip(),
        ]);
    }
}
