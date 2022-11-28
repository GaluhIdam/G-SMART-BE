<?php

namespace Database\Seeders;

use App\Models\AMS;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv_file = fopen(base_path("database/data/ams.csv"), "r");

        $first_line = true;
        while (($data = fgetcsv($csv_file, 2000, ",")) !== FALSE) {
            if (!$first_line) {
                try {
                    DB::beginTransaction();
                    $name = $data['0'];
                    $username = Str::lower($name);

                    $user = User::create([
                        'name'              => $name,
                        'username'          => $username,
                        'nopeg'             => rand(100000,999999),
                        'unit'              => 'TP',
                        'role_id'           => 5,
                        'email'             => "user.{$username}@gmf-aeroasia.co.id",
                        'password'          => Hash::make('password'),
                        'email_verified_at' => Carbon::now(),
                    ]);
                    $user->assignRole('AMS');

                    AMS::create([
                        'user_id' => $user->id,
                        'initial' => $data['1']
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
            $first_line = false;
        }
        fclose($csv_file);
    }
}
