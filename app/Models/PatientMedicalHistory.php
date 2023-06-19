<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'patient_medical_history';

    protected $fillable = [
        'patient_id', 'appointment_id', 'past_medical_history', 'current_treatment', 'illness', 'diagnosis_date', 'treatment'
    ];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }
}
