<?php

namespace Database\Seeders;

use App\Models\TransactionTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionTypes::create([
            'name'        => 'TMB',
            'description' => 'Time Material Based',
        ]);
        TransactionTypes::create([
            'name'        => 'PBTH',
            'description' => 'Power By The Hours',
        ]);
    }
}
