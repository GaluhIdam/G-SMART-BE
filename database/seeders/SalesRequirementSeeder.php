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
        SalesRequirement::create([
            'sales_id'       => 1,
            'requirement_id' => 1,
            'status'         => 1,
        ]);
    }
}
