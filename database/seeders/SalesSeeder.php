<?php

namespace Database\Seeders;

use App\Models\Sales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            'customer_id' => rand(1,10),
            'prospect_id' => 1,
            'ac_reg' => 'PK-GFM',
            'value' => 2500,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2021-01-19')->format('Y-m-d'),
            'end_date' => Carbon::parse('2021-03-27')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1, 10),
            'prospect_id' => 2,
            'ac_reg' => 'PK-CKL',
            'value' => 5000,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2022-05-02')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-06-12')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 3,
            'ac_reg' => 'PK-GPX',
            'value' => 3000,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2021-03-25')->format('Y-m-d'),
            'end_date' => Carbon::parse('2021-04-17')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 4,
            'ac_reg' => 'PK-NAN',
            'value' => 6200,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2021-12-25')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-01-08')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 5,
            'ac_reg' => 'PK-TES',
            'value' => 4800,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2022-07-20')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-07-30')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 6,
            'ac_reg' => 'PK-GRQ',
            'value' => 2500,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2022-08-27')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-09-15')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 7,
            'ac_reg' => 'PK-GGG',
            'value' => 5200,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2021-11-19')->format('Y-m-d'),
            'end_date' => Carbon::parse('2021-11-27')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 8,
            'ac_reg' => 'PK-GEM',
            'value' => 3200,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2021-11-10')->format('Y-m-d'),
            'end_date' => Carbon::parse('2021-11-16')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 9,
            'ac_reg' => 'PK-GAM',
            'value' => 1800,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2022-03-25')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-04-07')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);

        Sales::create([
            'customer_id' => rand(1,10),
            'prospect_id' => 10,
            'ac_reg' => 'PK-CMF',
            'value' => 2600,
            'maintenance_id' => rand(1,5),
            'tat' => rand(10,30),
            'start_date' => Carbon::parse('2022-10-14')->format('Y-m-d'),
            'end_date' => Carbon::parse('2022-10-27')->format('Y-m-d'),
            'so_number' => strval(random_int(1000000, 9999999)),
            'hangar_id' => rand(1,4),
            'product_id' => rand(1,5),
            'ac_type_id' => rand(1,8),
            'component_id' => rand(1,6),
            'engine_id' => rand(1,5),
            'apu_id' => rand(1,5),
            'is_rkap' => rand(0,1),
            'ams_id' => rand(1,3),
        ]);
    }
}
