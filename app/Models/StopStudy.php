<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
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
        'km',
        'file_name'
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
