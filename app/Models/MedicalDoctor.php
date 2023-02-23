<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DoctorAvailability;
use App\Models\UserType;


class MedicalDoctor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'medical_doctors';


    protected $fillable = [
       'user_type_id', 'first_name', 'last_name', 'specialty_id',
        'title', 'country_code', 'phone_number', 'email', 'address', 'gender', 'qualification',
        'profession', 'dob', 'languages', 'experience', 'image', 'service_fee', 'ip_address', 'current_version', 'unique_device_id',
        'fcm_token', 'otp', 'is_blocked', 'profile_status'
    ];

    public $timestamps = true;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'languages' => 'array',
    ];


    public function availability()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function isPatient()
    {
        $user_type_name = $this->userType->name;
        return stripos($user_type_name, 'patient') !== false;
    }

    // public function getImageAttribute($value)
    // {
    //     if ($value) {
    //         return url('storage/' . $value);
    //     }
    //     return null;
    // }

    public function thumbnail()
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
        return null;
    }
    
}