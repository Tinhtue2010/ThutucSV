<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MienGiamHocPhi extends Model
{
    use HasFactory;
    protected $table = 'mien_giam_hoc_phis';

    protected $fillable = [
        'ho_so_id',
        'status',
        'file_name',
        'student_code',
        'doi_tuong',
        'muc_hoc_phi',
        'ti_le_giam',
        'so_tien_giam_1_thang',
        'so_thang',
    ];

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class);
    }
}
