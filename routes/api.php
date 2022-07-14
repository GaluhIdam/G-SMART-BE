<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\StrategicInitiativeController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('prospect', [ProspectController::class, 'index']);
        Route::post('prospect-create', [ProspectController::class, 'create']);
        Route::get('users', [UserController::class, 'index']);

        //Strategic Initiative Routes
        Route::get('strategic-initiative', [StrategicInitiativeController::class, 'index']);
        Route::post('strategic-initiative-create', [StrategicInitiativeController::class, 'create']);
        Route::get('strategic-initiative-show', [StrategicInitiativeController::class, 'show']);
        Route::post('strategic-initiative-update', [StrategicInitiativeController::class, 'update']);
        Route::delete('strategic-initiative-delete', [StrategicInitiativeController::class, 'delete']);
    });
});

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('prospect', [ProspectController::class, 'index']);
    });
});
