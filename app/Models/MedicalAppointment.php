<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MedicalDoctor;
use App\Models\AppointmentType;

class MedicalAppointment extends Model
{
    use HasFactory;

    protected $table = 'medical_appointments';

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_number', 'appointment_type_id', 'appointment_date', 'symptoms', 'notes'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class);
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
}
