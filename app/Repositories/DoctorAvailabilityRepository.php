<?php

namespace App\Repositories;

use App\Models\DoctorAvailability;

class DoctorAvailabilityRepository
{
    protected $availability;

    public function __construct(DoctorAvailability $availability)
    {
        $this->availability = $availability;
    }

    public function create($availabilityData)
    {
        return $this->availability->create($availabilityData);
    }

    public function updateOrCreateSchedule($criteria, $scheduleData)
    {
        return $this->availability->updateOrCreate($criteria, $scheduleData);
    }

    public function get($id = null, $doctor_id)
    {

        if ($id) {
            return $this->availability->find($id);
        }

        if ($doctor_id) {
            return $this->availability->where('doctor_id', $doctor_id)
                ->select(['id', 'doctor_id', 'date', 'start_time', 'end_time'])->orderBy('date', 'desc')->get();
        }

        return $this->availability->select(['id', 'doctor_id', 'date', 'start_time', 'end_time'])->orderBy('date', 'desc')->get();
    }

    public function update($id, $availabilityData)
    {
        $availability = $this->availability->find($id);
        $availability->update($availabilityData);
        return $availability;
    }

    public function delete($id)
    {
        $availability = $this->availability->find($id);
        $availability->delete();
        return $availability;
    }

    public function exists($id)
    {
        $availability = $this->availability->where('id', $id)->exists();
        return $availability;
    }

    public function checkIfDoctorScheduleExists($doctor_id, $date, $start_time, $end_time)
    {
        return $this->availability->where('doctor_id', $doctor_id)->where('date', $date)
            ->where('start_time', $start_time)->where('end_time', $end_time)->exists();
    }

    public function checkIfDoctorScheduleExistsOnUpdate($id, $doctor_id, $date, $start_time, $end_time)
    {
        return $this->availability->where('id', '!=', $id)->where('doctor_id', $doctor_id)->where('date', $date)
            ->where('start_time', $start_time)->where('end_time', $end_time)->exists();
    }
}
