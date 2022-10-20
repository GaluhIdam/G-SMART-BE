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
            'strategic_initiative_id' => 3,
            'pm_id'                   => 1,
            'ams_customer_id'         => 8,
        ]);
        Prospect::create([
            'year'                    => 2021,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 2,
            'strategic_initiative_id' => 1,
            'pm_id'                   => 2,
            'ams_customer_id'         => 3,
        ]);
        Prospect::create([
            'year'                    => 2022,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 2,
            'strategic_initiative_id' => 3,
            'pm_id'                   => 3,
            'ams_customer_id'         => 5,
        ]);
        Prospect::create([
            'year'                    => 2020,
            'transaction_type_id'     => 2,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 2,
            'pm_id'                   => 3,
            'ams_customer_id'         => 1,
        ]);
        Prospect::create([
            'year'                    => 2021,
            'transaction_type_id'     => 2,
            'prospect_type_id'        => 2,
            'strategic_initiative_id' => 1,
            'pm_id'                   => 2,
            'ams_customer_id'         => 2,
        ]);
        Prospect::create([
            'year'                    => 2020,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 2,
            'pm_id'                   => 3,
            'ams_customer_id'         => 6,
        ]);
        Prospect::create([
            'year'                    => 2022,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 3,
            'pm_id'                   => 1,
            'ams_customer_id'         => 7,
        ]);
        Prospect::create([
            'year'                    => 2022,
            'transaction_type_id'     => 2,
            'prospect_type_id'        => 1,
            'strategic_initiative_id' => 2,
            'pm_id'                   => 2,
            'ams_customer_id'         => 4,
        ]);
        Prospect::create([
            'year'                    => 2021,
            'transaction_type_id'     => 2,
            'prospect_type_id'        => 2,
            'strategic_initiative_id' => 1,
            'pm_id'                   => 1,
            'ams_customer_id'         => 3,
        ]);
        Prospect::create([
            'year'                    => 2020,
            'transaction_type_id'     => 1,
            'prospect_type_id'        => 2,
            'strategic_initiative_id' => 2,
            'pm_id'                   => 3,
            'ams_customer_id'         => 2,
        ]);
    }
}
