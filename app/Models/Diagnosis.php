<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $table = 'diagnoses';

    protected $fillable = ['appointment_id', 'icd_code_id', 'diagnosis_date'];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }

    public function Icd10Code()
    {
        return $this->belongsTo(ICDCode::class, 'icd_code_id');
    }
}
