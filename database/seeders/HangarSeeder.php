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
        Hangar::create([
            'code' => 'H1',
            'name' => 'Hangar 1',
        ]);
        Hangar::create([
            'code' => 'H2',
            'name' => 'Hangar 2',
        ]);
        Hangar::create([
            'code' => 'H3',
            'name' => 'Hangar 3',
        ]);
        Hangar::create([
            'code' => 'H4',
            'name' => 'Hangar 4',
        ]);
    }
}
