<?php

namespace Database\Seeders;

use App\Models\Prospect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProspectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prospect::create([
            'year'                    => 2022,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 1,
            'pm_id'                   => 1,
            'ams_customer_id'         => 1,
        ]);
        Prospect::create([
            'year'                    => 2021,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 1,
            'pm_id'                   => 1,
            'ams_customer_id'         => 1,
        ]);
    }
}
