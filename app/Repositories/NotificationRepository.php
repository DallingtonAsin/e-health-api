<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Models\MedicalDoctor;


class NotificationRepository
{

    private function findPatient($patient_id)
    {
        return Patient::findOrFail($patient_id);
    }

    public function getPatientNotifications($patient_id)
    {
        $patient = $this->findPatient($patient_id);
        $notifications = $patient->notifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getPatientReadNotifications($patient_id)
    {
        $patient = $this->findPatient($patient_id);
        $notifications = $patient->readNotifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getPatientUnreadNotifications($patient_id)
    {
        $patient = $this->findPatient($patient_id);
        $notifications = $patient->unreadNotifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getPatientNotificationsCountStats($patient_id)
    {
        $patient = $this->findPatient($patient_id);
        $total = $patient->notifications()->count();
        $readCount = $patient->readNotifications()->count();
        $unreadCount = $patient->unreadNotifications()->count();

        $stats = [
            'total' => $total,
            'readCount' => $readCount,
            'unreadCount' => $unreadCount
        ];

        return $stats;
    }

    public function markPatientNotificationAsRead($patient_id, $notification_id)
    {
        try {

            $patient = $this->findPatient($patient_id);
            $notification = $patient->notifications()->find($notification_id);
            if ($notification && $notification->exists) {
                $notification->markAsRead();
            } else {
                abort(404, 'Notification not found');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    private function findDoctor($doctor_id)
    {
        return MedicalDoctor::findOrFail($doctor_id);
    }

    public function getDoctorNotifications($doctor_id)
    {
        $doctor = $this->findDoctor($doctor_id);
        $notifications = $doctor->notifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getDoctorReadNotifications($doctor_id)
    {
        $doctor = $this->findDoctor($doctor_id);
        $notifications = $doctor->readNotifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getDoctorUnreadNotifications($doctor_id)
    {
        $doctor = $this->findDoctor($doctor_id);
        $notifications = $doctor->unreadNotifications()->select(['id', 'notifiable_id', 'data', 'read_at'])->get();
        return $notifications;
    }

    public function getDoctorNotificationsCountStats($doctor_id)
    {
        $doctor = $this->findDoctor($doctor_id);
        $total = $doctor->notifications()->count();
        $readCount = $doctor->readNotifications()->count();
        $unreadCount = $doctor->unreadNotifications()->count();

        $stats = [
            'total' => $total,
            'readCount' => $readCount,
            'unreadCount' => $unreadCount
        ];

        return $stats;
    }

    public function markDoctorNotificationAsRead($doctor_id, $notification_id)
    {
        try {

            $doctor = $this->findDoctor($doctor_id);
            $notification = $doctor->notifications()->find($notification_id);
            if ($notification && $notification->exists) {
                $notification->markAsRead();
            } else {
                abort(404, 'Notification not found');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
