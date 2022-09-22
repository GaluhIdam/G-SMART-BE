<?php

namespace Database\Seeders;

use App\Models\Sales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sales::create([
            'customer_id'    => 1,
            'prospect_id'    => 1,
            'ac_reg'         => 'PK-GFM',
            'value'          => 50,
            'maintenance_id' => 1,
            'tat'            => 1,
            'start_date'     => date('Y-m-d'),
            'end_date'       => date('Y-m-d'),
            'so_number'      => '0181284129y41212412412412',
            'hangar_id'      => 1,
            'product_id'     => 1,
            'ac_type_id'     => 1,
            'component_id'   => 1,
            'engine_id'      => 1,
            'apu_id'         => 1,
            'is_rkap'        => 1,
        ]);

        Sales::create([
            'customer_id'    => 1,
            'prospect_id'    => 1,
            'ac_reg'         => 'PK-GFM',
            'value'          => 25,
            'maintenance_id' => 1,
            'tat'            => 1,
            'start_date'     => date('Y-m-d'),
            'end_date'       => date('Y-m-d'),
            'so_number'      => '0181284129y41212412412412',
            'hangar_id'      => 1,
            'product_id'     => 1,
            'ac_type_id'     => 1,
            'component_id'   => 1,
            'engine_id'      => 1,
            'apu_id'         => 1,
            'is_rkap'        => 1,
        ]);

        Sales::create([
            'customer_id'    => 1,
            'prospect_id'    => 1,
            'ac_reg'         => 'PK-GFM',
            'value'          => 40,
            'maintenance_id' => 1,
            'tat'            => 1,
            'start_date'     => date('Y-m-d'),
            'end_date'       => date('Y-m-d'),
            'so_number'      => '0181284129y41212412412412',
            'hangar_id'      => 1,
            'product_id'     => 1,
            'ac_type_id'     => 1,
            'component_id'   => 1,
            'engine_id'      => 1,
            'apu_id'         => 1,
            'is_rkap'        => 0,
        ]);

        Sales::create([
            'customer_id'    => 1,
            'prospect_id'    => 1,
            'ac_reg'         => 'PK-GFM',
            'value'          => 33,
            'maintenance_id' => 1,
            'tat'            => 1,
            'start_date'     => date('Y-m-d'),
            'end_date'       => date('Y-m-d'),
            'so_number'      => '0181284129y41212412412412',
            'hangar_id'      => 1,
            'product_id'     => 1,
            'ac_type_id'     => 1,
            'component_id'   => 1,
            'engine_id'      => 1,
            'apu_id'         => 1,
            'is_rkap'        => 1,
        ]);
    }
}
