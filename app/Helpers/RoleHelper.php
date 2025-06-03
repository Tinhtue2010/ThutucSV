<?php

use App\Models\Student;
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
            "financial_planning_division" => 5, // 5 phòng kế hoạch tài chính
            "student_union_leader" => 6, // 6 lãnh đạo ctsv
            "schoolLeader" => 7, // 7 phó hiệu trưởng
            "school_leader_0" => 8, // 8 hiệu trưởng
        ];

        if (Auth::check()) {
            $user = Auth::user();
            switch ($role) {
                case "studentActive":
                    $student = Student::find($user->student_id);
                    if ($student->status == 0) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case "notStudent":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "giaoVien":
                    if ($user->role == 2 || $user->role == 3) {
                        return true;
                    }
                    break;
                case "studentManager":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "scoreManager":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "scoreCalculate":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "tuitionManager":
                    if ($user->role != 1) {
                        return true;
                    }
                    break;
                case "khoaManager":
                    if ($user->role == 0 || $user->role == 4 || $user->role == 6) {
                        return true;
                    }
                    break;
                case "classManager":
                    if ($user->role == 0 || $user->role == 6 || $user->role == 4) {
                        return true;
                    }
                    break;
                case "teacherManager":
                    if ($user->role == 0 || $user->role == 4 || $user->role == 6) {
                        return true;
                    }
                    break;
                case "schoolLeader":
                    if ($user->role == 0 || $user->role == 4 || $user->role == 6) {
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

if (!function_exists('numberInVietnameseCurrency')) {
    function numInWords($num)
    {
        $nwords = array(
            0                   => 'không',
            1                   => 'một',
            2                   => 'hai',
            3                   => 'ba',
            4                   => 'bốn',
            5                   => 'năm',
            6                   => 'sáu',
            7                   => 'bảy',
            8                   => 'tám',
            9                   => 'chín',
            10                  => 'mười',
            11                  => 'mười một',
            12                  => 'mười hai',
            13                  => 'mười ba',
            14                  => 'mười bốn',
            15                  => 'mười lăm',
            16                  => 'mười sáu',
            17                  => 'mười bảy',
            18                  => 'mười tám',
            19                  => 'mười chín',
            20                  => 'hai mươi',
            30                  => 'ba mươi',
            40                  => 'bốn mươi',
            50                  => 'năm mươi',
            60                  => 'sáu mươi',
            70                  => 'bảy mươi',
            80                  => 'tám mươi',
            90                  => 'chín mươi',
            100                 => 'trăm',
            1000                => 'nghìn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ',
        );
        $separate = ' ';
        $negative = ' âm ';
        $rltTen   = ' linh ';
        $decimal  = ' phẩy ';
        if (!is_numeric($num)) {
            $w = '#';
        } else if ($num < 0) {
            $w = $negative . numInWords(abs($num));
        } else {
            if (fmod($num, 1) != 0) {
                $numInstr    = strval($num);
                $numInstrArr = explode(".", $numInstr);
                $w           = numInWords(intval($numInstrArr[0])) . $decimal . numInWords(intval($numInstrArr[1]));
            } else {
                $w = '';
                if ($num < 21) // 0 to 20
                {
                    $w .= $nwords[$num];
                } else if ($num < 100) {
                    // 21 to 99
                    $w .= $nwords[10 * floor($num / 10)];
                    $r = fmod($num, 10);
                    if ($r > 0) {
                        $w .= $separate . $nwords[$r];
                    }
                } else if ($num < 1000) {
                    // 100 to 999
                    $w .= $nwords[floor($num / 100)] . $separate . $nwords[100];
                    $r = fmod($num, 100);
                    if ($r > 0) {
                        if ($r < 10) {
                            $w .= $rltTen . $separate . numInWords($r);
                        } else {
                            $w .= $separate . numInWords($r);
                        }
                    }
                } else {
                    $baseUnit     = pow(1000, floor(log($num, 1000)));
                    $numBaseUnits = (int) ($num / $baseUnit);
                    $r            = fmod($num, $baseUnit);
                    if ($r == 0) {
                        $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit];
                    } else {
                        if ($r < 100) {
                            if ($r >= 10) {
                                $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm ' . numInWords($r);
                            } else {
                                $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm linh ' . numInWords($r);
                            }
                        } else {
                            $baseUnitInstr      = strval($baseUnit);
                            $rInstr             = strval($r);
                            $lenOfBaseUnitInstr = strlen($baseUnitInstr);
                            $lenOfRInstr        = strlen($rInstr);
                            if (($lenOfBaseUnitInstr - 1) != $lenOfRInstr) {
                                $numberOfZero = $lenOfBaseUnitInstr - $lenOfRInstr - 1;
                                if ($numberOfZero == 2) {
                                    $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm linh ' . numInWords($r);
                                } else if ($numberOfZero == 1) {
                                    $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . ' không trăm ' . numInWords($r);
                                } else {
                                    $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . $separate . numInWords($r);
                                }
                            } else {
                                $w = numInWords($numBaseUnits) . $separate . $nwords[$baseUnit] . $separate . numInWords($r);
                            }
                        }
                    }
                }
            }
        }
        return $w;
    }

    function numberInVietnameseWords($num)
    {
        return str_replace("mươi năm", "mươi lăm", str_replace("mươi một", "mươi mốt", numInWords($num)));
    }

    function numberInVietnameseCurrency($num)
    {
        $rs    = numberInVietnameseWords($num);
        $rs[0] = strtoupper($rs[0]);
        return $rs . ' đồng';
    }
}
