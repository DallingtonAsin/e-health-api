<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MedicalAppointment;

class AppointmentType extends Model
{
    use HasFactory;

    protected $table = 'appointment_types';

    protected $fillable = [
        'name'
    ];

    public function appointments()
    {
        return $this->hasMany(MedicalAppointment::class);
    }
}
