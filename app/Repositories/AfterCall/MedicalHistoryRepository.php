<?php

namespace App\Repositories\AfterCall;

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

    public function createOrUpdate($criteria, $data)
    {
        return $this->medicalHistory->createOrUpdate($criteria, $data);
    }

    public function find($id )
    {
        return $this->medicalHistory->find($id);
    }

    public function get()
    {
        $MedicalHistories = $this->medicalHistory->select(['id', 'appointment_id', 'presenting_complaint', 'past_medical_history', 'drug_allergies', 'findings'])->orderBy('id', 'asc');
        return $MedicalHistories->get();
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
