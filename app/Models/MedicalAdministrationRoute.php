<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAdministrationRoute extends Model
{
    use HasFactory;

    protected $table = 'medical_administration_routes';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;
    
}
