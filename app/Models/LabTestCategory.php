<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestCategory extends Model
{
    use HasFactory;

    protected $table = 'lab_test_categories';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
