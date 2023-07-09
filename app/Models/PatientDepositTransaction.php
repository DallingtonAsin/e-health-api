<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDepositTransaction extends Model
{
    use HasFactory;

    protected $table = 'patient_deposit_transactions';

    protected $fillable = [
        'patient_id',
        'amount',
        'transaction_date',
        'payment_method',
        'note',
        'request_id',
        'status',
        'status_code',
        'transaction_id',
        'description',
        'error_message',
        'ip_address',
        'date'
    ];

    public $timestamps = true;
}
