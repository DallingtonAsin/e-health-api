<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcdCodeCategory extends Model
{
    use HasFactory;

    protected $table = 'icd10_categories';

    protected $fillable = [
        'code',
        'title'
    ];

    public $timestamps = true;
}
