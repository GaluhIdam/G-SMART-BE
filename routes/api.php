<?php

use App\Http\Controllers\AreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [UserController::class, 'index']);

        //Prospect Routes
        Route::get('prospect', [ProspectController::class, 'index']);
        Route::post('prospect-create', [ProspectController::class, 'create']);

        //Area Routes
        Route::get('area', [AreaController::class, 'index']);
        Route::post('area-create', [AreaController::class, 'create']);
        Route::get('area-show/{id}', [AreaController::class, 'show']);
        Route::post('area-update/{id}', [AreaController::class, 'update']);
        Route::delete('area-delete/{id}', [AreaController::class, 'destroy']);
        //Maintenance Routes
        Route::get('maintenance', [MaintenanceController::class, 'index']);
        Route::post('maintenance-create', [MaintenanceController::class, 'create']);
        Route::get('maintenance-show/{id}', [MaintenanceController::class, 'show']);
        Route::post('maintenance-update/{id}', [MaintenanceController::class, 'update']);
        Route::delete('maintenance-delete/{id}', [MaintenanceController::class, 'destroy']);
        //Transaction Type Routes
        Route::get('transaction-type', [TransactionTypeController::class, 'index']);
        Route::post('transaction-type-create', [TransactionTypeController::class, 'create']);
        Route::get('transaction-type-show/{id}', [TransactionTypeController::class, 'show']);
        Route::post('transaction-type-update/{id}', [TransactionTypeController::class, 'update']);
        Route::delete('transaction-type-delete/{id}', [TransactionTypeController::class, 'destroy']);
    });
});

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('prospect', [ProspectController::class, 'index']);
    });
});
