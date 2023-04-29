<?php

namespace App\Repositories;

use App\Models\MedicalAppointment;
use App\Models\AppointmentType;
use App\Models\MedicalSpecialty;
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

    public function getMedicalAppointments($patient_id = null, $status = null, $doctor_id = null, $is_doctor_notified = null)
    {
        $appointments = $this->medicalAppointment->with(['patient' => function ($query) {
            $query->select(['id', 'first_name', 'last_name', 'country_code', 'phone_number', 'email', 'address', 'dob', 'image']);
        }])->with(['doctor' => function ($query) {
            $query->select(['id', 'first_name', 'last_name', 'specialty_id', 'country_code', 'phone_number', 'email', 'qualification', 'image', 'service_fee']);
        }])->with(['appointmentType' => function ($query) {
            $query->select(['id', 'name']);
        }])->with(['meetingAccess' => function ($query) {
            $query->select(['appointment_id', 'app_id as appId', 'channel', 'token']);
        }])->with(['medicalHistory' => function ($query) {
            $query->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment']);
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

        if (!is_null($is_doctor_notified)) {
            $appointments->where('is_doctor_notified', $is_doctor_notified);
        }

        $appointments->orderBy('appointment_date', 'desc');

        $appointments = $appointments->get()
            ->map(function ($appointment) {
                $is_online = $appointment->isOnline();
                $appointment->is_online = $appointment->isOnline();
                if ($is_online) {
                    if (!empty($appointment->meetingAccess->appointment_id)) {
                        unset($appointment->meetingAccess->appointment_id);
                    }
                }
                
                $appointment->is_video = $appointment->isVideo();
                $appointment->appointment_time =  Carbon::parse($appointment->appointment_date)->format('H:i');
                $appointment->appointment_date = Carbon::parse($appointment->appointment_date)->toDateString();
                $appointment->status = ucfirst($appointment->status);
                $appointment->patient->thumbnail = $appointment->patient->thumbnail();
                $appointment->doctor->thumbnail = $appointment->doctor->thumbnail();
                $appointment->doctor->specialty = MedicalSpecialty::where('id', $appointment->doctor->specialty_id)->value('name');
                $appointment->doctor->service_fee = number_format(floatval($appointment->doctor->service_fee));
                return $appointment;
            });

        $appointments->makeHidden(['created_at', 'updated_at']);

        return $appointments;
    }

    public function completeAppointment($patient_id, $appointment_id)
    {
        return $this->medicalAppointment->where('patient_id', $patient_id)->where('id', $appointment_id)
            ->update(['status' => 'completed', 'completed_at' => Carbon::now()]);
    }

    public function cancelAppointment($patient_id, $appointment_number)
    {
        return $this->medicalAppointment->where('patient_id', $patient_id)->where('appointment_number', $appointment_number)
            ->update(['status' => 'cancelled', 'cancelled_at' => Carbon::now()]);
    }

    public function findAppointmentByNumber($appointment_number)
    {
        return $this->medicalAppointment->where('appointment_number', $appointment_number)->first();
    }
}
