<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;

class DoctorNotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotifications()
    {
        try {

            $doctor_id = auth('doctor')->user()->id;
            $notifications = $this->notificationRepository->getDoctorNotifications($doctor_id);
            $stats = $this->notificationRepository->getDoctorNotificationsCountStats($doctor_id);
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
            $doctor_id = auth('doctor')->user()->id;
            $notifications = $this->notificationRepository->getDoctorReadNotifications($doctor_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getUnReadNotifications()
    {
        try {

            $doctor_id = auth('doctor')->user()->id;
            $notifications = $this->notificationRepository->getDoctorUnReadNotifications($doctor_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function markAsRead($notification_id)
    {
        try {
            $doctor_id = auth('doctor')->user()->id;
            $this->notificationRepository->markDoctorNotificationAsRead($doctor_id, $notification_id);
            return response()->json(['message' => 'Notification marked as read.'], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}