<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('item', ItemController::class);
    Route::get('/all-items/{supplierId}',[ItemController::class,'allItems'])->name('all-items');
    Route::post('/item-import',[ItemController::class,'import'])->name('item.import');
});
