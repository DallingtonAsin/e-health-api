<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DoctorAvailability;
use App\Models\UserType;


class MedicalDoctor extends Model
{
    use HasFactory;

    protected $table = 'medical_doctors';


    protected $fillable = [
        'first_name', 'last_name', 'specialty_id', 'title', 'phone_number',
        'email', 'qualification', 'profession', 'languages', 'experience', 'image', 'service_fee'
    ];

     /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'languages' => 'array',
    ];

    public $timestamps = true;

    public function availability()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    public function userType(){
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function isPatient(){
        $user_type_name = $this->userType->name;
        return stripos($user_type_name, 'patient') !== false;
    }

}
