<?php

namespace App\Repositories;

use App\Models\MedicalDoctor;
use Carbon\Carbon;

class MedicalDoctorRepository
{
    protected $medicalDoctor;

    public function __construct(MedicalDoctor $medicalDoctor)
    {
        $this->medicalDoctor = $medicalDoctor;
    }

    public function create($medicalDoctorData)
    {
        return $this->medicalDoctor->create($medicalDoctorData);
    }

    public function get($id = null, $specialty = null)
    {

        $today = Carbon::today();
        $doctors = $this->medicalDoctor->with(['availability' => function ($query) use ($today) {
            $query->where('date', '>=', $today)->orderBy('date', 'asc');
        }]);

        if ($id) {
            $doctors = $doctors->where('id', '=', $id);
        }
        if ($specialty) {
            $doctors = $doctors->where('specialty_id', '=', $specialty);
        }

        $doctors = $doctors->get();
        $doctors->makeHidden(['created_at', 'updated_at']);

        foreach ($doctors as $doctor) {
            $doctor['languages'] = implode(", ", unserialize(($doctor->languages)));
            $doctor['service_fee'] = config('app.currency') . '. ' . number_format($doctor->service_fee);
            $doctor->availability->makeHidden(['id', 'created_at', 'updated_at']);

            $timeSlots = [];

            $timeSlots = [];
            $dates = [];

            foreach ($doctor->availability as $slot) {
                $date = $slot->date;
                array_push($dates, $date);
                $startTime = strtotime($slot->start_time);
                $endTime = strtotime($slot->end_time);

                $time = $startTime;
                while ($time <= $endTime) {
                    $timeSlots[$date][] = date('H:i', $time);
                    $time += 60 * 60; 
                }
            }

            unset($doctor->availability);

            $doctor->schedule = $dates;
            $doctor->availability = $timeSlots;
        }

        return $doctors;
    }

    public function update($id, $medicalDoctorData)
    {
        $medicalDoctor = $this->medicalDoctor->find($id);
        $medicalDoctor->update($medicalDoctorData);
        return $medicalDoctor;
    }

    public function delete($id)
    {
        $medicalDoctor = $this->medicalDoctor->find($id);
        $medicalDoctor->delete();
        return $medicalDoctor;
    }

    public function exists($id)
    {
        $medicalDoctor = $this->medicalDoctor->where('id', $id)->exists();
        return $medicalDoctor;
    }

    private function sortTimes($times)
    {
        usort($times, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return $times;
    }
}
