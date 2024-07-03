<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'avatar'
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
            $lop_id = Student::find($this->student_id)->lop_id;
            $nganh = Lop::find($lop_id)->nganh;
            $list_nganh = [
                "Quản trị dịch vụ du lịch và lữ hành",
                "Quản trị Khách sạn",
                "Quản trị Nhà hàng và Dịch vụ ăn uống",
                "Ngôn ngữ Nhật",
                "Ngôn ngữ Hàn Quốc",
                "Ngôn ngữ Trung Quốc",
                "Nuôi trồng thủy sản",
            ];
            if (in_array($nganh, $list_nganh)) {
                return true;
            }
        }
        return false;
    }
}
