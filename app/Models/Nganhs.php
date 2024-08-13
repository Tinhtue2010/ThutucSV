<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nganhs extends Model
{
    use HasFactory;

    protected $fillable = ["tennganh","manganh","hedaotao","khoa_id"];


    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'khoa_id');
    }
}
