<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'khoa_id','nganh'];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'khoa_id');
    }
}
