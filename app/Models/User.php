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
                "quan_tri_dich_vu_du_lich_va_lu_hanh",
                "quan_tri_khach_san",
                "quan_tri_nha_hang_va_dich_vu_an_uong",
                "ngon_ngu_nhat",
                "ngon_ngu_han_quoc",
                "ngon_ngu_trung_quoc",
                "nuoi_trong_thuy_san",
            ];            
            if (in_array($this->convertVietnamese($nganh), $list_nganh)) {
                return true;
            }
        }
        return false;
    }
}
