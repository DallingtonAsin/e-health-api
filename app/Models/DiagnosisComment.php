<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisComment extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_comments';

    protected $fillable = [
        'appointment_id',
        'comments'
    ];

    public $timestamps = true;
}