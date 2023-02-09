<?php 
namespace App\Repositories;

use App\Models\MedicalSpecialty;

class MedicalSpecialtyRepository
{
    protected $medicalSpecialty;

    public function __construct(MedicalSpecialty $medicalSpecialty)
    {
        $this->medicalSpecialty = $medicalSpecialty;
    }

    public function create($medicalSpecialtyData)
    {
        return $this->medicalSpecialty->create($medicalSpecialtyData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->medicalSpecialty->find($id);
        }
        return $this->medicalSpecialty->get(['id', 'name'])->toArray();
    }

    public function update($id, $medicalSpecialtyData)
    {
        $medicalSpecialty = $this->medicalSpecialty->find($id);
        $medicalSpecialty->update($medicalSpecialtyData);
        return $medicalSpecialty;
    }

    public function delete($id)
    {
        $medicalSpecialty = $this->medicalSpecialty->find($id);
        $medicalSpecialty->delete();
        return $medicalSpecialty;
    }

    public function exists($id){
        $medicalSpecialty = $this->medicalSpecialty->where('id', $id)->exists();
        return $medicalSpecialty; 
    }

}