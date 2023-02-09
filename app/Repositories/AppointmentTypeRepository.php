<?php 
namespace App\Repositories;

use App\Models\AppointmentType;

class AppointmentTypeRepository
{
    protected $appointmentType;

    public function __construct(AppointmentType $appointmentType)
    {
        $this->appointmentType = $appointmentType;
    }

    public function create($appointmentTypeData)
    {
        return $this->appointmentType->create($appointmentTypeData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->appointmentType->find($id);
        }
        return $this->appointmentType->get(['id', 'name'])->toArray();
    }

    public function update($id, $appointmentTypeData)
    {
        $appointmentType = $this->appointmentType->find($id);
        $appointmentType->update($appointmentTypeData);
        return $appointmentType;
    }

    public function delete($id)
    {
        $appointmentType = $this->appointmentType->find($id);
        $appointmentType->delete();
        return $appointmentType;
    }

    public function exists($id){
        $appointmentType = $this->appointmentType->where('id', $id)->exists();
        return $appointmentType; 
    }

}