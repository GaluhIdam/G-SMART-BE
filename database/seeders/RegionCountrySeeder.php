<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Countries as Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            Region::create(['name' => 'Africa']),
            Region::create(['name' => 'Asia']),
            Region::create(['name' => 'Europe']),
            Region::create(['name' => 'North America']),
            Region::create(['name' => 'Oceania']),
            Region::create(['name' => 'South America']),
            Region::create(['name' => 'Domestic']),
        ];

        $csv_file = fopen(base_path("database/data/countries.csv"), "r");

        $first_line = true;
        while (($data = fgetcsv($csv_file, 2000, ",")) !== FALSE) {
            if (!$first_line) {
                $name = $data['0'];
                $region = $data['1'];

                foreach ($regions as $item) {
                    if ($region == $item->name) {
                        $region_id = $item->id;
                        break;
                    }
                }

                Country::create([
                    "name" => $name,
                    "region_id" => $region_id,
                ]);    
            }
            $first_line = false;
        }
        fclose($csv_file);
    }
}
