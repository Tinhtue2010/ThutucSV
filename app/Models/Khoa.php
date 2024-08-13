<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    public function nganhs()
    {
        return $this->hasMany(Nganhs::class, 'khoa_id');
    }
}
