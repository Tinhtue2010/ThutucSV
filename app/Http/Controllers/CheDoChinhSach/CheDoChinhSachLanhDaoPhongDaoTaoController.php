<?php

namespace App\Http\Controllers\CheDoChinhSach;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\StopStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheDoChinhSachLanhDaoPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('lanh_dao_phong_dao_tao.ds_che_do_chinh_sach.index', ['lop' => $lop]);
    }


    function getData(Request $request)
    {
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi');


        if (isset($request->type_miengiamhp)) {
            $query->where('stop_studies.type_miengiamhp', $request->type_miengiamhp);
        }
        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function xacnhan(Request $request) {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')->where(function($query) {
            $query->where('stop_studies.status', 2)
                  ->orWhere('stop_studies.status', 3)
                  ->orWhere('stop_studies.status', -3);
        })->select('stop_studies.*')->get();
        foreach ($query as $stopStudy) {
            $this->giaiQuyetCongViec($request->ykientiepnhan, $stopStudy,3);
            $stopStudy->status = 3; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            // $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Cán bộ phòng đào tạo duyệt danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }

    function tuchoi() {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')->where(function($query) {
            $query->where('stop_studies.status', 2)
                  ->orWhere('stop_studies.status', 3)
                  ->orWhere('stop_studies.status', -3);
        })->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -3; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            // $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Cán bộ phòng đào tạo từ chối danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
}
