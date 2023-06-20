<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    use HasFactory;

    protected $table = 'treatment_plans';

    protected $fillable = ['appointment_id', 'treatment_plan'];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }
}
