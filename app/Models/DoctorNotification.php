<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class DoctorNotification extends Notification
{
   protected $table = 'doctor_notifications';

   protected $fillable = ['id', 'type', 'notifiable_type', 'notifiable_id', 'data'];
}
