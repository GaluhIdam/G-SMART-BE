<?php

namespace Database\Seeders;

use App\Models\Countrie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Countrie::create([
            'name' => 'Indonesia',
            'region_id' => 1,
        ]);
    }
}
