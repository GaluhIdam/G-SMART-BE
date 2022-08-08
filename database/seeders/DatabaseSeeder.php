<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            AMSSeeder::class,
            MaintenanceSeeder::class,
            RegionSeeder::class,
            AreaSeeder::class,
            ProspectTypeSeeder::class,
            TransactionTypeSeeder::class,
            StrategicInitiativesSeeder::class,
            ApuSeeder::class,
            ComponentSeeder::class,
            EngineSeeder::class,
            AircraftTypeSeeder::class,
            CountriesSeeder::class,
            CustomerSeeder::class,
            ProspectSeeder::class,
        ]);
    }
}
