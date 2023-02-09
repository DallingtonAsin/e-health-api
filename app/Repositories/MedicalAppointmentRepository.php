<?php 
namespace App\Repositories;

use App\Models\MedicalAppointment;

class MedicalAppointmentRepository
{
    protected $medicalAppointment;

    public function __construct(MedicalAppointment $medicalAppointment)
    {
        $this->medicalAppointment = $medicalAppointment;
    }

    public function create($medicalAppointmentData)
    {
        return $this->medicalAppointment->create($medicalAppointmentData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->medicalAppointment->find($id);
        }
        return $this->medicalAppointment->all();
    }

    public function update($id, $medicalAppointmentData)
    {
        $medicalAppointment = $this->medicalAppointment->find($id);
        $medicalAppointment->update($medicalAppointmentData);
        return $medicalAppointment;
    }

    public function delete($id)
    {
        $medicalAppointment = $this->medicalAppointment->find($id);
        $medicalAppointment->delete();
        return $medicalAppointment;
    }

    public function exists($id){
        $medicalAppointment = $this->medicalAppointment->where('id', $id)->exists();
        return $medicalAppointment; 
    }

}