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
                'code' => "H1L{$i}",
                'name' => "Hangar 1 Line {$i}",
            ]);
            Line::create([
                'hangar_id' => 2,
                'code' => "H2L{$i}",
                'name' => "Hangar 2 Line {$i}",
            ]);
            Line::create([
                'hangar_id' => 3,
                'code' => "H3L{$i}",
                'name' => "Hangar 3 Line {$i}",
            ]);
            Line::create([
                'hangar_id' => 4,
                'code' => "H4L{$i}",
                'name' => "Hangar 4 Line {$i}",
            ]);
        }
    }
}
