<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'student_code',
        'date_of_birth',
        'phone',
        'email',
        'ma_lop',
        'nien_khoa',
        'khoa_hoc',
        'trinh_do',
        'note',
        'cmnd',
        'date_range_cmnd',
        'gioitinh',
        'status',
        'chu_ky',
        'hocphi'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_range_cmnd' => 'date',
        'gioitinh' => 'boolean',
        'status' => 'boolean',
    ];


    public function lop()
    {
        return $this->belongsTo(Lop::class, 'lop_id');
    }
}
