<?php

namespace Database\Seeders;

use App\Models\PBTH;
use App\Models\ProspectPBTH;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProspectPBTHSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = \App\Models\Product::all()->count();
        $ac_type = \App\Models\AircraftType::all()->count();
        $prospect = \App\Models\Prospect::all();

        foreach ($prospect as $item) {
            if ($item->transaction_type_id == 3){
                for ($i = 1; $i <= 12; $i++) {
                    $month = Carbon::create()->day(1)->month($i);

                    $pbth = PBTH::create([
                        'month' => $month->format('F'),
                        'rate' => rand(10, 90),
                        'flight_hour' => rand(1000, 100000),
                    ]);

                    ProspectPBTH::create([
                        'prospect_id' => $item->id,
                        'pbth_id' => $pbth->id,
                        'product_id' => rand(1, $product),
                        'ac_type_id' => rand(1, $ac_type),
                        'market_share' => rand(1000, 10000),
                    ]);
                }
            }
        }
    }
}
