<?php

namespace Database\Seeders;

use App\Models\AMS;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AMS::create([
            'user_id' => 5,
            'initial' => 'ZMI',
        ]);
        AMS::create([
            'user_id' => 6,
            'initial' => 'RJTI',
        ]);
        AMS::create([
            'user_id' => 7,
            'initial' => 'UJTI',
        ]);

        // $csv_file = fopen(base_path("database/data/ams.csv"), "r");

        // $first_line = true;
        // while (($data = fgetcsv($csv_file, 2000, ",")) !== FALSE) {
        //     if (!$first_line) {
        //         AMS::create(['initial' => $data['0']]);
        //     }
        //     $first_line = false;
        // }
        // fclose($csv_file);
    }
}
