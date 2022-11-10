<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
            DATASHEET PRODUCT DARI APLIKASI SEEDER
            - Learning
            - IGTE
            - Others
            - Engine & APU
            - Material Trading & Logistic
            - Line
            - Engineering
            - Component
            - Airframe
        */

        Product::create(['name' => 'Learning']);
        Product::create(['name' => 'IGTE']);
        Product::create(['name' => 'Others']);
        Product::create(['name' => 'Engine & APU']);
        Product::create(['name' => 'Material Trading & Logistic']);
        Product::create(['name' => 'Line']);
        Product::create(['name' => 'Engineering']);
        Product::create(['name' => 'Component']);
        Product::create(['name' => 'Airframe']);
    }
}
