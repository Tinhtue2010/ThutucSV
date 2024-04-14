<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApproveController extends Controller
{
    function index() {
        
        return view('approve.index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
        ->whereNull('parent_id')
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
        ->select('stop_studies.*','students.full_name','lops.name as lop_name');

        if(Role(2))
        {
            $lopIds = Lop::where('teacher_id', $user->teacher_id)->pluck('id');
            $query = $query->whereIn('stop_studies.lop_id', $lopIds);
        }
        if(Role(3))
        {
            $teacher = Teacher::where('id',$user->teacher_id)->first();
            $lopIds = Lop::where('khoa_id', $teacher->khoa_id)->pluck('id');
            $query = $query->whereIn('stop_studies.lop_id', $lopIds);
        }
        if(isset($request->year))
        {
            $query->whereYear('created_at', $request->year);
        }
        if(isset($request->status))
        {
            $query->whereYear('status', $request->status);
        }
        $data = $this->queryPagination($request, $query, []);

        return $data;
    }

    function xacnhan($id = null) {
        try {
            $stopStudy =  StopStudy::find($id);
            $stopStudy->update(["status"=>$stopStudy->status +1]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->parent_id = $id;
            
            if(Role(2))
            {
                $this->notification("Đơn xin rút của bạn đã được giáo viên chủ nhiệm xác nhận", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Giáo viên đã chấp nhận đơn";
            }
            if(Role(3))
            {
                $this->notification("Đơn xin rút của bạn đã được khoa xác nhận", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Khoa đã xác nhận đơn";
            }
            if(Role(4))
            {
                $this->notification("Đơn xin rút của bạn đã được phòng đào tạo xác nhận", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Phòng đào tạo đã xác nhận đơn";
            }
            if(Role(5))
            {
                $this->notification("Đơn xin rút của bạn đã được lãnh đạo phòng đào tạo xác nhận", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Lãnh đạo phòng đào đã xác nhận đơn";
            }
            if(Role(6))
            {
                $this->notification("Đơn xin rút của bạn đã được nhà trường xác nhận", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Nhà trường đã xác nhận đơn";
            }

            $newStopStudy->save();


        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhan($id = null) {
        try {
            $stopStudy =  StopStudy::find($id);
            $stopStudy->update(["status"=>-1]);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->parent_id = $id;
            
            if(Role(2))
            {
                $this->notification("Đơn xin rút của bạn đã bị từ chối bởi giáo viên chủ nhiệm", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Giáo viên từ chối đơn";
            }
            if(Role(3))
            {
                $this->notification("Đơn xin rút của bạn đã bị từ chối bởi khoa", $stopStudy->phieu_id, "RHS");
                $newStopStudy->note = "Khoa từ chối đơn";
            }

            $newStopStudy->save();


        } catch (QueryException $e) {
            abort(404);
        }
    }

    function viewPdf($id) {
        // try {
            $stopStudy =  StopStudy::find($id);
            if ($stopStudy) {

                $data = Phieu::where('id', $stopStudy->phieu_id)->first();
                return view('document.thoi_hoc', ['data' => json_decode($data->content, true)]);
            } else {
                abort(404);
            }
        // } catch (\Throwable $th) {
        //     abort(404);
        // }
    }
}
