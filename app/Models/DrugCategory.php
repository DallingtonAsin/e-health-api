<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Drug;

class DrugCategory extends Model
{
    use HasFactory;


    protected $table = 'drug_categories';


    protected $fillable = [ 'name' ];

    public $timestamps = true;


    public function drugs(){
        return $this->hasMany(Drug::class);
    }
}
