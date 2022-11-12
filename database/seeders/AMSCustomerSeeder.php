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
        $areas = Area::pluck('id');

        foreach ($customer as $item) {
            $area = collect($areas)->shuffle()->toArray();
            $total = rand(1, 2);

            for ($i = 0; $i < $total; $i++) {
                AMSCustomer::create([
                    'customer_id' => $item->id,
                    'area_id' => $area[$i],
                    'ams_id' => rand(1, $ams),
                ]);
            }
        }
    }
}
