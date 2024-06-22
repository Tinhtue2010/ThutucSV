<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chukys extends Model
{
    use HasFactory;
    protected $fillable = ['teacher_id','student_id','phieu_id'];
}
