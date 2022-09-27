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
            'year' => '2022',
            'target' => 5000
        ]);
        AMSTarget::create([
            'ams_id' => 2,
            'year' => '2020',
            'target' => 2500
        ]);
        AMSTarget::create([
            'ams_id' => 3,
            'year' => '2022',
            'target' => 10000
        ]);
        AMSTarget::create([
            'ams_id' => 1,
            'year' => '2020',
            'target' => 5000
        ]);
        AMSTarget::create([
            'ams_id' => 2,
            'year' => '2020',
            'target' => 7500
        ]);
        AMSTarget::create([
            'ams_id' => 3,
            'year' => '2021',
            'target' => 3000
        ]);
        AMSTarget::create([
            'ams_id' => 1,
            'year' => '2022',
            'target' => 2509
        ]);
        AMSTarget::create([
            'ams_id' => 2,
            'year' => '2020',
            'target' => 7500
        ]);
        AMSTarget::create([
            'ams_id' => 3,
            'year' => '2021',
            'target' => 10000
        ]);
    }
}
