<?php

namespace Database\Seeders;

use App\Models\ContactPerson as CP;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CP::create([
            'name' => 'Fulan bin Fulan',
            'phone' => '0923759732',
            'email' => 'fulan@example.com',
            'address' => 'Jalanin aja dulu No.1',
            'customer_id' => 1,
        ]);

        CP::create([
            'name' => 'John Doe',
            'phone' => '0123725732',
            'email' => 'doe@example.com',
            'address' => 'Jalanin aja dulu No.99',
            'customer_id' => 1,
        ]);
    }
}
