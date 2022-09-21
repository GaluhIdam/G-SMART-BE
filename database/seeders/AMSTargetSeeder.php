<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AMSTarget;

class AMSTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AMSTarget::create([
            'ams_id' => 1,
            'year' => date("Y"),
            'target' => 9999
        ]);

        AMSTarget::create([
            'ams_id' => 2,
            'year' => date("Y"),
            'target' => 999
        ]);

        AMSTarget::create([
            'ams_id' => 3,
            'year' => date("Y"),
            'target' => 10000
        ]);
    }
}
