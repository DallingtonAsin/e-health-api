<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    use HasFactory;

    protected $table = 'doctor_ratings';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'rating',
        'comment'

    ];

    public $timestamps = true;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
