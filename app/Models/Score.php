<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';

    protected $fillable = [
        'id',
        'student_id',
        'ky_hoc',
        'nam_hoc',
        'diem_ht',
        'xep_loai_ht',
        'diem_rl',
        'xep_loai_rl',
        'xep_loai',
        'so_tc_ht',
    ];
}
