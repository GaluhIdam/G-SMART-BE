<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'name' => 'South East Asia',
        ]);
        Region::create([
            'name' => 'East Asia',
        ]);
        Region::create([
            'name' => 'Central Asia',
        ]);
        Region::create([
            'name' => 'South Asia',
        ]);
    }
}
