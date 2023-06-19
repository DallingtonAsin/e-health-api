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
            CompanyInformationSeeder::class,
            LanguageSeeder::class,
            UserTypeSeeder::class,
            AppointmentTypeSeeder::class,
            MedicalSpecialtySeeder::class,
            MedicalFacilitySeeder::class,
            PatientSeeder::class,
            MedicalDoctorSeeder::class,
            DoctorAvailabilitySeeder::class,
            DrugCategorySeeder::class,
            DrugSeeder::class,
            DoctorRatingSeeder::class,
            IcdCodeCategorySeeder::class,
            Icd10CodeSeeder::class,
            ImageTestCategorySeeder::class,
            LabTestCategorySeeder::class,
            MedicalAdministrationRouteSeeder::class,
        ]);
    }
}
