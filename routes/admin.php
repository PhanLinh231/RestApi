<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [LoginController::class,'login'])->name('admin.index');
Route::post('login', [LoginController::class,'postLogin'])->name('admin.login');
Route::middleware('auth:admin')->group(function () {
    Route::get('/', [HomeController::class,'index'])->name('dashboard');
});
