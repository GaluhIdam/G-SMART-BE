<?php

namespace Database\Seeders;

use App\Models\ProspectType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProspectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProspectType::create([
            'name'        => 'Organic',
            'description' => 'If you need more info, please check it out'
        ]);
        ProspectType::create([
            'name'        => 'In Organic',
            'description' => 'Need to define which Strategic Initiative and selected PM'
        ]);
    }
}
