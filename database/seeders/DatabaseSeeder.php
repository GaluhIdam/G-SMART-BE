<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ProspectTypeSeeder::class,
            TransactionTypeSeeder::class,
            StrategicInitiativesSeeder::class,
            CustomerSeeder::class,
            ProspectSeeder::class,
        ]);
    }
}
