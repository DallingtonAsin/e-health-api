<?php

namespace App\Repositories;

use App\Models\DoctorAvailability;
use Carbon\Carbon;

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
                ->select(['id', 'doctor_id', 'date', 'start_time', 'end_time'])->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
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

    public function checkDoctorAvailability($doctorId)
    {
        $availability = $this->getDoctorAvailability($doctorId);
        if ($availability->isEmpty()) {
            throw new \Exception('Doctor has not set an availability schedule.');
        }
        return $availability;
    }

    public function getDoctorAvailability($doctorId)
    {
        $today = Carbon::today();
        $availability = $this->availability->select(['id', 'doctor_id', 'date', 'start_time', 'end_time'])->where('doctor_id', $doctorId)
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')->get();
        return $availability;
    }

    public function checkForTimeOverlap($doctorId, $date, $startTime, $endTime)
    {
        $conflictingCount = $this->availability->where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($subQuery) use ($startTime, $endTime) {
                    $subQuery->where('start_time', '>=', $startTime)
                        ->where('start_time', '<', $endTime);
                })->orWhere(function ($subQuery) use ($startTime, $endTime) {
                    $subQuery->where('end_time', '>', $startTime)
                        ->where('end_time', '<=', $endTime);
                })->orWhere(function ($subQuery) use ($startTime, $endTime) {
                    $subQuery->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                });
            })
            ->count();

        return $conflictingCount > 0;
    }

    public function getDoctorAvailabilityWindows($doctorId)
    {
        $today = date('Y-m-d');

        $availability = $this->availability->where('doctor_id', $doctorId)
            ->where('date', '>=', $today)
            ->orderBy('date')
            ->orderByRaw("TIME_FORMAT(start_time, '%H:%i')")
            ->get();

        $availableHoursByDate = [];
        $dates = [];

        foreach ($availability as $record) {
            $date = $record->date;
            $startTime = strtotime($record->start_time);
            $endTime =  strtotime($record->end_time);

            $startTime = ceil($startTime / 1800) * 1800;
            $endTime = floor($endTime / 1800) * 1800;

            for ($time = $startTime; $time <= $endTime; $time += 1800) {
                $hour = date('h:i A', $time);
                $availableHoursByDate[$date][] = $hour;
            }

            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        foreach ($availableHoursByDate as &$hours) {
            $hours = array_unique($hours);
        }

        foreach ($availableHoursByDate as &$hours) {
            usort($hours, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
        }

        $data = [];
        $data['schedule_dates'] = $dates;
        $data['schedule'] = $availableHoursByDate;

        return $data;
    }
}
