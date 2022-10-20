<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Fuselag',
            'description' => 'Badan pesawat terbang'
        ]);
        Product::create([
            'name' => 'Wing',
            'description' => 'Sayap pesawat terbang'
        ]);
        Product::create([
            'name' => 'Landing Gear',
            'description' => 'Roda pesawat terbang'
        ]);
        Product::create([
            'name' => 'Kokpit',
            'description' => 'Ruang kendali pilot'
        ]);
        Product::create([
            'name' => 'Tail',
            'description' => 'Ekor pesawat terbang'
        ]);
    }
}
