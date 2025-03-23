<?php

namespace App\Http\Controllers\TroCapHocPhi;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TroCapHocPhiLanhDaoTruongController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('lanh_dao_truong.ds_tro_cap_hoc_phi.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 3)
        ->studentActive()
        ->whereNull('parent_id')->whereNull('parent_id')
        ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi');

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
        $query = StopStudy::where('type', 3)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')->whereNull('parent_id')->where(function($query) {
            $query->where('stop_studies.status', 5)
                  ->orWhere('stop_studies.status', 6)
                  ->orWhere('stop_studies.status', -6);
        })
        ->select('stop_studies.*')
        ->get();


        $phieu = Phieu::where('key', 'QDTCHP')->where('status', 0)->first();

        $content = json_decode($phieu->content, true);
        $teacher = Teacher::find(Auth::user()->teacher_id);

        $content[0]['canbo_truong'] = $teacher->full_name;;
        $content[0]['canbo_truong_chu_ky'] = $teacher->chu_ky;
        $phieu->content = json_encode($content, true);
        $phieu->save();

        $phieu = Phieu::where('key', 'PTTCHP')->where('status', 0)->first();

        $content = json_decode($phieu->content, true);
        $content[0]['canbo_truong'] = $teacher->full_name;;
        $content[0]['canbo_truong_chu_ky'] = $teacher->chu_ky;
        $phieu->content = json_encode($content, true);
        $phieu->save();

        
        foreach ($query as $stopStudy) {
            $this->giaiQuyetCongViec($request->ykientiepnhan ?? '',$stopStudy,4);
            $stopStudy->status = 6; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Lãnh đạo trường đã phê duyệt danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }

    function tuchoi() {
        $query = StopStudy::where('type', 3)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')->where(function($query) {
            $query->where('stop_studies.status', 5)
                  ->orWhere('stop_studies.status', 6)
                  ->orWhere('stop_studies.status', -6);
        })
        ->select('stop_studies.*')
        ->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -6; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Lãnh đạo trường từ chối danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
}
