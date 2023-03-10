<?php

namespace App\Services;

use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Repositories\PatientRepository;
use Carbon\Carbon;

class NotificationService
{

    protected $patient, $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
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

    private function appointmentBookedMessage($appointment)
    {
        try {
            $message = "Your medical appointment (#" . $appointment->appointment_number . ") has been scheduled for " . $appointment->appointment_date . " at " . $appointment->appointment_time . ". Please arrive 15 minutes early to check in. If you need to reschedule, please do so at least 24 hours before your appointment. Thank you for using our app!";
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function appointmentCancelledMessage($appointment)
    {
        try {

            $current_date = Carbon::now()->format('Y-m-d');
            $current_time = Carbon::now()->format('H:i:s');
            $message = "Your medical appointment (#" . $appointment->appointment_number . ") on " . $current_date . " at " . $current_time . " has been successfully cancelled. Please reschedule your appointment at your earliest convenience. Thank you for using our app!";
            return $message;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
