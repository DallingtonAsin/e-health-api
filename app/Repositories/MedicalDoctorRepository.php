<?php 
namespace App\Repositories;

use App\Models\MedicalDoctor;

class MedicalDoctorRepository
{
    protected $medicalDoctor;

    public function __construct(MedicalDoctor $medicalDoctor)
    {
        $this->medicalDoctor = $medicalDoctor;
    }

    public function create($medicalDoctorData)
    {
        return $this->medicalDoctor->create($medicalDoctorData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->medicalDoctor->find($id);
        }
        return $this->medicalDoctor->all();
    }

    public function update($id, $medicalDoctorData)
    {
        $medicalDoctor = $this->medicalDoctor->find($id);
        $medicalDoctor->update($medicalDoctorData);
        return $medicalDoctor;
    }

    public function delete($id)
    {
        $medicalDoctor = $this->medicalDoctor->find($id);
        $medicalDoctor->delete();
        return $medicalDoctor;
    }

    public function exists($id){
        $medicalDoctor = $this->medicalDoctor->where('id', $id)->exists();
        return $medicalDoctor; 
    }

}