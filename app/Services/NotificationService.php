<?php

namespace App\Services;

use App\Notifications\NewAppointmentNotification;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentCompletedNotification;
use App\Repositories\PatientRepository;
use App\Repositories\MedicalDoctorRepository;
use Carbon\Carbon;

class NotificationService
{

    protected $patient, $patientRepository, $medicalDoctorRepository, $current_time;

    public function __construct(PatientRepository $patientRepository, MedicalDoctorRepository $medicalDoctorRepository)
    {
        $this->patientRepository = $patientRepository;
        $this->medicalDoctorRepository = $medicalDoctorRepository;

        $current_date = Carbon::now()->format('Y-m-d');
        $current_time = Carbon::now()->format('H:i A');
        $this->current_time = $current_date . ' ' . $current_time;
    }

    public function sendAppointmentBookedMessage($patient_id, $appointment)
    {
        try {

            $patient = $this->patientRepository->find($patient_id);
            $message = $this->appointmentBookedMessage($appointment);
            $notification = new AppointmentBookedNotification($appointment, $message);
            $patient->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public function sendAppointmentConfirmedMessage($patient_id, $appointment)
    {
        try {

            $patient = $this->patientRepository->find($patient_id);
            $message = $this->appointmentConfirmedMessage($appointment);
            $notification = new AppointmentConfirmedNotification($appointment, $message);
            $patient->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function sendAppointmentCancelledMessage($patient_id, $appointment)
    {
        try {

            $patient = $this->patientRepository->find($patient_id);
            $message = $this->appointmentCancelledMessage($appointment);
            $notification = new AppointmentCancelledNotification($appointment, $message);
            $patient->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function sendAppointmentCompletedMessage($appointment, $is_patient)
    {
        try {
            $user = $is_patient ? $this->patientRepository->find($appointment->patient_id) : $this->medicalDoctorRepository->find($appointment->doctor_id);
            $message = $this->appointmentCompletedMessage($appointment, $is_patient);
            $notification = new AppointmentCompletedNotification($appointment, $message);
            $user->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public function sendDoctorNewAppointmentMessage($doctor_id, $appointment)
    {
        try {
            $doctor = $this->medicalDoctorRepository->find($doctor_id);
            $message = $this->newAppointmentMessage($appointment);
            $notification = new NewAppointmentNotification($appointment, $message);
            $doctor->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function sendDoctorAppointmentCancelledMessage($doctor_id, $appointment)
    {
        try {

            $doctor = $this->medicalDoctorRepository->find($doctor_id);
            $message = $this->doctorAppointmentCancelledMessage($appointment);
            $notification = new AppointmentCancelledNotification($appointment, $message);
            $doctor->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function sendDoctorAppointmentConfirmedMessage($doctor_id, $appointment)
    {
        try {

            $doctor = $this->medicalDoctorRepository->find($doctor_id);
            $message = $this->doctorAppointmentConfirmedMessage($appointment);
            $notification = new AppointmentConfirmedNotification($appointment, $message);
            $doctor->notify($notification);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function appointmentBookedMessage($appointment)
    {
        try {
            $message = "Your medical appointment (#" . $appointment->appointment_number . ") has been scheduled for " . $appointment->appointment_date . " at " . $appointment->appointment_time . ". Please arrive 15 minutes early to check in. If you need to reschedule, please do so at least 24 hours before your appointment. Thank you for using our app!";
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function appointmentConfirmedMessage($appointment)
    {
        try {

            $message = "Your appointment (#" . $appointment->appointment_number . ") scheduled for " . $appointment->appointment_date . " at " . $appointment->appointment_time . " has been successfully confirmed at " . $this->current_time . ".";
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function appointmentCancelledMessage($appointment)
    {
        try {

            $message = "Your appointment (#" . $appointment->appointment_number . ") scheduled for " . $appointment->appointment_date . " at " . $appointment->appointment_time . " has been successfully cancelled at " . $this->current_time . ".";
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function newAppointmentMessage($appointment)
    {
        try {
            $patient = $this->patientRepository->find($appointment->patient_id);
            $patient_name = $patient->first_name . ' ' . $patient->last_name;
            $message = 'A new appointment (#' . $appointment->appointment_number . ') has been scheduled for you on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . ' with ' . $patient_name . '.';
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function doctorAppointmentConfirmedMessage($appointment)
    {
        try {
            $patient = $this->patientRepository->find($appointment->patient_id);
            $patient_name = $patient->first_name . ' ' . $patient->last_name;
            $message = 'Medical appointment (#' . $appointment->appointment_number . ')  with ' . $patient_name . ' scheduled on ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . ', has been confirmed at ' . $this->current_time . '.';
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function doctorAppointmentCancelledMessage($appointment)
    {
        try {
            $patient = $this->patientRepository->find($appointment->patient_id);
            $patient_name = $patient->first_name . ' ' . $patient->last_name;
            $message = 'Medical appointment (#' . $appointment->appointment_number . ')  with ' . $patient_name . ', originally scheduled for ' . $appointment->appointment_date . ' at ' . $appointment->appointment_time . ', has been cancelled at ' . $this->current_time . '.';
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function appointmentCompletedMessage($appointment, $is_patient = true)
    {
        try {
            $patient = $this->patientRepository->find($appointment->patient_id);
            $doctor = $this->medicalDoctorRepository->find($appointment->doctor_id);

            $patient_name = $patient->first_name . ' ' . $patient->last_name;
            $doctor_name = $doctor->first_name . ' ' . $doctor->last_name;

            $message = "Your appointment (#" . $appointment->appointment_number . ") with doctor " . $doctor_name . " on " . $appointment->appointment_date . " at " . $appointment->appointment_time . " has been completed at " . $this->current_time . ".";
            if (!$is_patient) {
                $message = "Your appointment (#" . $appointment->appointment_number . ") with patient " . $patient_name . " on " . $appointment->appointment_date . " at " . $appointment->appointment_time . " has been completed at " . $this->current_time . ".";
            }
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
