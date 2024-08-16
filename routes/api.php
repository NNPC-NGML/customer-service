<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CustomerContractController;
use App\Http\Controllers\CustomerContractAddendumController;
use App\Http\Controllers\CustomerContractTemplateController;
use App\Http\Controllers\CustomerContractSignatureController;


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

    // TODO:CONTRACT ENDPOINTS
    Route::post('contracts/sign', [CustomerContractSignatureController::class, 'sign']);
    Route::get('contracts/signatures/{signature}', [CustomerContractSignatureController::class, 'show']);
    Route::get('/contract-addendums', [CustomerContractAddendumController::class, 'index']);
    Route::post('/contract-addendums', [CustomerContractAddendumController::class, 'store']);
    Route::get('/contract-addendums/{contractAddendum}', [CustomerContractAddendumController::class, 'show']);
    Route::put('/contract-addendums/{contractAddendum}', [CustomerContractAddendumController::class, 'update']);
    Route::delete('/contract-addendums/{contractAddendum}', [CustomerContractAddendumController::class, 'destroy']);

    Route::get('/contracts', [CustomerContractController::class, 'index']);
    Route::post('/contracts', [CustomerContractController::class, 'store']);
    Route::get('/contracts/{contract}', [CustomerContractController::class, 'show']);
    Route::put('/contracts/{contract}', [CustomerContractController::class, 'update']);
    Route::delete('/contracts/{contract}', [CustomerContractController::class, 'destroy']);

    Route::get('/contract-templates', [CustomerContractTemplateController::class, 'index']);
    Route::post('/contract-templates', [CustomerContractTemplateController::class, 'store']);
    Route::get('/contract-templates/{contractTemplate}', [CustomerContractTemplateController::class, 'show']);
    Route::put('/contract-templates/{contractTemplate}', [CustomerContractTemplateController::class, 'update']);
    Route::delete('/contract-templates/{contractTemplate}', [CustomerContractTemplateController::class, 'destroy']);

    Route::get('/protected', function () {
        return response()->json(['message' => 'Access granted']);
    });
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
