<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DrugCategory;

class Drug extends Model
{
    use HasFactory;

    protected $table = 'drugs';


    protected $fillable = [
       'name', 'description', 'price', 'image', 'status'
    ];

    public $timestamps = true;

    public function category(){
        return $this->belongsTo(DrugCategory::class, 'category_id');
    }

    public function isInStock(){
        return stripos($this->status, 'in stock') !== false;
    }

}
