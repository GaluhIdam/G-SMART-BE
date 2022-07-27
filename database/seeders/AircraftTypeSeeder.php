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
            'name' => 'B777',
        ]);
    }
}
