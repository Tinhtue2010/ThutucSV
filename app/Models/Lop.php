<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lop extends Model
{
    use HasFactory;

    protected $table = 'lops';

    protected $fillable = ['name', 'ma_lop', 'ma_khoa', 'nganh_id', 'teacher_id', 'hocphi'];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'khoa_id');
    }
}
