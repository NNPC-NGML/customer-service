<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDdqExistingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('scope.user')->group(function () {

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::get('/protected', function () {
        return response()->json(['message' => 'Access granted']);
    });
    Route::get('customer-ddq-existings', [CustomerDdqExistingController::class, 'index']);
    Route::post('customer-ddq-existings', [CustomerDdqExistingController::class, 'store']);
    Route::get('customer-ddq-existings/{id}', [CustomerDdqExistingController::class, 'show']);
    Route::put('customer-ddq-existings/{id}', [CustomerDdqExistingController::class, 'update']);
    Route::delete('customer-ddq-existings/{id}', [CustomerDdqExistingController::class, 'destroy']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
