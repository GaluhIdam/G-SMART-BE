<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AMSCustomer;

class AMSCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AMSCustomer::create([
            'customer_id' => 1,
            'area_id' => 4,
            'ams_id' => 1,
        ]);
        AMSCustomer::create([
            'customer_id' => 2,
            'area_id' => 3,
            'ams_id' => 2,
        ]);
        AMSCustomer::create([
            'customer_id' => 3,
            'area_id' => 2,
            'ams_id' => 3,
        ]);
        AMSCustomer::create([
            'customer_id' => 4,
            'area_id' => 1,
            'ams_id' => 1,
        ]);
        AMSCustomer::create([
            'customer_id' => 5,
            'area_id' => 3,
            'ams_id' => 2,
        ]);
        AMSCustomer::create([
            'customer_id' => 6,
            'area_id' => 5,
            'ams_id' => 3,
        ]);
        AMSCustomer::create([
            'customer_id' => 7,
            'area_id' => 4,
            'ams_id' => 1,
        ]);
        AMSCustomer::create([
            'customer_id' => 8,
            'area_id' => 6,
            'ams_id' => 2,
        ]);
    }
}
