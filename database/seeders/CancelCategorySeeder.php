<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CancelCategory as Category;

class CancelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        /*  
            DATASHEET CANCEL CATEGORY DARI APLIKASI USER
            - Internal Customer Issue
            - Customer Financial Issue
            - Missed Plan
            - Reschedule
            - Capacity Capability Issue
            - Pricing Issue
            - BP Fee
            - Unidentified Reason
        */

        Category::create(['name' => 'Internal Customer Issue']);
        Category::create(['name' => 'Customer Financial Issue']);
        Category::create(['name' => 'Missed Plan']);
        Category::create(['name' => 'Reschedule']);
        Category::create(['name' => 'Capacity Capability Issue']);
        Category::create(['name' => 'Pricing Issue']);
        Category::create(['name' => 'BP Fee']);
        Category::create(['name' => 'Unidentified Reason']);
    }
}
