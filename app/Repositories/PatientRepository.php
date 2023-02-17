<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Patient;

class PatientRepository
{
    protected $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function create($patientData)
    {
        return $this->patient->create($patientData);
    }

    public function get($id = null)
    {
        if ($id) {
            return $this->patient->find($id);
        }
        return $this->patient->all();
    }

    public function find($id){
        return $this->patient->find($id);
    }

    public function update($id, $patientData)
    {
        $patient = $this->patient->find($id);
        $patient->update($patientData);
        return $patient;
    }

    public function delete($id)
    {
        $patient = $this->patient->find($id);
        $patient->delete();
        return $patient;
    }

    public function exists($id)
    {
        $patient = $this->patient->where('id', $id)->exists();
        return $patient;
    }

    public function isValidOTP($patient_id, $otp)
    {
        return $this->patient->where("id", $patient_id)->where("otp", "=", $otp)->exists();
    }

    public function checkIfPhoneNumberExists($country_code, $phone_number)
    {
        return $this->patient->where("country_code", "=", $country_code)
            ->where("phone_number", "=", $phone_number)
            ->exists();
    }

    public function getPatientDetailsByPhoneNumber($country_code, $phone_number)
    {
        return $this->patient->where("country_code", $country_code)
            ->where("phone_number", $phone_number)
            ->first();
    }

    public function generateAccessToken($id)
    {

        $patient = $this->patient->find($id);
        $phone_number = $patient->country_code . '' . $patient->phone_number;
        $access_token = $patient->createToken('Patient' . $phone_number, ['patient'])->accessToken;
        $patient->is_patient = $patient->isPatient();
        $patient->access_token = $access_token;
        if(!empty($patient->image)){
            $patient->image = url('storage/'.$patient->image.'');
        }
        return $patient;
    }
}