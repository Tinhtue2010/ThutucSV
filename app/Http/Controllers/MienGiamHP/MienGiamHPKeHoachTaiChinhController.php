<?php

namespace App\Http\Controllers\MienGiamHP;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MienGiamHPKeHoachTaiChinhController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('ke_hoach_tai_chinh.ds_mien_giam_hp.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 1)
        ->studentActive()
        ->whereNull('parent_id')->whereNull('parent_id')
        ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
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
        $query = StopStudy::where('type', 1)
        ->whereNull('parent_id')->whereNull('parent_id')->where(function($query) {
            $query->where('status', 4)
                  ->orWhere('status', 5)
                  ->orWhere('status', -5);
        })->get(); 
        $phieu = Phieu::where('key','PTGHP')->where('status',0)->first();
        $content = json_decode($phieu->content,true);
        $content[0]['y_kien_khtc'] = $request->ykientiepnhan;
        $phieu->content = json_encode($content,true);
        $phieu->save();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 5; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Phòng kết hoạch tài chính đã phê duyệt danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }

    function tuchoi() {
        $query = StopStudy::where('type', 1)
        ->whereNull('parent_id')->where(function($query) {
            $query->where('status', 4)
                  ->orWhere('status', 5)
                  ->orWhere('status', -5);
        })->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -5; 
            $stopStudy->save();  
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Phòng kết hoạch tài chính từ chối danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
}
