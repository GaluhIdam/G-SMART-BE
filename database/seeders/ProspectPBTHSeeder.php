<?php

namespace Database\Seeders;

use App\Models\PBTH;
use App\Models\ProspectPBTH;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProspectPBTHSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $market_share = [2351,1242,9832,2355,6782,5321,3522,8971,1244,8967,1241,8724];

        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 1,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 2,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 3,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 4,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 5,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 6,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 7,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 8,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 9,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 10,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 11,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 2,
            'pbth_id'           => 12,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 13,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 14,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 15,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 16,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 17,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 18,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 19,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 20,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 21,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 22,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 23,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 4,
            'pbth_id'           => 24,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 25,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 26,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 27,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 28,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 29,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 30,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 31,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 32,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 33,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 34,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 35,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 6,
            'pbth_id'           => 36,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 37,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 38,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 39,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 40,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 41,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 42,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 43,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 44,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 45,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 46,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 47,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 8,
            'pbth_id'           => 48,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 49,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 50,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 51,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 52,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 53,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 54,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 55,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 56,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 57,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 58,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 59,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 10,
            'pbth_id'           => 60,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 61,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 62,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 63,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 64,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 65,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 66,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 67,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 68,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 69,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 70,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 71,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 12,
            'pbth_id'           => 72,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 73,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 74,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 75,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 76,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 77,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 78,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 79,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 80,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 81,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 82,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 83,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 14,
            'pbth_id'           => 84,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 85,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 86,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 87,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 88,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 89,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 90,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 91,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 92,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 93,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 94,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 95,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 16,
            'pbth_id'           => 96,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);
        ProspectPBTH::create([
            'prospect_id'       => 17,
            'pbth_id'           => 97,
            'product_id'        => 1,
            'ac_type_id'        => 1,
            'market_share'      => $market_share[rand(0,11)],
        ]);

    }
}
