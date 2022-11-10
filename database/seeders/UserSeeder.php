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
        // TODO role mapping -> [Admin, TPC, TD, TP, TPR, AMS, CBO]

        // Administrator
        $admin = User::create([
            'name'              => 'Super Admin',
            'username'          => 'administrator',
            'nopeg'             => '99999',
            'unit'              => 'ADM',
            'role_id'           => 1,
            'email'             => 'admin.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('Administrator');

        // TPC
        $tpc = User::create([
            'name'              => 'TPC User',
            'username'          => 'tpc_user',
            'nopeg'             => '32986',
            'unit'              => 'TPC',
            'role_id'           => 2,
            'email'             => 'tpc.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpc->assignRole('TPC');

        // TPR
        $tpr = User::create([
            'name'              => 'TPR User',
            'username'          => 'tpr_user',
            'nopeg'             => '102982',
            'unit'              => 'TPR',
            'role_id'           => 3,
            'email'             => 'tpr.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $tpr->assignRole('TPR');
        
        // CBO
        $cbo = User::create([
            'name'              => 'CBO User',
            'username'          => 'cbo_user',
            'nopeg'             => '292375',
            'unit'              => 'CBO',
            'role_id'           => 4,
            'email'             => 'cbo.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $cbo->assignRole('CBO');

        // AMS
        $ams = User::create([
            'name'              => 'AMS User 1',
            'username'          => 'ams_user1',
            'nopeg'             => '017523',
            'unit'              => 'TPW',
            'role_id'           => 5,
            'email'             => 'ams1.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $ams->assignRole('AMS');
        $ams = User::create([
            'name'              => 'AMS User 2',
            'username'          => 'ams_user2',
            'nopeg'             => '235853',
            'unit'              => 'TPX',
            'role_id'           => 5,
            'email'             => 'ams2.gsmart@gmf-aeroasia.co.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $ams->assignRole('AMS');
        $ams = User::create([
            'name'              => 'AMS User 3',
            'username'          => 'ams_user3',
            'nopeg'             => '235352',
            'unit'              => 'TPY',
            'role_id'           => 5,
            'email'             => 'ams3.gsmart@gmf-aeroasia.co.id',
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
