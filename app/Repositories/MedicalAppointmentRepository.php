<?php

namespace App\Repositories;

use App\Models\MedicalAppointment;
use App\Models\AppointmentType;
use App\Models\MedicalSpecialty;
use App\Helpers\SharedHelper as Helper;
use App\Models\MedicalFacility;
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

    public function find($id)
    {
        return $this->medicalAppointment->findOrFail($id);
    }

    public function get($id = null, $patient_id = null, $status = null, $doctor_id = null, $is_doctor_notified = null)
    {
        return $this->getMedicalAppointments($id, $patient_id, $status, $doctor_id, $is_doctor_notified);
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
        return Helper::generateUniqueNumber('medical_appointments', 10, 'APT', 'appointment_number');
    }

    public function getMedicalAppointments($id = null, $patient_id = null, $status = null, $doctor_id = null, $is_doctor_notified = null)
    {
        $appointments = $this->medicalAppointment->with(['patient' => function ($query) {
            $query->select(['id', 'first_name', 'last_name', 'country_code', 'phone_number', 'email', 'address', 'dob', 'image']);
        }])->with(['doctor' => function ($query) {
            $query->select(['id', 'first_name', 'last_name', 'specialty_id', 'primary_facility_id', 'country_code', 'phone_number', 'email', 'qualification', 'address', 'image', 'service_fee', 'fcm_token']);
        }])->with(['appointmentType' => function ($query) {
            $query->select(['id', 'name']);
        }])->with(['meetingAccess' => function ($query) {
            $query->select(['appointment_id', 'app_id as appId', 'channel', 'token']);
        }])->with(['patientMedicalHistory' => function ($query) {
            $query->select(['id', 'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment']);
        }]);

        if ($id) {
            $appointments->where('id', $id);
        }

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

                $closedStatuses = ["completed", "cancelled"];
                if (!in_array($appointment->status, $closedStatuses)) {
                    $is_expired = $this->isAppointmentExpired($appointment->id);
                    $appointment->status = $is_expired ? 'Expired' : $appointment->status;
                    $appointment->is_expired = $is_expired;
                } else {
                    $appointment->is_expired = false;
                }
                
                if ($is_online) {
                    if (!empty($appointment->meetingAccess->appointment_id)) {
                        unset($appointment->meetingAccess->appointment_id);
                    }
                }

                $appointment->is_video = $appointment->isVideo();
                $appointment->appointment_time =  Carbon::parse($appointment->appointment_date)->format('H:i');
                $appointment->appointment_date = Carbon::parse($appointment->appointment_date)->toDateString();
                $appointment->status = ucfirst($appointment->status);
                $appointment->patient->age = Helper::calculateAge($appointment->patient->dob) . ' years';
                $appointment->patient->thumbnail = $appointment->patient->thumbnail();
                $appointment->doctor->thumbnail = $appointment->doctor->thumbnail();
                $appointment->doctor->specialty = MedicalSpecialty::where('id', $appointment->doctor->specialty_id)->value('name');
                $appointment->doctor->primary_facility = MedicalFacility::where('id', $appointment->doctor->primary_facility_id)->value('name');
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

    public function confirmAppointment($patient_id, $appointment_number)
    {
        return $this->medicalAppointment->where('patient_id', $patient_id)->where('appointment_number', $appointment_number)
            ->update(['status' => 'confirmed', 'confirmed_at' => Carbon::now()]);
    }

    public function cancelAppointment($patient_id, $appointment_number)
    {
        return $this->medicalAppointment->where('patient_id', $patient_id)->where('appointment_number', $appointment_number)
            ->update(['status' => 'cancelled', 'cancelled_at' => Carbon::now(), 'confirmed_at' => null]);
    }

    public function findAppointmentByNumber($appointment_number)
    {
        return $this->medicalAppointment->where('appointment_number', $appointment_number)->first();
    }

    public function isAppointmentConflict($doctor_id, $appointmentDateTime)
    {
        $endTime = $appointmentDateTime->copy()->addMinutes(30);
        $isConflict = $this->medicalAppointment->where('doctor_id', $doctor_id)->whereBetween('appointment_date', [$appointmentDateTime, $endTime])->exists();
        return $isConflict;
    }

    public function isAppointmentExpired($appointment_id)
    {
        $appointment = $this->find($appointment_id);
        $appointmentTime = Carbon::parse($appointment->appointment_date);
        $currentDateTime = Carbon::now();
        $endTime = $appointmentTime->copy()->addMinutes(30);
        $isExpired = $currentDateTime->isAfter($endTime);
        return $isExpired;
    }

    public function isSelectedAppointmentTimeInPast($appointmentTime)
    {
        return $appointmentTime->isPast();
    }

    public function getPostConsulationData($appointment_id)
    {
        $appointment = $this->medicalAppointment->select(['id', 'appointment_number', 'is_draft'])->where('id', $appointment_id)->with(['medicalHistory' => function ($query) {
            $query->select(['appointment_id', 'presenting_complaint', 'past_medical_history', 'drug_allergies', 'findings']);
        }])->with(['labTests' => function ($query) {
            $query->select(['appointment_id', 'labtest_category_id', 'findings']);
        }])->with(['imageTests' => function ($query) {
            $query->select(['appointment_id', 'imagetest_category_id', 'findings']);
        }])->with(['otherTests' => function ($query) {
            $query->select(['appointment_id', 'tests', 'findings']);
        }])->with(['diagnosis' => function ($query) {
            $query->select(['appointment_id', 'icd_code_id']);
        }])->with(['diagnosisComments' => function ($query) {
            $query->select(['appointment_id', 'comments']);
        }])->with(['prescriptions' => function ($query) {
            $query->select(['appointment_id', 'drug_id', 'dosage', 'admin_route_id', 'duration', 'quantity', 'instructions']);
        }])->with(['treatmentPlan' => function ($query) {
            $query->select(['appointment_id', 'treatment_plan']);
        }])->with(['labTestDocuments' => function ($query) {
            $query->select(['appointment_id', 'file_path', 'type', 'is_image as isImage']);
        }])->first();

        $labTests = $appointment->labTests;
        if (!empty($labTests)) {
            foreach ($labTests as $labTest) {
                $labTest->name = $labTest->labTestCategory->name;
                unset($labTest->appointment_id);
                unset($labTest->labtest_category_id);
                unset($labTest->labTestCategory);
            }
        }

        $imageTests = $appointment->imageTests;
        if (!empty($imageTests)) {
            foreach ($imageTests as $imageTest) {
                $imageTest->name = $imageTest->imageTestCategory->name;
                unset($imageTest->appointment_id);
                unset($imageTest->imagetest_category_id);
                unset($imageTest->imageTestCategory);
            }
        }


        $diagnoses = $appointment->diagnosis;
        $diagnosisIcdCodes = [];
        if (!empty($diagnoses)) {
            foreach ($diagnoses as $diagnosis) {
                $diagnosis_name = trim($diagnosis->Icd10Code->category_code) . ' ' . trim($diagnosis->Icd10Code->abbreviated_description);
                unset($diagnosis->appointment_id);
                unset($diagnosis->icd_code_id);
                unset($diagnosis->Icd10Code);
                array_push($diagnosisIcdCodes, $diagnosis_name);
            }
        }
        $appointment->diagnosisIcdCodes = $diagnosisIcdCodes;
        unset($appointment->diagnosis);

        $prescriptions = $appointment->prescriptions;
        if (!empty($prescriptions)) {
            foreach ($prescriptions as $prescription) {
                $prescription->name = $prescription->drug->name;
                $prescription->route_of_admin = $prescription->adminRoute->name;
                unset($prescription->appointment_id);
                unset($prescription->drug_id);
                unset($prescription->admin_route_id);
                unset($prescription->drug);
                unset($prescription->adminRoute);
            }
        }

        $labTestDocuments = $appointment->labTestDocuments;
        if (!empty($labTestDocuments)) {
            foreach ($labTestDocuments as $labTestDocument) {
                $labTestDocument->uri = $labTestDocument->filePath();
                $labTestDocument->isOnline = true;
                unset($labTestDocument->appointment_id);
                unset($labTestDocument->file_path);
            }
        }

        return $appointment;
    }
}
