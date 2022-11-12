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
use App\Http\Controllers\AircraftTypeController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ApuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ModulePermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesHistoryController;
use App\Http\Controllers\SalesLevelController;
use App\Http\Controllers\SalesRejectController;
use App\Http\Controllers\SalesRequirementController;
use App\Http\Controllers\SalesRescheduleController;
use App\Http\Controllers\SalesUpdateController;
use App\Http\Controllers\ContactPersonController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\HangarController;
use App\Http\Controllers\AMSCustomerController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// TODO route untuk testing tanpe perlu autentikasi
// Route::get('test/{id}', [FileController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('module-permission', [ModulePermissionController::class, 'index']);

    //User Routes
    Route::get('users', [UserController::class, 'index'])->middleware(['permission:read_users|manage_users']);
    Route::post('users-create', [UserController::class, 'create'])->middleware(['permission:create_users|manage_users']);
    Route::get('users-show/{id}', [UserController::class, 'show'])->middleware(['permission:show_users|manage_users']);
    Route::put('users-update/{id}', [UserController::class, 'update'])->middleware(['permission:update_users|manage_users']);
    Route::delete('users-delete/{id}', [UserController::class, 'destroy'])->middleware(['permission:delete_users|manage_users']);

    //Role Routes
    Route::get('role', [RoleController::class, 'index'])->middleware(['permission:read_role|manage_role']);
    Route::post('role-create', [RoleController::class, 'create'])->middleware(['permission:create_role|manage_role']);
    Route::get('role-show/{id}', [RoleController::class, 'show'])->middleware(['permission:show_role|manage_role']);
    Route::put('role-update/{id}', [RoleController::class, 'update'])->middleware(['permission:update_role|manage_role']);
    Route::delete('role-delete/{id}', [RoleController::class, 'destroy'])->middleware(['permission:delete_role|manage_role']);

    //Permission Routes
    Route::get('permission', [PermissionController::class, 'index'])->middleware(['permission:read_permission|manage_permission']);
    Route::get('permission-show/{id}', [PermissionController::class, 'show'])->middleware(['permission:show_permission|manage_permission']);
    Route::put('permission-update/{id}', [PermissionController::class, 'update'])->middleware(['permission:update_permission|manage_permission']);

    //Prospect Routes
    Route::get('prospect', [ProspectController::class, 'index'])->middleware(['permission:read_prospects']);
    Route::post('prospect-create', [ProspectController::class, 'create'])->middleware(['permission:create_prospects']);
    Route::get('prospect-show/{id}', [ProspectController::class, 'show'])->middleware(['permission:show_prospects']);
    Route::get('prospect-pbth/{id}', [ProspectController::class, 'pbth'])->middleware(['permission:pickup_prospects']);
    Route::get('prospect-tmb/{id}', [ProspectController::class, 'tmb'])->middleware(['permission:pickup_prospects']);
    Route::get('prospect-get-tmb', [ProspectController::class, 'tmbOnly'])->middleware(['permission:read_prospects']);

    //Customer Routes
    Route::get('customer', [CustomerController::class, 'index'])->middleware(['permission:read_customer|manage_customer']);
    Route::post('customer-create', [CustomerController::class, 'create'])->middleware(['permission:create_customer|manage_customer']);
    Route::get('customer-show/{id}', [CustomerController::class, 'show'])->middleware(['permission:show_customer|manage_customer']);
    Route::put('customer-update/{id}', [CustomerController::class, 'update'])->middleware(['permission:update_customer|manage_customer']);
    Route::delete('customer-delete/{id}', [CustomerController::class, 'destroy'])->middleware(['permission:delete_customer|manage_customer']);

    //Strategic Initiative Routes
    Route::get('strategic-initiative', [StrategicInitiativeController::class, 'index'])->middleware(['permission:read_strategic_initiative|manage_strategic_initiative']);
    Route::post('strategic-initiative-create', [StrategicInitiativeController::class, 'create'])->middleware(['permission:create_strategic_initiative|manage_strategic_initiative']);
    Route::get('strategic-initiative-show/{id}', [StrategicInitiativeController::class, 'show'])->middleware(['permission:show_strategic_initiative|manage_strategic_initiative']);
    Route::put('strategic-initiative-update/{id}', [StrategicInitiativeController::class, 'update'])->middleware(['permission:update_strategic_initiative|manage_strategic_initiative']);
    Route::delete('strategic-initiative-delete/{id}', [StrategicInitiativeController::class, 'destroy'])->middleware(['permission:delete_strategic_initiative|manage_strategic_initiative']);

    //Region Routes
    Route::get('region', [RegionController::class, 'index'])->middleware(['permission:read_region|manage_region']);
    Route::post('region-create', [RegionController::class, 'create'])->middleware(['permission:create_region|manage_region']);
    Route::get('region-show/{id}', [RegionController::class, 'show'])->middleware(['permission:show_region|manage_region']);
    Route::put('region-update/{id}', [RegionController::class, 'update'])->middleware(['permission:update_region|manage_region']);
    Route::delete('region-delete/{id}', [RegionController::class, 'destroy'])->middleware(['permission:delete_region|manage_region']);

    //Countries Routes
    Route::get('countries', [CountriesController::class, 'index'])->middleware(['permission:read_countries|manage_countries']);
    Route::post('countries-create', [CountriesController::class, 'create'])->middleware(['permission:create_countries|manage_countries']);
    Route::get('countries-show/{id}', [CountriesController::class, 'show'])->middleware(['permission:show_countries|manage_countries']);
    Route::put('countries-update/{id}', [CountriesController::class, 'update'])->middleware(['permission:update_countries|manage_countries']);
    Route::delete('countries-delete/{id}', [CountriesController::class, 'destroy'])->middleware(['permission:delete_countries|manage_countries']);

    //Area Routes
    Route::get('area', [AreaController::class, 'index'])->middleware(['permission:read_area|manage_area']);
    Route::post('area-create', [AreaController::class, 'create'])->middleware(['permission:create_area|manage_area']);
    Route::get('area-show/{id}', [AreaController::class, 'show'])->middleware(['permission:show_area|manage_area']);
    Route::put('area-update/{id}', [AreaController::class, 'update'])->middleware(['permission:update_area|manage_area']);
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

    //Product Routes
    Route::get('product', [ProductController::class, 'index'])->middleware(['permission:read_product|manage_product']);
    Route::post('product-create', [ProductController::class, 'create'])->middleware(['permission:create_product|manage_product']);
    Route::get('product-show/{id}', [ProductController::class, 'show'])->middleware(['permission:show_product|manage_product']);
    Route::put('product-update/{id}', [ProductController::class, 'update'])->middleware(['permission:update_product|manage_product']);
    Route::delete('product-delete/{id}', [ProductController::class, 'destroy'])->middleware(['permission:delete_product|manage_product']);
    
    //Approval
    Route::get('approval', [ApprovalController::class, 'index'])->middleware(['permission:read_approval|manage_approval']);
    Route::post('approval-create', [ApprovalController::class, 'create'])->middleware(['permission:create_approval|manage_approval']);
    Route::get('approval-show/{id}', [ApprovalController::class, 'show'])->middleware(['permission:show_approval|manage_approval']);
    Route::put('approval-update/{id}', [ApprovalController::class, 'update'])->middleware(['permission:update_approval|manage_approval']);
    Route::delete('approval-delete/{id}', [ApprovalController::class, 'destroy'])->middleware(['permission:delete_approval|manage_approval']);
    
    //Level
    Route::get('level', [LevelController::class, 'index'])->middleware(['permission:read_level|manage_level']);
    Route::post('level-create', [LevelController::class, 'create'])->middleware(['permission:create_level|manage_level']);
    Route::get('level-show/{id}', [LevelController::class, 'show'])->middleware(['permission:show_level|manage_level']);
    Route::put('level-update/{id}', [LevelController::class, 'update'])->middleware(['permission:update_level|manage_level']);
    Route::delete('level-delete/{id}', [LevelController::class, 'destroy'])->middleware(['permission:delete_level|manage_level']);
    
    //Requirement
    Route::get('requirement', [RequirementController::class, 'index'])->middleware(['permission:read_requirement|manage_requirement']);
    Route::post('requirement-create', [RequirementController::class, 'create'])->middleware(['permission:create_requirement|manage_requirement']);
    Route::get('requirement-show/{id}', [RequirementController::class, 'show'])->middleware(['permission:show_requirement|manage_requirement']);
    Route::put('requirement-update/{id}', [RequirementController::class, 'update'])->middleware(['permission:update_requirement|manage_requirement']);
    Route::delete('requirement-delete/{id}', [RequirementController::class, 'destroy'])->middleware(['permission:delete_requirement|manage_requirement']);
    
    //Sales
    Route::get('sales', [SalesController::class, 'index'])->middleware(['permission:read_sales']);
    Route::get('sales-show/{id}', [SalesController::class, 'show'])->middleware(['permission:show_sales']);
    Route::post('sales-create-tmb', [SalesController::class, 'createTmb'])->middleware(['permission:create_sales']);
    Route::post('sales-create-pbth', [SalesController::class, 'createPbth'])->middleware(['permission:create_sales']);
    Route::post('sales-slot-request', [SalesController::class, 'slotRequest'])->middleware(['permission:slot_request']);
    Route::put('sales-slot-confirm/{id}', [SalesController::class, 'slotConfirm'])->middleware(['permission:slot_confirm']);
    Route::put('sales-so-number/{id}', [SalesController::class, 'inputSONumber'])->middleware(['permission:input_so_number']);
    Route::put('sales-switch-ams/{id}', [SalesController::class, 'switchAMS'])->middleware(['permission:switch_ams']);
    Route::post('sales-request-upgrade', [SalesController::Class, 'requestUpgrade'])->middleware(['permission:sales_request_upgrade']);
    Route::put('sales-upgrade-level/{id}', [SalesController::class, 'confirmUpgrade'])->middleware(['permission:sales_confirm_upgrade']);
    Route::put('sales-update/{id}', [SalesController::class, 'update'])->middleware(['permission:update_sales']);
    Route::post('sales-request-cogs', [SalesController::class, 'cogsRequest'])->middleware(['permission:sales_request_cogs']);
    Route::put('sales-reschedule/{id}', [SalesController::class, 'rescheduleSales'])->middleware(['permission:reschedule_sales']);
    Route::put('sales-reject/{id}', [SalesController::class, 'rejectSales'])->middleware(['permission:reject_sales']);
    Route::put('sales-close/{id}', [SalesController::class, 'closeSales'])->middleware(['permission:close_sales']);
    Route::get('sales-show-tmb/{id}', [SalesController::class, 'showTmbSales'])->middleware(['permission:pickup_prospects']);
    Route::delete('sales-delete-tmb/{id}', [SalesController::class, 'deleteTmbSales'])->middleware(['permission:delete_sales']);

    // Line
    Route::get('line', LineController::class)->middleware('permission:read_lines');

    // Hangar
    Route::get('hangar', HangarController::class)->middleware(['permission:read_hangars']);

    // AMS Customer (pivot)
    Route::get('ams-customer/{id}', AMSCustomerController::class)->middleware(['permission:read_ams']);

    //File
    Route::get('file', [FileController::class, 'index'])->middleware(['permission:read_files']);
    Route::post('file-create', [FileController::class, 'store'])->middleware(['permission:upload_files']);
    Route::get('file-show/{id}', [FileController::class, 'show'])->middleware(['permission:show_files']);
    Route::get('file-history/{sales_id}', [FileController::class, 'history'])->middleware(['permission:file_histories']);
    Route::delete('file-delete/{id}', [FileController::class, 'destroy'])->middleware(['permission:delete_files']);

    // Contact Person
    Route::get('contact-person', [ContactPersonController::class, 'index'])->middleware(['permission:read_contacts']);
    Route::post('contact-person-create', [ContactPersonController::class, 'store'])->middleware(['permission:create_contacts']);
    Route::delete('contact-person-delete/{id}', [ContactPersonController::class, 'destroy'])->middleware(['permission:delete_contacts']);

    //Sales History
    Route::get('sales-history', [SalesHistoryController::class, 'index'])->middleware(['permission:read_sales_history|manage_sales_history']);
    Route::post('sales-history-create', [SalesHistoryController::class, 'create'])->middleware(['permission:create_sales_history|manage_sales_history']);
    Route::get('sales-history-show/{id}', [SalesHistoryController::class, 'show'])->middleware(['permission:show_sales_history|manage_sales_history']);
    Route::put('sales-history-update/{id}', [SalesHistoryController::class, 'update'])->middleware(['permission:update_sales_history|manage_sales_history']);
    Route::delete('sales-history-delete/{id}', [SalesHistoryController::class, 'destroy'])->middleware(['permission:delete_sales_history|manage_sales_history']);
    
    //Sales level
    Route::get('sales-level', [SalesLevelController::class, 'index'])->middleware(['permission:read_sales_level|manage_sales_level']);
    Route::post('sales-level-create', [SalesLevelController::class, 'create'])->middleware(['permission:create_sales_level|manage_sales_level']);
    Route::get('sales-level-show/{id}', [SalesLevelController::class, 'show'])->middleware(['permission:show_sales_level|manage_sales_level']);
    Route::put('sales-level-update/{id}', [SalesLevelController::class, 'update'])->middleware(['permission:update_sales_level|manage_sales_level']);
    Route::delete('sales-level-delete/{id}', [SalesLevelController::class, 'destroy'])->middleware(['permission:delete_sales_level|manage_sales_level']);
    
    //Sales Reject
    Route::get('sales-reject', [SalesRejectController::class, 'index'])->middleware(['permission:read_sales_reject|manage_sales_reject']);
    Route::post('sales-reject-create', [SalesRejectController::class, 'create'])->middleware(['permission:create_sales_reject|manage_sales_reject']);
    Route::get('sales-reject-show/{id}', [SalesRejectController::class, 'show'])->middleware(['permission:show_sales_reject|manage_sales_reject']);
    Route::put('sales-reject-update/{id}', [SalesRejectController::class, 'update'])->middleware(['permission:update_sales_reject|manage_sales_reject']);
    Route::delete('sales-reject-delete/{id}', [SalesRejectController::class, 'destroy'])->middleware(['permission:delete_sales_reject|manage_sales_reject']);
    
    //Sales Requirement
    Route::get('sales-requirement', [SalesRequirementController::class, 'index'])->middleware(['permission:read_sales_requirement|manage_sales_requirement']);
    Route::post('sales-requirement-create', [SalesRequirementController::class, 'create'])->middleware(['permission:create_sales_requirement|manage_sales_requirement']);
    Route::get('sales-requirement-show/{id}', [SalesRequirementController::class, 'show'])->middleware(['permission:show_sales_requirement|manage_sales_requirement']);
    Route::put('sales-requirement-update/{id}', [SalesRequirementController::class, 'update'])->middleware(['permission:update_sales_requirement|manage_sales_requirement']);
    Route::delete('sales-requirement-delete/{id}', [SalesRequirementController::class, 'destroy'])->middleware(['permission:delete_sales_requirement|manage_sales_requirement']);
    
    //Sales Reschedule
    Route::get('sales-reschedule', [SalesRescheduleController::class, 'index'])->middleware(['permission:read_sales_reschedule|manage_sales_reschedule']);
    Route::post('sales-reschedule-create', [SalesRescheduleController::class, 'create'])->middleware(['permission:create_sales_reschedule|manage_sales_reschedule']);
    Route::get('sales-reschedule-show/{id}', [SalesRescheduleController::class, 'show'])->middleware(['permission:show_sales_reschedule|manage_sales_reschedule']);
    Route::put('sales-reschedule-update/{id}', [SalesRescheduleController::class, 'update'])->middleware(['permission:update_sales_reschedule|manage_sales_reschedule']);
    Route::delete('sales-reschedule-delete/{id}', [SalesRescheduleController::class, 'destroy'])->middleware(['permission:delete_sales_reschedule|manage_sales_reschedule']);
    
    //Sales Update
    Route::get('sales-update', [SalesUpdateController::class, 'index'])->middleware(['permission:read_sales_update|manage_sales_update']);
    Route::post('sales-update-create', [SalesUpdateController::class, 'create'])->middleware(['permission:create_sales_update|manage_sales_update']);
    Route::get('sales-update-show/{id}', [SalesUpdateController::class, 'show'])->middleware(['permission:show_sales_update|manage_sales_update']);
    Route::put('sales-update-update/{id}', [SalesUpdateController::class, 'update'])->middleware(['permission:update_sales_update|manage_sales_update']);
    Route::delete('sales-update-delete/{id}', [SalesUpdateController::class, 'destroy'])->middleware(['permission:delete_sales_update|manage_sales_update']);
});
