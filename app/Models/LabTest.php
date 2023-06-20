<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    protected $table = 'lab_tests';

    protected $fillable = ['appointment_id', 'labtest_category_id', 'findings'];

    public $timestamps = true;

    public function appointment()
    {
        return $this->belongsTo(MedicalAppointment::class, 'appointment_id');
    }
}
