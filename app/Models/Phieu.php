<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phieu extends Model
{    
    use HasFactory;
    protected $fillable = [
        'content',
        'student_id',
        'teacher_id',
        'name',
        'key',
        'status'
    ];
}
