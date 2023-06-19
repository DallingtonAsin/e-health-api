<?php

namespace App\Repositories\AfterCall;

use App\Models\MedicalFinding;

class MedicalFindingRepository
{
    protected $medicalFinding;

    public function __construct(MedicalFinding $medicalFinding)
    {
        $this->medicalFinding = $medicalFinding;
    }

    public function create($medicalFindingData)
    {
        return $this->medicalFinding->create($medicalFindingData);
    }

    public function createOrUpdate($criteria, $data)
    {
        return $this->medicalFinding->createOrUpdate($criteria, $data);
    }

    public function find($id )
    {
        return $this->medicalFinding->find($id);
    }

    public function get()
    {
        $medicalFindings = $this->medicalFinding->select(['id', 'appointment_id', 'labtest_category_id', 'finding'])->orderBy('id', 'asc');
        return $medicalFindings->get();
    }

    public function update($id, $medicalFindingData)
    {
        $medicalFinding = $this->medicalFinding->find($id);
        $medicalFinding->update($medicalFindingData);
        return $medicalFinding;
    }

    public function delete($id)
    {
        $medicalFinding = $this->medicalFinding->find($id);
        $medicalFinding->delete();
        return $medicalFinding;
    }

    public function exists($id)
    {
        $medicalFinding = $this->medicalFinding->where('id', $id)->exists();
        return $medicalFinding;
    }
}
