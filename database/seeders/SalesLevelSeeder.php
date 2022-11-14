<?php

namespace Database\Seeders;

use App\Models\SalesLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesplan = \App\Models\Sales::all();
        $levels = \App\Models\Level::all();

        foreach ($salesplan as $sales) {
            foreach ($levels as $level) {
                if ($sales->type == 'PBTH') {
                    $status = ($level->id == 1) ? 1 : 2;
                } else {
                    $status = 1;
                }

                SalesLevel::create([
                    'sales_id' => $sales->id,
                    'level_id' => $level->id,
                    'status' => $status,
                ]);
            }
        }
    }
}
