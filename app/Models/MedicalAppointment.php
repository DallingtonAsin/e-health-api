<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\MedicalDoctor;
use App\Models\AppointmentType;
use App\Models\MeetingToken;
use App\Models\MedicalHistory;

class MedicalAppointment extends Model
{
    use HasFactory;

    protected $table = 'medical_appointments';

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_number', 'appointment_type_id', 'appointment_date', 'reason', 'notes', 'is_doctor_notified', 'alert_status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(MedicalDoctor::class);
    }

    public function appointmentType(){
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    public function isOnline(){
        $appointment_type_name = $this->appointmentType->name;
        return stripos($appointment_type_name, 'audio') !== false || stripos($appointment_type_name, 'video') !== false;
    }

    public function isVideo(){
        $appointment_type_name = $this->appointmentType->name;
        return stripos($appointment_type_name, 'video') !== false;
    }

    public function meetingAccess()
    {
        return $this->hasOne(MeetingToken::class, 'appointment_id');
    }

    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class, 'appointment_id');
    }

}
