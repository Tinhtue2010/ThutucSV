<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TroCapXaHoi extends Model
{
    use HasFactory;
    protected $table = 'tro_cap_xa_hois';

    protected $fillable = [
        'ho_so_id',
        'status',
        'file_name',
        'student_code',
        'doi_tuong',
        'muc_tro_cap_xh',
        'so_thang',
    ];

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class);
    }
}
