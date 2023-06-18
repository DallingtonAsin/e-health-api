<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    use HasFactory;

    protected $table = 'company_information';

    protected $fillable = [
        'name',
        'mobile_phone_no',
        'sms_phone_no',
        'whatsapp_number',
        'email'
    ];

    public $timestamps = true;
}
