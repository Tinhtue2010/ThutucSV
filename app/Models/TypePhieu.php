<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePhieu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'key',
        'slug_name',
        'display_name',
    ];
}
