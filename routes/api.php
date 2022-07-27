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
use App\Http\Controllers\ProspectTypeController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\StrategicInitiativeController;
use App\Http\Controllers\AircraftTypeController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ApuController;
use App\Http\Controllers\RoleController;
use Spatie\Permission\Contracts\Role;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    //User Routes
    Route::get('users', [UserController::class, 'index'])->middleware(['permission:read_users|manage_users']);
    Route::post('users-create', [UserController::class, 'create'])->middleware(['permission:create_users|manage_users']);
    Route::get('users-show/{id}', [UserController::class, 'show'])->middleware(['permission:show_users|manage_users']);
    Route::put('users-update/{id}', [UserController::class, 'update'])->middleware(['permission:update_users|manage_users']);
    Route::delete('users-delete/{id}', [UserController::class, 'delete'])->middleware(['permission:delete_users|manage_users']);

    //User Routes
    Route::get('role', [RoleController::class, 'index'])->middleware(['permission:read_role|manage_role']);
    Route::post('role-create', [RoleController::class, 'create'])->middleware(['permission:create_role|manage_role']);
    Route::get('role-show/{id}', [RoleController::class, 'show'])->middleware(['permission:show_role|manage_role']);
    Route::put('role-update/{id}', [RoleController::class, 'update'])->middleware(['permission:update_role|manage_role']);
    Route::delete('role-delete/{id}', [RoleController::class, 'destroy'])->middleware(['permission:delete_role|manage_role']);

    //Prospect Routes #Status Hold
    Route::get('prospect', [ProspectController::class, 'index']);
    Route::post('prospect-create', [ProspectController::class, 'create']);
    Route::get('prospect-show/{id}', [ProspectController::class, 'show']);
    Route::put('prospect-update/{id}', [ProspectController::class, 'update']);
    Route::delete('prospect-delete/{id}', [ProspectController::class, 'destroy']);

    //Strategic Initiative Routes
    Route::get('strategic-initiative', [StrategicInitiativeController::class, 'index'])->middleware(['permission:read_strategic_initiative|manage_strategic_initiative']);
    Route::post('strategic-initiative-create', [StrategicInitiativeController::class, 'create'])->middleware(['permission:create_strategic_initiative|manage_strategic_initiative']);
    Route::get('strategic-initiative-show/{id}', [StrategicInitiativeController::class, 'show'])->middleware(['permission:show_strategic_initiative|manage_strategic_initiative']);
    Route::post('strategic-initiative-update/{id}', [StrategicInitiativeController::class, 'update'])->middleware(['permission:update_strategic_initiative|manage_strategic_initiative']);
    Route::delete('strategic-initiative-delete/{id}', [StrategicInitiativeController::class, 'destroy'])->middleware(['permission:delete_strategic_initiative|manage_strategic_initiative']);

    //Region Routes
    Route::get('region', [RegionController::class, 'index'])->middleware(['permission:read_region|manage_region']);
    Route::post('region-create', [RegionController::class, 'create'])->middleware(['permission:create_region|manage_region']);
    Route::get('region-show/{id}', [RegionController::class, 'show'])->middleware(['permission:show_region|manage_region']);
    Route::post('region-update/{id}', [RegionController::class, 'update'])->middleware(['permission:update_region|manage_region']);
    Route::delete('region-delete/{id}', [RegionController::class, 'destroy'])->middleware(['permission:delete_region|manage_region']);

    //Countries Routes
    Route::get('countries', [CountriesController::class, 'index'])->middleware(['permission:read_countries|manage_countries']);
    Route::post('countries-create', [CountriesController::class, 'create'])->middleware(['permission:create_countries|manage_countries']);
    Route::get('countries-show/{id}', [CountriesController::class, 'show'])->middleware(['permission:show_countries|manage_countries']);
    Route::post('countries-update/{id}', [CountriesController::class, 'update'])->middleware(['permission:update_countries|manage_countries']);
    Route::delete('countries-delete/{id}', [CountriesController::class, 'destroy'])->middleware(['permission:delete_countries|manage_countries']);

    //Area Routes
    Route::get('area', [AreaController::class, 'index'])->middleware(['permission:read_area|manage_area']);
    Route::post('area-create', [AreaController::class, 'create'])->middleware(['permission:create_area|manage_area']);
    Route::get('area-show/{id}', [AreaController::class, 'show'])->middleware(['permission:show_area|manage_area']);
    Route::post('area-update/{id}', [AreaController::class, 'update'])->middleware(['permission:update_area|manage_area']);
    Route::delete('area-delete/{id}', [AreaController::class, 'destroy'])->middleware(['permission:delete_area|manage_area']);;

    //Maintenance Routes
    Route::get('maintenance', [MaintenanceController::class, 'index'])->middleware(['permission:read_maintenance|manage_maintenance']);
    Route::post('maintenance-create', [MaintenanceController::class, 'create'])->middleware(['permission:create_maintenance|manage_maintenance']);
    Route::get('maintenance-show/{id}', [MaintenanceController::class, 'show'])->middleware(['permission:show_maintenance|manage_maintenance']);
    Route::put('maintenance-update/{id}', [MaintenanceController::class, 'update'])->middleware(['permission:update_maintenance|manage_maintenance']);
    Route::delete('maintenance-delete/{id}', [MaintenanceController::class, 'destroy'])->middleware(['permission:delete_maintenance|manage_maintenance']);

    //Transaction Type Routes
    Route::get('transaction-type', [TransactionTypeController::class, 'index'])->middleware(['permission:read_transaction_type|manage_transaction_type']);
    Route::post('transaction-type-create', [TransactionTypeController::class, 'create'])->middleware(['permission:create_transaction_type|manage_transaction_type']);
    Route::get('transaction-type-show/{id}', [TransactionTypeController::class, 'show'])->middleware(['permission:show_transaction_type|manage_transaction_type']);
    Route::put('transaction-type-update/{id}', [TransactionTypeController::class, 'update'])->middleware(['permission:update_transaction_type|manage_transaction_type']);
    Route::delete('transaction-type-delete/{id}', [TransactionTypeController::class, 'destroy'])->middleware(['permission:delete_transaction_type|manage_transaction_type']);

    //AMS Routes
    Route::get('ams', [AMSController::class, 'index'])->middleware(['permission:read_ams|manage_ams']);
    Route::post('ams-create', [AMSController::class, 'create'])->middleware(['permission:create_ams|manage_ams']);
    Route::get('ams-show/{id}', [AMSController::class, 'show'])->middleware(['permission:show_ams|manage_ams']);
    Route::put('ams-update/{id}', [AMSController::class, 'update'])->middleware(['permission:update_ams|manage_ams']);
    Route::delete('ams-delete/{id}', [AMSController::class, 'destroy'])->middleware(['permission:delete_ams|manage_ams']);

    //Prospect Type Routes
    Route::get('prospect-type', [ProspectTypeController::class, 'index'])->middleware(['permission:read_prospect_type|manage_prospect_type']);
    Route::post('prospect-type-create', [ProspectTypeController::class, 'create'])->middleware(['permission:create_prospect_type|manage_prospect_type']);
    Route::get('prospect-type-show/{id}', [ProspectTypeController::class, 'show'])->middleware(['permission:show_prospect_type|manage_prospect_type']);
    Route::put('prospect-type-update/{id}', [ProspectTypeController::class, 'update'])->middleware(['permission:update_prospect_type|manage_prospect_type']);
    Route::delete('prospect-type-delete/{id}', [ProspectTypeController::class, 'destroy'])->middleware(['permission:delete_prospect_type|manage_prospect_type']);

    //Aircraft Type Routes
    Route::get('aircraft-type', [AircraftTypeController::class, 'index'])->middleware(['permission:read_aircraft_type|manage_aircraft_type']);
    Route::post('aircraft-type-create', [AircraftTypeController::class, 'create'])->middleware(['permission:create_aircraft_type|manage_aircraft_type']);
    Route::get('aircraft-type-show/{id}', [AircraftTypeController::class, 'show'])->middleware(['permission:show_aircraft_type|manage_aircraft_type']);
    Route::put('aircraft-type-update/{id}', [AircraftTypeController::class, 'update'])->middleware(['permission:update_aircraft_type|manage_aircraft_type']);
    Route::delete('aircraft-type-delete/{id}', [AircraftTypeController::class, 'destroy'])->middleware(['permission:delete_aircraft_type|manage_aircraft_type']);

    //Engine Routes
    Route::get('engine', [EngineController::class, 'index'])->middleware(['permission:read_engine|manage_engine']);
    Route::post('engine-create', [EngineController::class, 'create'])->middleware(['permission:create_engine|manage_engine']);
    Route::get('engine-show/{id}', [EngineController::class, 'show'])->middleware(['permission:show_engine|manage_engine']);
    Route::put('engine-update/{id}', [EngineController::class, 'update'])->middleware(['permission:update_engine|manage_engine']);
    Route::delete('engine-delete/{id}', [EngineController::class, 'destroy'])->middleware(['permission:delete_engine|manage_engine']);

    //Component Routes
    Route::get('component', [ComponentController::class, 'index'])->middleware(['permission:read_component|manage_component']);
    Route::post('component-create', [ComponentController::class, 'create'])->middleware(['permission:create_component|manage_component']);
    Route::get('component-show/{id}', [ComponentController::class, 'show'])->middleware(['permission:show_component|manage_component']);
    Route::put('component-update/{id}', [ComponentController::class, 'update'])->middleware(['permission:update_component|manage_component']);
    Route::delete('component-delete/{id}', [ComponentController::class, 'destroy'])->middleware(['permission:delete_component|manage_component']);

    //APU Routes
    Route::get('apu', [ApuController::class, 'index'])->middleware(['permission:read_apu|manage_apu']);
    Route::post('apu-create', [ApuController::class, 'create'])->middleware(['permission:create_apu|manage_apu']);
    Route::get('apu-show/{id}', [ApuController::class, 'show'])->middleware(['permission:show_apu|manage_apu']);
    Route::put('apu-update/{id}', [ApuController::class, 'update'])->middleware(['permission:update_apu|manage_apu']);
    Route::delete('apu-delete/{id}', [ApuController::class, 'destroy'])->middleware(['permission:delete_apu|manage_apu']);
});
