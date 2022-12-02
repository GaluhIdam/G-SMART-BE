<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Administrator
        $admin = User::create([
            'name'              => 'Super Admin [Testing]',
            'username'          => 'administrator',
            'nopeg'             => null,
            'unit'              => null,
            'role_id'           => 1,
            'email'             => null,
            'password'          => Hash::make('p@ssw0rd'),
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('Administrator');

        // TPC
        $tpc = User::create([
            'name'              => 'TPC User [Testing]',
            'username'          => 'tpc_user',
            'nopeg'             => null,
            'unit'              => null,
            'role_id'           => 2,
            'email'             => null,
            'password'          => Hash::make('p@ssw0rd'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpc->assignRole('TPC');

        // TPR
        $tpr = User::create([
            'name'              => 'TPR User [Testing]',
            'username'          => 'tpr_user',
            'nopeg'             => null,
            'unit'              => null,
            'role_id'           => 3,
            'email'             => null,
            'password'          => Hash::make('p@ssw0rd'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpr->assignRole('TPR');
        
        // CBO
        $cbo = User::create([
            'name'              => 'CBO User [Testing]',
            'username'          => 'cbo_user',
            'nopeg'             => null,
            'unit'              => null,
            'role_id'           => 4,
            'email'             => null,
            'password'          => Hash::make('p@ssw0rd'),
            'email_verified_at' => Carbon::now(),
        ]);
        $cbo->assignRole('CBO');
    }
}
