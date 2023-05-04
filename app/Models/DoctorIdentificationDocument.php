<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MedicalDoctor;

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

    public function medicalDoctor()
    {
        return $this->belongsTo(MedicalDoctor::class);
    }

    public function getFrontPathAttribute()
    {
        if ($this->front) {
            return url('storage/' . $this->front);
        }
        return null;
    }

    public function getBackPathAttribute()
    {
        if ($this->back) {
            return url('storage/' . $this->back);
        }
        return null;
    }
}
