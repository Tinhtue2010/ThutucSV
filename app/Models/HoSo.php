<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'file_list',
        'file_quyet_dinh',
        'stop_studie_id',
        'list_info',
        'ky_hoc',
        'nam_hoc',
        'type'
    ];

    protected $casts = [
        'list_info' => 'array',
    ];

    public function stopStudy()
    {
        return $this->belongsTo(StopStudy::class, 'stop_studie_id');
    }
}
