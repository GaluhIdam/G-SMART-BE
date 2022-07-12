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

        $view_user = Permission::create(['name' => 'view_user']);

        $role_super_admin = Role::create(['name' => 'super-admin']);
        $role_super_admin->givePermissionTo(Permission::all());

        $role_admin = Role::create(['name' => 'admin']);
        $role_admin->givePermissionTo($view_user);

        $role_user = Role::create(['name' => 'user']);
    }
}
