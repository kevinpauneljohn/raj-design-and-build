<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/web/user.php';
require __DIR__ . '/web/rolesAndPermissions.php';
require __DIR__ . '/web/client.php';
require __DIR__ . '/web/supplier.php';
require __DIR__ . '/web/item.php';
require __DIR__ . '/web/applicant.php';
require __DIR__ . '/web/criteria.php';
require __DIR__ . '/web/score.php';
require __DIR__ . '/web/kpiForms.php';

Route::get('/', function () {
    return redirect(\route('home'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
