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
        /*
            DATASHEET COMPONENT DARI APLIKASI USER
            - Component Retail
            - Component PBTH
            - Landing Gear
            - Wheel & Brake
            - 737CL
        */

        Component::create(['name' => 'Component Retail',]);
        Component::create(['name' => 'Component PBTH',]);
        Component::create(['name' => 'Landing Gear',]);
        Component::create(['name' => 'Wheel & Brake',]);
        Component::create(['name' => '737CL',]);
    }
}
