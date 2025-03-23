<?php

namespace App\Http\Controllers\MienGiamHP;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MienGiamHPPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('phong_dao_tao.create_ds_mien_giam_hp.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name');

        if (isset($request->type_miengiamhp)) {
            $query->where('stop_studies.type_miengiamhp', $request->type_miengiamhp);
        }
        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('stop_studies.status', $request->status);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function updatePercent(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $stopStudy->phantramgiam =  $request->phantramgiam;
        $stopStudy->save();
        return;
    }
    function createList(Request $request)
    {
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->update(['status' => 2]);
        $username = Auth::user()->name;
        $users = User::where('role', 6)->get();
        foreach ($users as $item) {
            $this->notification("Danh sách miễn giảm học phí đã được tạo bởi " . $username, null, "GHP", $item->id);
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
            "50" => 0,
            "70" => 0,
            "100" => 0,
            "50_tong" => 0,
            "70_tong" => 0,
            "100_tong" => 0,
        ];

        $content_DSMGHP = [];
        
        $query = StopStudy::where('type', 1)
        ->where('stop_studies.status','>=',1)
        ->studentActive()
        ->whereNull('parent_id')
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
        ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi')
        ->get();
        
        $content["tong_hs"] = count($query);

        $miengiamhp = config('doituong.miengiamhp');
        foreach($query as $item){
            $tien_1_thang = ($item->hocphi/5)*($item->phantramgiam/100);
            $content_DSMGHP[] =  [
                "ho_ten" => $item->full_name,
                "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                "lop" => $item->lop_name,
                "doi_tuong" => 'Đối tượng '.$item->type_miengiamhp + 1,
                "muc_hoc_phi" => $item->hocphi,
                "student_id" => $item->student_id,
                "ti_le_giam" => $item->phantramgiam,
                "so_tien_giam_1_thang" => $tien_1_thang,
                "so_thang_mien_giam" => 5,
                "mien_giam_ky" =>5*$tien_1_thang
              ];
              $content["tong"] += 5*$tien_1_thang;
              if($item->phantramgiam == 50)
              {
                $content["50"]++;
                $content["50_tong"] += 5*$tien_1_thang;
              }
              if($item->phantramgiam == 70)
              {
                $content["70"]++;
                $content["70_tong"] += 5*$tien_1_thang;
              }
              if($item->phantramgiam == 100)
              {
                $content["100"]++;
                $content["100_tong"] += 5*$tien_1_thang;
              }
        }

        $phieu =  Phieu::updateOrCreate(
            [
                'key' => 'DSMGHP',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được miễn giảm học phí',
                'content' => json_encode([$content,$content_DSMGHP], true)
            ]
        );
        Phieu::updateOrCreate(
            [
                'key' => 'QDGHP',
                'status' => 0
            ],
            [
                'name' => 'Quyết định miễn giảm học phí',
                'content' => json_encode([$content,0], true)
            ]
        );

        Phieu::updateOrCreate(
            [
                'key' => 'PTGHP',
                'status' => 0
            ],
            [
                'name' => 'Phiếu trình miễn giảm học phí',
                'content' => json_encode([$content,0], true)
            ]
        );

        return true;
    }
    function xoaQuyetDinh() {
        Phieu::where('status',0)
        ->whereIn('key', ['PTGHP', 'DSMGHP', 'QDGHP'])
        ->delete();
        return redirect()->back();
    }

    function getQuyetDinh(Request $request)
    {
        $phieu =  Phieu::where('key','DSMGHP')->where('status',0)->first();
        if($phieu)
        {
            return json_decode($phieu->content,true)[0];
        }
        return [];
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->update(['status' => 1]);

        return redirect()->back();
    }

    function guiTBSV()
    {
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->where('stop_studies.status', 3)
            ->select('stop_studies.*')
            ->get();
            $phieu = Phieu::where('key','DSMGHP')->orderBy('created_at', 'desc')->first();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 4;
            $stopStudy->save();
            $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
            $this->notification("Danh sách miễn giảm học phí dự kiến", $phieu->id, "GHP", $user_id);

            $users = User::where(function ($query) {
                $query->where('role', 2)
                    ->orWhere('role', 3);
            })->get();
            foreach ($users as $item) {
                $this->notification("Danh sách miễn giảm học phí dự kiến", $phieu->id, "GHP", $item->id);
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
        
        $phieu = Phieu::where('key','DSMGHP')->orderBy('created_at', 'desc')->first();

        $users = User::get();
        foreach ($users as $item) {
            $this->notification("Danh sách miễn giảm học phí", $phieu->id, "GHP", $item->id);
        }


        $phieu = Phieu::where('status', 0)->update(['status' => 1]);
        $query = StopStudy::where('type', 1)
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

    function tinhSoLuong()
    {

        $miengiamHP = StopStudy::where('type', 1)->where('stop_studies.status', '>', 0)
            ->studentActive()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->selectRaw('ROUND(SUM(stop_studies.phantramgiam / 100 * 1)) as hocphi, COUNT(*) as tong')
            ->get()->toArray();
        return $miengiamHP;
    }
}
