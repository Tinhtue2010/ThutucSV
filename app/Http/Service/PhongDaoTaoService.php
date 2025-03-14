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
    function bosunghsRHSPDF($request, $stopStudy)
    {

        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
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
        $user = Auth::user();
        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0;
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
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;
        $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());
        $content_phieu['chu_ky'] = $chu_ky;



        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $phieu = new Phieu();
        $phieu->student_id = $stopStudy->student_id;
        $phieu->teacher_id = $user->teacher_id;
        $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
        $phieu->key = "HDBSSH";
        $phieu->content = json_encode($content_phieu);

        $pdf =  $this->createPDF($phieu);
        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'BO_SUNG_HS_RHS_SV_' . $student->student_code);
    }

    function bosunghsRHS($request, $stopStudy)
    {

        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }
        $user = Auth::user();
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);

        if ($getPDF === 0) {
            return 0;
        }

        $file_name = $this->saveBase64AsPdf($getPDF, 'BO_SUNG_HS_RHS');

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn cần bổ sung hồ sơ", null, "RHS", $user_id);

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 0;
        $newStopStudy->teacher_id = $user->teacher_id;
        $newStopStudy->file_name = $file_name;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
        $newStopStudy->save();
        $stopStudy->update(["status" => -3, "is_update" => 0]);
        return;
    }


    
    function bosunghsGHPPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        $user = Auth::user();
        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0;
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
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
        $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());
        $content_phieu['chu_ky'] = $chu_ky;
        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn cần bổ sung hồ sơ", null, "GHP", $user_id);

        $phieu = new Phieu();
        $phieu->student_id = $stopStudy->student_id;
        $phieu->teacher_id = Auth::user()->teacher_id;
        $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
        $phieu->key = "HDBSSH";
        $phieu->content = json_encode($content_phieu);

        $pdf =  $this->createPDF($phieu);
        // return $file_name = $this->saveBase64AsPdf($pdf,'DEMO');

        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'BO_SUNG_HS_RHS_SV_' . $student->student_code);
    }
    function bosunghsGHP($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn cần bổ sung hồ sơ", null, "GHP", $user_id);

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
    function bosunghsTCXHPDF($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
            $stopStudy->update(["status" => 0]);
        }
        if ($stopStudy->status != 0 && $stopStudy->status != -1 && $stopStudy->status != 1 && $stopStudy->status != 2 && $stopStudy->status != -2) {
            abort(404);
        }
        $user = Auth::user();
        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0;
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -1) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
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
        $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());
        $content_phieu['chu_ky'] = $chu_ky;
        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";

        $phieu = new Phieu();
        $phieu->student_id = $stopStudy->student_id;
        $phieu->teacher_id = Auth::user()->teacher_id;
        $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
        $phieu->key = "HDBSSH";
        $phieu->content = json_encode($content_phieu);
        $pdf = $this->createPDF($phieu);
        return $this->craeteSignatur($info_signature, $pdf, $user->cccd, 'BO_SUNG_HS_RHS_SV_' . $student->student_code);
    }
    function bosunghsTCXH($request, $stopStudy)
    {
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
        if ($getPDF === 0) {
            return 0;
        }
        $file_name = $this->saveBase64AsPdf($getPDF, 'RUT_HO_SO');

        $this->deletePdfAndTmp($stopStudy->file_name);


        $content_phieu['day'] = Carbon::now()->day;
        $content_phieu['month'] = Carbon::now()->month;
        $content_phieu['year'] = Carbon::now()->year;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn cần bổ sung hồ sơ", null, "GHP", $user_id);



        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 0;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->file_name = $file_name;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Yêu cầu bổ sung hồ sơ";
        $newStopStudy->save();
        $stopStudy->update(["status" => -1, "is_update" => 0]);
        return;
    }
    function tiepnhanhsRHSPDF($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }

        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == 3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
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

        $user = Auth::user();
        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0;
        }

        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
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
        $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());
        $content_phieu['chu_ky'] = $chu_ky;

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


        $phieu = new Phieu();
        $phieu->student_id = $stopStudy->student_id;
        $phieu->teacher_id = Auth::user()->teacher_id;
        $phieu->name = "Phiếu tiếp nhận hồ sơ";
        $phieu->key = "TNHS";
        $phieu->content = json_encode($content_phieu);

        $pdf =  $this->createPDF($phieu);
        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'TIEP_NHAN_HS_RHS_SV_' . $student->student_code);
    }
    function tiepnhanhsRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
        }
        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
        if ($getPDF === 0) {
            return 0;
        }
        $file_name = $this->saveBase64AsPdf($getPDF, 'RUT_HO_SO');

        $this->deletePdfAndTmp($stopStudy->file_name);


        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "RHS", $user_id);



        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->file_name = $file_name;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đã được nhận bởi phòng đào tạo";
        $newStopStudy->save();
        $stopStudy->update(["status" => 3]);


        return;
    }
    function tiepnhanhsGHP($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
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

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP", $user_id);


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
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
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

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP", $user_id);


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
    function tiepnhanhsTCHP($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
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

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP", $user_id);


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
    function tiepnhanhsCDCS($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
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

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP", $user_id);


        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu tiếp nhận hồ sơ";
            $phieu->key = "CDCS";
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
            $phieu->key = "CDCS";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => 1]);
            return $phieu->id;
        }

        abort(404);
    }
    function tuchoihsRHSPDF($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != 3 && $stopStudy->status != -3 && $stopStudy->status != -4 && $stopStudy->status != 4) {
            abort(404);
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -4) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
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
        $user = Auth::user();

        $info_signature = $this->getInfoSignature($user->cccd);
        if ($info_signature === false) {
            return 0; //chưa đăng ký chữ ký số cần đăng ký chữ ký số
        }
        $chu_ky =  $this->convertImageToBase64($user->getUrlChuKy());


        $student = Student::find($stopStudy->student_id);

        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content_phieu['giaovien'] = $teacher->full_name ?? '';
        $content_phieu['sinhvien'] = $student->full_name ?? '';
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
        $content_phieu['chu_ky'] = $chu_ky;


        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo ", null, "RHS", $user_id);


        $phieu = new Phieu();
        $phieu->student_id = $stopStudy->student_id;
        $phieu->teacher_id = Auth::user()->teacher_id;
        $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
        $phieu->key = "TCGQ";
        $phieu->content = json_encode($content_phieu);
        $pdf =  $this->createPDF($phieu);
        // $file_name = $this->saveBase64AsPdf($pdf,'TU_CHOI_GIAI_QUYET');

        return $this->craeteSignature($info_signature, $pdf, $user->cccd, 'TU_CHOI_GIAI_QUYET_RHS_SV_' . $student->student_code);
    }

    function tuchoihsRHS($request, $stopStudy)
    {

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo ", null, "RHS", $user_id);



        $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);

        if ($getPDF === 0) {
            return 0;
        }

        $file_name = $this->saveBase64AsPdf($getPDF, 'TU_CHOI_GIAI_QUYET_RHS');


        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 0;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->file_name = $file_name;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đã bị từ chối bởi phòng đào tạo";
        $newStopStudy->save();
        $stopStudy->update(["status" => -4]);
        return $file_name;
    }
    function tuchoihsGHP($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP", $user_id);

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
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi phòng đào tạo ", null, "GHP", $user_id);

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
    function tuchoihsTCHP($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP", $user_id);

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
    function tuchoihsCDCS($request, $stopStudy)
    {
        if ($stopStudy->status == -3 || $stopStudy->status == -5 || $stopStudy->status == -6) {
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
        $content_phieu['chuky'] = $teacher->chu_ky ?? '';
        $content_phieu['cmnd'] = $student->cmnd ?? '';
        $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
        $content_phieu['sdt'] = $student->phone ?? '';
        $content_phieu['email'] = $student->email ?? '';

        if ($student->date_range_cmnd == null) {
            $content_phieu['ngaycap'] = '';
        } else {
            $date = substr($student->date_range_cmnd, 0, 10);
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $content_phieu['ngaycap'] = $formattedDate;
        }

        $content_phieu['tao_day'] = Carbon::now()->day;
        $content_phieu['tao_month'] = Carbon::now()->month;
        $content_phieu['tao_year'] = Carbon::now()->year;

        $content_phieu['lydo'] = $request->lydo;

        $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp học phí";

        $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
        $this->notification("Đơn xin trợ cấp học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP", $user_id);

        if ($stopStudy->status == 0 || $stopStudy->status == 2) {

            $phieu = new Phieu();
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "CDCS";
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
            $phieu->key = "CDCS";
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


        $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";

        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 1;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->note = "Đang chờ cán bộ phòng CTSV xác nhận";
        $newStopStudy->save();
        $stopStudy->update(["status" => 4]);
        return true;
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
    function duyethsTCXH($request, $stopStudy)
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
