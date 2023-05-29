<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AppointmentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment, $message, $title;

    public function __construct($appointment, $message = null)
    {
        $this->appointment = $appointment;
        $this->message = $message;
        $this->title = 'Confirmed Appointment';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = 'Your medical appointment has been completed.';
        if ($this->message) {
            $message = $this->message;
        }

        return [
            'title' => $this->title,
            'appointment_id' => $this->appointment->id,
            'appointment_number' => $this->appointment->appointment_number,
            'message' => $message
        ];
    }
}