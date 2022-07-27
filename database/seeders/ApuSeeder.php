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
    }
}
