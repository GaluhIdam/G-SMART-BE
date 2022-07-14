<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ProspectTypeController;
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

        //Prospect Type Routes
        Route::get('prospect-type', [ProspectTypeController::class, 'index']);
        Route::post('prospect-type-create', [ProspectTypeController::class, 'create']);
        Route::get('prospect-type-show/{id}', [ProspectTypeController::class, 'show']);
        Route::post('prospect-type-update/{id}', [ProspectTypeController::class, 'update']);
        Route::delete('prospect-type-delete/{id}', [ProspectTypeController::class, 'destroy']);
    });
});

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('prospect', [ProspectController::class, 'index']);
    });
});
