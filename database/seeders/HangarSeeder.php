<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hangar;

class HangarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hangar::create(['name' => 'I']);
        Hangar::create(['name' => 'II']);
        Hangar::create(['name' => 'III']);
        Hangar::create(['name' => 'IV']);
        Hangar::create(['name' => 'Parking']);
    }
}
