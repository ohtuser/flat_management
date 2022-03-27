<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
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



Route::middleware('permission_check')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('building-add', [BuildingController::class, 'buildingAdd'])->name('building_add');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login-attempt', [AuthController::class, 'login_attempt'])->name('login_attempt');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register-attempt', [AuthController::class, 'register_attempt'])->name('register_attempt');
