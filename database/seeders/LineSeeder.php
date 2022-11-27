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
        $hangars = \App\Models\Hangar::all();

        foreach ($hangars as $hangar) {
            Line::create([
                'hangar_id' => $hangar->id,
                'name' => "I",
            ]);
            Line::create([
                'hangar_id' => $hangar->id,
                'name' => "II",
            ]);
            Line::create([
                'hangar_id' => $hangar->id,
                'name' => "III",
            ]);
            Line::create([
                'hangar_id' => $hangar->id,
                'name' => "IV",
            ]);
        }
    }
}
