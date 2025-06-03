<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MienPhiKyTucXa extends Model
{
    use HasFactory;

    protected $table = 'mien_phi_ky_tuc_xas';

    protected $fillable = [
        'ho_so_id',
        'status',
        'file_name',
        'student_code',
        'doi_tuong',
        'ngay_vao_o',
        'so_thang',
    ];

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class);
    }
}
