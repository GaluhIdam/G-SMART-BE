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
        SalesLevel::create([
            'sales_id'  => 1,
            'level_id'  => 1,
            'status' => 1,
        ]);

        SalesLevel::create([
            'sales_id'  => 2,
            'level_id'  => 2,
            'status' => 2,
        ]);

        SalesLevel::create([
            'sales_id'  => 3,
            'level_id'  => 3,
            'status' => 3,
        ]);

        SalesLevel::create([
            'sales_id'  => 4,
            'level_id'  => 4,
            'status' => 4,
        ]);
    }
}
