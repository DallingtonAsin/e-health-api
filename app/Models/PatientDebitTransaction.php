<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDebitTransaction extends Model
{
    use HasFactory;

    protected $table = 'patient_debit_transactions';

    protected $fillable = [
        'patient_id',
        'amount',
        'transaction_id',
        'note',
        'date'
    ];

    public $timestamps = true;
}
