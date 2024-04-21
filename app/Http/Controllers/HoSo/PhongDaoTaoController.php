<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhongDaoTaoController extends Controller
{
    function index()
    {

        return view('phong_dao_tao.index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name');

        $lopIds = Lop::where('teacher_id', $user->teacher_id)->pluck('id');
        $query = $query->whereIn('stop_studies.lop_id', $lopIds);

        if (isset($request->year)) {
            $query->whereYear('created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        if (isset($request->type)) {
            $query->where('type', $request->type);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function xacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->status != 1) {
                abort(404);
            }
            $stopStudy->update(["status" => 2]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;

            if ($stopStudy->type == 0) {
                $this->notification("Đơn xin rút hồ sơ của bạn đã được cán bộ khoa xác nhận", null, "RHS");
            }
            if ($stopStudy->type == 1) {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã được cán bộ khoa xác nhận", null, "GHP");
            }
            if ($stopStudy->type == 2) {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã được cán bộ khoa xác nhận", null, "TCXH");
            }

            if ($stopStudy->type == 3) {
                $this->notification("Đơn xin chế độ chính sách của bạn đã được cán bộ khoa xác nhận", null, "CDCS");
            }

            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->status != 1) {
                abort(404);
            }
            $stopStudy->update(["status" => -2]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $request->id;

            if ($stopStudy->type == 0) {
                $this->notification("Đơn xin rút hồ sơ của bạn đã bị từ chối bởi cán bộ khoa", null, "RHS");
            }
            if ($stopStudy->type == 1) {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã bị từ chối bởi cán bộ khoa", null, "GHP");
            }
            if ($stopStudy->type == 2) {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã bị từ chối bởi cán bộ khoa", null, "TCXH");
            }

            if ($stopStudy->type == 3) {
                $this->notification("Đơn xin chế độ chính sách của bạn đã bị từ chối bởi cán bộ khoa", null, "CDCS");
            }
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi cán bộ khoa", null, "RHS");
            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function bosunghs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);

        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3) {
            abort(404);
        }
        if ($stopStudy->status == 3) {
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

        if ($stopStudy->type == 0) {
            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            $this->notification("Đơn xin rút hồ sơ của bạn cần bổ xung hồ sơ", null, "RHS");
        }
        if ($stopStudy->type == 1) {
            $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            $this->notification("Đơn xin miễn giảm học phí của bạn cần bổ xung hồ sơ", null, "GHP");
        }
        if ($stopStudy->type == 2) {
            $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            $this->notification("Đơn xin trợ cấp xã hội của bạn cần bổ xung hồ sơ", null, "TCXH");
        }

        if ($stopStudy->type == 3) {
            $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            $this->notification("Đơn xin chế độ chính sách của bạn cần bổ xung hồ sơ", null, "CDCS");
        }

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
            $newStopStudy->note = "Yêu cầu bổ xung hồ sơ";
            $newStopStudy->save();
            $stopStudy->update(["status" => -3]);
            return $phieu->id;
        }
        if ($stopStudy->status == -3) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Yêu cầu bổ xung hồ sơ";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu hướng dẫn bổ sung hồ sơ";
            $phieu->key = "HDBSSH";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -3]);
            return $phieu->id;
        }

        abort(404);
    }
    function getbosunghs($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $stopStudy =  StopStudy::find($id);
        if (!$stopStudy) {
            abort(404);
        }
        if ($stopStudy->status != 2 && $stopStudy->status != -3  && $stopStudy->status != 3) {
            abort(404);
        }
        $newStopStudy = $stopStudy->where('parent_id', $stopStudy->id)->orderBy('created_at', 'desc')->first();
        $phieu = Phieu::find($newStopStudy->phieu_id);
        if (!$phieu) {
            abort(404);
        }
        return json_decode($phieu->content);
    }

    function tiepnhanhs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->status != 2 && $stopStudy->status != -3 && $stopStudy->status != 3) {
            abort(404);
        }
        if ($stopStudy->status == -3) {
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

        if ($stopStudy->type == 0) {
            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            $this->notification("Đơn xin rút hồ sơ của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "RHS");
        }
        if ($stopStudy->type == 1) {
            $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            $this->notification("Đơn xin miễn giảm học phí của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "GHP");
        }
        if ($stopStudy->type == 2) {
            $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            $this->notification("Đơn xin trợ cấp xã hội của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "TCXH");
        }

        if ($stopStudy->type == 3) {
            $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            $this->notification("Đơn xin chế độ chính sách của bạn đã được nhận bởi phòng đào tạo vui lòng chờ kết quả", null, "CDCS");
        }

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

    function gettiepnhanhs($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $stopStudy =  StopStudy::find($id);
        if (!$stopStudy) {
            abort(404);
        }
        if ($stopStudy->status != 2 && $stopStudy->status != -3) {
            abort(404);
        }
        $newStopStudy = $stopStudy->where('parent_id', $stopStudy->id)->orderBy('created_at', 'desc')->first();
        $phieu = Phieu::find($newStopStudy->phieu_id);
        if (!$phieu) {
            abort(404);
        }
        return json_decode($phieu->content);
    }

    function tuchoihs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->status != 3 && $stopStudy->status != -4 && $stopStudy->status != 4) {
            abort(404);
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


        if ($stopStudy->type == 0) {
            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi phòng đào tạo ", null, "RHS");
        }
        if ($stopStudy->type == 1) {
            $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi phòng đào tạo ", null, "GHP");
        }
        if ($stopStudy->type == 2) {
            $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi phòng đào tạo ", null, "TCXH");
        }

        if ($stopStudy->type == 3) {
            $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            $this->notification("Đơn xin chế độ chính sách của bạn bị từ chối bởi phòng đào tạo ", null, "CDCS");
        }

        if ($stopStudy->status == 3) {

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
        if ($stopStudy->status == -4) {
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

    function xacnhankinhphi(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type != 0) {
            abort(404);
        }
        if ($stopStudy->status != 3 && $stopStudy->status != -4) {
            abort(404);
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


        if ($stopStudy->type == 0) {
            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            $this->notification("Đơn xin rút hồ sơ của bạn đang được xác nhận kinh phí", null, "RHS");
        }
        if ($stopStudy->type == 1) {
            $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            $this->notification("Đơn xin miễn giảm học phí của bạn đang được xác nhận kinh phí", null, "GHP");
        }
        if ($stopStudy->type == 2) {
            $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            $this->notification("Đơn xin trợ cấp xã hội của bạn đang được xác nhận kinh phí", null, "TCXH");
        }

        if ($stopStudy->type == 3) {
            $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            $this->notification("Đơn xin chế độ chính sách của bạn đang được xác nhận kinh phí", null, "CDCS");
        }


        $newStopStudy = $stopStudy->replicate();
        $newStopStudy->status = 0;
        $newStopStudy->teacher_id = Auth::user()->teacher_id;
        $newStopStudy->parent_id = $request->id;
        $newStopStudy->phieu_id = null;
        $newStopStudy->note = "Đang xác nhận kinh phí";
        $newStopStudy->save();
        $stopStudy->update(["status" => 4]);
        return true;


        abort(404);
    }
}
