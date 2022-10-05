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
                'ac_reg' => $acregs[rand(0,7)],
                'value' => rand(2500,7500),
                'maintenance_id' => rand(1,5),
                'tat' => $tat,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'so_number' => null,
                'hangar_id' => rand(1,4),
                'product_id' => rand(1,5),
                'ac_type_id' => rand(1,8),
                'component_id' => rand(1,6),
                'engine_id' => rand(1,5),
                'apu_id' => rand(1,5),
                'is_rkap' => rand(0,1),
                'ams_id' => rand(1,3),
                'line_id' => null,
            ]);
        }
    }
}
