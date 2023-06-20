<?php

namespace Database\Seeders;

use App\Models\DoctorRating;
use Illuminate\Database\Seeder;
use App\Models\MedicalDoctor;
use App\Models\Patient;
use Carbon\Carbon;

class DoctorRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors = MedicalDoctor::get();
        foreach ($doctors as $doctor) {

            $rating = [
                [
                    'doctor_id' => $doctor->id,
                    'patient_id' => Patient::inRandomOrder()->first()->id,
                    'rating' => random_int(1, 5),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ];

            DoctorRating::insert($rating);
        }
    }
}