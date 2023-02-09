<?php 
namespace App\Repositories;

use App\Models\MedicalAppointment;
use App\Helpers\SharedHelper as Helper;

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

    public function checkIfAppointmentExists($patient_id, $doctor_id, $appointment_type_id, $appointment_date){
      return $this->medicalAppointment
              ->where('patient_id', $patient_id)
              ->where('doctor_id', $doctor_id)
              ->where('appointment_type_id', $appointment_type_id)
              ->where('appointment_date', $appointment_date)
              ->exists();
    }

    public function generateAppointmentNumber(){
        return Helper::generateUniqueNumber('medical_appointments', 'appointment_number', 10, 'APT');
    }

}