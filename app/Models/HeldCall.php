<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeldCall extends Model
{
    use HasFactory;
    protected $table = 'held_calls';


    protected $fillable = [
       'appointment_id', 'start_time', 'end_time', 'duration'
    ];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }

}
