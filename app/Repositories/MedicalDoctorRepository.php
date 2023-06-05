<?php

namespace App\Repositories;

use App\Models\MedicalDoctor;
use Carbon\Carbon;

class MedicalDoctorRepository
{
    protected $medicalDoctor, $medicalFacilityRepository, $medicalSpecialtyRepository, $patientRepository;

    public function __construct(
        MedicalDoctor $medicalDoctor,
        MedicalFacilityRepository $medicalFacilityRepository,
        MedicalSpecialtyRepository $medicalSpecialtyRepository,
        PatientRepository $patientRepository
    ) {
        $this->medicalDoctor = $medicalDoctor;
        $this->medicalFacilityRepository = $medicalFacilityRepository;
        $this->medicalSpecialtyRepository = $medicalSpecialtyRepository;
        $this->patientRepository = $patientRepository;
    }

    public function create($medicalDoctorData)
    {
        return $this->medicalDoctor->create($medicalDoctorData);
    }

    public function find($id)
    {
        return $this->medicalDoctor->find($id);
    }

    public function checkIfEmailExists($email)
    {
        return $this->medicalDoctor->where('email', $email)->exists();
    }

    public function get($id = null, $specialty = null, $patient_id = null, $is_online = null)
    {

        $today = Carbon::today();
        $doctors = $this->medicalDoctor->where('is_verified', 1)->withCount('ratings')->with(['availability' => function ($query) use ($today) {
            $query->where('date', '>=', $today)->orderBy('date', 'asc');
        }]);

        if ($id) {
            $doctors = $doctors->where('id', '=', $id);
        }
        if ($specialty) {
            $doctors = $doctors->where('specialty_id', '=', $specialty);
        }

        if (!is_null($is_online)) {
            $doctors = $doctors->where('is_online', '=', $is_online);
        }

        $doctors = $doctors->get();
        $doctors->makeHidden(['created_at', 'updated_at']);

        if ($patient_id) {
            $patient = $this->patientRepository->find($patient_id);
            $favouriteDoctors = $patient->favouriteDoctors()->get();
            foreach ($doctors as $doctor) {
                $doctor->is_favourite = $favouriteDoctors->contains($doctor);
            }
        }

        foreach ($doctors as $doctor) {
            $languages = unserialize($doctor->languages);
            // $languageString = implode(', ', $languages);
            $doctor['languages'] =  $languages;
            $rating = $doctor->ratings->sum('rating');
            $doctor['rating']  = $rating > 0 ? $rating / $doctor->ratings_count : 0;
            unset($doctor->ratings_count);
            $doctor['service_fee'] = config('app.currency') . '. ' . number_format($doctor->service_fee);
            $doctor['image'] = $doctor->thumbnail();

            if ($doctor->primary_facility_id) {
                $medicalFacility = $this->medicalFacilityRepository->find($doctor->primary_facility_id);
                $medicalSpecialty = $this->medicalSpecialtyRepository->find($doctor->specialty_id);
                $doctor['facility'] = $medicalFacility->name;
                $doctor['specialty'] = $medicalSpecialty->name;
            }

            $doctor->availability->makeHidden(['id', 'created_at', 'updated_at']);

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
                    $time += 60 * 30;
                }
            }

            unset($doctor->availability);

            $doctor['schedule_dates'] = $dates;
            $doctor['schedule'] = $timeSlots;
        }

        $sortedDoctors = collect($doctors)
            ->sortByDesc('is_favourite')
            ->values()
            ->all();

        return $sortedDoctors;
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
        $exists = $this->medicalDoctor->where('id', $id)->exists();
        return $exists;
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

    public function getDetailsByPhoneNumber($country_code, $phone_number)
    {
        return $this->medicalDoctor->where("country_code", $country_code)
            ->where("phone_number", $phone_number)
            ->first();
    }

    public function getDetailsByEmail($email)
    {
        return $this->medicalDoctor->where("email", $email)->first();
    }

    public function generateAccessToken($id)
    {

        $doctor = $this->medicalDoctor->withCount('ratings')->with(['identificationDocument' => function ($query) {
            $query->select(['doctor_id', 'front', 'back']);
        }])->find($id);

        $phone_number = $doctor->country_code . '' . $doctor->phone_number;
        $access_token = $doctor->createToken('Doctor' . $phone_number, ['doctor'])->accessToken;
        $doctor->is_patient = $doctor->isPatient();
        if (!is_null($doctor->other_facilities)) {
            $doctor->other_facilities = unserialize($doctor->other_facilities);
        }
        if ($doctor->identificationDocument) {
            $doctor->identificationDocument->front = $doctor->identificationDocument->front_path;
            $doctor->identificationDocument->back = $doctor->identificationDocument->back_path;
            unset($doctor->identificationDocument->doctor_id);
        }
        $rating = $doctor->ratings->sum('rating');
        $doctor->rating  = $rating > 0 ? $rating / $doctor->ratings_count : 0;
        unset($doctor->ratings_count);
        unset($doctor->ratings);
        $doctor->image = $doctor->thumbnail();
        $doctor->access_token = $access_token;
        return $doctor;
    }
}
