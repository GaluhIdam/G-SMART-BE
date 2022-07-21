<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AMSController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProspectTypeController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\StrategicInitiativeController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('users', [UserController::class, 'index']);

        //Prospect Routes
        Route::get('prospect', [ProspectController::class, 'index']);
        Route::post('prospect-create', [ProspectController::class, 'create']);
        Route::get('users', [UserController::class, 'index']);

        //Strategic Initiative Routes
        Route::get('strategic-initiative', [StrategicInitiativeController::class, 'index']);
        Route::post('strategic-initiative-create', [StrategicInitiativeController::class, 'create']);
        Route::get('strategic-initiative-show/{id}', [StrategicInitiativeController::class, 'show']);
        Route::post('strategic-initiative-update/{id}', [StrategicInitiativeController::class, 'update']);
        Route::delete('strategic-initiative-delete/{id}', [StrategicInitiativeController::class, 'delete']);

        //Region Routes
        Route::get('region', [RegionController::class, 'index']);
        Route::post('region-create', [RegionController::class, 'create']);
        Route::get('region-show/{id}', [RegionController::class, 'show']);
        Route::post('region-update/{id}', [RegionController::class, 'update']);
        Route::delete('region-delete/{id}', [RegionController::class, 'destroy']);

        //Countries Routes
        Route::get('countries', [CountriesController::class, 'index']);
        Route::post('countries-create', [CountriesController::class, 'create']);
        Route::get('countries-show/{id}', [CountriesController::class, 'show']);
        Route::post('countries-update/{id}', [CountriesController::class, 'update']);
        Route::delete('countries-delete/{id}', [CountriesController::class, 'destroy']);

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

        //AMS Routes
        Route::get('ams', [AMSController::class, 'index']);
        Route::post('ams-create', [AMSController::class, 'create']);
        Route::get('ams-show/{id}', [AMSController::class, 'show']);
        Route::post('ams-update/{id}', [AMSController::class, 'update']);
        Route::delete('ams-delete/{id}', [AMSController::class, 'destroy']);

        //Prospect Type Routes
        Route::get('prospect-type', [ProspectTypeController::class, 'index']);
        Route::post('prospect-type-create', [ProspectTypeController::class, 'create']);
        Route::get('prospect-type-show/{id}', [ProspectTypeController::class, 'show']);
        Route::post('prospect-type-update/{id}', [ProspectTypeController::class, 'update']);
        Route::delete('prospect-type-delete/{id}', [ProspectTypeController::class, 'destroy']);

        //Permission Routes
        Route::get('permission', [PermissionController::class, 'index']);
        Route::post('permission-create', [PermissionController::class, 'create']);
        Route::get('permission-show/{id}', [PermissionController::class, 'show']);
        Route::post('permission-update/{id}', [PermissionController::class, 'update']);
        Route::delete('permission-delete/{id}', [PermissionController::class, 'destroy']);
    });
});

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('prospect', [ProspectController::class, 'index']);
    });
});
