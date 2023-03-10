<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\NotificationRepository;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;


class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getPatientNotifications($patient_id)
    {
        try {
            $notifications = $this->notificationRepository->getPatientNotifications($patient_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientReadNotifications($patient_id)
    {
        try {

            $notifications = $this->notificationRepository->getPatientReadNotifications($patient_id);
            return response()->json($notifications, 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getPatientUnReadNotifications($patient_id)
    {
        try {

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
            $this->notificationRepository->markAsRead($patient_id, $notification_id);
            return response()->json(['message' => 'Notification marked as read.'], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}
