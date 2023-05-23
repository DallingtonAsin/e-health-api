<?php

namespace App\Services\email;

use App\Repositories\MedicalAppointmentRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendingAppointmentEmail;
use App\Repositories\MedicalDoctorRepository;
use App\Services\PushNotificationService;
use Illuminate\Support\Facades\Log;

class EmailService
{

    protected $medicalAppointmentRepository, $pushNotificationService, $doctorRepository;

    public function __construct(
        MedicalAppointmentRepository $medicalAppointmentRepository,
        PushNotificationService $pushNotificationService,
        MedicalDoctorRepository $doctorRepository
    ) {
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
        $this->pushNotificationService = $pushNotificationService;
        $this->doctorRepository = $doctorRepository;
    }

    public function sendPendingAppointmentMail()
    {
        try {
            $appointments = $this->medicalAppointmentRepository->getMedicalAppointments(null, null, 'pending', null, 0);
            foreach ($appointments as $appointment) {

                $appointment_date = $appointment->appointment_date;
                $body = [
                    'doctor_name' => $appointment->doctor->first_name . " " . $appointment->doctor->last_name,
                    'appointment_number' => $appointment->appointment_number,
                    'appointment_date' => $appointment->appointment_date,
                    'patient_name' => $appointment->patient->first_name . " " . $appointment->patient->last_name,
                    'patient_phone_number' => $appointment->patient->country_code . "" . $appointment->patient->phone_number,
                    'patient_address' => $appointment->patient->address,
                    'reason' => $appointment->reason,
                ];


                $doctor = $this->doctorRepository->find($appointment->doctor->id);
                if (!empty($doctor->fcm_token)) {
                    $fcmToken = $appointment->doctor->fcm_token;
                    $fcmTitle = 'New Appointment Alert';
                    $fcmBody = "There is a new appointment scheduled on " . $appointment_date;
                    $this->pushNotificationService->sendPushNotification($fcmToken, $fcmTitle, $fcmBody);
                }

                if ($appointment->doctor->email) {
                    $doctor_email = $appointment->doctor->email;
                    Mail::to($doctor_email)->send(new PendingAppointmentEmail($body));
                    $this->medicalAppointmentRepository->update($appointment->id, ['is_doctor_notified' => 1]);
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
