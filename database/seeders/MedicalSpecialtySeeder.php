<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalSpecialty;

class MedicalSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalSpecialty::create([
            "name" => "Allergy and immunology",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Surgery",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Pediatrics",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Child Specialist",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Ear Nose Throat",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Clinic Nutrietion",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Eye Specialist",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Neurology",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Obstetrics and gynecology",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Radiation oncology",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Dermatology",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Medical genetics",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Emergency medicine",
            "recorded_by" => "Dr.Grace",
        ]);

        MedicalSpecialty::create([
            "name" => "Diagnostic radiology",
            "recorded_by" => "Dr.Grace",
        ]);
    }
}
