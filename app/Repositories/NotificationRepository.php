<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

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

    public function markAsRead($patient_id, $notification_id)
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
}
