<?php

namespace Database\Seeders;

use App\Models\Requirement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Requirement::create([
            'level_id'       => 1,
            'requirement' => 'This is requirement',
        ]);
    }
}
