<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSo extends Model
{
    use HasFactory;

    protected $table = 'ho_sos';

    protected $fillable = [
        'name',
        'so_quyet_dinh',
        'ngay_quyet_dinh',
        'type',
        'file_quyet_dinh',
        'stop_studie_id',
        'student_code',
        'doi_tuong',
        'ma_lop',
        'nam_hoc',
        'ky_hoc',
        'status',
        'round',
        'note',
        'is_update',
        'is_pay',
        'note_pay',
        'time_receive',
        'type_miengiamhp',
        'tiep_nhan',
        'y_kien',

    ];

    public function stopStudy()
    {
        return $this->belongsTo(StopStudy::class, 'stop_studie_id');
    }
    public function scopeStudentActive($query)
    {
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
