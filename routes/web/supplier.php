<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('supplier', SupplierController::class);
    Route::get('/all-suppliers', [SupplierController::class, 'allSuppliers'])->name('all-suppliers');
});
