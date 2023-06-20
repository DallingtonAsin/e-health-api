<?php

namespace App\Repositories;

use App\Models\PatientMedicalHistory;

class PatientMedicalHistoryRepository
{
    protected $patientMedicalHistory;

    public function __construct(PatientMedicalHistory $patientMedicalHistory)
    {
        $this->patientMedicalHistory = $patientMedicalHistory;
    }

    public function create($data)
    {
        return $this->patientMedicalHistory->create($data);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->patientMedicalHistory->updateOrCreate($criteria, $data);
    }

    public function find($id)
    {
        return $this->patientMedicalHistory->find($id);
    }

    public function get($patient_id = null)
    {
        $history = $this->patientMedicalHistory->whereNotNull('diagnosis_date')->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment'])->get();
        if ($patient_id) {
            $history = $this->patientMedicalHistory->where('patient_id', $patient_id)->whereNotNull('diagnosis_date')->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment'])->get();
        }
        return $history;
    }

    public function update($id, $patientMedicalHistoryData)
    {
        $patientMedicalHistory = $this->patientMedicalHistory->find($id);
        $patientMedicalHistory->update($patientMedicalHistoryData);
        return $patientMedicalHistory;
    }

    public function delete($id)
    {
        $patientMedicalHistory = $this->patientMedicalHistory->find($id);
        $patientMedicalHistory->delete();
        return $patientMedicalHistory;
    }

    public function exists($id)
    {
        $patientMedicalHistory = $this->patientMedicalHistory->where('id', $id)->exists();
        return $patientMedicalHistory;
    }

    public function updateMedicalHistory($patient_id, $appointment_id, $patientMedicalHistoryData)
    {
        $patientMedicalHistory = $this->patientMedicalHistory->where('patient_id', $patient_id)->where('appointment_id', $appointment_id)->first();
        return $patientMedicalHistory->update($patientMedicalHistoryData);
    }
}
