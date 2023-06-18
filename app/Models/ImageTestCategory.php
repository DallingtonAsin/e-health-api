<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTestCategory extends Model
{
    use HasFactory;

    protected $table = 'image_test_categories';

    protected $fillable = [
        'code',
        'name',
        'category'
    ];

    public $timestamps = true;
}
