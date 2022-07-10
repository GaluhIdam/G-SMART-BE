<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::create([
            'name'              => 'Super Administrator',
            'username'          => 'super_admin',
            'email'             => 'super_admin@gmf.com',
            'password'          => password_hash('password_super_admin', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $super_admin->assignRole('super-admin');

        $admin = User::create([
            'name'              => 'Administrator',
            'username'          => 'admin',
            'email'             => 'admin@gmf.com',
            'password'          => password_hash('password_admin', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('admin');

        $user  = User::create([
            'name'              => 'User',
            'username'          => 'user',
            'email'             => 'user@gmf.com',
            'password'          => password_hash('password_user', PASSWORD_BCRYPT),
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('user');
    }
}
