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
            'name' => 'GIA11',
            'scope' => 'Garuda Indonesia',
        ]);
        Area::create([
            'name' => 'CTI21',
            'scope' => 'Citilink',
        ]);
        Area::create([
            'name' => 'ARS01',
            'scope' => 'Air Asia',
        ]);
        Area::create([
            'name' => 'LIR45',
            'scope' => 'Lion Air',
        ]);
    }
}
