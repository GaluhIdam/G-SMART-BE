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
        for ($i = 1; $i <= 10; $i++) {
            $acregs = ['PK-GFM', 'PK-CKL', 'PK-GAM', 'PK-GCA', 'PK-ZTE', 'PK-MAN', 'PK-NAN', 'PK-RRQ'];
            $years = ['2020', '2021', '2022'];
            $date = $years[rand(0,2)].'-'.rand(1,12).'-'.rand(1,30);
            $start_date = Carbon::parse($date)->format('Y-m-d');
            $tat = rand(10, 50);
            $end_date = Carbon::parse($date)->addDays($tat)->format('Y-m-d');

            Sales::create([
                'customer_id' => $i,
                'prospect_id' => $i,
                'ac_reg' => $acregs[rand(0,7)], // nullable
                'value' => rand(2500,7500),
                'maintenance_id' => rand(1,5), // nullable
                'tat' => $tat,
                'start_date' => $start_date,
                'end_date' => $end_date, // nullable
                'so_number' => null, // nullable
                'hangar_id' => rand(1,4), // nullable
                'product_id' => rand(1,5), // nullable
                'ac_type_id' => rand(1,8), // nullable
                'component_id' => rand(1,6), // nullable
                'engine_id' => rand(1,5), // nullable
                'apu_id' => rand(1,5), // nullable
                'is_rkap' => rand(0,1), // nullable
                'ams_id' => rand(1,3), // nullable
                'line_id' => null, // nullable
            ]);
        }
    }
}
