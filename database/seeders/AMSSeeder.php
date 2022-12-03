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
                    $initial = $data['1'];
                    $nopeg = $data['2'];
                    $email = $data['3'];
                    $unit = $data['4'];

                    $user = User::create([
                        'name'              => $name,
                        'username'          => $nopeg,
                        'nopeg'             => $nopeg,
                        'unit'              => $unit,
                        'role_id'           => 5,
                        'email'             => $email,
                        'password'          => null,
                        'email_verified_at' => null,
                    ]);
                    $user->assignRole('AMS');

                    AMS::create([
                        'user_id' => $user->id,
                        'initial' => $initial,
                    ]);

                    \Artisan::call("ldap:import", [
                        'provider' => 'users',
                        'user' => $user->nopeg,
                        '--no-interaction',
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    dd($e);
                }
            }
            $first_line = false;
        }
        fclose($csv_file);
    }
}
