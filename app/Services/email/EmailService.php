<?php

namespace App\Services\email;

use App\Repositories\MedicalAppointmentRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendingAppointmentEmail;


class EmailService{
    
    protected $medicalAppointmentRepository;

    public function __construct(MedicalAppointmentRepository $medicalAppointmentRepository)
    {
        $this->medicalAppointmentRepository = $medicalAppointmentRepository;
    }
    
    public function sendPendingAppointmentMail()
    {

        try {

            $appointments = $this->medicalAppointmentRepository->getMedicalAppointments(null, 'pending', null, 0);
            foreach ($appointments as $appointment) {
               
                $body = [
                    'doctor_name' => $appointment->doctor->first_name . " " . $appointment->doctor->last_name,
                    'appointment_number' => $appointment->appointment_number,
                    'appointment_date' => $appointment->appointment_date,
                    'patient_name' => $appointment->patient->first_name . " " . $appointment->patient->last_name,
                    'patient_phone_number' => $appointment->patient->country_code . "" . $appointment->patient->phone_number,
                    'patient_address' => $appointment->patient->address,
                    'reason' => $appointment->reason,
                ];

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