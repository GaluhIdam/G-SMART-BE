<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create(['name' => 'III']);
        Area::create(['name' => 'II']);
        Area::create(['name' => 'I']);
        Area::create(['name' => 'KAM']);
    }
}
