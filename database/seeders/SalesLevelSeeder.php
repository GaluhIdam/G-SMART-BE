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
        for ($i = 1; $i <= 10; $i++) {
            SalesLevel::create([
                'sales_id' => 1,
                'level_id' => $i,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => 2,
                'level_id' => $i,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => 3,
                'level_id' => $i,
                'status' => 1,
            ]);
            SalesLevel::create([
                'sales_id' => 4,
                'level_id' => $i,
                'status' => 1,
            ]);
        }
    }
}
