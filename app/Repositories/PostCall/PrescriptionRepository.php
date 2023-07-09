<?php

namespace App\Repositories\PostCall;

use App\Models\Prescription;

class PrescriptionRepository
{
    protected $prescription;

    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    public function create($prescriptionData)
    {
        return $this->prescription->create($prescriptionData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->prescription->updateOrCreate($criteria, $data);
    }

    public function find($id )
    {
        return $this->prescription->find($id);
    }

    public function get()
    {
        $prescriptions = $this->prescription->select(['id', 'appointment_id', 'drug_id', 'dosage', 'admin_route_id', 'duration', 'quantity', 'instructions'])->orderBy('id', 'asc');
        return $prescriptions->get();
    }

    public function update($id, $prescriptionData)
    {
        $prescription = $this->prescription->find($id);
        $prescription->update($prescriptionData);
        return $prescription;
    }

    public function delete($id)
    {
        $prescription = $this->prescription->find($id);
        $prescription->delete();
        return $prescription;
    }

    public function exists($id)
    {
        $prescription = $this->prescription->where('id', $id)->exists();
        return $prescription;
    }
}
