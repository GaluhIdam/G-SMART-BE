<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name'       => 'Garuda Indonesia',
            'code'       => 'GA001',
            'country_id' => 1,
            'logo_path'  => 'logo/garuda.png',
        ]);
        Customer::create([
            'name'       => 'Singapore Arilines',
            'code'       => 'SG007',
            'country_id' => 3,
            'logo_path'  => 'logo/singapore_air.png',
        ]);
        Customer::create([
            'name'       => 'Air Asia',
            'code'       => 'AA006',
            'country_id' => 2,
            'logo_path'  => 'logo/air_asia.png',
        ]);
        Customer::create([
            'name'       => 'Qatar Airways',
            'code'       => 'QT005',
            'country_id' => 5,
            'logo_path'  => 'logo/qatar_air.png',
        ]);
        Customer::create([
            'name'       => 'Nippon Air',
            'code'       => 'NP021',
            'country_id' => 4,
            'logo_path'  => 'logo/nippon_air.png',
        ]);
        Customer::create([
            'name'       => 'Indian Airways',
            'code'       => 'ID072',
            'country_id' => 6,
            'logo_path'  => 'logo/indian_air.png',
        ]);
        Customer::create([
            'name'       => 'Citilink Indonesia',
            'code'       => 'CT010',
            'country_id' => 1,
            'logo_path'  => 'logo/citilink.png',
        ]);
        Customer::create([
            'name'       => 'Eva Air',
            'code'       => 'EV004',
            'country_id' => 7,
            'logo_path'  => 'logo/eva_air.png',
        ]);
        Customer::create([
            'name'       => 'Batik Air',
            'code'       => 'BT022',
            'country_id' => 1,
            'logo_path'  => 'logo/batik_air.png',
        ]);
        Customer::create([
            'name'       => 'American Airways',
            'code'       => 'AA001',
            'country_id' => 8,
            'logo_path'  => 'logo/american_air.png',
        ]);
    }
}
