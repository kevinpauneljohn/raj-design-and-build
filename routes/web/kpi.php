<?php

use App\Http\Controllers\KeyPerformanceIndicatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('kpi', KeyPerformanceIndicatorController::class);
    Route::get('/all-kpi', [KeyPerformanceIndicatorController::class, 'allKpi'])->name('all-kpi');
});
