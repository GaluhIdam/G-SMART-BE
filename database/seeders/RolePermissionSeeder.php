<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //User Permission
        $read_users = Permission::create(['name' => 'read_users']);
        $create_users = Permission::create(['name' => 'create_users']);
        $show_users = Permission::create(['name' => 'show_users']);
        $update_users = Permission::create(['name' => 'update_users']);
        $delete_users = Permission::create(['name' => 'delete_users']);

        //Role Permission
        $role = Permission::create(['name' => 'role']);
        $read_role = Permission::create(['name' => 'read_role']);
        $create_role = Permission::create(['name' => 'create_role']);
        $show_role = Permission::create(['name' => 'show_role']);
        $update_role = Permission::create(['name' => 'update_role']);
        $delete_role = Permission::create(['name' => 'delete_role']);

        //Strategic Initiative Permission
        $read_strategic_initiative = Permission::create(['name' => 'read_strategic_initiative']);
        $create_strategic_initiative = Permission::create(['name' => 'create_strategic_initiative']);
        $show_strategic_initiative = Permission::create(['name' => 'show_strategic_initiative']);
        $update_strategic_initiative = Permission::create(['name' => 'update_strategic_initiative']);
        $delete_strategic_initiative = Permission::create(['name' => 'delete_strategic_initiative']);

        //Region Permission
        $read_region = Permission::create(['name' => 'read_region']);
        $create_region = Permission::create(['name' => 'create_region']);
        $show_region = Permission::create(['name' => 'show_region']);
        $update_region = Permission::create(['name' => 'update_region']);
        $delete_region = Permission::create(['name' => 'delete_region']);

        //Countries Permission
        $read_countries = Permission::create(['name' => 'read_countries']);
        $create_countries = Permission::create(['name' => 'create_countries']);
        $show_countries = Permission::create(['name' => 'show_countries']);
        $update_countries = Permission::create(['name' => 'update_countries']);
        $delete_countries = Permission::create(['name' => 'delete_countries']);

        //Area Permission
        $read_area = Permission::create(['name' => 'read_area']);
        $create_area = Permission::create(['name' => 'create_area']);
        $show_area = Permission::create(['name' => 'show_area']);
        $update_area = Permission::create(['name' => 'update_area']);
        $delete_area = Permission::create(['name' => 'delete_area']);

        //Maintenance Permission
        $read_maintenance = Permission::create(['name' => 'read_maintenance']);
        $create_maintenance = Permission::create(['name' => 'create_maintenance']);
        $show_maintenance = Permission::create(['name' => 'show_maintenance']);
        $update_maintenance = Permission::create(['name' => 'update_maintenance']);
        $delete_maintenance = Permission::create(['name' => 'delete_maintenance']);

        //Transaction Type Permission
        $read_transaction_type = Permission::create(['name' => 'read_transaction_type']);
        $create_transaction_type = Permission::create(['name' => 'create_transaction_type']);
        $show_transaction_type = Permission::create(['name' => 'show_transaction_type']);
        $update_transaction_type = Permission::create(['name' => 'update_transaction_type']);
        $delete_transaction_type = Permission::create(['name' => 'delete_transaction_type']);

        //AMS Permission
        $read_ams = Permission::create(['name' => 'read_ams']);
        $create_ams = Permission::create(['name' => 'create_ams']);
        $show_ams = Permission::create(['name' => 'show_ams']);
        $update_ams = Permission::create(['name' => 'update_ams']);
        $delete_ams = Permission::create(['name' => 'delete_ams']);

        //Prospect Type Permission
        $read_prospect_type = Permission::create(['name' => 'read_prospect_type']);
        $create_prospect_type = Permission::create(['name' => 'create_prospect_type']);
        $show_prospect_type = Permission::create(['name' => 'show_prospect_type']);
        $update_prospect_type = Permission::create(['name' => 'update_prospect_type']);
        $delete_prospect_type = Permission::create(['name' => 'delete_prospect_type']);

        //Aircraft Type Permission
        $read_aircraft_type = Permission::create(['name' => 'read_aircraft_type']);
        $create_aircraft_type = Permission::create(['name' => 'create_aircraft_type']);
        $show_aircraft_type = Permission::create(['name' => 'show_aircraft_type']);
        $update_aircraft_type = Permission::create(['name' => 'update_aircraft_type']);
        $delete_aircraft_type = Permission::create(['name' => 'delete_aircraft_type']);

        //Engine Permission
        $read_engine = Permission::create(['name' => 'read_engine']);
        $create_engine = Permission::create(['name' => 'create_engine']);
        $show_engine = Permission::create(['name' => 'show_engine']);
        $update_engine = Permission::create(['name' => 'update_engine']);
        $delete_engine = Permission::create(['name' => 'delete_engine']);

        //Component Permission
        $read_component = Permission::create(['name' => 'read_component']);
        $create_component = Permission::create(['name' => 'create_component']);
        $show_component = Permission::create(['name' => 'show_component']);
        $update_component = Permission::create(['name' => 'update_component']);
        $delete_component = Permission::create(['name' => 'delete_component']);

        //APU Permission
        $read_apu = Permission::create(['name' => 'read_apu']);
        $create_apu = Permission::create(['name' => 'create_apu']);
        $show_apu = Permission::create(['name' => 'show_apu']);
        $update_apu = Permission::create(['name' => 'update_apu']);
        $delete_apu = Permission::create(['name' => 'delete_apu']);

        //Role Admin
        $admin = Role::create([
            'name' => 'Administrator',
            'description' => 'Manage All Module',
        ])->givePermissionTo(Permission::all());

        $Roler = Role::create([
            'name' => 'Roler',
            'description' => 'Manage All Role',
        ])->givePermissionTo(['role']);

        //Role User
        $user = Role::create([
            'name' => 'User',
            'description' => 'Only Specific Permission',
        ])->givePermissionTo($read_role, $create_role, $show_role, $update_role, $delete_role);
    }
}
