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
        'student_id',
        'date_of_birth',
        'phone',
        'email',
        'lop_id',
        'school_year',
        'ngay_nhap_hoc',
        'note',
        'cmnd',
        'date_range_cmnd',
        'gioitinh',
        'status',
        'file_name'
    ];

    public function lop()
    {
        return $this->belongsTo(Lop::class, 'lop_id');
    }
}
