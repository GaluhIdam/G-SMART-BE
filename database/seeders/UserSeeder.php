<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        $administrator = User::create([
            'name'              => 'Zudith Muhammad Iqbal',
            'username'          => 'administrator',
            'nopeg'             => 582813,
            'unit'              => 'TDI-2',
            'role_id'           => 1,
            'email'             => 'zudith@gmf-aeroasia.co.id',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $administrator->assignRole('Administrator');
        // $administrator->givePermissionTo(Permission::all());

        $roler = User::create([
            'name'              => 'Roler JTI',
            'username'          => 'roler',
            'nopeg'             => 582815,
            'unit'              => 'TDI-2',
            'role_id'           => 2,
            'email'             => 'roler@gmf.com',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $roler->assignRole('Roler');
        // $user->givePermissionTo('read_product');

        $user = User::create([
            'name'              => 'User JTI',
            'username'          => 'user',
            'nopeg'             => 582814,
            'unit'              => 'TDI-2',
            'role_id'           => 3,
            'email'             => 'user@gmf.com',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('User');
        // $user->givePermissionTo('read_product');
    }
}
