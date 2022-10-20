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
            'name' => 'Middle East',
        ]);
        Region::create([
            'name' => 'South Asia',
        ]);
        Region::create([
            'name' => 'Europe',
        ]);
        Region::create([
            'name' => 'North America',
        ]);
        Region::create([
            'name' => 'South America',
        ]);
    }
}
