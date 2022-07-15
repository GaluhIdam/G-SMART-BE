<?php

use App\Http\Controllers\AMSController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('users', [UserController::class, 'index']);

        //Prospect Routes
        Route::get('prospect', [ProspectController::class, 'index']);
        Route::post('prospect-create', [ProspectController::class, 'create']);

        //AMS Routes
        Route::get('ams', [AMSController::class, 'index']);
        Route::post('ams-create', [AMSController::class, 'create']);
        Route::get('ams-show/{id}', [AMSController::class, 'show']);
        Route::post('ams-update/{id}', [AMSController::class, 'update']);
        Route::delete('ams-delete/{id}', [AMSController::class, 'destroy']);
    });
});

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('prospect', [ProspectController::class, 'index']);
    });
});
