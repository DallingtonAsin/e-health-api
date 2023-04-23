<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorIdentificationDocument extends Model
{
    use HasFactory;

    protected $table = 'doctor_identification_documents';
    protected $fillable = [
        'doctor_id',
        'front',
        'back'
    ];

    public $timestamps = true;
}
