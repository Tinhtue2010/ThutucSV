<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;
    protected $table = 'tuitions';

    protected $fillable = [
        'id',
        'student_id',
        'ky_hoc',
        'nam_hoc',
        'hoc_phi',
    ];
}
