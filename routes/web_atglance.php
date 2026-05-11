<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

// At-a-glance result quick scaffold route
Route::get('/result/at-glance', [FrontController::class, 'atGlanceResult'])->name('atGlanceResult');
