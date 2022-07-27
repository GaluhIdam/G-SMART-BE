<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use phpseclib3\Math\BigInteger\Engines\Engine;

class DatabaseSeeder extends Seeder
{
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
            ApuSeeder::class,
            ComponentSeeder::class,
            EngineSeeder::class,
        ]);
    }
}
