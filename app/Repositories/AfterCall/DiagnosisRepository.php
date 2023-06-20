<?php

namespace App\Repositories\AfterCall;

use App\Models\Diagnosis;

class DiagnosisRepository
{
    protected $diagnosis;

    public function __construct(Diagnosis $diagnosis)
    {
        $this->diagnosis = $diagnosis;
    }

    public function create($diagnosisData)
    {
        return $this->diagnosis->create($diagnosisData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->diagnosis->updateOrCreate($criteria, $data);
    }

    public function find($id )
    {
        return $this->diagnosis->find($id);
    }

    public function get()
    {
        $diagnoses = $this->diagnosis->select(['id', 'appointment_id', 'icd_code_id', 'comments', 'diagnosis_date'])->orderBy('id', 'asc');
        return $diagnoses->get();
    }

    public function update($id, $diagnosisData)
    {
        $diagnosis = $this->diagnosis->find($id);
        $diagnosis->update($diagnosisData);
        return $diagnosis;
    }

    public function delete($id)
    {
        $diagnosis = $this->diagnosis->find($id);
        $diagnosis->delete();
        return $diagnosis;
    }

    public function exists($id)
    {
        $diagnosis = $this->diagnosis->where('id', $id)->exists();
        return $diagnosis;
    }
}
