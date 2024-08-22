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
    Route::get('ddq-groups', [CustomerDdqGroupController::class, 'index']);
    Route::post('ddq-groups', [CustomerDdqGroupController::class, 'store']);
    Route::get('ddq-groups/{id}', [CustomerDdqGroupController::class, 'show']);
    Route::put('ddq-groups/{id}', [CustomerDdqGroupController::class, 'update']);
    Route::delete('ddq-groups/{id}', [CustomerDdqGroupController::class, 'destroy']);
    Route::get('ddq-subgroups', [CustomerDdqSubGroupController::class, 'index']);
    Route::post('ddq-subgroups', [CustomerDdqSubGroupController::class, 'store']);
    Route::get('ddq-subgroups/{id}', [CustomerDdqSubGroupController::class, 'show']);
    Route::put('ddq-subgroups/{id}', [CustomerDdqSubGroupController::class, 'update']);
    Route::delete('ddq-subgroups/{id}', [CustomerDdqSubGroupController::class, 'destroy']);
    // Route::post('ddqs/approve/{id}', [CustomerDdqController::class, 'approve']);
    // Route::post('ddqs/decline/{id}', [CustomerDdqController::class, 'decline']);
    // // Route::get('ddqs/view/{customer_id}/{site_id}/{group_id}/{subgroup_id}', [CustomerDdqController::class, 'showDDQ']);
    // Route::get('ddqs/view/{id}', [CustomerDdqController::class, 'viewDDQ']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
