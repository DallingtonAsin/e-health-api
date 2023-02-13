<?php

namespace App\Repositories;

use App\Models\DoctorAuthentication;

class DoctorAuthenticationRepository
{
    protected $doctorAuthentication;

    public function __construct(DoctorAuthentication $doctorAuthentication)
    {
        $this->doctorAuthentication = $doctorAuthentication;
    }

    public function create($doctorAuthenticationData)
    {
        return $this->doctorAuthentication->create($doctorAuthenticationData);
    }

    public function get($id = null)
    {

       if($id){
        return $this->doctorAuthentication->find($id);
       }

        return $this->doctorAuthentication->get();
    }

    public function isValidCode($country_code, $phone_number, $auth_code)
    {
        return $this->doctorAuthentication->where("country_code", $country_code)->where('phone_number', $phone_number)
                                          ->where("auth_code", "=", $auth_code)->exists();
    }

    public function update($id, $doctorAuthenticationData)
    {
        $doctorAuthentication = $this->doctorAuthentication->find($id);
        $doctorAuthentication->update($doctorAuthenticationData);
        return $doctorAuthentication;
    }

    public function delete($id)
    {
        $doctorAuthentication = $this->doctorAuthentication->find($id);
        $doctorAuthentication->delete();
        return $doctorAuthentication;
    }

    public function exists($id)
    {
        $doctorAuthentication = $this->doctorAuthentication->where('id', $id)->exists();
        return $doctorAuthentication;
    }


}
