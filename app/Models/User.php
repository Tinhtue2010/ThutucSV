<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CommonHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'student_id',
        'teacher_id',
        'avatar',
        'cccd'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function gmail()
    {
        $email = "";
        try {
            if ($this->teacher_id != null) {
                $email = Teacher::find($this->teacher_id)->email;
            } else {
                $email = Student::find($this->student_id)->email;
            }
        } catch (\Throwable $th) {
            return $email;
        }

        return $email;
    }

    function getUrlChuKy()
    {
        $url = "";
        try {
            if ($this->teacher_id != null) {
                $url = Teacher::find($this->teacher_id)->chu_ky;
            } else {
                $url = Student::find($this->student_id)->chu_ky;
            }
        } catch (\Throwable $th) {
            return $url;
        }

        return $url;
    }
    function checkNganhCheDoChinhSach()
    {
        if ($this->teacher_id != null) {
            return false;
        } else {
            $ma_lop = Student::find($this->student_id)->ma_lop;
            $lop = Lop::where('ma_lop',$ma_lop)->first();
            if(isset($lop))
            {
                $list_nganh = [
                    "7810103",
                    "7810201",
                    "7810202",
                    "7220209",
                    "7220210",
                    "7220204",
                    "7620301",
                    "7480101" // cntt demo test
                ];            
                if (in_array($lop->nganh_id, $list_nganh)) {
                    return true;
                }
            }

        }
        return false;
    }
}
