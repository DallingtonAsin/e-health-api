<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAuthentication extends Model
{
    use HasFactory;

    protected $table = 'doctor_authentication';


    protected $fillable = [
       'country_code', 'phone_code', 'auth_code'
    ];

    public $timestamps = true;
}
