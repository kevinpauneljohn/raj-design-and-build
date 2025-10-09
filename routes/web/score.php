<?php

use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('score', ScoreController::class);
    Route::get('/all-scores', [ScoreController::class, 'allScores'])->name('all-scores');
});
