<?php

namespace App\Repositories\Patient;

use App\Models\Patient;
use App\Repositories\Transactions\Credit\PatientCreditTransactionRepository;
use App\Repositories\Transactions\Debit\PatientDebitTransactionRepository;

class PatientRepository
{
    protected $patient, $creditTransactionRepository, $debitTransactionRepository;

    public function __construct(
        Patient $patient,
        PatientCreditTransactionRepository $creditTransactionRepository,
        PatientDebitTransactionRepository $debitTransactionRepository

    ) {
        $this->patient = $patient;
        $this->creditTransactionRepository = $creditTransactionRepository;
        $this->debitTransactionRepository = $debitTransactionRepository;
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

    public function find($id)
    {
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

    public function getDetailsByPhoneNumber($country_code, $phone_number)
    {
        return $this->patient->where("country_code", $country_code)
            ->where("phone_number", $phone_number)
            ->first();
    }

    public function getDetailsByEmail($email)
    {
        return $this->patient->where("email", $email)->first();
    }

    public function checkIfEmailIsTaken($email)
    {
        return $this->patient->where('email', $email)->exists();
    }

    public function getWalletBalance($patient_id)
    {
        $credits = $this->creditTransactionRepository->getTotalCredit($patient_id);
        $debits = $this->debitTransactionRepository->getTotalDebit($patient_id);
        $balance = $credits - $debits;
        return $balance;
    }

    public function generateAccessToken($id)
    {

        $patient = $this->patient->find($id);
        $phone_number = $patient->country_code . '' . $patient->phone_number;
        $access_token = $patient->createToken('Patient' . $phone_number, ['patient'])->accessToken;
        $patient->is_patient = $patient->isPatient();
        $patient->image = $patient->thumbnail();
        $patient->access_token = $access_token;
        $patient->balance = $this->getWalletBalance($id);
        return $patient;
    }
}
