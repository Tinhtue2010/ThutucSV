<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phieu extends Model
{    
    use HasFactory;
    protected $fillable = [
        'type_phieu_id',
        'content',
        'student_id',
        'teacher_id',
        'name',
        'key',
        'type'
    ];
}
