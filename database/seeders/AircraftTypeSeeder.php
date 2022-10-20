<?php

namespace Database\Seeders;

use App\Models\AircraftType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AircraftTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AircraftType::create([
            'name' => 'B707',
        ]);
        AircraftType::create([
            'name' => 'B717',
        ]);
        AircraftType::create([
            'name' => 'B727',
        ]);
        AircraftType::create([
            'name' => 'B737',
        ]);
        AircraftType::create([
            'name' => 'B747',
        ]);
        AircraftType::create([
            'name' => 'B757',
        ]);
        AircraftType::create([
            'name' => 'B767',
        ]);
        AircraftType::create([
            'name' => 'B777',
        ]);
    }
}
