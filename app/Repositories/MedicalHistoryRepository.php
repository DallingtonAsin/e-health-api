<?php

namespace App\Repositories;

use App\Models\MedicalHistory;

class MedicalHistoryRepository
{
    protected $medicalHistory;

    public function __construct(MedicalHistory $medicalHistory)
    {
        $this->medicalHistory = $medicalHistory;
    }

    public function create($medicalHistoryData)
    {
        return $this->medicalHistory->create($medicalHistoryData);
    }

    public function find($id)
    {
        return $this->medicalHistory->find($id);
    }

    public function get($patient_id = null)
    {
        $history = $this->medicalHistory->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment'])->get();
        if ($patient_id) {
            $history = $this->medicalHistory->where('patient_id', $patient_id)->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment'])->get();
        }
        return $history;
    }

    public function update($id, $medicalHistoryData)
    {
        $medicalHistory = $this->medicalHistory->find($id);
        $medicalHistory->update($medicalHistoryData);
        return $medicalHistory;
    }

    public function delete($id)
    {
        $medicalHistory = $this->medicalHistory->find($id);
        $medicalHistory->delete();
        return $medicalHistory;
    }

    public function exists($id)
    {
        $medicalHistory = $this->medicalHistory->where('id', $id)->exists();
        return $medicalHistory;
    }
}
