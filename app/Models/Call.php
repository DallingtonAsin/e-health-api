<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;

    protected $table = 'calls';

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'patient_duration',
        'doctor_duration',
    ];

    public $timestamps = true;

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
