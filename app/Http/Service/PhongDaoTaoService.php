<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PhongDaoTaoService extends Controller
{

    function bosunghsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }

        if ($stopStudy->status == 3 || $stopStudy->status == -4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 2]);
                } catch (\Exception $e) {
                }
            }
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 2]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);
        $content_phieu['bosunggiayto'] = $request->bosunggiayto ?? '';
        $content_phieu['kekhailaigiayto'] = $request->kekhailaigiayto ?? '';
        $content_phieu['huongdankhac'] = $request->huongdankhac ?? '';
        $content_phieu['lydo'] = $request->lydo ?? '';

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';
        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn cần bổ sung hồ sơ", null, "RHS",$user_id);

        // if ($stopStudy->type == 0) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
        //     $this->notification("Đơn xin rút hồ sơ của bạn cần bổ sung hồ sơ", null, "RHS");
        // }
        // if ($stopStudy->type == 1) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
        //     $this->notification("Đơn xin miễn giảm học phí của bạn cần bổ sung hồ sơ", null, "GHP");
        // }
        // if ($stopStudy->type == 2) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
        //     $this->notification("Đơn xin trợ cấp xã hội của bạn cần bổ sung hồ sơ", null, "TCXH");
        // }

        // if ($stopStudy->type == 3) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
        //     $this->notification("Đơn xin chế độ chính sách của bạn cần bổ sung hồ sơ", null, "CDCS");
        // }

        if ($stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();
            $stopStudy->update(["status" => -3, "is_update" => 0]);
            return $phieu->id;
        }
        if ($stopStudy->status == -3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -3, "is_update" => 0]);
            return $phieu->id;
        }

        abort(404);
    }
    function bosunghsGHP($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        if ($stopStudy->status == 1 || $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            }
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);
        $content_phieu['bosunggiayto'] = $request->bosunggiayto ?? '';
        $content_phieu['kekhailaigiayto'] = $request->kekhailaigiayto ?? '';
        $content_phieu['huongdankhac'] = $request->huongdankhac ?? '';
        $content_phieu['lydo'] = $request->lydo ?? '';

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';
        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn cần bổ sung hồ sơ", null, "GHP",$user_id);

        if ($stopStudy->status == 0  || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();
            $stopStudy->update(["status" => -1, "is_update" => 0]);
            return $phieu->id;
        }
        if ($stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -1, "is_update" => 0]);
            return $phieu->id;
        }

        abort(404);
    }
    function bosunghsTCXH($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        if ($stopStudy->status == 1 || $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            }
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);
        $content_phieu['bosunggiayto'] = $request->bosunggiayto ?? '';
        $content_phieu['kekhailaigiayto'] = $request->kekhailaigiayto ?? '';
        $content_phieu['huongdankhac'] = $request->huongdankhac ?? '';
        $content_phieu['lydo'] = $request->lydo ?? '';

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';
        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn cần bổ sung hồ sơ", null, "GHP",$user_id);

        if ($stopStudy->status == 0  || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();
            $stopStudy->update(["status" => -1, "is_update" => 0]);
            return $phieu->id;
        }
        if ($stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -1, "is_update" => 0]);
            return $phieu->id;
        }

        abort(404);
    }
    function tiepnhanhsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }

        if ($stopStudy->status == -3 || $stopStudy->status == -4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 2]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == 3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 2]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantiepnhan));
        $content_phieu['tiepnhan_day'] = date('d', $timestamp);
        $content_phieu['tiepnhan_month'] = date('m', $timestamp);
        $content_phieu['tiepnhan_year'] = date('Y', $timestamp);
        $content_phieu['tiepnhan_gio'] = date('H', $timestamp);
        $content_phieu['tiepnhan_phut'] = date('i', $timestamp);

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantraketqua));
        $content_phieu['ketqua_day'] = date('d', $timestamp);
        $content_phieu['ketqua_month'] = date('m', $timestamp);
        $content_phieu['ketqua_year'] = date('Y', $timestamp);
        $content_phieu['ketqua_gio'] = date('H', $timestamp);
        $content_phieu['ketqua_phut'] = date('i', $timestamp);

        $tmp = [];
        foreach ($request->tengiayto as $index => $item) {
            $tmp[] = [
                "tengiayto" => $request->tengiayto[$index] ?? '',
                "hinhthuc" => $request->hinhthuc[$index] ?? '',
                "ghichu" => $request->ghichu[$index] ?? '',
            ];
        }
        $content_phieu['bang'] = $tmp;

        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "RHS",$user_id);


        if ($stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu tiếp nhận hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => 3]);
            return $phieu->id;
        }
        if ($stopStudy->status == 3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => 3]);
            return $phieu->id;
        }

        abort(404);
    }
    function tiepnhanhsGHP($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }

        if ($stopStudy->status == -1 || $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == 1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantiepnhan));
        $content_phieu['tiepnhan_day'] = date('d', $timestamp);
        $content_phieu['tiepnhan_month'] = date('m', $timestamp);
        $content_phieu['tiepnhan_year'] = date('Y', $timestamp);
        $content_phieu['tiepnhan_gio'] = date('H', $timestamp);
        $content_phieu['tiepnhan_phut'] = date('i', $timestamp);

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantraketqua));
        $content_phieu['ketqua_day'] = date('d', $timestamp);
        $content_phieu['ketqua_month'] = date('m', $timestamp);
        $content_phieu['ketqua_year'] = date('Y', $timestamp);
        $content_phieu['ketqua_gio'] = date('H', $timestamp);
        $content_phieu['ketqua_phut'] = date('i', $timestamp);

        $tmp = [];
        foreach ($request->tengiayto as $index => $item) {
            $tmp[] = [
                "tengiayto" => $request->tengiayto[$index] ?? '',
                "hinhthuc" => $request->hinhthuc[$index] ?? '',
                "ghichu" => $request->ghichu[$index] ?? '',
            ];
        }
        $content_phieu['bang'] = $tmp;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
        
        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP",$user_id);


        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu tiếp nhận hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }
        if ($stopStudy->status == 0) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }

        abort(404);
    }
    function tiepnhanhsTCXH($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }

        if ($stopStudy->status == -1 || $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == 1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);

                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantiepnhan));
        $content_phieu['tiepnhan_day'] = date('d', $timestamp);
        $content_phieu['tiepnhan_month'] = date('m', $timestamp);
        $content_phieu['tiepnhan_year'] = date('Y', $timestamp);
        $content_phieu['tiepnhan_gio'] = date('H', $timestamp);
        $content_phieu['tiepnhan_phut'] = date('i', $timestamp);

        $timestamp = strtotime(str_replace('/', '-', $request->thoigiantraketqua));
        $content_phieu['ketqua_day'] = date('d', $timestamp);
        $content_phieu['ketqua_month'] = date('m', $timestamp);
        $content_phieu['ketqua_year'] = date('Y', $timestamp);
        $content_phieu['ketqua_gio'] = date('H', $timestamp);
        $content_phieu['ketqua_phut'] = date('i', $timestamp);

        $tmp = [];
        foreach ($request->tengiayto as $index => $item) {
            $tmp[] = [
                "tengiayto" => $request->tengiayto[$index] ?? '',
                "hinhthuc" => $request->hinhthuc[$index] ?? '',
                "ghichu" => $request->ghichu[$index] ?? '',
            ];
        }
        $content_phieu['bang'] = $tmp;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
        
        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP",$user_id);


        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu tiếp nhận hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }
        if ($stopStudy->status == 0) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "TNHS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }

        abort(404);
    }
    function tuchoihsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != 3 && $stopStudy->status != -3 && $stopStudy->status != -4 && $stopStudy->status != 4) {
            abort(404);
        }
        if ($stopStudy->status == -3 || $stopStudy->status == 3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 3]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 3]);
                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo ", null, "RHS",$user_id);

        // if ($stopStudy->type == 0) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
        //     $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo ", null, "RHS");
        // }
        // if ($stopStudy->type == 1) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
        //     $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP");
        // }
        // if ($stopStudy->type == 2) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
        //     $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi phòng đào tạo ", null, "TCXH");
        // }

        // if ($stopStudy->type == 3) {
        //     $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
        //     $this->notification("Đơn xin chế độ chính sách của bạn bị từ chối bởi phòng đào tạo ", null, "CDCS");
        // }



        if ($stopStudy->status == 3 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã bị từ chối bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => -4]);
            return $phieu->id;
        }
        if ($stopStudy->status == -4 || $stopStudy->status == 4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -4]);
            return $phieu->id;
        }

        abort(404);
    }
    function tuchoihsGHP($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        if ($stopStudy->status == -1 || $stopStudy->status == 1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP",$user_id);

        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã bị từ chối bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => -2]);
            return $phieu->id;
        }
        if ($stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -2]);
            return $phieu->id;
        }

        abort(404);
    }
    function tuchoihsTCXH($request, $stopStudy)
    {
        if($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6)
        {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        if ($stopStudy->status == -1 || $stopStudy->status == 1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 0]);
                    return true;
                } catch (\Exception $e) {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        if ($request->button_clicked == "huy_phieu") {
            return true;
        }
        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi phòng đào tạo ", null, "GHP",$user_id);

        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = $phieu->id;
            $newStopStudy->parent_id = $request->id;
            $newStopStudy->note = "Đã bị từ chối bởi phòng đào tạo";
            $newStopStudy->save();
            $stopStudy->update(["status" => -2]);
            return $phieu->id;
        }
        if ($stopStudy->status == -2) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -2]);
            return $phieu->id;
        }

        abort(404);
    }
    function duyethsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 3 && $stopStudy->status != -4 && $stopStudy->status != 4) {
            abort(404);
        }
        if ($stopStudy->status == 4) {
            return true;
        }

        if ($stopStudy->status == -4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                } catch (\Exception $e) {
                }
            } else {
            }
        }

        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn đang chờ cán bộ phòng CTSV xác nhận", null, "RHS",$stopStudy->student_id,$user_id);

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->phieu_id = null;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 4]);
        return true;


        abort(404);
    }
    function duyethsGHP($request, $stopStudy)
    {
        if ($stopStudy->status != 1) {
            abort(404);
        }

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->phieu_id = null;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 2]);
        return true;


        abort(404);
    }
}
