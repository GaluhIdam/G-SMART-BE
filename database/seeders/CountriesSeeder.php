<?php

namespace Database\Seeders;

use App\Models\Countries;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Countries::create([
            'name' => 'Indonesia',
            'region_id' => 1,
        ]);
        Countries::create([
            'name' => 'Malaysia',
            'region_id' => 1,
        ]);
        Countries::create([
            'name' => 'Singapore',
            'region_id' => 1,
        ]);
        Countries::create([
            'name' => 'Japan',
            'region_id' => 2,
        ]);
        Countries::create([
            'name' => 'Qatar',
            'region_id' => 3,
        ]);
        Countries::create([
            'name' => 'India',
            'region_id' => 4,
        ]);
        Countries::create([
            'name' => 'Germany',
            'region_id' => 5,
        ]);
        Countries::create([
            'name' => 'USA',
            'region_id' => 6,
        ]);
        Countries::create([
            'name' => 'Taiwan',
            'region_id' => 7,
        ]);
        Countries::create([
            'name' => 'Chile',
            'region_id' => 7,
        ]);
    }
}
