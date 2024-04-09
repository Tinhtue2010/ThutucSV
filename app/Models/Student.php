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
        'sum_point',
        'he_tuyen_sinh',
        'nganh_tuyen_sinh',
        'trinh_do',
        'ngay_nhap_hoc',
        'gv_tiep_nhan',
        'gv_thu_tien',
        'so_tien',
        'status_dk',
        'note',
        'cmnd',
        'date_range_cmnd'
    ];

    public function lop()
    {
        return $this->belongsTo(Lop::class, 'lop_id');
    }
}
