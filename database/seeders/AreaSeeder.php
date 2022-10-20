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
        Area::create([
            'name' => 'Area 1',
            'scope' => 'Garuda Indonesia',
        ]);
        Area::create([
            'name' => 'Area 2',
            'scope' => 'Citilink',
        ]);
        Area::create([
            'name' => 'Area 3',
            'scope' => 'Air Asia',
        ]);
        Area::create([
            'name' => 'Area 4',
            'scope' => 'Lion Air',
        ]);
        Area::create([
            'name' => 'Area 5',
            'scope' => 'Sriwijaya Air',
        ]);
        Area::create([
            'name' => 'Area 6',
            'scope' => 'Batik Air',
        ]);
    }
}
