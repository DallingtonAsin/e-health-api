<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFacility extends Model
{
    use HasFactory;

    protected $table = 'medical_facilities';

    protected $fillable = [
        'district',
        'name'
    ];

    public $timestamps = true;
}
