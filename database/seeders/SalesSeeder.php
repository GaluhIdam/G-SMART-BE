<?php

namespace Database\Seeders;

use App\Models\Sales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Maintenance;
use App\Models\Hangar;
use App\Models\Product;
use App\Models\AircraftType;
use App\Models\Component;
use App\Models\Engine;
use App\Models\Apu;
use App\Models\AMS;
use App\Models\Prospect;
use Illuminate\Support\Str;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prospect = Prospect::all();
        $maintenance = Maintenance::all()->count();
        $hangar = Hangar::all()->count();
        $product = Product::all()->count();
        $ac_type = AircraftType::all()->count();
        $component = Component::all()->count();
        $engine = Engine::all()->count();
        $apu = Apu::all()->count();
        $ams = AMS::all()->count();

        $years = ['2019', '2020', '2021', '2022', '2023'];

        foreach ($prospect as $item) {
            $years = collect($years)->shuffle()->toArray();
            $total = rand(1,5);
            
            for ($i = 0; $i < $total); $i++) {
                $date = $years[$i].'-'.rand(1,12).'-'.rand(1,30);
                $start_date = Carbon::parse($date)->format('Y-m-d');
                $tat = rand(10, 50);
                $end_date = Carbon::parse($date)->addDays($tat)->format('Y-m-d');

                Sales::create([
                    'customer_id' => $item->amsCustomer->customer_id,
                    'prospect_id' => $item->id,
                    'ac_reg' => 'PK-'.Str::upper(Str::random(3)), // nullable
                    'value' => rand(1000, 10000),
                    'maintenance_id' => rand(1, $maintenance), // nullable
                    'tat' => $tat,
                    'start_date' => $start_date,
                    'end_date' => $end_date, // nullable
                    'so_number' => null, // nullable
                    'hangar_id' => rand(1, $hangar), // nullable
                    'product_id' => rand(1, $product), // nullable
                    'ac_type_id' => rand(1, $ac_type), // nullable
                    'component_id' => rand(1, $component), // nullable
                    'engine_id' => rand(1, $engine), // nullable
                    'apu_id' => rand(1, $apu), // nullable
                    'is_rkap' => rand(0, 1), // nullable
                    'ams_id' => rand(1, $ams), // nullable
                    'line_id' => null, // nullable
                ]);
            }
        }
    }
}
