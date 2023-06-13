<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ICDCode extends Model
{
    use HasFactory;

    protected $table = 'icd_10_codes';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
