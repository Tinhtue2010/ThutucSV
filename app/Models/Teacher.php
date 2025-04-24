<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'full_name',
        'ma_khoa',
        'dia_chi',
        'sdt',
        'email',
        'chuc_danh',
        'chu_ky',
    ];

    
    // public function teacher(){

    // }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
