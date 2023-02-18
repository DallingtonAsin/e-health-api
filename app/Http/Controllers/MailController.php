<?php

namespace App\Http\Controllers;

use App\Services\email\EmailService;

class MailController extends Controller
{

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendPendingAppointmentMail()
    {

        try {
            $this->emailService->sendPendingAppointmentMail();
            return response()->json('Mails sent successfully', 200);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
    }

}
