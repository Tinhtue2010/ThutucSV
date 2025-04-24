<?php

namespace App\Http\Controllers\TroCapXaHoi;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TroCapXaHoiPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('phong_dao_tao.create_ds_tro_cap_xa_hoi.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 2)
        ->studentActive()
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

    function updateTroCap(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $stopStudy->muctrocapxh =  $request->muctrocapxh;
        $stopStudy->save();
        return;
    }
    function createList(Request $request)
    {
        $query = StopStudy::where('type', 2)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->select('stop_studies.*')
            ->whereNull('parent_id')
            ->update(['status' => 2]);
        $username = Auth::user()->name;
        $users = User::where('role',6)->get();
        foreach($users as $item)
        {
            $this->notification("Danh sách miễn giảm học phí đã được tạo bởi ".$username, null, "TCXH", $item->id);
        }
        return redirect()->back();
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 2)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->where(function($query) {
            $query->where('stop_studies.status', 1)
                  ->orWhere('stop_studies.status', 2);
        })
        ->select('stop_studies.*')
        ->whereNull('parent_id')
        ->update(['status' => 1]);

        return redirect()->back();
    }

    function guiTBSV() {
        $query = StopStudy::where('type', 2)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')
        ->where('stop_studies.status', 3)
        ->select('stop_studies.*')
        ->get();
        $phieu = Phieu::where('key','DSTCXH')->orderBy('created_at', 'desc')->first();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 4; 
            $stopStudy->save();  
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Danh sách trợ cấp xã hội dự kiến", $phieu->id, "TCXH", $user_id);
            
            $users = User::where(function($query) {
                $query->where('role', 2)
                      ->orWhere('role', 3);
            })->get();
            foreach($users as $item)
            {
                $this->notification("Danh sách trợ cấp xã hội dự kiến", $phieu->id, "TCXH", $item->id);
            }
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Gửi thông báo về danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
    function guiTBSALL()
    {
        
        $phieu = Phieu::where('key','DSTCXH')->orderBy('created_at', 'desc')->first();

        $users = User::get();
        foreach ($users as $item) {
            $this->notification("Danh sách trợ cấp xã hội", $phieu->id, "TCXH", $item->id);
        }


        $phieu = Phieu::where('status', 0)->update(['status' => 1]);
        $query = StopStudy::where('type', 2)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')
        ->where('stop_studies.status', 6)
        ->select('stop_studies.*')
        ->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -99;
            $stopStudy->save();
        }
        return redirect()->back();
    }
    function createQuyetDinh(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->thoi_gian_tao,);

        $day = $date->day;
        $month = $date->month;
        $year = $date->year;
        $content = [
            "so_QD" => $request->so_QD,
            "thoi_gian_tao_ngay" => $day,
            "thoi_gian_tao_thang" => $month,
            "thoi_gian_tao_nam" => $year,
            "tom_tat" => $request->tom_tat ?? "",
            "nam" => $request->nam,
            "ky" => $request->ky,
            "tong" => 0,
        ];

        $content_DSTCXH = [];
        
        $query = StopStudy::where('type', 2)
        ->where('stop_studies.status','>=',1)
        ->studentActive()
        ->whereNull('parent_id')
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
        ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi')
        ->get();
        
        $content["tong_hs"] = count($query);

        foreach($query as $item){
            $content_DSTCXH[] =  [
                "ho_ten" => $item->full_name,
                "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                "lop" => $item->lop_name,
                "doi_tuong" => 'Đối tượng '.$item->type_miengiamhp + 1,
                "muc_tro_cap_xh" => $item->muctrocapxh,
                "so_thang_tro_cap" => 6,
                "tro_cap_ky" =>6*$item->muctrocapxh
              ];
              $content["tong"] += 6*$item->muctrocapxh;
        }

        $phieu =  Phieu::updateOrCreate(
            [
                'key' => 'DSTCXH',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được trợ cấp xã hội',
                'content' => json_encode([$content,$content_DSTCXH], true)
            ]
        );
        Phieu::updateOrCreate(
            [
                'key' => 'QDTCXH',
                'status' => 0
            ],
            [
                'name' => 'Quyết định trợ cấp xã hội',
                'content' => json_encode([$content,0], true)
            ]
        );
        Phieu::updateOrCreate(
            [
                'key' => 'PTTCXH',
                'status' => 0
            ],
            [
                'name' => 'Phiếu trình trợ cấp xã hội',
                'content' => json_encode([$content,0], true)
            ]
        );

        return true;
    }
    function xoaQuyetDinh() {
        Phieu::where('status',0)
        ->whereIn('key', ['DSTCXH', 'QDTCXH', 'PTTCXH'])
        ->delete();
        return redirect()->back();
    }

    function getQuyetDinh(Request $request)
    {
        $phieu =  Phieu::where('key','DSTCXH')->where('status',0)->first();
        if($phieu)
        {
            return json_decode($phieu->content,true)[0];
        }
        return [];
    }
}
