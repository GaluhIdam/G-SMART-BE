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
        $manage_users = Permission::create(['name' => 'manage_users']);
        $read_users = Permission::create(['name' => 'read_users']);
        $create_users = Permission::create(['name' => 'create_users']);
        $show_users = Permission::create(['name' => 'show_users']);
        $update_users = Permission::create(['name' => 'update_users']);
        $delete_users = Permission::create(['name' => 'delete_users']);

        //Role Permission
        $manage_role = Permission::create(['name' => 'manage_role']);
        $read_role = Permission::create(['name' => 'read_role']);
        $create_role = Permission::create(['name' => 'create_role']);
        $show_role = Permission::create(['name' => 'show_role']);
        $update_role = Permission::create(['name' => 'update_role']);
        $delete_role = Permission::create(['name' => 'delete_role']);

        //Strategic Initiative Permission
        $manage_strategic_initiative = Permission::create(['name' => 'manage_strategic_initiative']);
        $read_strategic_initiative = Permission::create(['name' => 'read_strategic_initiative']);
        $create_strategic_initiative = Permission::create(['name' => 'create_strategic_initiative']);
        $show_strategic_initiative = Permission::create(['name' => 'show_strategic_initiative']);
        $update_strategic_initiative = Permission::create(['name' => 'update_strategic_initiative']);
        $delete_strategic_initiative = Permission::create(['name' => 'delete_strategic_initiative']);

        //Region Permission
        $manage_region = Permission::create(['name' => 'manage_region']);
        $read_region = Permission::create(['name' => 'read_region']);
        $create_region = Permission::create(['name' => 'create_region']);
        $show_region = Permission::create(['name' => 'show_region']);
        $update_region = Permission::create(['name' => 'update_region']);
        $delete_region = Permission::create(['name' => 'delete_region']);

        //Countries Permission
        $manage_countries = Permission::create(['name' => 'manage_countries']);
        $read_countries = Permission::create(['name' => 'read_countries']);
        $create_countries = Permission::create(['name' => 'create_countries']);
        $show_countries = Permission::create(['name' => 'show_countries']);
        $update_countries = Permission::create(['name' => 'update_countries']);
        $delete_countries = Permission::create(['name' => 'delete_countries']);

        //Area Permission
        $manage_area = Permission::create(['name' => 'manage_area']);
        $read_area = Permission::create(['name' => 'read_area']);
        $create_area = Permission::create(['name' => 'create_area']);
        $show_area = Permission::create(['name' => 'show_area']);
        $update_area = Permission::create(['name' => 'update_area']);
        $delete_area = Permission::create(['name' => 'delete_area']);

        //Maintenance Permission
        $manage_maintenance = Permission::create(['name' => 'manage_maintenance']);
        $read_maintenance = Permission::create(['name' => 'read_maintenance']);
        $create_maintenance = Permission::create(['name' => 'create_maintenance']);
        $show_maintenance = Permission::create(['name' => 'show_maintenance']);
        $update_maintenance = Permission::create(['name' => 'update_maintenance']);
        $delete_maintenance = Permission::create(['name' => 'delete_maintenance']);

        //Transaction Type Permission
        $manage_transaction_type = Permission::create(['name' => 'manage_transaction_type']);
        $read_transaction_type = Permission::create(['name' => 'read_transaction_type']);
        $create_transaction_type = Permission::create(['name' => 'create_transaction_type']);
        $show_transaction_type = Permission::create(['name' => 'show_transaction_type']);
        $update_transaction_type = Permission::create(['name' => 'update_transaction_type']);
        $delete_transaction_type = Permission::create(['name' => 'delete_transaction_type']);

        //AMS Permission
        $manage_ams = Permission::create(['name' => 'manage_ams']);
        $read_ams = Permission::create(['name' => 'read_ams']);
        $create_ams = Permission::create(['name' => 'create_ams']);
        $show_ams = Permission::create(['name' => 'show_ams']);
        $update_ams = Permission::create(['name' => 'update_ams']);
        $delete_ams = Permission::create(['name' => 'delete_ams']);

        //Prospect Type Permission
        $manage_prospect_type = Permission::create(['name' => 'manage_prospect_type']);
        $read_prospect_type = Permission::create(['name' => 'read_prospect_type']);
        $create_prospect_type = Permission::create(['name' => 'create_prospect_type']);
        $show_prospect_type = Permission::create(['name' => 'show_prospect_type']);
        $update_prospect_type = Permission::create(['name' => 'update_prospect_type']);
        $delete_prospect_type = Permission::create(['name' => 'delete_prospect_type']);

        //Aircraft Type Permission
        $manage_aircraft_type = Permission::create(['name' => 'manage_aircraft_type']);
        $read_aircraft_type = Permission::create(['name' => 'read_aircraft_type']);
        $create_aircraft_type = Permission::create(['name' => 'create_aircraft_type']);
        $show_aircraft_type = Permission::create(['name' => 'show_aircraft_type']);
        $update_aircraft_type = Permission::create(['name' => 'update_aircraft_type']);
        $delete_aircraft_type = Permission::create(['name' => 'delete_aircraft_type']);

        //Engine Permission
        $manage_engine = Permission::create(['name' => 'manage_engine']);
        $read_engine = Permission::create(['name' => 'read_engine']);
        $create_engine = Permission::create(['name' => 'create_engine']);
        $show_engine = Permission::create(['name' => 'show_engine']);
        $update_engine = Permission::create(['name' => 'update_engine']);
        $delete_engine = Permission::create(['name' => 'delete_engine']);

        //Component Permission
        $manage_component = Permission::create(['name' => 'manage_component']);
        $read_component = Permission::create(['name' => 'read_component']);
        $create_component = Permission::create(['name' => 'create_component']);
        $show_component = Permission::create(['name' => 'show_component']);
        $update_component = Permission::create(['name' => 'update_component']);
        $delete_component = Permission::create(['name' => 'delete_component']);

        //APU Permission
        $manage_apu = Permission::create(['name' => 'manage_apu']);
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

        $roler = Role::create([
            'name' => 'Roler',
            'description' => 'Manage All Role',
        ])->givePermissionTo($manage_role);

        //Role User
        $user = Role::create([
            'name' => 'User',
            'description' => 'Only Specific Permission',
        ])->givePermissionTo($read_role, $create_role, $show_role, $update_role, $delete_role);
    }
}
