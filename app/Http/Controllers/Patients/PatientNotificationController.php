<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Repositories\Notifications\NotificationRepository;

class PatientNotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotifications()
    {
        try {

            $patient_id = auth('patient')->user()->id;
            $notifications = $this->notificationRepository->getPatientNotifications($patient_id);
            $stats = $this->notificationRepository->getPatientNotificationsCountStats($patient_id);
            $data['notifications'] = $notifications;
            $data['stats'] = $stats;

            return response()->json($data, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getReadNotifications()
    {
        try {
            $patient_id = auth('patient')->user()->id;
            $notifications = $this->notificationRepository->getPatientReadNotifications($patient_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getUnReadNotifications()
    {
        try {

            $patient_id = auth('patient')->user()->id;
            $notifications = $this->notificationRepository->getPatientUnReadNotifications($patient_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function markAsRead($notification_id)
    {
        try {
            $patient_id = auth('patient')->user()->id;
            $this->notificationRepository->markPatientNotificationAsRead($patient_id, $notification_id);
            return response()->json(['message' => 'Notification marked as read.'], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
