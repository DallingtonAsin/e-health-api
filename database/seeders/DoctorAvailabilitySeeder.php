<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalDoctor;
use App\Models\DoctorAvailability;
use Carbon\Carbon;


class DoctorAvailabilitySeeder extends Seeder
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

            $availability = [
                [
                    'doctor_id' => $doctor->id,
                    'date' => $this->generateRandomDate(),
                    'start_time' => '09:00:00',
                    'end_time' => '10:00:00',
                ],
                [
                    'doctor_id' => $doctor->id,
                    'date' => $this->generateRandomDate(),
                    'start_time' => '11:00:00',
                    'end_time' => '13:00:00',
                ],
                [
                    'doctor_id' => $doctor->id,
                    'date' => $this->generateRandomDate(),
                    'start_time' => '14:00:00',
                    'end_time' => '16:00:00',
                ],
                [
                    'doctor_id' => $doctor->id,
                    'date' => $this->generateRandomDate(),
                    'start_time' => '19:00:00',
                    'end_time' => '21:00:00',
                ],
            ];

            DoctorAvailability::insert($availability);
        }
    }


    private function generateRandomDate()
    {
        $start = Carbon::now();
        $end = Carbon::createFromDate(2023, date('n') , 30);
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = Carbon::createFromTimestamp($randomTimestamp);
        $formattedDate = $randomDate->toDateString();

        return $formattedDate;
    }
}
