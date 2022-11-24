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
        $prospects = Prospect::all();
        $maintenances = Maintenance::all()->count();

        foreach ($prospects as $prospect) {
            if ($prospect->pbth) {
                $month = Carbon::parse("1 {$prospect->pbth->month}")->month;
                $date = $prospect->years.'-'.$month.'-1';
                $tat = 30;
                $value = $prospect->pbth->market_share;
                $product = $prospect->pbth->product_id;
                $ac_type = $prospect->pbth->ac_type_id;
                $component = null;
                $engine = null;
                $apu = null;
                $maintenance = rand(1, $maintenances);
            } else {
                $date = $prospect->years.'-'.rand(1, 12).'-'.rand(1,30);
                $tat = rand(15, 45);
                $value = $prospect->tmb->market_share;
                $product = $prospect->tmb->product_id;
                $ac_type = $prospect->tmb->ac_type_id ?? null;
                $component = $prospect->tmb->component_id ?? null;
                $engine = $prospect->tmb->engine_id ?? null;
                $apu = $prospect->tmb->apu_id ?? null;
                $maintenance = $prospect->tmb->maintenance_id;
            }
            $start_date = Carbon::parse($date)->format('Y-m-d');
            $end_date = Carbon::parse($date)->addDays($tat)->format('Y-m-d');

            Sales::create([
                'customer_id' => $prospect->amsCustomer->customer_id,
                'prospect_id' => $prospect->id,
                'ac_reg' => 'PK-'.Str::upper(Str::random(3)), // nullable
                'value' => $value,
                'maintenance_id' => $maintenance, // nullable
                'tat' => $tat,
                'start_date' => $start_date,
                'end_date' => $end_date, // nullable
                'so_number' => null, // nullable
                'hangar_id' => null, // nullable
                'product_id' => $product, // nullable
                'ac_type_id' => $ac_type, // nullable
                'component_id' => $component, // nullable
                'engine_id' => $engine, // nullable
                'apu_id' => $apu, // nullable
                'is_rkap' => 1, // nullable
                'ams_id' => $prospect->amsCustomer->ams_id, // nullable
                'line_id' => null, // nullable
            ]);
        }
    }
}
