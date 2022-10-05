<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Line;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Line::create([
                'hangar_id' => 1,
                'code' => 'LN'.$i,
                'name' => 'Line '.$i,
            ]);
            Line::create([
                'hangar_id' => 2,
                'code' => 'LN'.$i,
                'name' => 'Line '.$i,
            ]);
            Line::create([
                'hangar_id' => 3,
                'code' => 'LN'.$i,
                'name' => 'Line '.$i,
            ]);
            Line::create([
                'hangar_id' => 4,
                'code' => 'LN'.$i,
                'name' => 'Line '.$i,
            ]);
        }
    }
}
