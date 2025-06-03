<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_code',
        'ma_lop',
        'name',
        'round',
        'parent_id',
        'note',
        'status',
        'lop_id',
        'files',
        'type',
        'teacher_id',
        'is_update',
        'is_pay',
        'note_pay',
        'nam_hoc',
        'ky_hoc',
        'time_receive',
        'type_miengiamhp',
        'phantramgiam',
        'tiepnhan',
        'ykien',
        'lanhdaophong',
        'lanhdaotruong',
        'muchotrohp',
        'muctrocapxh',
        'doi_tuong_chinh_sach',
        'che_do_chinh_sach_data',
        'diachi',
        'noi_thuong_tru',
        'km',
        'file_name',
        'ly_do',
        'phieu_id',
        'is_giam_hp',
        'is_ky_tuc_xa',
        'is_tien_an',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function parent()
    {
        return $this->belongsTo(StopStudy::class, 'parent_id');
    }

    public function phieu()
    {
        return $this->belongsTo(Phieu::class, 'phieu_id');
    }

    public function scopeStudentActive($query) {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query->where('stop_studies.type', '!=', 0)
                    ->where('students.status', 0);
            })
                ->orWhere(function ($query) {
                    $query->where('stop_studies.type', 0)
                        ->where('students.status', '!=', 2);
                });
        });
    }
}
