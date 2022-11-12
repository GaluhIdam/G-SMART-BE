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
        $sales = \App\Models\Sales::all();

        foreach ($sales as $item) {
            SalesLevel::create([
                'sales_id' => $item->id,
                'level_id' => 1,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => $item->id,
                'level_id' => 2,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => $item->id,
                'level_id' => 3,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => $item->id,
                'level_id' => 4,
                'status' => 1,
            ]);
        }
    }
}
