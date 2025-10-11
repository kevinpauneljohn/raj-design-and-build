<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
   Route::resource('project', ProjectController::class);
    Route::get('/all-projects',[ProjectController::class,'allProjects'])->name('all-projects');
});
