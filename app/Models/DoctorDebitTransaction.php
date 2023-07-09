<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDebitTransaction extends Model
{
    use HasFactory;

    protected $table = 'doctor_debit_transactions';

    protected $fillable = [
        'doctor_id',
        'amount',
        'note',
        'date'
    ];

    public $timestamps = true;
}
