<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
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

    public function find($id){
        return $this->medicalDoctor->find($id);
    }

    public function checkIfEmailExists($email){
        return $this->medicalDoctor->where('email', $email)->exists();
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

            $doctor['schedule_dates'] = $dates;
            $doctor['schedule'] = $timeSlots;
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

    public function isValidOTP($doctor_id, $otp)
    {
        return $this->medicalDoctor->where("id", $doctor_id)->where("otp", "=", $otp)->exists();
    }

    public function checkIfPhoneNumberExists($country_code, $phone_number)
    {
        return $this->medicalDoctor->where("country_code", "=", $country_code)
            ->where("phone_number", "=", $phone_number)
            ->exists();
    }

    public function getDoctorDetailsByPhoneNumber($country_code, $phone_number)
    {
        return $this->medicalDoctor->where("country_code", $country_code)
            ->where("phone_number", $phone_number)
            ->first();
    }

    public function generateAccessToken($id){

        $doctor = $this->medicalDoctor->find($id);
        $phone_number = $doctor->country_code . '' . $doctor->phone_number;
        $access_token = $doctor->createToken('Doctor' . $phone_number, ['doctor'])->accessToken;
        $doctor->is_patient = $doctor->isPatient();
        $doctor->image = $doctor->thumbnail();
        $doctor->access_token = $access_token;
        return $doctor;
    }

}
