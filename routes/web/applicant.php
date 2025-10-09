<?php

use App\Http\Controllers\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('applicant', ApplicantController::class);
    Route::get('/all-applicant', [ApplicantController::class, 'allApplicants'])->name('all-applicant');
});
