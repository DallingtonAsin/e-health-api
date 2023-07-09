<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestCategory extends Model
{
    use HasFactory;

    protected $table = 'labtest_categories';

    protected $fillable = [
        'code',
        'name',
        'report_name',
        'category',
        'low_range',
        'high_range',
        'units'
    ];

    public $timestamps = true;

    public function labTests()
    {
        return $this->hasMany(LabTest::class, 'labtest_category_id');
    }
}
