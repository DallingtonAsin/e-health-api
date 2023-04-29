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

    public function find($id)
    {
           return $this->medicalSpecialty->find($id);
    }

    public function get($id = null)
    {
        if($id){
           return $this->medicalSpecialty->find($id);
        }
        return $this->medicalSpecialty->select(['id', 'name'])->get();
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

    public function getSpecialtyByName($name){
        return $this->medicalSpecialty->where('name', $name)->select('id', 'name')->first();
    }

}