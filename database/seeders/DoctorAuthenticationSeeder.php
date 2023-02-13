<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorAuthentication;


class DoctorAuthenticationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DoctorAuthentication::create([
            'country_code' => '+256',
            'phone_number' => '700477421',
            'auth_code' => '7878',
        ]);
        DoctorAuthentication::create([
            'country_code' => '+256',
            'phone_number' => '786857180',
            'auth_code' => '5050',
        ]);

        DoctorAuthentication::create([
            'country_code' => '+256',
            'phone_number' => '772833275',
            'auth_code' => '1010',
        ]);
    }
}
