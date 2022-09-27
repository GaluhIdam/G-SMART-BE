<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProspectTMB;

class ProspectTMBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProspectTMB::create([
            'prospect_id' => 1,
            'tmb_id' => 6,
        ]);
        ProspectTMB::create([
            'prospect_id' => 2,
            'tmb_id' => 7,
        ]);
        ProspectTMB::create([
            'prospect_id' => 3,
            'tmb_id' => 8,
        ]);
        ProspectTMB::create([
            'prospect_id' => 4,
            'tmb_id' => 9,
        ]);
        ProspectTMB::create([
            'prospect_id' => 5,
            'tmb_id' => 10,
        ]);
        ProspectTMB::create([
            'prospect_id' => 10,
            'tmb_id' => 5,
        ]);
        ProspectTMB::create([
            'prospect_id' => 9,
            'tmb_id' => 4,
        ]);
        ProspectTMB::create([
            'prospect_id' => 8,
            'tmb_id' => 3,
        ]);
        ProspectTMB::create([
            'prospect_id' => 7,
            'tmb_id' => 2,
        ]);
        ProspectTMB::create([
            'prospect_id' => 6,
            'tmb_id' => 1,
        ]);
        ProspectTMB::create([
            'prospect_id' => 1,
            'tmb_id' => 3,
        ]);
        ProspectTMB::create([
            'prospect_id' => 2,
            'tmb_id' => 6,
        ]);
        ProspectTMB::create([
            'prospect_id' => 3,
            'tmb_id' => 9,
        ]);
        ProspectTMB::create([
            'prospect_id' => 4,
            'tmb_id' => 1,
        ]);
        ProspectTMB::create([
            'prospect_id' => 5,
            'tmb_id' => 4,
        ]);
        ProspectTMB::create([
            'prospect_id' => 6,
            'tmb_id' => 7,
        ]);
        ProspectTMB::create([
            'prospect_id' => 7,
            'tmb_id' => 10,
        ]);
        ProspectTMB::create([
            'prospect_id' => 8,
            'tmb_id' => 2,
        ]);
        ProspectTMB::create([
            'prospect_id' => 9,
            'tmb_id' => 5,
        ]);
        ProspectTMB::create([
            'prospect_id' => 10,
            'tmb_id' => 8,
        ]);
    }
}
