<?php

namespace App\Repositories;

use App\Models\MedicalAppointment;
use App\Models\AppointmentType;
use App\Helpers\SharedHelper as Helper;
use Carbon\Carbon;


class MedicalAppointmentRepository
{
    protected $medicalAppointment, $appointmentType;

    public function __construct(MedicalAppointment $medicalAppointment, AppointmentType $appointmentType)
    {
        $this->medicalAppointment = $medicalAppointment;
        $this->appointmentType = $appointmentType;
    }

    public function create($medicalAppointmentData)
    {
        return $this->medicalAppointment->create($medicalAppointmentData);
    }

    public function get($id = null, $status = null)
    {
        if ($id) {
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

    public function exists($id)
    {
        $medicalAppointment = $this->medicalAppointment->where('id', $id)->exists();
        return $medicalAppointment;
    }

    public function checkIfAppointmentExists($patient_id, $doctor_id, $appointment_type_id, $appointment_date)
    {
        return $this->medicalAppointment
            ->where('patient_id', $patient_id)
            ->where('doctor_id', $doctor_id)
            ->where('appointment_type_id', $appointment_type_id)
            ->where('appointment_date', $appointment_date)
            ->exists();
    }

    public function generateAppointmentNumber()
    {
        return Helper::generateUniqueNumber('medical_appointments', 'appointment_number', 10, 'APT');
    }

    public function getMedicalAppointments($patient_id = null, $status = null, $doctor_id = null)
    {
        $appointments = $this->medicalAppointment->with(['doctor' => function ($query) {
            $query->select(['id', 'first_name', 'last_name', 'specialty_id', 'title', 'phone_number', 'email', 'qualification', 'profession', 'experience', 'image', 'service_fee']);
        }])->with(['appointmentType' => function ($query) {
            $query->select(['id', 'name']);
        }]);

        if ($patient_id) {
            $appointments->where('patient_id', $patient_id);
        }

        if ($doctor_id) {
            $appointments->where('doctor_id', $doctor_id);
        }

        if ($status) {
            $appointments->where('status', $status);
        }

        $appointments->orderBy('appointment_date', 'desc');


        $appointments = $appointments->get();


        foreach ($appointments as $appointment) {
            $datetime = Carbon::parse($appointment->appointment_date);
            $appointment['is_online'] = $appointment->isOnline();
            $appointment['appointment_date'] = $datetime->toDateString();
            $appointment['appointment_time'] = date('H:i A', strtotime($datetime->toTimeString()));
            $appointment['status'] = ucfirst($appointment->status);
            unset($appointment->appointment_type_id);
            $appointment->doctor->service_fee =  number_format(floatval($appointment->doctor->service_fee));
        }

        $appointments->makeHidden(['created_at', 'updated_at']);

        return $appointments;
    }


    public function cancelAppointment($patient_id, $appointment_number){
      return $this->medicalAppointment->where('patient_id', $patient_id)->where('appointment_number', $appointment_number)
                  ->update(['status' => 'cancelled', 'cancelled_at' => Carbon::now()]);
    }



}
