<?php

use App\Http\Controllers\KpiFormController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('kpi-forms', KpiFormController::class);
    Route::get('/all-kpi-forms', [KpiFormController::class, 'allKpiForms'])->name('all-kpi-forms');
});
