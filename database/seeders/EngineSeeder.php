<?php

namespace Database\Seeders;

use App\Models\Engine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EngineSeeder extends Seeder
{

    public function run()
    {
        Engine::create([
            'name' => 'CFM56-5B',
        ]);
    }
}
