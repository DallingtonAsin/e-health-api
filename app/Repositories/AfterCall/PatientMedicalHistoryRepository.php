<?php

namespace App\Repositories\AfterCall;

use App\Models\PatientMedicalHistory;

class PatientMedicalHistoryRepository
{
    protected $patientMedicalHistory;

    public function __construct(PatientMedicalHistory $patientMedicalHistory)
    {
        $this->patientMedicalHistory = $patientMedicalHistory;
    }

    public function create($patientMedicalHistoryData)
    {
        return $this->patientMedicalHistory->create($patientMedicalHistoryData);
    }

    public function createOrUpdate($criteria, $data)
    {
        return $this->patientMedicalHistory->createOrUpdate($criteria, $data);
    }

    public function find($id = null)
    {
        return $this->patientMedicalHistory->find($id);
    }

    public function get()
    {
        $patientMedicalHistories = $this->patientMedicalHistory->select(['id', 'appointment_id', 'medical_history', 'drug_allergies'])->orderBy('id', 'asc');
        return $patientMedicalHistories->get();
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
}
