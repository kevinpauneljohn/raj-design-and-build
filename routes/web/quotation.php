<?php

use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('quotation', QuotationController::class);
});
