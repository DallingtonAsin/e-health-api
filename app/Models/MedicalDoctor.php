<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DoctorAvailability;
use App\Models\DoctorIdentificationDocument;
use App\Models\UserType;


class MedicalDoctor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'medical_doctors';


    protected $fillable = [
        'user_type_id',
        'first_name',
        'last_name',
        'specialty_id',
        'country_code',
        'phone_number',
        'email',
        'dob',
        'address',
        'gender',
        'qualification',
        'primary_facility_id',
        'other_facilities',
        'training_institute',
        'umdp_license_id',
        'bio_summary',
        'service_fee',
        'password',
        'ip_address',
        'current_version',
        'unique_device_id',
        'fcm_token',
        'otp',
        'image',
        'profile_status',
        'is_registered',
        'is_verified',
        'is_online',
        'is_blocked'
    ];

    public $timestamps = true;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'other_facilities' => 'array',
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

    public function identificationDocument()
    {
        return $this->hasOne(DoctorIdentificationDocument::class, 'doctor_id');
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

    public function favouriteByPatients()
    {
        return $this->belongsToMany(Patient::class, 'patient_favourite_doctors', 'doctor_id', 'patient_id')->withTimestamps();
    }
}
