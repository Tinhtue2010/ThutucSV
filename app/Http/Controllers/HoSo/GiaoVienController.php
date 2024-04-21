<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiaoVienController extends Controller
{
    function index()
    {

        return view('giao_vien.index');
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
            $query->whereYear('status', $request->status);
        }
        if(isset($request->type))
        {
            $query->where('type', $request->type);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function xacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->status > 0) {
                abort(404);
            }
            $stopStudy->update(["status" => 1]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;

            if($stopStudy->type == 0)
            {
                $this->notification("Đơn xin rút hồ sơ của bạn đã được giáo viên chủ nhiệm xác nhận", null, "RHS");
            }
            if($stopStudy->type == 1)
            {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã được giáo viên chủ nhiệm xác nhận", null, "GHP");
            }
            if($stopStudy->type == 2)
            {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã được giáo viên chủ nhiệm xác nhận", null, "TCXH");
            }
            
            if($stopStudy->type == 3)
            {
                $this->notification("Đơn xin chế độ chính sách của bạn đã được giáo viên chủ nhiệm xác nhận", null, "CDCS");
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
            if ($stopStudy->status > 0) {
                abort(404);
            }
            $stopStudy->update(["status" => -1]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $request->id;

            if($stopStudy->type == 0)
            {
                $this->notification("Đơn xin rút hồ sơ của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "RHS");
            }
            if($stopStudy->type == 1)
            {
                $this->notification("Đơn xin miễn giảm học phí của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "GHP");
            }
            if($stopStudy->type == 2)
            {
                $this->notification("Đơn xin trợ cấp xã hội của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "TCXH");
            }
            
            if($stopStudy->type == 3)
            {
                $this->notification("Đơn xin chế độ chính sách của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "CDCS");
            }
            $this->notification("Đơn xin rút của bạn đã bị từ chối bởi giáo viên chủ nhiệm", null, "RHS");
            $newStopStudy->note = $request->note;


            $newStopStudy->save();
        } catch (QueryException $e) {
            abort(404);
        }
    }


    function getDataChild($id = null)
    {
        try {
            $don = StopStudy::where('id', $id)->first();
            $don_chill =  StopStudy::where('parent_id', $id)
            ->leftJoin('teachers', 'teachers.id', '=', 'stop_studies.teacher_id')
            ->select('stop_studies.*','teachers.full_name','teachers.chuc_danh')
            ->orderBy('created_at', 'desc')->get();
            $data[] = json_decode($don->files ?? '[]');
            $data[] = $don_chill;
            $data[] = $don->phieu_id;
            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
