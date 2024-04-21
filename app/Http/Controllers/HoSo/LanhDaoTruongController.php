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

class LanhDaoTruongController extends Controller
{
    function index()
    {

        return view('lanh_dao_truong.index');
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
            $query->whereYear('stop_studies.created_at', $request->year);
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
            if ($stopStudy->status != 7 && $stopStudy->status != -8 && $stopStudy->status != 8) {
                abort(404);
            }
            if ($stopStudy->status == -8) {
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

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;

            if($stopStudy->type == 0)
            {
                $this->notification("Đơn xin rút hồ sơ của bạn đã được lãnh đạo phòng CTSV xác nhận", null, "RHS");
            }
            if($stopStudy->type == 1)
            {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã được lãnh đạo phòng CTSV xác nhận", null, "GHP");
            }
            if($stopStudy->type == 2)
            {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã được lãnh đạo phòng CTSV xác nhận", null, "TCXH");
            }
            
            if($stopStudy->type == 3)
            {
                $this->notification("Đơn xin chế độ chính sách của bạn đã được lãnh đạo phòng CTSV xác nhận", null, "CDCS");
            }

            $newStopStudy->note = $request->note;


            $newStopStudy->save();
            $stopStudy->update(["status" => 8]);
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->status != 4 && $stopStudy->status != 5) {
                abort(404);
            }
            $stopStudy->update(["status" => -5]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $request->id;

            if($stopStudy->type == 0)
            {
                $this->notification("Đơn xin rút hồ sơ của bạn đã bị từ chối bởi phòng kế hoạch tài chính", null, "RHS");
            }
            if($stopStudy->type == 1)
            {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã bị từ chối bởi phòng kế hoạch tài chính", null, "GHP");
            }
            if($stopStudy->type == 2)
            {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã bị từ chối bởi phòng kế hoạch tài chính", null, "TCXH");
            }
            
            if($stopStudy->type == 3)
            {
                $this->notification("Đơn xin chế độ chính sách của bạn đã bị từ chối bởi phòng kế hoạch tài chính", null, "CDCS");
            }
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi phòng kế hoạch tài chính", null, "RHS");
            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function tuchoihs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->status != 7 && $stopStudy->status != -8 && $stopStudy->status != 8) {
            abort(404);
        }
        if ($stopStudy->status == 8) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                $newStopStudy->delete();
                $stopStudy->update(["status" => 7]);
            } else {
            }
        }
        if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -8) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            if ($newStopStudy) {
                try {
                    $phieu = Phieu::find($newStopStudy->phieu_id);
                    if ($phieu) {
                        $phieu->delete();
                    }
                    $newStopStudy->delete();
                    $stopStudy->update(["status" => 7]);

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
            $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi lãnh đạo trường", null, "RHS");
        }
        if ($stopStudy->type == 1) {
            $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi lãnh đạo trường", null, "GHP");
        }
        if ($stopStudy->type == 2) {
            $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi lãnh đạo trường", null, "TCXH");
        }

        if ($stopStudy->type == 3) {
            $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            $this->notification("Đơn xin chế độ chính sách của bạn bị từ chối bởi lãnh đạo trường", null, "CDCS");
        }

        if ($stopStudy->status == 7) {

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
            $newStopStudy->note = "Đã bị từ chối bởi lãnh đạo trường";
            $newStopStudy->save();
            $stopStudy->update(["status" => -8]);
            return $phieu->id;
        }
        if ($stopStudy->status == -8) {
            $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->note = "Đã bị từ chối bởi lãnh đạo trường";
            $newStopStudy->save();

            $phieu = Phieu::find($newStopStudy->phieu_id);
            $phieu->student_id = $stopStudy->student_id;
            $phieu->teacher_id = Auth::user()->teacher_id;
            $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
            $phieu->key = "TCGQ";
            $phieu->content = json_encode($content_phieu);
            $phieu->save();
            $stopStudy->update(["status" => -8]);
            return $phieu->id;
        }

        abort(404);
    }
}
