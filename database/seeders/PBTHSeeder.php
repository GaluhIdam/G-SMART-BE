<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PBTH;

class PBTHSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PBTH::create([
            'month' => 'January',
            'rate' => 100,
            'flight_hour' => 10000,
        ]);
        PBTH::create([
            'month' => 'February',
            'rate' => 80,
            'flight_hour' => 5000,
        ]);
        PBTH::create([
            'month' => 'March',
            'rate' => 50,
            'flight_hour' => 10000,
        ]);
        PBTH::create([
            'month' => 'April',
            'rate' => 70,
            'flight_hour' => 10000,
        ]);
        PBTH::create([
            'month' => 'May',
            'rate' => 100,
            'flight_hour' => 6000,
        ]);
        PBTH::create([
            'month' => 'June',
            'rate' => 50,
            'flight_hour' => 75000,
        ]);
        PBTH::create([
            'month' => 'July',
            'rate' => 40,
            'flight_hour' => 8000,
        ]);
        PBTH::create([
            'month' => 'August',
            'rate' => 100,
            'flight_hour' => 10000,
        ]);
        PBTH::create([
            'month' => 'September',
            'rate' => 100,
            'flight_hour' => 5500,
        ]);
        PBTH::create([
            'month' => 'October',
            'rate' => 80,
            'flight_hour' => 7000,
        ]);
        PBTH::create([
            'month' => 'November',
            'rate' => 100,
            'flight_hour' => 10000,
        ]);
        PBTH::create([
            'month' => 'December',
            'rate' => 70,
            'flight_hour' => 5000,
        ]);
    }
}
