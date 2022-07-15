<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrategicInitiatives;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StrategicInitiativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StrategicInitiatives::create([
            'name'        => 'Restructuring',
            'description' => 'Menstruktur ulang',
        ]);
    }
}