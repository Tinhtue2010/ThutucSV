<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoTroTienAn extends Model
{
    use HasFactory;
    protected $table = 'ho_tro_tien_ans';
    protected $fillable = [
        'ho_so_id',
        'status',
        'file_name',
        'student_code',
        'doi_tuong',
        'so_tien_giam_1_thang',
        'so_thang',
    ];

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class);
    }
}
