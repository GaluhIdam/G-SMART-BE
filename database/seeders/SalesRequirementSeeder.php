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
        $sales = \App\Models\Sales::all();
        $requirements = \App\Models\Requirement::all();

        foreach ($sales as $item) {
            foreach ($requirements as $requirement) {
                SalesRequirement::create([
                    'sales_id' => $item->id,
                    'requirement_id' => $requirement->id,
                    'status' => in_array($requirement->id, [1,4]) ? 1 : 0,
                ]);
            }
        }
    }
}
