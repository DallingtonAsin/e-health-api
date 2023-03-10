<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification
{
    use Queueable;

    protected $appointment, $message;

    public function __construct($appointment, $message = null)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = 'Your medical appointment has been cancelled.';
        if ($this->message) {
            $message = $this->message;
        }

        return [
            'appointment_id' => $this->appointment->id,
            'message' => $message
        ];
    }
}