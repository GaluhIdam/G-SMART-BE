<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        $administrator = User::create([
            'name'              => 'Admin JTI',
            'username'          => 'administrator',
            'role_id'           => 1,
            'email'             => 'admin@gmf.com',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $administrator->assignRole('Administrator');

        $user = User::create([
            'name'              => 'User JTI',
            'username'          => 'user',
            'role_id'           => 2,
            'email'             => 'user@gmf.com',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('User');

        $roler = User::create([
            'name'              => 'Roler JTI',
            'username'          => 'roler',
            'role_id'           => 3,
            'email'             => 'roler@gmf.com',
            'password'          => password_hash('password', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $roler->assignRole('User');
    }
}
