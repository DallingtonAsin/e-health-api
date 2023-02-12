<?php 
namespace App\Repositories;

use App\Models\UserType;

class UserTypeRepository
{
    protected $userType;

    public function __construct(UserType $userType)
    {
        $this->userType = $userType;
    }

    public function create($userTypeData)
    {
        return $this->userType->create($userTypeData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->userType->find($id);
        }
        return $this->userType->all();
    }

    public function update($id, $userTypeData)
    {
        $userType = $this->userType->find($id);
        $userType->update($userTypeData);
        return $userType;
    }

    public function delete($id)
    {
        $userType = $this->userType->find($id);
        $userType->delete();
        return $userType;
    }

    public function exists($id){
        $userType = $this->userType->where('id', $id)->exists();
        return $userType; 
    }

    public function getUserTypeId($appUser){
        return $this->userType->where('name', 'like', '%'.$appUser.'%')->first()->id;
    }

    public function getPatientTypeId(){
        return $this->getUserTypeId('patient');
    }

    public function getDoctorTypeId(){
        return $this->getUserTypeId('doctor');
    }

}