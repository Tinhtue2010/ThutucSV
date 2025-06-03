<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TroCapHocPhi extends Model
{
    use HasFactory;

    protected $table = 'tro_cap_hoc_phis';

    protected $fillable = [
        'ho_so_id',
        'status',
        'file_name',
        'student_code',
        'doi_tuong',
        'diem_ht',
        'diem_rl',
        'xep_loai',
        'ti_le_ho_tro',
        'so_tien_giam_1_thang',
        'so_thang',
    ];

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class);
    }
}
