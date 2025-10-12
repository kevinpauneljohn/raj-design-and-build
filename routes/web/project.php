<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
   Route::resource('project', ProjectController::class);
    Route::get('/all-projects',[ProjectController::class,'allProjects'])->name('all-projects');
    Route::get('/project/{project}/assigned-users',[ProjectController::class,'assignedUsers'])->name('assigned-users');
    Route::post('/project/{project}/assign-user',[ProjectController::class,'assignUser'])->name('assign-user');
});
