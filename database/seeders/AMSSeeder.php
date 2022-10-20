<?php

namespace Database\Seeders;

use App\Models\AMS;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AMS::create([
            'user_id' => 5,
            'initial' => 'ZMI',
        ]);
        AMS::create([
            'user_id' => 6,
            'initial' => 'RJTI',
        ]);
        AMS::create([
            'user_id' => 7,
            'initial' => 'UJTI',
        ]);
    }
}
