<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Component::create([
            'name' => 'Wheel & Brake',
        ]);
        Component::create([
            'name' => 'Fuselag',
        ]);
        Component::create([
            'name' => 'Wing',
        ]);
        Component::create([
            'name' => 'Landing Gear',
        ]);
        Component::create([
            'name' => 'Kokpit',
        ]);
        Component::create([
            'name' => 'Tail',
        ]);
    }
}
