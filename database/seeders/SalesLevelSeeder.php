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
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 2,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 3,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 4,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 5,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 6,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 7,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 8,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 9,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 10,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 1,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 2,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 3,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 4,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 5,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 6,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 7,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 8,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 9,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
        SalesLevel::create([
            'sales_id'  => 10,
            'level_id'  => rand(1,4),
            'status' => rand(1,4),
        ]);
    }
}
