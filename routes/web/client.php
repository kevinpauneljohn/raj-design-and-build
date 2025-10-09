<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('client',ClientController::class);
    Route::get('/all-clients',[ClientController::class,'allClients'])->name('all-clients');
});
