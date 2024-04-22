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
        'phieu_id',
        'note',
        'status',
        'lop_id',
        'files',
        'type',
        'teacher_id',
        'is_update',
        'is_pay',
        'note_pay',
        'time_receive'
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
}
