<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTypeSeeder::class,
            AppointmentTypeSeeder::class,
            MedicalSpecialtySeeder::class,
            DoctorAuthenticationSeeder::class,
            MedicalDoctorSeeder::class,
            DoctorAvailabilitySeeder::class,
        ]);
    }
}
