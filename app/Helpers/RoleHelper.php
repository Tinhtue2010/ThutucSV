<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('Role')) {
    function Role($role)
    {
        $roleNames = [
            "admin" => 0, // 0 admin
            "student" => 1, // 1 học sinh
            "teacher" => 2, // 2 giáo viên
            "department_leader" => 3, // 3 lãnh đạo khoa
            "student_affairs_office" => 4, // 4 phòng công tác hssv
            "student_union_leader" => 5, // 5 lãnh đạo ctsv
            "school_leader" => 6, // 6 lãnh đạo trường
        ];

        if (Auth::check()) {
            $user = Auth::user();
            switch ($role) {
                case "notStudent":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "studentManager":
                    if ($user->role == 0 || $user->role == 4 || $user->role == 5) {
                        return true;
                    }
                    break;
                case "khoaManager":
                    if ($user->role == 0 || $user->role == 3 || $user->role == 6) {
                        return true;
                    }
                    break;
                case "classManager":
                    if ($user->role == 0 || $user->role == 2 || $user->role == 4) {
                        return true;
                    }
                    break;
                case "teacherManager":
                    if ($user->role == 0 || $user->role == 3 || $user->role == 6) {
                        return true;
                    }
                    break;
                        
            }

            if ($user->role == $role) {
                return true;
            }
            if (!isset($roleNames[$role])) {
                return false;
            }
            if ($user->role == $roleNames[$role]) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
}
