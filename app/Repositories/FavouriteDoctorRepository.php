<?php

namespace App\Repositories;

use App\Models\PatientFavouriteDoctor;

class FavouriteDoctorRepository
{
    protected $favouriteDoctor, $medicalDoctorRepository;

    public function __construct(PatientFavouriteDoctor $favouriteDoctor)
    {
        $this->favouriteDoctor = $favouriteDoctor;
    }

    public function create($favouriteDoctorData)
    {
        return $this->favouriteDoctor->create($favouriteDoctorData);
    }

    public function get($id = null)
    {
        if ($id) {
            return $this->favouriteDoctor->find($id);
        }
        return $this->favouriteDoctor->all();
    }

    public function update($id, $favouriteDoctorData)
    {
        $favouriteDoctor = $this->favouriteDoctor->find($id);
        $favouriteDoctor->update($favouriteDoctorData);
        return $favouriteDoctor;
    }

    public function delete($id)
    {
        $favouriteDoctor = $this->favouriteDoctor->find($id);
        $favouriteDoctor->delete();
        return $favouriteDoctor;
    }

    public function exists($id)
    {
        $favouriteDoctor = $this->favouriteDoctor->where('id', $id)->exists();
        return $favouriteDoctor;
    }

}
