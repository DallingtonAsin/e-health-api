<?php

namespace App\Repositories;

use App\Models\MedicalFacility;

class MedicalFacilityRepository
{
    protected $medicalFacility;

    public function __construct(MedicalFacility $medicalFacility)
    {
        $this->medicalFacility = $medicalFacility;
    }

    public function create($medicalFacilityData)
    {
        return $this->medicalFacility->create($medicalFacilityData);
    }

    public function find($id)
    {
        return $this->medicalFacility->find($id);
    }

    public function findByName($name)
    {
        return $this->medicalFacility->where('name', $name)->first();
    }

    public function get()
    {
        $medical_facilities = $this->medicalFacility->select(['id as key', 'name as value'])->orderBy('id', 'asc')->get();
        return $medical_facilities;
    }

    public function update($id, $medicalFacilityData)
    {
        $medicalFacility = $this->medicalFacility->find($id);
        $medicalFacility->update($medicalFacilityData);
        return $medicalFacility;
    }

    public function delete($id)
    {
        $medicalFacility = $this->medicalFacility->find($id);
        $medicalFacility->delete();
        return $medicalFacility;
    }

    public function exists($id)
    {
        $medicalFacility = $this->medicalFacility->where('id', $id)->exists();
        return $medicalFacility;
    }
}
