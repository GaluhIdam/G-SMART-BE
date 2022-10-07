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
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'John Doe',
            'phone' => '0123725732',
            'email' => 'doe@example.com',
            'address' => 'Jalanin aja dulu No.99',
            'customer_id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Muhammad Sumbul',
            'phone' => '09135235',
            'email' => 'sumbul@example.com',
            'address' => 'Jalanin aja dulu No.65',
            'customer_id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Jane Doe',
            'phone' => '021255432',
            'email' => 'jane@example.com',
            'address' => 'Jalanin aja dulu No.19',
            'customer_id' => 2,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Farhan Hadi Lutfi',
            'phone' => '021875983',
            'email' => 'farhan@example.com',
            'address' => 'Jalanin aja dulu No.81',
            'customer_id' => 2,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Rizky Ibrahim',
            'phone' => '0932732232',
            'email' => 'rizky@example.com',
            'address' => 'Jalanin aja dulu No.42',
            'customer_id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Surya Intan Permana',
            'phone' => '023586824',
            'email' => 'surya@example.com',
            'address' => 'Jalanin aja dulu No.68',
            'customer_id' => 3,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Okitora Winnetou',
            'phone' => '0123725732',
            'email' => 'tora@example.com',
            'address' => 'Jalanin aja dulu No.91',
            'customer_id' => 3,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Galuh Idam',
            'phone' => '0092353253',
            'email' => 'idaman@example.com',
            'address' => 'Jalanin aja dulu No.7',
            'customer_id' => 4,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Dawney Johnson',
            'phone' => '084932943',
            'email' => 'johnson@example.com',
            'address' => 'Jalanin aja dulu No.35',
            'customer_id' => 4,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Scarlet Johansson',
            'phone' => '032553234',
            'email' => 'scarlet@example.com',
            'address' => 'Jalanin aja dulu No.80',
            'customer_id' => 5,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Naruto Uzumaki',
            'phone' => '032532553',
            'email' => 'nartoo@example.com',
            'address' => 'Jalanin aja dulu No.76',
            'customer_id' => 5,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Uchiha Sasuke',
            'phone' => '029217424',
            'email' => 'saskee@example.com',
            'address' => 'Jalanin aja dulu No.12',
            'customer_id' => 6,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Monkey D Luffy',
            'phone' => '03532532',
            'email' => 'luffy@example.com',
            'address' => 'Jalanin aja dulu No.62',
            'customer_id' => 7,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Pevita Pearce',
            'phone' => '087365389',
            'email' => 'pevpearce@example.com',
            'address' => 'Jalanin aja dulu No.3',
            'customer_id' => 8,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Chelsea Islan',
            'phone' => '02365235',
            'email' => 'chislan@example.com',
            'address' => 'Jalanin aja dulu No.26',
            'customer_id' => 9,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Jalaludin Rumi',
            'phone' => '043643245',
            'email' => 'rumi@example.com',
            'address' => 'Jalanin aja dulu No.95',
            'customer_id' => 10,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
        CP::create([
            'name' => 'Nur Cahaya',
            'phone' => '012758324',
            'email' => 'nurajah@example.com',
            'address' => 'Jalanin aja dulu No.72',
            'customer_id' => 10,
            'title' => 'Lorem ipsum dolor sit amet',
        ]);
    }
}
