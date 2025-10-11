<?php

use App\Http\Controllers\PhaseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('phase', PhaseController::class);
    Route::get('/all-phases',[PhaseController::class,'allPhases'])->name('all-phases');
});
