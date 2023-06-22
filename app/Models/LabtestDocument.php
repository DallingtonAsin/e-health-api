<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabtestDocument extends Model
{
    use HasFactory;

    protected $table = 'labtest_documents';

    protected $fillable = [
        'appointment_id',
        'file_path',
        'type',
        'is_image'
    ];

    public $timestamps = true;

    public function filePath()
    {
        if ($this->file_path) {
            return url('storage/' . $this->file_path);
        }
        return null;
    }

}
