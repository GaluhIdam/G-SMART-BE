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
        ]);
        Customer::create([
            'name'       => 'Singapore Arilines',
            'code'       => 'SG007',
            'country_id' => 3,
        ]);
        Customer::create([
            'name'       => 'Air Asia',
            'code'       => 'AA006',
            'country_id' => 2,
        ]);
        Customer::create([
            'name'       => 'Qatar Airways',
            'code'       => 'QT005',
            'country_id' => 5,
        ]);
        Customer::create([
            'name'       => 'Nippon Air',
            'code'       => 'NP021',
            'country_id' => 4,
        ]);
        Customer::create([
            'name'       => 'Indian Airways',
            'code'       => 'ID072',
            'country_id' => 6,
        ]);
        Customer::create([
            'name'       => 'Citilink Indonesia',
            'code'       => 'CT010',
            'country_id' => 1,
        ]);
        Customer::create([
            'name'       => 'Eva Air',
            'code'       => 'EV004',
            'country_id' => 7,
        ]);
        Customer::create([
            'name'       => 'Batik Air',
            'code'       => 'BT022',
            'country_id' => 1,
        ]);
        Customer::create([
            'name'       => 'American Airways',
            'code'       => 'AA001',
            'country_id' => 8,
        ]);
    }
}
