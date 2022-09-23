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
            'area_id' => 1,
            'ams_id' => 1,
        ]);
    }
}
