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
    Route::post('building-store', [BuildingController::class, 'buildingStore'])->name('building_store');
    Route::get('building-info', [BuildingController::class, 'buildingInfo'])->name('building_info');
    Route::get('building-transactions', [BuildingController::class, 'buildingTransactions'])->name('building_transactions');
    Route::post('building-transactions-import', [BuildingController::class, 'buildingTransactionsImport'])->name('building_transactions.import');


    Route::get('renter', [BuildingController::class, 'renter'])->name('renter');
    Route::post('renter-store', [BuildingController::class, 'renterStore'])->name('renter.store');

    Route::post('flat-rent', [BuildingController::class, 'flatRent'])->name('flat_rent');
    Route::post('flat-rent-update', [BuildingController::class, 'update_flat_rent'])->name('update_flat_rent');
    Route::post('make-payment', [BuildingController::class, 'makePayment'])->name('make_payment');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login-attempt', [AuthController::class, 'login_attempt'])->name('login_attempt');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register-attempt', [AuthController::class, 'register_attempt'])->name('register_attempt');
