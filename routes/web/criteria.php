<?php

use App\Http\Controllers\CriteriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('criteria', CriteriaController::class);
    Route::get('/all-criteria', [CriteriaController::class, 'allCriteria'])->name('all-criteria');
});
