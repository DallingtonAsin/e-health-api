<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserType;


class Patient extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "patients";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type_id',
        'first_name',
        'last_name',
        'country_code',
        'phone_number',
        'email',
        'address',
        'gender',
        'dob',
        'ip_address',
        'current_version',
        'unique_device_id',
        'fcm_token',
        'otp',
        'image',
        'profile_status',
        'is_verified',
        'is_blocked'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
