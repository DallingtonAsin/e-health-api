<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAppointment extends Model
{
    use HasFactory;

    protected $table = 'medical_appointments';

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_number', 'appointment_type_id', 'appointment_date', 'reason', 'notes',
        'status', 'confirmed_at', 'is_doctor_notified', 'alert_status', 'is_draft'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(MedicalDoctor::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    public function isOnline()
    {
        $appointment_type_name = $this->appointmentType->name;
        return stripos($appointment_type_name, 'audio') !== false || stripos($appointment_type_name, 'video') !== false;
    }

    public function isVideo()
    {
        $appointment_type_name = $this->appointmentType->name;
        return stripos($appointment_type_name, 'video') !== false;
    }

    public function meetingAccess()
    {
        return $this->hasOne(MeetingToken::class, 'appointment_id');
    }

    public function patientMedicalHistory()
    {
        return $this->hasOne(PatientMedicalHistory::class, 'appointment_id');
    }

    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class, 'appointment_id');
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class, 'appointment_id');
    }

    public function imageTests()
    {
        return $this->hasMany(imageTest::class, 'appointment_id');
    }

    public function otherTests()
    {
        return $this->hasOne(otherTest::class, 'appointment_id');
    }

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class, 'appointment_id');
    }

    public function diagnosisComments()
    {
        return $this->hasOne(DiagnosisComment::class, 'appointment_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id');
    }

    public function treatmentPlan()
    {
        return $this->hasOne(TreatmentPlan::class, 'appointment_id');
    }

    public function labTestDocuments()
    {
        return $this->hasMany(LabTestDocument::class, 'appointment_id');
    }
}
