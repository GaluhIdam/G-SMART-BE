<?php

namespace Database\Seeders;

use App\Models\SalesRequirement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                SalesRequirement::create([
                    'sales_id' => $i,
                    'requirement_id' => $j,
                    'status' => ($j == 1 || $j == 4) ? 1 : 0,
                ]);
            }
        }
    }
}
