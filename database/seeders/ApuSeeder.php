<?php

namespace Database\Seeders;

use App\Models\Apu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Apu::create([
            'name' => 'GTCP85',
        ]);
        Apu::create([
            'name' => 'GPXR19',
        ]);
        Apu::create([
            'name' => 'ZTEE65',
        ]);
        Apu::create([
            'name' => 'RRQH90',
        ]);
        Apu::create([
            'name' => 'OPEQ01',
        ]);
    }
}
