<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AMSCustomer;
use App\Models\AMS;
use App\Models\Customer;
use App\Models\Area;

class AMSCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::all();
        $ams = AMS::all()->count();
        $area = Area::all()->count();

        foreach ($customer as $item) {
            AMSCustomer::create([
                'customer_id' => $item->id,
                'area_id' => rand(1, $area),
                'ams_id' => rand(1, $ams),
            ]);
        }
    }
}
