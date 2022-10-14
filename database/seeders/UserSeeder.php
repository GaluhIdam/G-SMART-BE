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
        // $administrator = User::create([
        //     'name'              => 'Zudith Muhammad Iqbal',
        //     'username'          => 'administrator',
        //     'nopeg'             => 582813,
        //     'unit'              => 'TDI-2',
        //     'role_id'           => 1,
        //     'email'             => 'zudith@gmf-aeroasia.co.id',
        //     'password'          => password_hash('password', PASSWORD_BCRYPT),
        //     'email_verified_at' => Carbon::now(),
        // ]);
        // $administrator->assignRole('Administrator');
        // $administrator->givePermissionTo(Permission::all());

        // $roler = User::create([
        //     'name'              => 'Roler JTI',
        //     'username'          => 'roler',
        //     'nopeg'             => 582815,
        //     'unit'              => 'TDI-2',
        //     'role_id'           => 2,
        //     'email'             => 'roler@gmf.com',
        //     'password'          => password_hash('password', PASSWORD_BCRYPT),
        //     'email_verified_at' => Carbon::now(),
        // ]);
        // $roler->assignRole('Roler');
        // $user->givePermissionTo('read_product');

        // $user = User::create([
        //     'name'              => 'User JTI',
        //     'username'          => 'user',
        //     'nopeg'             => 582814,
        //     'unit'              => 'TDI-2',
        //     'role_id'           => 3,
        //     'email'             => 'user@gmf.com',
        //     'password'          => password_hash('password', PASSWORD_BCRYPT),
        //     'email_verified_at' => Carbon::now(),
        // ]);
        // $user->assignRole('User');
        // $user->givePermissionTo('read_product');

        // TODO role mapping -> [Admin, TPC, TD, TP, TPR, AMS, C]

        // Administrator
        $admin = User::create([
            'name'              => 'Fulan bin Fulan',
            'username'          => 'administrator',
            'nopeg'             => '999999',
            'unit'              => 'ABC-1',
            'role_id'           => 1,
            'email'             => 'fulan@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('Administrator');

        // TPC
        $tpc = User::create([
            'name'              => 'User TPC',
            'username'          => 'tpc_user',
            'nopeg'             => '329865',
            'unit'              => 'TPC-1',
            'role_id'           => 2,
            'email'             => 'tpc.user@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpc->assignRole('TPC');

        // TPR
        $tpr = User::create([
            'name'              => 'User TPR',
            'username'          => 'tpr_user',
            'nopeg'             => '102985',
            'unit'              => 'TPR-1',
            'role_id'           => 3,
            'email'             => 'tpr.user@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpr->assignRole('TPR');
        
        // CBO
        $tpr = User::create([
            'name'              => 'User CBO',
            'username'          => 'cbo_user',
            'nopeg'             => '092375',
            'unit'              => 'CBO-1',
            'role_id'           => 4,
            'email'             => 'cbo.user@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpr->assignRole('CBO');

        // AMS
        $ams = User::create([
            'name'              => 'User AMS 1',
            'username'          => 'ams_user1',
            'nopeg'             => '017523',
            'unit'              => 'AMS-1',
            'role_id'           => 5,
            'email'             => 'ams.user1@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $ams->assignRole('AMS');
        $ams = User::create([
            'name'              => 'User AMS 2',
            'username'          => 'ams_user2',
            'nopeg'             => '235853',
            'unit'              => 'AMS-2',
            'role_id'           => 5,
            'email'             => 'ams.user2@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $ams->assignRole('AMS');
        $ams = User::create([
            'name'              => 'User AMS 3',
            'username'          => 'ams_user3',
            'nopeg'             => '0235352',
            'unit'              => 'AMS-3',
            'role_id'           => 5,
            'email'             => 'ams.user3@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $ams->assignRole('AMS');

        // TD
        // $td = User::create([
        //     'name'              => 'User TD',
        //     'username'          => 'td_user',
        //     'nopeg'             => '923524',
        //     'unit'              => 'TDI-1',
        //     'role_id'           => 3,
        //     'email'             => 'tdi@gmf-aeroasia.co.id',
        //     'password'          => Hash::mak('password'),
        //     'email_verified_at' => Carbon::now(),
        // ]);
        // $td->assignRole('TD');

        // TP
        // $tp = User::create([
        //     'name'              => 'User TP',
        //     'username'          => 'tp_user',
        //     'nopeg'             => '092375',
        //     'unit'              => 'TPA-1',
        //     'role_id'           => 4,
        //     'email'             => 'tpa@gmf-aeroasia.co.id',
        //     'password'          => Hash::mak('password'),
        //     'email_verified_at' => Carbon::now(),
        // ]);
        // $tp->assignRole('TP');
    }
}
