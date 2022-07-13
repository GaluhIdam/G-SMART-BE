<?php

namespace Database\Seeders;

use App\Models\ProspectTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProspectTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProspectTypes::create([
            'name'        => 'Organic',
            'description' => 'If you need more info, please check it out'
        ]);
        ProspectTypes::create([
            'name'        => 'In Organic',
            'description' => 'Need to define which Strategic Initiative and selected PM'
        ]);
    }
}
