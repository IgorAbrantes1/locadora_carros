<?php

use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Car\CarController;
use App\Http\Controllers\CarModel\CarModelController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Rental\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('brand', BrandController::class);
Route::apiResource('car', CarController::class);
Route::apiResource('carModel', CarModelController::class);
Route::apiResource('customer', CustomerController::class);
Route::apiResource('rental', RentalController::class);
