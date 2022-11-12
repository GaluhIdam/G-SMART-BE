<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProspectTMB;
Use App\Models\TMB;

class ProspectTMBSeeder extends Seeder
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
        $component = \App\Models\Component::all()->count();
        $engine = \App\Models\Engine::all()->count();
        $apu = \App\Models\Apu::all()->count();
        $maintenance = \App\Models\Maintenance::all()->count();
        $prospect = \App\Models\Prospect::all();

        foreach ($prospect as $item) {
            if (in_array($item->transaction_type_id, [1,2])){
                $tmb = TMB::create([
                    'product_id' => rand(1, $product),
                    'ac_type_id' => rand(1, $ac_type),
                    'component_id' => rand(1, $component),
                    'engine_id' => rand(1, $engine),
                    'apu_id' => rand(1, $apu),
                    'maintenance_id' => rand(1, $maintenance),
                    'market_share' => rand(1000, 10000),
                    'remarks' => 'This is remarks',
                ]);

                ProspectTMB::create([
                    'prospect_id' => $item->id,
                    'tmb_id' => $tmb->id,
                ]);
            }
        }
    }
}
