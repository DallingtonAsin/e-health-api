<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MedicalAppointment;

class MeetingToken extends Model
{
    use HasFactory;

    protected $table = 'meeting_tokens';


    protected $fillable = ['appointment_id', 'app_id', 'channel', 'token'];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }
}
