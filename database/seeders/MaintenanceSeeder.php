<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Maintenance::create([
            'name' => 'Engineering',
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
        Maintenance::create([
            'name' => 'Preparation',
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
        Maintenance::create([
            'name' => 'Verifying',
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
        Maintenance::create([
            'name' => 'Maintenance',
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
        Maintenance::create([
            'name' => 'Testing',
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
    }
}
