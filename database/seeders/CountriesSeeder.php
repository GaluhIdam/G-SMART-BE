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
            'name' => 'Taiwan',
            'region_id' => 2,
        ]);
        Countries::create([
            'name' => 'South Korea',
            'region_id' => 2,
        ]);
        Countries::create([
            'name' => 'Turkmenistan',
            'region_id' => 3,
        ]);
        Countries::create([
            'name' => 'Kazakhstan ',
            'region_id' => 3,
        ]);
        Countries::create([
            'name' => 'Pakistan',
            'region_id' => 4,
        ]);
        Countries::create([
            'name' => 'Afghanistan ',
            'region_id' => 4,
        ]);
    }
}
