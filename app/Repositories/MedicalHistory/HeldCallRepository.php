<?php

namespace App\Repositories\MedicalHistory;

use App\Models\HeldCall;
use App\Repositories\MedicalAppointmentRepository;

class HeldCallRepository
{
    protected $heldCall, $medicalAppointmentRepository;

    public function __construct(HeldCall $heldCall, MedicalAppointmentRepository $medicalAppointmentRepository)
    {
        $this->heldCall = $heldCall;
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
    }

    public function get($id = null, $patient_id = null, $doctor_id = null)
    {

        return $this->medicalAppointmentRepository->getMedicalAppointments($id, $patient_id, 'completed', $doctor_id, null);

        // $heldCall = $this->heldCall;
        // if($heldCall){
        //     $heldCall = $heldCall->where('id', $id);
        // }
        // $heldCall = $heldCall->with(['appointment' => function ($query) {
        //     $query->select(['id', 'patient_id', 'doctor_id', 'appointment_number', 'appointment_date', 'reason', 'notes', 'status', 'completed_at']);
        // }])->select(['id', 'appointment_id', 'start_time', 'end_time', 'duration'])->get();
        // return $heldCall;
    }

    public function find($id)
    {
        return $this->get($id);
    }

    public function create($data)
    {
        return $this->heldCall->create($data);
    }

    public function updateOrCreateCall($criteria, $scheduleData)
    {
        return $this->heldCall->updateOrCreate($criteria, $scheduleData);
    }

    public function update($id, $heldCallData)
    {
        $heldCall = $this->heldCall->find($id);
        $heldCall->update($heldCallData);
        return $heldCall;
    }

    public function delete($id)
    {
        $heldCall = $this->heldCall->find($id);
        $heldCall->delete();
        return $heldCall;
    }

    public function exists($id)
    {
        $heldCall = $this->heldCall->where('id', $id)->exists();
        return $heldCall;
    }
}
