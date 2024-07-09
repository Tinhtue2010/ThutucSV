<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'full_name',
        'khoa_id',
        'dia_chi',
        'sdt',
        'email',
        'chuc_danh',
        'chu_ky'
    ];
    
    // public function teacher(){

    // }
}
