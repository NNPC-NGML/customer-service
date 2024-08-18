<?php

use App\Http\Controllers\CustomerDdqController;
use App\Http\Controllers\CustomerDdqGroupController;
use App\Http\Controllers\CustomerDdqSubGroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

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
    Route::get('/protected', function () {
        return response()->json(['message' => 'Access granted']);
    });
    Route::apiResource('ddq-groups', CustomerDdqGroupController::class);
    Route::apiResource('ddq-subgroups', CustomerDdqSubGroupController::class);
    Route::post('ddqs/approve/{id}', [CustomerDdqController::class, 'approve']);
    Route::post('ddqs/decline/{id}', [CustomerDdqController::class, 'decline']);
    // Route::get('ddqs/view/{customer_id}/{site_id}/{group_id}/{subgroup_id}', [CustomerDdqController::class, 'showDDQ']);
    Route::get('ddqs/view/{id}', [CustomerDdqController::class, 'viewDDQ']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
