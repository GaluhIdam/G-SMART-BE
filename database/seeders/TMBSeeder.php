<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TMB;

class TMBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TMB::create([
            'product_id' => 1,
            'ac_type_id' => 4,
            'component_id' => 3,
            'engine_id' => 5,
            'apu_id' => 2,
            'market_share' => 5000,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 1,
        ]);
        TMB::create([
            'product_id' => 2,
            'ac_type_id' => 8,
            'component_id' => 6,
            'engine_id' => 2,
            'apu_id' => 5,
            'market_share' => 2500,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 1,
        ]);
        TMB::create([
            'product_id' => 3,
            'ac_type_id' => 3,
            'component_id' => 5,
            'engine_id' => 4,
            'apu_id' => 1,
            'market_share' => 10000,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 2,
        ]);
        TMB::create([
            'product_id' => 4,
            'ac_type_id' => 1,
            'component_id' => 1,
            'engine_id' => 3,
            'apu_id' => 4,
            'market_share' => 2000,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 5,
        ]);
        TMB::create([
            'product_id' => 5,
            'ac_type_id' => 2,
            'component_id' => 2,
            'engine_id' => 1,
            'apu_id' => 3,
            'market_share' => 3200,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 5,
        ]);
        TMB::create([
            'product_id' => 4,
            'ac_type_id' => 7,
            'component_id' => 1,
            'engine_id' => 2,
            'apu_id' => 1,
            'market_share' => 6500,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 4,
        ]);
        TMB::create([
            'product_id' => 3,
            'ac_type_id' => 3,
            'component_id' => 5,
            'engine_id' => 1,
            'apu_id' => 2,
            'market_share' => 1700,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 2,
        ]);
        TMB::create([
            'product_id' => 2,
            'ac_type_id' => 6,
            'component_id' => 4,
            'engine_id' => 4,
            'apu_id' => 3,
            'market_share' => 3000,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 3,
        ]);
        TMB::create([
            'product_id' => 5,
            'ac_type_id' => 8,
            'component_id' => 2,
            'engine_id' => 5,
            'apu_id' => 4,
            'market_share' => 5800,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 4,
        ]);
        TMB::create([
            'product_id' => 1,
            'ac_type_id' => 1,
            'component_id' => 6,
            'engine_id' => 3,
            'apu_id' => 5,
            'market_share' => 2200,
            'remarks' => 'Lorem ipsum',
            'maintenance_id' => 3,
        ]);
    }
}
