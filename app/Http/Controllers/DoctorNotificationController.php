<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SharedHelper as Helper;
use Illuminate\Support\Facades\Validator;
use App\Repositories\NotificationRepository;
use App\Services\PushNotificationService;

class DoctorNotificationController extends Controller
{
    protected $notificationRepository, $pushNotificationService;

    public function __construct(NotificationRepository $notificationRepository, PushNotificationService $pushNotificationService)
    {
        $this->notificationRepository = $notificationRepository;
        $this->pushNotificationService = $pushNotificationService;
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


    public function sendTestPushNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'title' => 'required',
            'body' => 'required'
        ]);

        try {
            if ($validator->fails()) {
                $message = $validator->errors()->all();
                return Helper::sendFailedHttpResponse($message);
            } else {

                $fcmToken = $request->fcm_token;
                $title = $request->title;
                $body = $request->body;
                $response = $this->pushNotificationService->sendPushNotification($fcmToken, $title, $body);
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
