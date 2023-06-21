<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'appointment_id', 'drug_id', 'dosage', 'admin_route_id', 'duration', 'quantity', 'instructions'
    ];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }

    public function adminRoute()
    {
        return $this->belongsTo(MedicalAdministrationRoute::class, 'admin_route_id');
    }
}
